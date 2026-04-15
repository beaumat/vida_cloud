<?php

namespace App\Livewire\BillPayments;

use App\Services\BillPaymentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ContactRecord extends Component
{
    #[Reactive]
    public int $CONTACT_ID;
    private $billPaymentServices;
    public function boot(BillPaymentServices $billPaymentServices)
    {
        $this->billPaymentServices = $billPaymentServices;
    }
    public function render()
    {
        $data = $this->billPaymentServices->getContactRecord($this->CONTACT_ID);
        return view('livewire.bill-payments.contact-record', ['dataList' => $data]);
    }
}
