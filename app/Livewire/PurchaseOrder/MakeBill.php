<?php

namespace App\Livewire\PurchaseOrder;

use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\DateServices;
use App\Services\PaymentTermServices;
use App\Services\PurchaseOrderServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Component;

class MakeBill extends Component
{

    public string $DATE;
    public bool $showModal = false;
    public int $PO_ID;
    public string $CODE;
    public string $NOTES;
    public string $PO_DATE;
    public int $LOCATION_ID;
    private $purchaseOrderServices;
    private  $dateServices;
    private $billingServices;
    private $paymentTermServices;
    private $accountServices;
    public function boot(
        PurchaseOrderServices $purchaseOrderServices,
        DateServices $dateServices,
        BillingServices $billingServices,
        PaymentTermServices $paymentTermServices,
        AccountServices $accountServices
    ) {
        $this->purchaseOrderServices = $purchaseOrderServices;
        $this->dateServices = $dateServices;
        $this->billingServices = $billingServices;
        $this->paymentTermServices =  $paymentTermServices;
        $this->accountServices = $accountServices;
    }
    #[On('open-make-bill')]
    public function openModal($purchase)
    {
        $dataPO = $this->purchaseOrderServices->get($purchase['PO_ID']);

        if ($dataPO) {
            $this->PO_ID = $dataPO->ID;
            $this->PO_DATE = $dataPO->DATE;
            $this->DATE = $this->dateServices->NowDate();
            $this->LOCATION_ID = $dataPO->LOCATION_ID;
            $this->showModal = true;
            $this->NOTES = $dataPO->NOTES ?? '';
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save()
    {
        $this->validate([
            'DATE' => 'required|date',
        ], [], [
            'Date' => 'Billing Date'
        ]);

        if ($this->PO_DATE > $this->DATE) {
            session()->flash('error', 'Invalid Date');
            return;
        }

        DB::beginTransaction();

        try {

            $poData = $this->purchaseOrderServices->get($this->PO_ID);
            if ($poData) {
                $DUE_DATE = $this->paymentTermServices->getDueDate($poData->PAYMENT_TERMS_ID ?? 0, $this->DATE);
                $ACCOUNTS_PAYABLE_ID = $this->accountServices->getByName('Accounts Payable');
                $BILL_ID =  (int) $this->billingServices->store(
                    $this->CODE ?? '',
                    $this->DATE,
                    $poData->VENDOR_ID,
                    $poData->LOCATION_ID,
                    $poData->PAYMENT_TERMS_ID,
                    $DUE_DATE,
                    '',
                    0,
                    $this->NOTES,
                    $ACCOUNTS_PAYABLE_ID,
                    $poData->INPUT_TAX_ID,
                    $poData->INPUT_TAX_RATE,
                    $poData->INPUT_TAX_AMOUNT,
                    $poData->INPUT_TAX_VAT_METHOD,
                    $poData->INPUT_TAX_ACCOUNT_ID,
                    0
                );

                $poItem = $this->purchaseOrderServices->GetItemList($this->PO_ID);

                foreach ($poItem as $list) {

                    $this->billingServices->ItemStore(
                        $BILL_ID,
                        $list->ITEM_ID,
                        $list->QUANTITY,
                        $list->UNIT_ID,
                        $list->UNIT_BASE_QUANTITY ?? 0,
                        $list->RATE ?? 0,
                        $list->RATE_TYPE,
                        $list->AMOUNT,
                        0,
                        $list->ASSET_ACCOUNT_ID,
                        $list->ID,
                        $list->TAXABLE,
                        $list->TAXABLE_AMOUNT,
                        $list->TAX_AMOUNT,
                        0
                    );
                }


                $this->billingServices->ReComputed($BILL_ID);
                DB::commit();
                return Redirect::route('vendorsbills_edit', ['id' => $BILL_ID])->with('message', 'Successfully created');
            }
            DB::rollBack();
        } catch (\Exception $ex) {
            DB::rollBack();
            session()->flash('error', $ex->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.purchase-order.make-bill');
    }
}
