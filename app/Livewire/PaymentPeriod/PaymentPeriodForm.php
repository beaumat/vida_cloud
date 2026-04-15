<?php
namespace App\Livewire\PaymentPeriod;

use App\Exports\PaymentPeriodExport;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\LocationServices;
use App\Services\PaymentPeriodServices;
use App\Services\PaymentServices;
use App\Services\TaxCreditServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Payment Period (ACPN)')]
class PaymentPeriodForm extends Component
{
    public int $ID;
    public string $RECEIPT_NO;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public float $TOTAL_PAYMENT; // GROSS AMOUNT
    public float $TOTAL_WTAX;
    public int $BANK_ACCOUNT_ID;
    public string $DATE;

    public bool $Modify  = false;
    public $locationList = [];
    public $accountList  = [];
    private $paymentPeriodServices;
    private $accountServices;
    private $locationServices;
    private $paymentServices;
    private $taxCreditServices;
    private $accountJournalServices;
    private $billingServices;
    public function boot(
        PaymentPeriodServices $paymentPeriodServices,
        AccountServices $accountServices,
        LocationServices $locationServices,
        PaymentServices $paymentServices,
        TaxCreditServices $taxCreditServices,
        AccountJournalServices $accountJournalServices,
        BillingServices $billingServices
    ) {
        $this->paymentPeriodServices  = $paymentPeriodServices;
        $this->accountServices        = $accountServices;
        $this->locationServices       = $locationServices;
        $this->paymentServices        = $paymentServices;
        $this->taxCreditServices      = $taxCreditServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->billingServices        = $billingServices;
    }

    public function mount($id = null)
    {
        try {
            if (is_numeric($id)) {
                $data = $this->paymentPeriodServices->get($id);
                if ($data) {
                    $this->ID              = $data->ID;
                    $this->DATE            = $data->DATE;
                    $this->RECEIPT_NO      = $data->RECEIPT_NO;
                    $this->DATE_FROM       = $data->DATE_FROM;
                    $this->DATE_TO         = $data->DATE_TO;
                    $this->LOCATION_ID     = $data->LOCATION_ID;
                    $this->TOTAL_PAYMENT   = $data->TOTAL_PAYMENT;
                    $this->TOTAL_WTAX      = $data->TOTAL_WTAX;
                    $this->BANK_ACCOUNT_ID = $data->BANK_ACCOUNT_ID;
                    $this->dropdownLoad();
                    return;
                }

            }
            return Redirect::route('patientspayment_period')->with('error', 'Record not found');
        } catch (\Throwable $th) {
            return Redirect::route('patientspayment_period')->with('error', $th->getMessage());
        }
    }
    public function exportGenerate()
    {
        $dataExport = $this->paymentServices->getListInvoicePaymentTaxBillPhic($this->ID);
        return Excel::download(new PaymentPeriodExport(
            $dataExport,
            $this->TOTAL_PAYMENT
        ), 'payment-period-export.xlsx');
    }
    public function save()
    {
        $this->validate(
            [
                'DATE'       => 'required|date',
                'DATE_FROM'  => 'required|date',
                'DATE_TO'    => 'required|date',
                'RECEIPT_NO' => 'required|numeric',

            ],
            [],
            [

                'DATE'       => 'OR Date',
                'DATE_FROM'  => 'Date From',
                'DATE_TO'    => 'Date To',
                'RECEIPT_NO' => 'OR number',
            ]
        );

        $isBankAccountExist = (bool) $this->paymentPeriodServices->bankAccountExists($this->ID, $this->BANK_ACCOUNT_ID);
        $isDateExist        = (bool) $this->paymentPeriodServices->dateExists($this->ID, $this->DATE);
        $isORnumberExist    = (bool) $this->paymentPeriodServices->orNumberExists($this->ID, $this->RECEIPT_NO);

        $this->paymentPeriodServices->Update(
            $this->ID,
            $this->RECEIPT_NO,
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->DATE,
            $this->TOTAL_PAYMENT,
            $this->BANK_ACCOUNT_ID
        );

        if (! $isDateExist) {
            //DATE UPDATE
            $this->getUpdateOrderDate();
            return;
        }
        if (! $isBankAccountExist) {
            //BANK ACCOUNT UPDATE
            $this->getUpdateBankAccount();
        }
        if (! $isORnumberExist) {
            //OR NUMBER UPDATE
            $this->getUpdateOrNumber();
        }

        $this->Modify = false;
        session()->flash('message', 'Successfully update');
    }

    private function getUpdateOrderDate()
    {
        DB::beginTransaction();
        try {
            $dataList = $this->paymentServices->getPaymentbyPaymentPeriod($this->ID);
            foreach ($dataList as $list) {
                // PAYMENT
                $this->paymentServices->getUpdateDateOnly($list->ID, $this->DATE);

                $PAY_JOUNRAL_NO = $this->accountJournalServices->getRecord($this->paymentServices->object_type_payment, $list->ID);
                $this->accountJournalServices->updateObjectDate($PAY_JOUNRAL_NO, $this->DATE);

                // TAX
                $TAX_CREDIT_ID = $this->taxCreditServices->updateDateOnly($list->INVOICE_ID, $this->DATE);
                if ($TAX_CREDIT_ID > 0) {
                    $TAX_JOURNAL_NO = $this->accountJournalServices->getRecord($this->taxCreditServices->object_type_tax_credit, $TAX_CREDIT_ID);
                    $this->accountJournalServices->updateObjectDate($TAX_JOURNAL_NO, $this->DATE);
                }
                // BILL
                if ($list->BILL_ID) {
                    $this->billingServices->billingUpdateDateOnly($list->BILL_ID, $this->DATE);
                    $BILL_JOURNAL_NO = $this->accountJournalServices->getRecord($this->billingServices->object_type_map_bill, $list->BILL_ID);
                    $this->accountJournalServices->updateObjectDate($BILL_JOURNAL_NO, $this->DATE);
                }

            }
            DB::commit();
            redirect::route('patientspayment_period', ['id' => $this->ID])->with('message', 'successfully date update');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            session()->flash('error', 'Invalid:' . $th->getMessage());
        }
    }
    private function getUpdateBankAccount()
    {
        $dataList = $this->paymentServices->getPaymentbyPaymentPeriod($this->ID);
        foreach ($dataList as $list) {
            $dataPay = $this->paymentServices->getUpdateUndeposit(
                $list->ID,
                $this->BANK_ACCOUNT_ID
            );
            if ($dataPay) {
                $this->accountJournalServices->updateAccount(
                    $dataPay->ID,
                    $this->paymentServices->object_type_payment,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $dataPay->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                    $this->BANK_ACCOUNT_ID
                );
            }
        }
    }
    private function getUpdateOrNumber()
    {
        $dataList = $this->paymentServices->getPaymentbyPaymentPeriod($this->ID);
        foreach ($dataList as $list) {
            $this->paymentServices->getUpdateReceiptNo($list->ID, $this->RECEIPT_NO);
        }
    }
    public function updateCancel()
    {
        return Redirect::route('patientspayment_period_details', ['id' => $this->ID]);
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function dropdownLoad()
    {
        $this->accountList  = $this->accountServices->getBankAccount();
        $this->locationList = $this->locationServices->getList();
    }
    public function render()
    {
        return view('livewire.payment-period.payment-period-form');
    }
}
