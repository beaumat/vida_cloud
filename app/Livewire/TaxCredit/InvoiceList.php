<?php

namespace App\Livewire\TaxCredit;

use App\Services\AccountJournalServices;
use App\Services\InvoiceServices;
use App\Services\TaxCreditServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InvoiceList extends Component
{
    #[Reactive]
    public int $TAX_CREDIT_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $EWT_RATE;

    public $dataList = [];
    private $taxCreditServices;
    private $invoiceServices;
    private $accountJournalServices;
    public function boot(TaxCreditServices $taxCreditServices, InvoiceServices $invoiceServices, AccountJournalServices $accountJournalServices)
    {
        $this->taxCreditServices = $taxCreditServices;
        $this->invoiceServices = $invoiceServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function delete(int $ID, int $INVOICE_ID)
    {
        DB::beginTransaction();
        try {
            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->taxCreditServices->object_type_tax_credit,
                    $this->TAX_CREDIT_ID
                );
                $payData = $this->taxCreditServices->Get($this->TAX_CREDIT_ID);
                if ($payData) {
                    $payInvoices = $this->taxCreditServices->GetTaxCreditInvoiceExists($ID, $this->TAX_CREDIT_ID, $INVOICE_ID);
                    if ($payInvoices) {
                        // ACCOUNT_ID
                        $this->accountJournalServices->DeleteJournal(
                            $payInvoices->ACCOUNTS_RECEIVABLE_ID,
                            $payData->LOCATION_ID,
                            $JOURNAL_NO,
                            $INVOICE_ID,
                            $ID,
                            $this->taxCreditServices->object_type_tax_credit_invoices,
                            $payData->DATE,
                            1
                        );
                    }
                }
            }

            $this->taxCreditServices->DeleteInvoice($ID, $this->TAX_CREDIT_ID);
            $this->invoiceServices->updateInvoiceBalance($INVOICE_ID);
            $NEW_AMOUNT = $this->taxCreditServices->getTotal($this->TAX_CREDIT_ID);
            $this->taxCreditServices->UpdateAMOUNT_WITHHELD($this->TAX_CREDIT_ID, $NEW_AMOUNT);
            $this->dispatch('reload_invoice');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    #[On('reload_invoice')]
    public function render()
    {
        $this->dataList = $this->taxCreditServices->GetInvoiceList($this->TAX_CREDIT_ID);
        return view('livewire.tax-credit.invoice-list');
    }
}
