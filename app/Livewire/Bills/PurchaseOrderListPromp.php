<?php

namespace App\Livewire\Bills;

use App\Services\BillingServices;
use App\Services\PurchaseOrderServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PurchaseOrderListPromp extends Component
{
    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $BILL_ID;

    #[Reactive]
    public int $STATUS;
    public $showModal = false;
    private $billingServices;
    private $purchaseOrderServices;
    public $dataList = [];
    public function boot(BillingServices $billingServices, PurchaseOrderServices $purchaseOrderServices)
    {
        $this->billingServices = $billingServices;
        $this->purchaseOrderServices = $purchaseOrderServices;
    }

    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function createToBill(int $PO_ID)
    {
        try {
            //code...
            $po = $this->purchaseOrderServices->get($PO_ID);
            if ($po) {
                $itemList = $this->purchaseOrderServices->GetItemList($PO_ID);      
                DB::beginTransaction();
                foreach ($itemList as $list) {
                    $this->billingServices->ItemStore(
                        $this->BILL_ID,
                        $list->ITEM_ID,
                        $list->QUANTITY,
                        $list->UNIT_ID ?? 0,
                        $list->UNIT_BASE_QUANTITY ?? 1,
                        $list->RATE ?? 0,
                        $list->RATE_TYPE ?? 0,
                        $list->AMOUNT ?? 0,
                        0,
                        $list->ASSET_ACCOUNT_ID,
                        $list->ID,
                        $list->TAXABLE ?? false,
                        $list->TAXABLE_AMOUNT ?? 0,
                        $list->TAX_AMOUNT ?? 0,
                        0
                    );
                    $this->purchaseOrderServices->UpdateItemBills($list->ID, $list->QUANTITY, true);
                }

                $this->purchaseOrderServices->StatusUpdate($PO_ID, 3);
                DB::commit();
                $getResult = $this->billingServices->ReComputed($this->BILL_ID);
                $this->dispatch('update-amount', result: $getResult);
                $this->closeModal();
            }

        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage());
        }

    }
    public function render()
    {
        $this->dataList = $this->purchaseOrderServices->PurchaseOrderAvailableList($this->VENDOR_ID, $this->LOCATION_ID);
        return view('livewire.bills.purchase-order-list-promp');
    }
}
