<?php

namespace App\Livewire\BillPayments;

use App\Services\BillPaymentServices;
use App\Services\ContactServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Bill Payments')]
class BillPaymentPrint extends Component
{
    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $BANK_ACCOUNT_ID;
    public int $PAY_TO_ID;
    public int $LOCATION_ID;
    public float $AMOUNT;
    public string $NOTES;
    public int $ACCOUNTS_PAYABLE_ID;
    public int $CONTACT_TYPE;
    public string $CONTACT_NAME;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public string $LOCATION_NAME;
    public string $LOGO_FILE;
    public $billList = [];
    private $billPaymentServices;
    private $contactServices;
    private $locationServices;
    public function boot(BillPaymentServices $billPaymentServices, ContactServices $contactServices, LocationServices $locationServices)
    {
        $this->billPaymentServices = $billPaymentServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }
    public function mount($id = null)
    {

        $data = $this->billPaymentServices->get($id);
        if ($data) {
            $this->ID = $data->ID;
            $this->PAY_TO_ID = $data->PAY_TO_ID;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->CODE = $data->CODE;
            $this->DATE = $data->DATE;
            $this->NOTES = $data->NOTES;
            $this->AMOUNT = $data->AMOUNT;
            $con = $this->contactServices->getSingleData($this->PAY_TO_ID);
            if ($con) {
                $this->CONTACT_NAME = $con->PRINT_NAME_AS;
                $this->CONTACT_TYPE = $con->TYPE ?? 0;
            }
            $locData = $this->locationServices->get($this->LOCATION_ID);
            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                $this->LOCATION_NAME  = $locData->NAME;
                $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
            }
            
            $this->billList = $this->billPaymentServices->billPaymentBills($this->ID);
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
        return view('livewire.bill-payments.bill-payment-print');
    }
}
