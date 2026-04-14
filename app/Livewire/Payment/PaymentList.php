<?php

namespace App\Livewire\Payment;

use App\Services\AccountJournalServices;
use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\PaymentServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Payments')]
class PaymentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 30;
    public int $locationid;
    public $locationList = [];
    private $paymentServices;
    private $locationServices;
    private $userServices;
    private $uploadServices;
    private $invoiceServices;
    private $accountJournalServices;
    public function boot(
        PaymentServices $paymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        UploadServices $uploadServices,
        InvoiceServices $invoiceServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->uploadServices = $uploadServices;
        $this->invoiceServices = $invoiceServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }

    public function deleteJournal(object $data, int $id)
    {

        $JOURNAL_NO  = (int) $this->accountJournalServices->getRecord($this->paymentServices->object_type_payment, $id);
        
        $payData = $this->paymentServices->PaymentInvoiceList($id);

        foreach ($payData as $list) {

            $this->accountJournalServices->DeleteJournal(
                $list->ACCOUNTS_RECEIVABLE_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->CUSTOMER_ID,
                $list->ID,
                $this->paymentServices->object_type_payment_invoices,
                $data->DATE,
                1
            );
        }

        $this->accountJournalServices->DeleteJournal(
            $data->UNDEPOSITED_FUNDS_ACCOUNT_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->CUSTOMER_ID,
            $data->ID,
            $this->paymentServices->object_type_payment,
            $data->DATE,
            0
        );

        if ($data->ACCOUNTS_RECEIVABLE_ID  > 0) {
            // optional if remaining
            $this->accountJournalServices->DeleteJournal(
                $data->ACCOUNTS_RECEIVABLE_ID ?? 0,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->CUSTOMER_ID,
                $data->ID,
                $this->paymentServices->object_type_payment,
                $data->DATE,
                1
            );
        }
    }
    public function unposted(int $id)
    {
        $this->paymentServices->StatusUpdate($id, 16);
    }

    public function delete(int $id)
    { 
        try {
            $data = $this->paymentServices->get($id);
            if ($data) {
                if ($data->DEPOSITED == 0 || UserServices::GetUserRightAccess('customer.received-payment.delete')) {
                    if ($data->STATUS ==  0 || $data->STATUS == 16) {
                        DB::beginTransaction();

                        if ($data->STATUS  > 0) {
                            $this->deleteJournal($data, $id);
                        }

                        $paymentList = $this->paymentServices->PaymentInvoiceList($data->ID);
                        $this->paymentServices->delete($data->ID);

                        foreach ($paymentList as $list) {
                            $this->invoiceServices->updateInvoiceBalance($list->INVOICE_ID);
                        }

                        session()->flash('message', 'Successfully deleted.');
                        DB::commit();
                        return;
                    }
                    session()->flash('error', 'Invalid. this file already posted');
                    return;
                }

                session()->flash('error', 'Invalid. this file cannot be deleted. or this payment already deposited');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $dataList = $this->paymentServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.payment.payment-list', ['dataList' => $dataList]);
    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
}
