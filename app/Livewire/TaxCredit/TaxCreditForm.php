<?php
namespace App\Livewire\TaxCredit;

use App\Services\AccountJournalServices;
use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\TaxCreditServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tax Credit')]
class TaxCreditForm extends Component
{

    public bool $Modify;
    public int $openStatus = 0;

    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $CUSTOMER_ID;
    public int $EWT_ID;
    public float $EWT_RATE;
    public int $EWT_ACCOUNT_ID;
    public int $LOCATION_ID;
    public float $AMOUNT;
    public string $NOTES;
    public int $STATUS = 0;
    public int $STATUS_DESCRIPTION;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public $contactList  = [];
    public $locationList = [];
    public $taxList      = [];
    private $taxCreditServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $taxServices;
    private $accountJournalServices;
    private $systemSettingServices;
    public function boot(
        TaxCreditServices $taxCreditServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        TaxServices $taxServices,
        AccountJournalServices $accountJournalServices,
        SystemSettingServices $systemSettingServices

    ) {
        $this->taxCreditServices      = $taxCreditServices;
        $this->contactServices        = $contactServices;
        $this->userServices           = $userServices;
        $this->locationServices       = $locationServices;
        $this->taxServices            = $taxServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->systemSettingServices  = $systemSettingServices;
    }
    private function LoadDropdown()
    {
        $this->contactList  = $this->contactServices->getCustoPatientList();
        $this->locationList = $this->locationServices->getList();
        $this->taxList      = $this->taxServices->getWtax();
    }
    private function getInfo($data)
    {
        $this->ID                     = $data->ID;
        $this->CODE                   = $data->CODE ?? '';
        $this->CUSTOMER_ID            = $data->CUSTOMER_ID ?? 0;
        $this->LOCATION_ID            = $data->LOCATION_ID ?? 0;
        $this->DATE                   = $data->DATE;
        $this->EWT_ACCOUNT_ID         = $data->EWT_ACCOUNT_ID ?? 0;
        $this->ACCOUNTS_RECEIVABLE_ID = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;
        $this->AMOUNT                 = $data->AMOUNT ?? 0;
        $this->EWT_RATE               = $data->EWT_RATE ?? 0;
        $this->EWT_ID                 = $data->EWT_ID ?? 0;
        $this->NOTES                  = $data->NOTES ?? '';
        $this->STATUS                 = $data->STATUS ?? 0;

        if($this->STATUS  == 16) {
            $this->removeJournal();
        }
    }

    public function updatedEwtId()
    {
        $data = $this->taxServices->get($this->EWT_ID);
        if ($data) {
            $this->EWT_RATE       = $data->RATE ?? 0;
            $this->EWT_ACCOUNT_ID = $data->ASSET_ACCOUNT_ID ?? 0;
            return;
        }
        $this->EWT_RATE       = 0;
        $this->EWT_ACCOUNT_ID = 0;
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->taxCreditServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('customerstax_credit')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->DATE                   = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID            = $this->userServices->getLocationDefault();
        $this->CODE                   = '';
        $this->CUSTOMER_ID            = 0;
        $this->EWT_ACCOUNT_ID         = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = 12;
        $this->AMOUNT                 = 0;
        $this->EWT_RATE               = 0;
        $this->EWT_ID                 = 0;
        $this->ID                     = 0;
        $this->NOTES                  = '';
        $this->Modify                 = true;
        $this->STATUS                 = 0;
    }
    #[On('reload_invoice')]
    public function updateAmount()
    {
        $this->AMOUNT = $this->taxCreditServices->getTotal($this->ID);
    }

    public function save()
    {

        $this->validate(
            [
                'EWT_ID'      => 'required|integer|exists:tax,id',
                'CODE'        => 'nullable|max:20|unique:tax_credit,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'CUSTOMER_ID' => 'required|integer|exists:contact,id',
                'LOCATION_ID' => 'required|integer|exists:location,id',
                'DATE'        => 'required|date|string',
            ],
            [],
            [
                'EWT_ID'      => 'Withholding Tax Type',
                'CODE'        => 'Reference No.',
                'CUSTOMER_ID' => 'Customer',
                'LOCATION_ID' => 'Location',
                'DATE'        => 'Date',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        DB::beginTransaction();
        try {

            if ($this->ID == 0) {
                $this->ID = $this->taxCreditServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->EWT_ID,
                    $this->EWT_RATE,
                    $this->EWT_ACCOUNT_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID
                );
                DB::commit();
                return Redirect::route('customerstax_credit_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            }

            $data = $this->taxCreditServices->Get($this->ID);
            if ($data) {
                if ($this->STATUS == 16) {
                    $JNO = $this->accountJournalServices->getRecord($this->taxCreditServices->object_type_tax_credit, $this->ID);
                    if ($JNO > 0) {
                        $this->accountJournalServices->AccountSwitch($this->EWT_ACCOUNT_ID, $data->EWT_ACCOUNT_ID, $this->LOCATION_ID, $JNO, $data->CUSTOMER_ID, $this->ID, $this->taxCreditServices->object_type_tax_credit, $this->DATE, 0);
                    }
                }

                $this->AMOUNT = (float) $this->taxCreditServices->UpdateAMOUNT_WITHHELD($this->ID, $this->EWT_RATE);
                $this->taxCreditServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->EWT_ID,
                    $this->EWT_RATE,
                    $this->EWT_ACCOUNT_ID,
                    $this->NOTES,
                    $this->AMOUNT,
                    $this->ACCOUNTS_RECEIVABLE_ID
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

    public function getModify()
    {
        $this->Modify = true;
    }
    public function updateCancel()
    {
        $data = $this->taxCreditServices->Get($this->ID);
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
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->taxCreditServices->object_type_tax_credit, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($this->taxCreditServices->object_type_tax_credit, $this->ID) + 1;
            }

            $paymentData = $this->taxCreditServices->TaxCreditJournal($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $paymentData,
                $this->LOCATION_ID,
                $this->taxCreditServices->object_type_tax_credit,
                $this->DATE,
                "TAX"
            );

            $paymentDataR = $this->taxCreditServices->TaxCreditJournalRemaining($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $paymentDataR,
                $this->LOCATION_ID,
                $this->taxCreditServices->object_type_tax_credit,
                $this->DATE,
                "A/R"
            );

            $paymentInvoiceData = $this->taxCreditServices->TaxCreditInvoicejournal($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $paymentInvoiceData,
                $this->LOCATION_ID,
                $this->taxCreditServices->object_type_tax_credit_invoices,
                $this->DATE,
                "A/R"
            );

            $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                $this->taxCreditServices->StatusUpdate($this->ID, 15);
                DB::commit();
                $data = $this->taxCreditServices->get($this->ID);
                if ($data) {
                    $this->getInfo($data);
                    $this->Modify = false;
                    return;
                }
                session()->flash('message', 'Successfully posted');
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
            $this->taxCreditServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('customerstax_credit_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

      private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->taxCreditServices->object_type_tax_credit, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->taxCreditServices->object_type_tax_credit, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }

    public function render()
    {
        return view('livewire.tax-credit.tax-credit-form');
    }
}
