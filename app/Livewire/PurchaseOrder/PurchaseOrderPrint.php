<?php

namespace App\Livewire\PurchaseOrder;

use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\PurchaseOrderServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("PO Print")]
class PurchaseOrderPrint extends Component
{

    public float $poItemAmount = 0;

    public int $rows = 0;
    public $LOGO_FILE = null;
    public int $PO_ID;
    public string $CODE;
    public string $DATE;
    public  int $VENDOR_ID;
    public string $CONTACT_NAME;
    public int $LOCATION_ID;
    public string $LOCATION_NAME;
    public float $AMOUNT;
    public string $NOTES;
    public $itemList = [];

    private $purchaseOrderServices;
    private $contactServices;
    private $locationServices;

    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public function boot(PurchaseOrderServices $purchaseOrderServices, ContactServices $contactServices, LocationServices $locationServices)
    {
        $this->purchaseOrderServices = $purchaseOrderServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }
    public function mount($id = null)
    {

        $data = $this->purchaseOrderServices->get($id);
        if ($data) {
            $this->PO_ID = $data->ID;
            $this->VENDOR_ID = $data->VENDOR_ID;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->CODE = $data->CODE;
            $this->DATE = $data->DATE;
            $this->NOTES = $data->NOTES;
            $this->AMOUNT = $data->AMOUNT;
            $con = $this->contactServices->getSingleData($this->VENDOR_ID);
            if ($con) {
                $this->CONTACT_NAME = $con->PRINT_NAME_AS;
            }
            $locData = $this->locationServices->get($this->LOCATION_ID);
            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                $this->LOCATION_NAME  = $locData->NAME;
                $this->LOGO_FILE = $locData->LOGO_FILE ?? null;
            }
            $this->itemList = $this->purchaseOrderServices->ItemView($this->PO_ID);
            $this->dispatch('preview_print');
            return;
        }
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.purchase-order.purchase-order-print');
    }
}
