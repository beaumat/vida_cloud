<?php
namespace App\Livewire\WithHoldingTax;

use App\Services\AccountJournalServices;
use App\Services\BillingServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use App\Services\WithholdingTaxServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Withholding Tax')]
class WithHoldingTaxForm extends Component
{
    public bool $Modify;
    public int $openStatus = 0;

    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $WITHHELD_FROM_ID;
    public int $EWT_ID;
    public float $EWT_RATE;
    public int $EWT_ACCOUNT_ID;
    public int $LOCATION_ID;
    public float $AMOUNT;
    public string $NOTES;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION;
    public int $ACCOUNTS_PAYABLE_ID;
    public $contactList  = [];
    public $locationList = [];
    public $taxList      = [];

    private $withholdingTaxServices;
    private $userServices;
    private $locationServices;
    private $taxServices;
    private $accountJournalServices;
    private $contactServices;
    private $documentStatusServices;
    private $billingServices;
    private $systemSettingServices;
    public function boot(
        WithholdingTaxServices $withholdingTaxService,
        UserServices $userServices,
        LocationServices $locationServices,
        TaxServices $taxServices,
        AccountJournalServices $accountJournalServices,
        ContactServices $contactServices,
        DocumentStatusServices $documentStatusServices,
        BillingServices $billingServices,
        SystemSettingServices $systemSettingServices
    ) {
        $this->withholdingTaxServices = $withholdingTaxService;
        $this->userServices           = $userServices;
        $this->locationServices       = $locationServices;
        $this->taxServices            = $taxServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->contactServices        = $contactServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->billingServices        = $billingServices;
        $this->systemSettingServices  = $systemSettingServices;
    }
    public function updatedEwtId()
    {
        $data = $this->taxServices->get($this->EWT_ID);
        if ($data) {
            $this->EWT_RATE       = $data->RATE ?? 0;
            $this->EWT_ACCOUNT_ID = $data->TAX_ACCOUNT_ID ?? 0;
            return;
        }
        $this->EWT_RATE       = 0;
        $this->EWT_ACCOUNT_ID = 0;
    }
    private function LoadDropdown()
    {
        $this->contactList  = $this->contactServices->getVendorDoc();
        $this->locationList = $this->locationServices->getList();
        $this->taxList      = $this->taxServices->getWTax();
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->withholdingTaxServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorswithholding_tax')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->DATE                = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID         = $this->userServices->getLocationDefault();
        $this->CODE                = '';
        $this->WITHHELD_FROM_ID    = 0;
        $this->EWT_ACCOUNT_ID      = 0;
        $this->ACCOUNTS_PAYABLE_ID = 21;
        $this->AMOUNT              = 0;
        $this->EWT_RATE            = 0;
        $this->EWT_ID              = 0;
        $this->ID                  = 0;
        $this->NOTES               = '';
        $this->Modify              = true;
        $this->STATUS              = 0;
        $this->STATUS_DESCRIPTION  = "";
    }
    public function save()
    {

        $this->validate(
            [
                'EWT_ID'           => 'required|integer|exists:tax,id',
                'CODE'             => 'nullable|max:20|unique:withholding_tax,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'WITHHELD_FROM_ID' => 'required|integer|exists:contact,id',
                'LOCATION_ID'      => 'required|integer|exists:location,id',
                'DATE'             => 'required|date|string',
            ],
            [],
            [
                'EWT_ID'           => 'Withholding Tax Type',
                'CODE'             => 'Reference No.',
                'WITHHELD_FROM_ID' => 'Vendor',
                'LOCATION_ID'      => 'Location',
                'DATE'             => 'Date',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        DB::beginTransaction();
        try {

            if ($this->ID == 0) {
                $this->ID = (int) $this->withholdingTaxServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->WITHHELD_FROM_ID,
                    $this->EWT_RATE,
                    $this->EWT_ID,
                    $this->EWT_ACCOUNT_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID
                );

                DB::commit();
                return Redirect::route('vendorswithholding_tax_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            }
            $data = $this->withholdingTaxServices->Get($this->ID);
            if ($data) {
                if ($this->STATUS == 16) {
                    $JNO = $this->accountJournalServices->getRecord($this->withholdingTaxServices->object_type_withholding_tax_id, $this->ID);
                    if ($JNO > 0) {
                        $this->accountJournalServices->AccountSwitch(
                            $this->EWT_ACCOUNT_ID,
                            $data->EWT_ACCOUNT_ID,
                            $this->LOCATION_ID,
                            $JNO,
                            $data->WITHHELD_FROM_ID,
                            $this->ID,
                            $this->withholdingTaxServices->object_type_withholding_tax_id,
                            $this->DATE,
                            0
                        );
                    }
                }

                $this->AMOUNT = (float) $this->withholdingTaxServices->UpdateAMOUNT_WITHHELD($this->ID, $this->EWT_RATE);
                $this->withholdingTaxServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->WITHHELD_FROM_ID,
                    $this->EWT_RATE,
                    $this->EWT_ID,
                    $this->EWT_ACCOUNT_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->AMOUNT
                );

                DB::commit();
                session()->flash('message', 'Successfully updated');
            }
            $this->Modify = false;
        } catch (\Throwable $e) {
            DB::rollback();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function getInfo($data)
    {

        $this->ID                  = $data->ID;
        $this->CODE                = $data->CODE ?? '';
        $this->WITHHELD_FROM_ID    = $data->WITHHELD_FROM_ID ?? 0;
        $this->LOCATION_ID         = $data->LOCATION_ID ?? 0;
        $this->DATE                = $data->DATE;
        $this->EWT_ACCOUNT_ID      = $data->EWT_ACCOUNT_ID ?? 0;
        $this->WITHHELD_FROM_ID    = $data->WITHHELD_FROM_ID ?? 0;
        $this->AMOUNT              = $data->AMOUNT ?? 0;
        $this->ACCOUNTS_PAYABLE_ID = $data->ACCOUNTS_PAYABLE_ID ?? 0;
        $this->EWT_RATE            = $data->EWT_RATE ?? 0;
        $this->EWT_ID              = $data->EWT_ID ?? 0;
        $this->NOTES               = $data->NOTES ?? '';
        $this->STATUS              = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION  = $this->documentStatusServices->getDesc($this->STATUS);
    }

    #[On('reload_bill')]
    public function updateAmount()
    {

        $this->AMOUNT = $this->withholdingTaxServices->getTotal($this->ID);
    }

    public function getModify()
    {
        $this->Modify = true;
    }
    public function updateCancel()
    {
        $data = $this->withholdingTaxServices->Get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
        $this->Modify = false;
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function getPosted()
    {
        try {

            DB::beginTransaction();
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->withholdingTaxServices->object_type_withholding_tax_id, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($this->withholdingTaxServices->object_type_withholding_tax_id, $this->ID) + 1;
            }

            $paymentData = $this->withholdingTaxServices->WtaxJournal($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $paymentData,
                $this->LOCATION_ID,
                $this->withholdingTaxServices->object_type_withholding_tax_id,
                $this->DATE,
                "TAX"
            );

            $paymentDataR = $this->withholdingTaxServices->WtaxRemaining($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $paymentDataR,
                $this->LOCATION_ID,
                $this->withholdingTaxServices->object_type_withholding_tax_id,
                $this->DATE,
                "A/P"
            );

            $taxBillData = $this->withholdingTaxServices->WTaxBillJournal($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $taxBillData,
                $this->LOCATION_ID,
                $this->withholdingTaxServices->object_type_witholding_tax_bills_id,
                $this->DATE,
                "A/P"
            );

            $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {

                $this->withholdingTaxServices->StatusUpdate($this->ID, 15);
                DB::commit();
                $data = $this->withholdingTaxServices->get($this->ID);
                if ($data) {
                    $this->getInfo($data);
                    $this->Modify = false;
                    session()->flash('message', 'Successfully posted');
                    return;
                }
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->withholdingTaxServices->StatusUpdate($this->ID, 16);
            DB::commit();
            Redirect::route('vendorswithholding_tax_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->withholdingTaxServices->object_type_withholding_tax_id, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
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
    private function delete(int $ID)
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

                        Redirect::route('vendorswithholding_tax')->with('message', 'Successfully deleted');
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

    public function DeleteEntry()
    {
        $this->delete($this->ID);
    }
    public function render()
    {
        return view('livewire.with-holding-tax.with-holding-tax-form');
    }
}
