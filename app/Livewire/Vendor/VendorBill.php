<?php

namespace App\Livewire\Vendor;

use App\Services\BillingServices;
use Livewire\Component;

class VendorBill extends Component
{
    public $dataList = [];
    public int $contact_id = 0;
    private $billingServices;

    public function boot(BillingServices $billingServices)
    {
        $this->billingServices = $billingServices;
    }
    public function mount($id = 0)
    {
        // Initialize the contact_id with the provided id or default to 0
        $this->contact_id = $id;
    }

    public function render()
    {
        // Fetch the list of bills for the contact_id
        $this->dataList = $this->billingServices->listViaContact($this->contact_id);
        
        return view('livewire.vendor.vendor-bill');
    }
}
