<?php

namespace App\Livewire\Invoice;

use App\Services\ContactServices;
use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\PaymentTermServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Print Invoice')]
class PrintInvoice extends Component
{

    public int $rows = 0;
    public string $LOGO_FILE = '';
    public int $INVOICE_ID;
    public string $CODE;
    public string $DATE;
    public string $TERMS;
    public  int $CUSTOMER_ID;
    public string $CONTACT_NAME;
    public int $LOCATION_ID;
    public string $LOCATION_NAME;
    public float $AMOUNT;
    public string $NOTES;
    public string $PO_NUMBER;
    public string $DR_NAME;
    public string $ADDRESS;
    public string $CONTACT_NO;
    public $itemList = [];
    private $invoiceServices;
    private $contactServices;
    private $locationServices;
    private $paymentTermServices;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public function boot(
        InvoiceServices $invoiceServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        PaymentTermServices $paymentTermServices
    ) {
        $this->invoiceServices = $invoiceServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->paymentTermServices = $paymentTermServices;
    }
    public function mount($id = null)
    {

        $data = $this->invoiceServices->get($id);
        if ($data) {
            $this->INVOICE_ID = $data->ID;
            $this->CUSTOMER_ID = $data->CUSTOMER_ID;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->CODE = $data->CODE;
            $this->DATE = $data->DATE;
            $this->NOTES = $data->NOTES;
            $this->AMOUNT = $data->AMOUNT;
            $this->PO_NUMBER = $data->PO_NUMBER ?? '';
            $this->TERMS = $this->paymentTermServices->get($data->PAYMENT_TERMS_ID ?? 0);


            $con = $this->contactServices->getSingleData($this->CUSTOMER_ID);
            if ($con) {
                $this->CONTACT_NAME = $con->PRINT_NAME_AS;
                $this->CONTACT_NO = $con->TELEPHONE_NO ?? ' ' .  $con->MOBILE_NO ?? ' ';
                $this->ADDRESS = $con->POSTAL_ADDRESS ?? '';
                $this->DR_NAME = "";
            }
            $locData = $this->locationServices->get($this->LOCATION_ID);
            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                $this->LOCATION_NAME  = $locData->NAME;
                $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
            }
            $this->itemList = $this->invoiceServices->ItemView($this->INVOICE_ID);
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
        return view('livewire.invoice.print-invoice');
    }
}
