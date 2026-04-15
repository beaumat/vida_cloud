<?php
namespace App\Livewire\PhilHealth;

use App\Models\PhilHealth;
use App\Services\AccountJournalServices;
use App\Services\ComputeServices;
use App\Services\InvoiceServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\PaymentTermServices;
use App\Services\PhilHealthServices;
use App\Services\PriceLevelLineServices;
use App\Services\TaxServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ArForm extends Component
{
    public int $INVOICE_ID;
    public string $INVOICE_CODE;
    public string $INVOICE_AMOUNT;

    public bool $showModal = false;
    public int $PHILHEALTH_ID;
    public string $AR_DATE;
    public string $AR_NO;
    public string $CODE;
    public string $DATE;
    public bool $isPaid = false;
    private $philHealthServices;
    private $invoiceServices;
    private $paymentTermServices;
    private $itemServices;
    private $itemInventoryServices;
    private $priceLevelLineServices;
    private $accountJournalServices;
    private $taxServices;
    private $computeServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        InvoiceServices $invoiceServices,
        PaymentTermServices $paymentTermServices,
        ItemServices $itemServices,
        ItemInventoryServices $itemInventoryServices,
        PriceLevelLineServices $priceLevelLineServices,
        AccountJournalServices $accountJournalServices,
        TaxServices $taxServices,
        ComputeServices $computeServices
    ) {
        $this->philHealthServices     = $philHealthServices;
        $this->invoiceServices        = $invoiceServices;
        $this->paymentTermServices    = $paymentTermServices;
        $this->itemServices           = $itemServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->taxServices            = $taxServices;
        $this->computeServices        = $computeServices;
    }
    public function save()
    {

        if ($this->AR_DATE == '' && $this->AR_NO != '') {
            session()->flash('error', 'Date Transmit Requred');
            return;
        }

        if ($this->AR_DATE != '' && $this->AR_NO == '') {
            session()->flash('error', 'LHIO No. Requred');
            return;
        }

        if ($this->AR_DATE != '' && $this->AR_NO != '') {
            if ($this->philHealthServices->IsExistsARNumber($this->AR_NO, $this->PHILHEALTH_ID)) {
                session()->flash('error', 'LHIO No. already used.');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $this->philHealthServices->UpdateAR($this->PHILHEALTH_ID, $this->AR_NO, $this->AR_DATE);

            $data = $this->makeReceivableForCustomer($this->PHILHEALTH_ID);

            if ($data['STATUS'] == true) {
                session()->flash('message', $data['MESSAGE']);
                $this->dispatch('reload-list');
            } else {
                session()->flash('error', $data['MESSAGE']);
            }

            DB::commit();

            $dataAR = [
                'AR_DATE'       => $this->AR_DATE,
                'AR_NO'         => $this->AR_NO,
                'PHILHEALTH_ID' => $this->PHILHEALTH_ID,
            ];

            $this->dispatch('ar-form-data', ar: $dataAR);

        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            session()->flash('error', $e->getMessage());
            return;
        }

    }
    #[On('ar-form-show')]
    public function openModal($result)
    {
        $this->PHILHEALTH_ID = $result['PHILHEALTH_ID'];
        $data                = $this->philHealthServices->get($this->PHILHEALTH_ID);
        if ($data) {
            $this->CODE       = $data->CODE;
            $this->DATE       = $data->DATE;
            $this->INVOICE_ID = $data->INVOICE_ID ?? 0;
            $this->AR_DATE    = $data->AR_DATE ?? '';
            $this->AR_NO      = $data->AR_NO ?? '';
            $this->showModal  = true;
            $this->isPaid     = $this->philHealthServices->isPaid($this->PHILHEALTH_ID);

        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    private function isTreatmentHaveBelow2026(int $PHILHEALTH_ID): bool
    {
        return $this->philHealthServices->getPhilhealthHaveBelow2026($PHILHEALTH_ID);
    }
    private function makeReceivableForCustomer(int $PHILHEALTH_ID): array
    {

        if ($this->isTreatmentHaveBelow2026($PHILHEALTH_ID) == false) {
            return [
                'STATUS'     => true,
                'MESSAGE'    => 'Cannot create invoice for treatments below year 2026. but LHIO saved successfully.',
                'INVOICE_ID' => 0,
            ];
        }

        $dataPhic = $this->philHealthServices->get($PHILHEALTH_ID);
        if ($dataPhic->INVOICE_ID > 0) {
            $dtInv = $this->invoiceServices->get($dataPhic->INVOICE_ID);
            if ($dtInv) {
                if ($dtInv->DATE == $dataPhic->AR_DATE && $dtInv->PO_NUMBER != $dataPhic->AR_NO) {
                    $para = [
                        'PO_NUMBER' => $dataPhic->AR_NO,
                    ];
                    // update PO number
                    $this->invoiceServices->UpdateParameter($dataPhic->INVOICE_ID, $para);
                    return [
                        'STATUS'     => true,
                        'MESSAGE'    => 'Successfully save & LHIO number has updated',
                        'INVOICE_ID' => $dataPhic->INVOICE_ID,
                    ];
                } else {
                    //delete
                    $this->delete($dataPhic->INVOICE_ID);

                }

            }
        }

        if ($dataPhic->AR_DATE == '') {
            return [
                'STATUS'     => false,
                'MESSAGE'    => '',
                'INVOICE_ID' => 0,
            ];
        }

        $QTY              = $this->philHealthServices->getNumberOfTreatment($dataPhic->CONTACT_ID, $dataPhic->LOCATION_ID, $dataPhic->DATE_ADMITTED, $dataPhic->DATE_DISCHARGED);
        $RATE             = (float) $dataPhic->P1_TOTAL / $QTY;
        $INVOICE_ID       = $this->makeInvoice($dataPhic, $this->philHealthServices->TERM_ID, $PHILHEALTH_ID, $QTY, $this->philHealthServices->PHIL_HEALTH_ITEM_ID, $this->philHealthServices->TAX_ID, $RATE);
        $this->INVOICE_ID = $INVOICE_ID;
        return [
            'STATUS'     => true,
            'MESSAGE'    => 'Successfully save & invoice created',
            'INVOICE_ID' => $INVOICE_ID,
        ];
    }
    private function makeInvoice($data, int $TERM_ID, int $PHILHEALTH_ID, int $QTY, int $PHIL_HEALTH_ITEM_ID, int $TAX_ID, float $RATE): int
    {

        $DUE_DATE              = (string) $this->paymentTermServices->getDueDate($TERM_ID, $data->AR_DATE);
        $ACCOUNT_RECEIVABLE_ID = 4;
        $OUTPUT_TAX_ID         = 12;
        $OUTPUT_TAX_RATE       = 0;
        $OUTPUT_TAX_VAT_METHOD = 0;
        $OUTPUT_TAX_ACCOUNT_ID = 28;

        $INVOICE_ID = (int) $this->invoiceServices->Store(
            '',
            $data->AR_DATE,
            $data->CONTACT_ID,
            $data->LOCATION_ID,
            0,
            0,
            $data->AR_NO,
            '',
            0,
            null,
            $TERM_ID,
            $DUE_DATE,
            null,
            0,
            '',
            $ACCOUNT_RECEIVABLE_ID,
            15,
            $OUTPUT_TAX_ID,
            $OUTPUT_TAX_RATE,
            $OUTPUT_TAX_VAT_METHOD,
            $OUTPUT_TAX_ACCOUNT_ID,
            $PHILHEALTH_ID
        );

        $dataItem = $this->itemServices->get($PHIL_HEALTH_ITEM_ID);
        if ($dataItem) {
            // $RATE = $this->priceLevelLineServices->GetPriceByLocation($data->LOCATION_ID, $PHIL_HEALTH_ITEM_ID);
            $AMOUNT = $RATE * $QTY;

            $taxRate    = $this->taxServices->getRate($TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($AMOUNT, $dataItem->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                $TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $TAX_AMOUNT     = $tax_result['TAX_AMOUNT'];
            }

            $this->invoiceServices->ItemStore(
                $INVOICE_ID,
                $PHIL_HEALTH_ITEM_ID,
                $QTY,
                0,
                1,
                $RATE,
                0,
                $AMOUNT,
                $dataItem->TAXABLE ?? false,
                $TAXABLE_AMOUNT,
                $TAX_AMOUNT,
                $dataItem->COGS_ACCOUNT_ID ?? 0,
                $dataItem->ASSET_ACCOUNT_ID ?? 0,
                $dataItem->GL_ACCOUNT_ID ?? 0,
                0,
                0,
                0,
                0,
                0,
                0
            );

            $this->invoiceServices->ReComputed($INVOICE_ID);
        }

        // JOURNAL
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $INVOICE_ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->invoiceServices->object_type_invoice, $INVOICE_ID) + 1;
        }

        //Main
        $invoiceData = $this->invoiceServices->getInvoiceJournal($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceData, $data->LOCATION_ID, $this->invoiceServices->object_type_invoice, $data->AR_DATE);
        //Tax
        $invoiceDataTax = $this->invoiceServices->getInvoiceTaxJournal($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceDataTax, $data->LOCATION_ID, $this->invoiceServices->object_type_invoice, $data->AR_DATE);
        //Income
        $invoiceItemData = $this->invoiceServices->getInvoiceItemJournalIncome($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemData, $data->LOCATION_ID, $this->invoiceServices->object_type_invoice_item, $data->AR_DATE);

        PhilHealth::where('ID', '=', $PHILHEALTH_ID)
            ->update([
                'INVOICE_ID' => $INVOICE_ID,
            ]);

        return (int) $INVOICE_ID;

    }
    private function deleteItem(int $Id, $INVOICE_ID, $JOURNAL_NO)
    {
        $invoiceDate = $this->invoiceServices->get($INVOICE_ID);
        if ($invoiceDate) {
            $invoiceItemData = $this->invoiceServices->ItemGet($Id, $INVOICE_ID);
            if ($invoiceItemData) {
                // Inventory
                $this->itemInventoryServices->InventoryModify(
                    $invoiceItemData->ITEM_ID,
                    $invoiceDate->LOCATION_ID,
                    $Id,
                    $this->invoiceServices->document_type_id,
                    $invoiceDate->DATE,
                    0,
                    0,
                    0
                );

                // INCOME_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->INCOME_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    1,

                );
                // COGS_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->COGS_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    0,

                );
                // ASSET_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->ASSET_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    1,

                );
            }
        }
    }
    private function delete($INVOICE_ID)
    {
        try {

            $data = $this->invoiceServices->get($INVOICE_ID);
            if ($data) {
                if ($data->STATUS == 15) {
                    //Main
                    $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $INVOICE_ID);
                    $this->accountJournalServices->DeleteJournal(
                        $data->ACCOUNTS_RECEIVABLE_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $INVOICE_ID,
                        $this->invoiceServices->object_type_invoice,
                        $data->DATE,
                        0,

                    );
                    //Tax
                    $this->accountJournalServices->DeleteJournal(
                        $data->OUTPUT_TAX_ACCOUNT_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $INVOICE_ID,
                        $this->invoiceServices->object_type_invoice,
                        $data->DATE,
                        1,

                    );
                    $dataitem = $this->invoiceServices->ItemView($INVOICE_ID);
                    foreach ($dataitem as $list) {
                        $this->deleteItem($list->ID, $INVOICE_ID, $JOURNAL_NO);
                    }
                }
            }
            // Delete main
            $this->invoiceServices->Delete($INVOICE_ID);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function invoiceRefresh()
    {
        $invoice_data = $this->invoiceServices->get($this->INVOICE_ID);
        if ($invoice_data) {
            $this->INVOICE_CODE   = $invoice_data->CODE;
            $this->INVOICE_AMOUNT = $invoice_data->AMOUNT;
            return;
        }
        $this->INVOICE_CODE   = '';
        $this->INVOICE_AMOUNT = 0;
    }
    public function render()
    {
        if ($this->showModal) {
            $this->invoiceRefresh();
        }

        return view('livewire.phil-health.ar-form');
    }
}
