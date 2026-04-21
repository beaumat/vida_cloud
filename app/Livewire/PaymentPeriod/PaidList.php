<?php
namespace App\Livewire\PaymentPeriod;

use App\Services\AccountJournalServices;
use App\Services\HemoServices;
use App\Services\InvoiceServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentServices;
use App\Services\PhilHealthServices;
use App\Services\ServiceChargeServices;
use App\Services\TaxCreditServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaidList extends Component
{
    #[Reactive()]
    public int $PAYMENT_PERIOD_ID;

    #[Reactive]
    public float $GROSS_TOTAL;
    public $dataList = [];
    private $paymentServices;
    private $accountJournalServices;
    private $invoiceServices;
    private $taxCreditServices;
    private $philHealthServices;
    private $patientPaymentServices;
    private $hemoServices;
    private $serviceChargeServices;
    public function boot(PaymentServices $paymentServices,
        AccountJournalServices $accountJournalServices,
        InvoiceServices $invoiceServices,
        TaxCreditServices $taxCreditServices,
        PhilHealthServices $philHealthServices,
        PatientPaymentServices $patientPaymentServices,
        HemoServices $hemoServices,
        ServiceChargeServices $serviceChargeServices
    ) {
        $this->paymentServices        = $paymentServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->invoiceServices        = $invoiceServices;
        $this->taxCreditServices      = $taxCreditServices;
        $this->philHealthServices     = $philHealthServices;
        $this->patientPaymentServices = $patientPaymentServices;
        $this->hemoServices           = $hemoServices;
        $this->serviceChargeServices  = $serviceChargeServices;
    }
    private function loadData()
    {
        $this->dataList = $this->paymentServices->getListInvoicePaymentTaxBillPhic($this->PAYMENT_PERIOD_ID);
    }

    public function DeletePaid(int $PAYMENT_ID)
    {
        if (! UserServices::GetUserRightAccess('customer.received-payment.delete')) {
            session()->flash('error', 'You don`t have permission to delete');
            return;
        }

        DB::beginTransaction();
        try {

            if ($PAYMENT_ID > 0) {
                $gotTaxCreditDelete = false;
                $tax_ID             = $this->taxCreditServices->GetTaxID($PAYMENT_ID);
                if ($this->TaxCreditdeleteEntry($tax_ID)) {
                    $gotTaxCreditDelete = true;
                    // NEW STYLE
                }
                if (! $gotTaxCreditDelete) {
                    session()->flash('error', 'No tax credit entry found for this payment');
                    DB::rollBack();
                    return;
                }
                if (! $this->PaymentdeleteEntry($PAYMENT_ID)) {
                    session()->flash('error', 'this payment already deposited');
                    DB::rollBack();
                    return;
                }
                $PH_DATA = $this->philHealthServices->getDataByPayment($PAYMENT_ID);
                if ($PH_DATA) {

                    $this->getTreamentSummary($PH_DATA);

                    if ($this->philHealthServices->deletePayableForDoctor($PH_DATA->ID)) {
                        session()->flash('error', 'This payment cannot be deleted. This is Bill payment for doctor fee has already posted to accounts payable. Please delete the bill payment entry first to proceed deleting this payment');
                        DB::rollBack();
                        return;
                    }
                    $this->philHealthServices->UpdatePayment($PH_DATA->ID, 0, $PAYMENT_ID);

                    DB::commit();
                    session()->flash('message', 'Payment canceled');
                }

                // delete service charge from patient_payment_

                DB::rollBack();
                session()->flash('error', 'No philhealth entry found for this payment');

            }

        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollBack();
        }

    }

    private function getTreamentSummary($phData)
    {
        $PATIENT_PAYMENT_ID = $this->patientPaymentServices->PH_exists($phData->ID);

        $summaryList = $this->hemoServices->GetSummary($phData->CONTACT_ID, $phData->LOCATION_ID, $phData->DATE_ADMITTED, $phData->DATE_DISCHARGED);

        foreach ($summaryList as $sumList) {
            $PP_ITEM_ID = $this->patientPaymentServices->PaymentChargesExist($PATIENT_PAYMENT_ID, $sumList->SCI_ID);

            if ($PP_ITEM_ID > 0) {
                $this->patientPaymentServices->PaymentChargesDelete($PP_ITEM_ID, $PATIENT_PAYMENT_ID, $sumList->SCI_ID);
            }

            $this->serviceChargeServices->updateServiceChargesItemPaid($sumList->SCI_ID);
            $this->serviceChargeServices->updateServiceChargesBalance($sumList->SERVICE_CHARGES_ID);
        }

        $this->patientPaymentServices->PH_Delete($PATIENT_PAYMENT_ID, $phData->ID);
    }
    public function PaymentdeleteEntry(int $id): bool
    {
        $data = $this->paymentServices->get($id);
        if ($data) {

            if ($data->DEPOSITED == 1) {

                if ($data->STATUS == 15) {
                    $this->PaymentdeleteJournal($data, $id);
                    $paymentList = $this->paymentServices->PaymentInvoiceList($data->ID);
                    $this->paymentServices->delete($data->ID);

                    foreach ($paymentList as $list) {
                        $this->invoiceServices->updateInvoiceBalance($list->INVOICE_ID);
                    }
                    return true;
                }
            }
        }

        return false;
    }
    public function PaymentdeleteJournal(object $data, int $id)
    {

        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->paymentServices->object_type_payment, $id);
        $payData    = $this->paymentServices->PaymentInvoiceList($id);

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

        if ($data->ACCOUNTS_RECEIVABLE_ID > 0) {
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
    private function TaxCreditdeleteJournal($data, int $id)
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
    public function TaxCreditdeleteEntry(int $id)
    {
        $data = $this->taxCreditServices->Get($id);
        if ($data) {
            if ($data->STATUS == 15) {
                $this->TaxCreditdeleteJournal($data, $id);
            }
            $invoiceList = $this->taxCreditServices->GetInvoiceList($id); // get first invoice Tax Credit
            $this->taxCreditServices->Delete($id);                        // Delete Main and Invoice tax credit
            foreach ($invoiceList as $list) {
                $this->invoiceServices->updateInvoiceBalance($list->INVOICE_ID);
            }
            return true;
        }

        return false;
    }
    public function render()
    {

        $this->loadData();
        return view('livewire.payment-period.paid-list');
    }
}
