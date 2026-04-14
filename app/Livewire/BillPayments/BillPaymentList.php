<?php

namespace App\Livewire\BillPayments;

use App\Services\AccountJournalServices;
use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bill Payments')]
class BillPaymentList extends Component
{
    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $billPaymentServices;
    private $accountJournalServices;
    private $billingServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices,
        BillingServices $billingServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->billingServices = $billingServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function deleteJournal(object $data, int $id)
    {

        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->billPaymentServices->object_type_check, $id);
        $billData = $this->billPaymentServices->billPaymentBills($id);

        foreach ($billData as $list) {

            $this->accountJournalServices->DeleteJournal(
                $list->ACCOUNTS_PAYABLE_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->PAY_TO_ID,
                $list->ID,
                $this->billPaymentServices->object_type_check_bills,
                $data->DATE,
                0
            );
        }

        $this->accountJournalServices->DeleteJournal(
            $data->BANK_ACCOUNT_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->PAY_TO_ID,
            $data->ID,
            $this->billPaymentServices->object_type_check,
            $data->DATE,
            1
        );

        // optional if remaining
        $this->accountJournalServices->DeleteJournal(
            $data->BANK_ACCOUNT_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->PAY_TO_ID,
            $data->ID,
            $this->billPaymentServices->object_type_check,
            $data->DATE,
            0
        );
    }
    public function delete(int $id)
    {

        $data = $this->billPaymentServices->Get($id);
        if ($data) {
            if ($data->STATUS == 0 || $data->STATUS == 16) {
                DB::beginTransaction();
                try {
                    if ($data->STATUS == 16) {
                        $this->deleteJournal($data, $id);
                    }
                    $billList = $this->billPaymentServices->billPaymentBills($id);
                    $this->billPaymentServices->Delete($id);

                    foreach ($billList as $list) {
                        $this->billingServices->UpdateBalance($list->BILL_ID);
                    }

                    session()->flash('message', 'Successfully deleted.');
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $errorMessage = 'Error occurred: ' . $e->getMessage();
                    session()->flash('error', $errorMessage);
                }
                return;
            }
            session()->flash('error', 'Invalid. this file cannot be deleted.');
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
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
    public function render()
    {
        $dataList = $this->billPaymentServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.bill-payments.bill-payment-list', ['dataList' => $dataList]);
    }
}
