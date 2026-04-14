<?php

namespace App\Livewire\TaxCredit;

use App\Services\AccountJournalServices;
use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\TaxCreditServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Tax Credit')]
class TaxCreditList extends Component
{
    use WithPagination;

    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public int $locationid;

    public $search;
    public $locationList = [];

    private $taxCreditServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    private $invoiceServices;
    public function boot(TaxCreditServices $taxCreditServices, LocationServices $locationServices, UserServices $userServices, AccountJournalServices $accountJournalServices, InvoiceServices $invoiceServices)
    {
        $this->taxCreditServices = $taxCreditServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->invoiceServices = $invoiceServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
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
    private function deleteJournal($data, int $id)
    {

        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->taxCreditServices->object_type_tax_credit, $id);
        $invoiceListData = $this->taxCreditServices->GetInvoiceList($id);
        $this->accountJournalServices->DeleteJournal(
            $data->EWT_ACCOUNT_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->CUSTOMER_ID,
            $data->ID,
            $this->taxCreditServices->object_type_tax_credit,
            $data->DATE,
            0
        );

        foreach ($invoiceListData as $list) {
            $this->accountJournalServices->DeleteJournal(
                $list->ACCOUNTS_RECEIVABLE_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $list->INVOICE_ID,
                $list->ID,
                $this->taxCreditServices->object_type_tax_credit_invoices,
                $data->DATE,
                1
            );
        }

        // optional if remaining
        $this->accountJournalServices->DeleteJournal(
            $data->ACCOUNTS_RECEIVABLE_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->CUSTOMER_ID,
            $data->ID,
            $this->taxCreditServices->object_type_tax_credit,
            $data->DATE,
            1
        );
    }
    public function delete(int $id)
    {
        $data = $this->taxCreditServices->Get($id);
        if ($data) {
            if ($data->STATUS == 0 || $data->STATUS == 16) {
                DB::beginTransaction();
                try {
                    if ($data->STATUS == 16) {
                        $this->deleteJournal($data, $id);
                    }
                    $invoiceList = $this->taxCreditServices->GetInvoiceList($id); 
                    $this->taxCreditServices->Delete($id);
                    foreach ($invoiceList as $list) {         
                        $this->invoiceServices->updateInvoiceBalance($list->INVOICE_ID);
                    }
                    DB::commit();
                    session()->flash('message', 'Delete successfully');
                    return;
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
    public function unposted(int $id)
    {

        if (UserServices::GetUserRightAccess('customer.tax-credit.edit')) {
            $this->taxCreditServices->StatusUpdate($id, 16);
        } else {
            session()->flash('error', "You don't have authorization to unpost");
        }
    }
    public function render()
    {

        $data = $this->taxCreditServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.tax-credit.tax-credit-list', ['dataList' => $data]);
    }
}
