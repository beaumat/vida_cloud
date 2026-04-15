<?php

namespace App\Livewire\Invoice;

use App\Services\InvoiceServices;
use App\Services\SalesOrderServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SalesOrderListPromp extends Component
{
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $INVOICE_ID;
    public $showModal = false;
    private $invoiceServices;
    private $salesOrderServices;
    public $dataList = [];
    public function boot(InvoiceServices $invoiceServices, SalesOrderServices $salesOrderServices)
    {
        $this->invoiceServices = $invoiceServices;
        $this->salesOrderServices = $salesOrderServices;
    }

    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function createToInvoice(int $SALES_ORDER_ID)
    {
        try {
            //code...

            $salesOrder = $this->salesOrderServices->get($SALES_ORDER_ID);

            if ($salesOrder) {

                $itemList = $this->salesOrderServices->GetItemList($SALES_ORDER_ID);
                DB::beginTransaction();
                foreach ($itemList as $list) {

                    $this->invoiceServices->ItemStore(
                        $this->INVOICE_ID,
                        $list->ITEM_ID,
                        $list->QUANTITY,
                        $list->UNIT_ID ?? 0,
                        $list->UNIT_BASE_QUANTITY ?? 1,
                        $list->RATE ?? 0,
                        $list->RATE_TYPE ?? 0,
                        $list->AMOUNT ?? 0,
                        $list->TAXABLE ?? false,
                        $list->TAXABLE_AMOUNT ?? 0,
                        $list->TAX_AMOUNT ?? 0,
                        $list->COGS_ACCOUNT_ID > 0 ? $list->COGS_ACCOUNT_ID : 0,
                        $list->ASSET_ACCOUNT_ID > 0 ? $list->ASSET_ACCOUNT_ID : 0,
                        $list->GL_ACCOUNT_ID > 0 ? $list->GL_ACCOUNT_ID : 0,
                        $list->ID,
                        0,
                        $list->GROUP_LINE_ID ?? 0,
                        $list->PRINT_IN_FORMS ?? false,
                        false,
                        $list->PRICE_LEVEL_ID ?? 0
                    );

                    $this->salesOrderServices->UpdateItemInvoice($list->ID, $list->QUANTITY, true);
                }

                $this->salesOrderServices->StatusUpdate($SALES_ORDER_ID, 3);
                DB::commit();
                $getResult = $this->invoiceServices->ReComputed($this->INVOICE_ID);
                $this->dispatch('update-amount', result: $getResult);

                $this->closeModal();
            }

        } catch (\Exception $ex) {

            DB::rollBack();
   
        }

    }
    public function render()
    {
        $this->dataList = $this->salesOrderServices->SalesOrderListAvailable($this->CUSTOMER_ID, $this->LOCATION_ID);

        return view('livewire.invoice.sales-order-list-promp');
    }
}
