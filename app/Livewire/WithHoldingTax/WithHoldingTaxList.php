<?php

namespace App\Livewire\WithHoldingTax;

use App\Services\AccountJournalServices;
use App\Services\BillingServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use App\Services\WithholdingTaxServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Witholding Tax')]
class WithHoldingTaxList extends Component
{
    use WithPagination;

    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public int $locationid;
    public $locationList = [];
    private $withholdingTaxServices;
    private $userServices;
    private $locationServices;
    private $accountJournalServices;
    private $billingServices;
    public function boot(
        WithholdingTaxServices $withholdingTaxServices,
        UserServices $userServices,
        LocationServices $locationServices,
        AccountJournalServices $accountJournalServices,
        BillingServices $billingServices
    ) {
        $this->withholdingTaxServices = $withholdingTaxServices;
        $this->userServices = $userServices;
        $this->locationServices = $locationServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->billingServices = $billingServices;
    }
    public function mount()
    {
        $this->locationid = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
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

        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->withholdingTaxServices->object_type_withholding_tax_id, $id);

        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->DeleteJournal(
                $data->EWT_ACCOUNT_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->WITHHELD_FROM_ID,
                $data->ID,
                $this->withholdingTaxServices->object_type_withholding_tax_id,
                $data->DATE,
                1
            );
            $billListData = $this->withholdingTaxServices->GetBillList($id);
            foreach ($billListData as $list) {
                $this->accountJournalServices->DeleteJournal(
                    $list->ACCOUNTS_PAYABLE_ID,
                    $data->LOCATION_ID,
                    $JOURNAL_NO,
                    $list->BILL_ID,
                    $list->ID,
                    $this->withholdingTaxServices->object_type_witholding_tax_bills_id,
                    $data->DATE,
                    0
                );
            }

            // optional if remaining
            $this->accountJournalServices->DeleteJournal(
                $data->ACCOUNTS_PAYABLE_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->WITHHELD_FROM_ID,
                $data->ID,
                $this->withholdingTaxServices->object_type_withholding_tax_id,
                $data->DATE,
                0
            );
        }

    }
    public function delete(int $ID)
    {
        try {
            $data = $this->withholdingTaxServices->Get($ID);
            if ($data) {
                if ($data->STATUS == 0 || $data->STATUS == 16) {
                    DB::beginTransaction();
                    try {
                        if ($data->STATUS == 16) {
                            $this->deleteJournal($data, $ID);
                        }
                        $billList = $this->withholdingTaxServices->GetBillList($ID);
                        $this->withholdingTaxServices->Delete($ID);
                        foreach ($billList as $list) {
                            $this->billingServices->UpdateBalance($list->BILL_ID);
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
        } catch (\Throwable $th) {

            session()->flash('error', 'Error:' . $th->getMessage());
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
        $dataList = $this->withholdingTaxServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.with-holding-tax.with-holding-tax-list', ['dataList' => $dataList]);
    }
}
