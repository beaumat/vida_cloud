<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimerServices
{
    private $scheduleServices;
    private $hemoServices;
    private $dateServices;
    private $itemInventoryServices;
    private $serviceChargeServices;
    private $userServices;
    private $postingLogServices;
    private $philHealthServices;
    private $invoiceServices;
    private $accountJournalServices;
    private $paymentTermServices;
    private $itemServices;
    private $taxServices;
    private $computeServices;
    function __construct(
        ScheduleServices $scheduleServices,
        HemoServices $hemoServices,
        DateServices $dateServices,
        ItemInventoryServices $itemInventoryServices,
        ServiceChargeServices $serviceChargeServices,
        UserServices $userServices,
        PostingLogServices $postingLogServices,
        PhilHealthServices $philHealthServices,
        InvoiceServices $invoiceServices,
        AccountJournalServices $accountJournalServices,
        PaymentTermServices $paymentTermServices,
        ItemServices $itemServices,
        TaxServices $taxServices,
        ComputeServices $computeServices
    ) {
        $this->scheduleServices       = $scheduleServices;
        $this->hemoServices           = $hemoServices;
        $this->dateServices           = $dateServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->serviceChargeServices  = $serviceChargeServices;
        $this->userServices           = $userServices;
        $this->postingLogServices     = $postingLogServices;
        $this->philHealthServices     = $philHealthServices;
        $this->invoiceServices        = $invoiceServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->paymentTermServices    = $paymentTermServices;
        $this->itemServices           = $itemServices;
        $this->taxServices            = $taxServices;
        $this->computeServices        = $computeServices;

    }
    private function generateUnposted()
    {
        // $ php artisan schedule:work = must run per minute
        $unPostList = $this->hemoServices->GetUnpostedTreatment();
        foreach ($unPostList as $list) {
            $this->getPosted($list->CUSTOMER_ID, $list->DATE, $list->LOCATION_ID);
        }
    }
    private function generateWaitingList($transDate)
    {
        $schedlist = $this->scheduleServices->getWaitingList($transDate);
        foreach ($schedlist as $sched) {
            $this->getPosted($sched->CONTACT_ID, $sched->SCHED_DATE, $sched->LOCATION_ID);
        }
    }
    private function generateItemHemo($transDate)
    {

        $itemData = $this->hemoServices->CallOutItemUnPosted($transDate);

        foreach ($itemData as $list) {
            $this->updateUnpostedItemOnly($list->HEMO_ID);
            Log::warning('Done Hemo ID:' . $list->HEMO_ID . ' - DATE:' . $list->DATE . '- LOCATION_ID:' . $list->LOCATION_ID);
        }

    }
    private function updateUnpostedItemOnly(int $HEMO_ID)
    {
        DB::beginTransaction();
        try {
            $this->hemoServices->getMakeJournal($HEMO_ID);
            $this->hemoServices->makeItemInventory($HEMO_ID);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error executing generateItem() : ' . $th->getMessage());
        }
    }
    private function GenerateItemServiceCharges($transDate)
    {

        DB::beginTransaction();
        try {
            $SOURCE_REF_TYPE = 29;
            $itemData        = $this->serviceChargeServices->GetWalkInServiceChargeTransaction($transDate);
            foreach ($itemData as $list) {
                $QTY = (float) ($list->QUANTITY * $list->UNIT_BASE_QUANTITY ?? 1) * -1;
                $this->itemInventoryServices->InventoryModify(
                    $list->ITEM_ID,
                    $list->LOCATION_ID,
                    $list->ID,
                    $SOURCE_REF_TYPE,
                    $list->DATE,
                    0,
                    $QTY,
                    $list->COST ?? 0
                );
            }
            $this->serviceChargeServices->GetWalkInServiceChargePosted($transDate); // to update update
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error executing SC generateItem() : ' . $th->getMessage());
        }
    }
    private function GenerateItemServiceChargesMakeJournalPhic156($transDate)
    {

        $dataList = $this->serviceChargeServices->GetItemPhic156UnInvoice($transDate, $this->philHealthServices->PHIL_HEALTH_ITEM_ID);

        foreach ($dataList as $data) {
            $this->setSC_TO_INVOICE($data);
        }

    }
    private function setSC_TO_INVOICE($data)
    {
        DB::beginTransaction();
        try {

            $INVOICE_ID = $this->makeInvoice($data,
                3,
                1,
                $this->philHealthServices->PHIL_HEALTH_ITEM_ID,
            );
            $this->serviceChargeServices->UpdateInvoiceID($data->ID, $INVOICE_ID);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            dd($th->getMessage());

        }
    }

    private function makeInvoice($dataSC, int $TERM_ID, int $QTY, int $PHIL_HEALTH_ITEM_ID): int
    {

        $DUE_DATE              = (string) $this->paymentTermServices->getDueDate($TERM_ID, $dataSC->DATE);
        $ACCOUNT_RECEIVABLE_ID = 4;
        $OUTPUT_TAX_ID         = 12;
        $OUTPUT_TAX_RATE       = 0;
        $OUTPUT_TAX_VAT_METHOD = 0;
        $OUTPUT_TAX_ACCOUNT_ID = 28;

        $INVOICE_ID = (int) $this->invoiceServices->Store(
            '',
            $dataSC->DATE,
            $dataSC->PATIENT_ID,
            $dataSC->LOCATION_ID,
            0,
            0,
            '',
            '',
            0,
            null,
            $TERM_ID,
            $DUE_DATE,
            null,
            0,
            'SERVICE CHARGE PHIC 156',
            $ACCOUNT_RECEIVABLE_ID,
            15,
            $OUTPUT_TAX_ID,
            $OUTPUT_TAX_RATE,
            $OUTPUT_TAX_VAT_METHOD,
            $OUTPUT_TAX_ACCOUNT_ID,
            0,
        );

        $dataItem = $this->itemServices->get($PHIL_HEALTH_ITEM_ID);
        if ($dataItem) {
            // $RATE = $this->priceLevelLineServices->GetPriceByLocation($data->LOCATION_ID, $PHIL_HEALTH_ITEM_ID);
            $AMOUNT = $dataSC->AMOUNT ?? 0 * $QTY;
            $taxRate    = $this->taxServices->getRate(0);
            $tax_result = $this->computeServices->ItemComputeTax($AMOUNT, $dataItem->TAXABLE, 0, $taxRate);
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
                $dataSC->AMOUNT ?? 0,
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
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceData, $dataSC->LOCATION_ID, $this->invoiceServices->object_type_invoice, $dataSC->DATE);
        //Tax
        $invoiceDataTax = $this->invoiceServices->getInvoiceTaxJournal($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceDataTax, $dataSC->LOCATION_ID, $this->invoiceServices->object_type_invoice, $dataSC->DATE);
        //Income
        $invoiceItemData = $this->invoiceServices->getInvoiceItemJournalIncome($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemData, $dataSC->LOCATION_ID, $this->invoiceServices->object_type_invoice_item, $dataSC->DATE);

        return (int) $INVOICE_ID;

    }
    public function getExecute()
    {
        $transDate = $this->dateServices->NowDate();

        $this->generateUnposted();
        $this->generateWaitingList($transDate);
        $this->GenerateItemServiceCharges($transDate);
        $this->generateItemHemo($transDate);
        $this->userDefaultUserDate();
        $this->postingLogServices->logPosting($transDate);

        $this->GenerateItemServiceChargesMakeJournalPhic156($transDate);

    }
    public function getExecutePrevious()
    {

        $transDate = $this->dateServices->BackDate();
        $this->generateUnposted();
        $this->generateWaitingList($transDate);
        $this->GenerateItemServiceCharges($transDate);
        $this->generateItemHemo($transDate);
        $this->userDefaultUserDate();

        $this->postingLogServices->logPosting($transDate);

        $this->GenerateItemServiceChargesMakeJournalPhic156($transDate);
    }
    public function getPosted(int $CONTACT_ID, string $DATE, int $LOCATION_ID)
    {
        try {

            $data = $this->hemoServices->getTreatmentID($CONTACT_ID, $DATE, $LOCATION_ID);

            $ID         = (int) $data['ID']; //HEMO_ID
            $TIME_START = empty($data['TIME_START']) ? null : $data['TIME_START'];
            $TIME_END   = empty($data['TIME_END']) ? null : $data['TIME_END'];
            $STATUS_ID  = (int) $data['STATUS_ID'];
            $IS_PF      = (bool) $data['IS_PF'];

            $PRE_WEIGHT          = (int) $data['PRE_WEIGHT'];
            $PRE_BLOOD_PRESSURE  = (int) $data['PRE_BLOOD_PRESSURE'];
            $PRE_BLOOD_PRESSURE2 = (int) $data['PRE_BLOOD_PRESSURE2'];
            $PRE_HEART_RATE      = (int) $data['PRE_HEART_RATE'];
            $PRE_O2_SATURATION   = (int) $data['PRE_O2_SATURATION'];

            $POST_WEIGHT          = (int) $data['POST_WEIGHT'];
            $POST_BLOOD_PRESSURE  = (int) $data['POST_BLOOD_PRESSURE'];
            $POST_BLOOD_PRESSURE2 = (int) $data['POST_BLOOD_PRESSURE2'];
            $POST_HEART_RATE      = (int) $data['POST_HEART_RATE'];
            $POST_O2_SATURATION   = (int) $data['POST_O2_SATURATION'];
            $IS_INCOMPLETE        = (bool) $data['IS_INCOMPLETE'];

            DB::beginTransaction();

            if ($ID > 0) {
                if ($IS_INCOMPLETE == true) {
                    // Do nothing
                } elseif ($IS_PF == true) {
                    // Do nothing
                } else {
                    if ($PRE_WEIGHT == 0 || $PRE_BLOOD_PRESSURE == 0 || $PRE_BLOOD_PRESSURE2 == 0 || $PRE_HEART_RATE == 0 || $PRE_O2_SATURATION == 0) {
                        $this->hemoServices->StatusUpdate($ID, 3);                                  // VOID
                        $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 2); // ABSENT
                        DB::commit();
                        return;
                    }
                }

                $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 1); //PRESENT
                if ($POST_WEIGHT == 0 || $POST_BLOOD_PRESSURE == 0 || $POST_BLOOD_PRESSURE2 == 0 || $POST_HEART_RATE == 0 || $POST_O2_SATURATION == 0 || empty($TIME_START) == true || empty($TIME_END) == true) {
                    if ($IS_INCOMPLETE == true || $IS_PF == true) {
                        $this->hemoServices->StatusUpdate($ID, 2);   // POSTED
                        $this->hemoServices->getMakeJournal($ID);    // to journal
                        $this->hemoServices->makeItemInventory($ID); // item inventory
                    } else {
                        $this->hemoServices->StatusUpdate($ID, 4); // UNPOSTED
                    }
                } else {
                    $this->hemoServices->StatusUpdate($ID, 2);   // POSTED
                    $this->hemoServices->getMakeJournal($ID);    // to journal
                    $this->hemoServices->makeItemInventory($ID); // item inventory
                }
                DB::commit();
            } else {
                $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 3); // CANCELLED
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error executing Schedule executed in getPosted: ' . $e->getMessage() . '[' . $CONTACT_ID . ',' . $LOCATION_ID . ', ' . $DATE . ']');
        }
    }
    public function userDefaultUserDate()
    {
        $this->userServices->resetDefaultTime();
    }
}
