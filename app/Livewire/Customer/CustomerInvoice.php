<?php

namespace App\Livewire\Customer;

use App\Services\InvoiceServices;
use Livewire\Component;

class CustomerInvoice extends Component
{

    public int $customerId;
    public $dataList = [];
    private $invoiceServices;
    public function boot(InvoiceServices $invoiceServices)
    {
        $this->invoiceServices = $invoiceServices;
    }
    public function mount(int $id = 0)
    {
        $this->customerId = $id;
    }
    public function render()
    {
        $this->dataList = $this->invoiceServices->listViaContact($this->customerId);
        // Fetch the list of invoices for the customerId
        return view('livewire.customer.customer-invoice');
    }
}
