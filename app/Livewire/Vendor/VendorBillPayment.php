<?php

namespace App\Livewire\Vendor;

use App\Services\BillPaymentServices;
use Livewire\Component;

class VendorBillPayment extends Component
{
    public $dataList = [];
    public int $contact_id = 0;
    private $billPaymentServices;
    public function boot(BillPaymentServices $billPaymentServices)
    {
        $this->billPaymentServices = $billPaymentServices;

    }
    public function mount($id = 0)
    {
        // Initialize the contact_id with the provided id or default to 0
        $this->contact_id = $id;
    }

    public function render()
    {
        $this->dataList = $this->billPaymentServices->listViaContact($this->contact_id);

        return view('livewire.vendor.vendor-bill-payment');
    }
}
