<?php
namespace App\Livewire\PaymentPeriod;

use App\Services\AccountJournalServices;
use App\Services\InvoiceServices;
use App\Services\PaymentServices;
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
    public function boot(PaymentServices $paymentServices, AccountJournalServices $accountJournalServices, InvoiceServices $invoiceServices, TaxCreditServices $taxCreditServices)
    {
        $this->paymentServices        = $paymentServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->invoiceServices        = $invoiceServices;
        $this->taxCreditServices      = $taxCreditServices;
    }
    private function loadData()
    {
        $this->dataList = $this->paymentServices->getListInvoicePaymentTaxBillPhic($this->PAYMENT_PERIOD_ID);
    }
    public function callTaxCreditByPaymentID(int $PAYMENT_ID)
    {

        // call URL and new TAB

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
                if (! $this->PaymentdeleteEntry($PAYMENT_ID)) {
                    session()->flash('error', 'this payment already deposited');
                    DB::rollBack();
                    return;
                }
                $taxCredit = $this->taxCreditServices->GetListViaPayments($PAYMENT_ID);
                foreach ($taxCredit as $tax) {
                    if ($tax->TAX_CREDIT_ID > 0) {
                        $this->TaxCreditdeleteEntry($tax->TAX_CREDIT_ID);
                    }
                }

                DB::commit();
                session()->flash('message', 'Successuflly Canceld');
            }

        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollBack();
        }

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
