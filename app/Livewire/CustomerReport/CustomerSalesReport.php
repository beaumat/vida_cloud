<?php
namespace App\Livewire\CustomerReport;

use App\Exports\DynamicExport;
use App\Services\CustomerServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PaymentMethodServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title("Customer Sales Report")]
class CustomerSalesReport extends Component
{
    public float $TOTAL = 0;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];
    public int $PAYMENT_METHOD_ID;
    public $paymentMethodList = [];

    public $dataList = [];

    private $customerServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $paymentMethodServices;
    public function boot(
        CustomerServices $customerServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        PaymentMethodServices $paymentMethodServices
    ) {
        $this->customerServices      = $customerServices;
        $this->dateServices          = $dateServices;
        $this->locationServices      = $locationServices;
        $this->userServices          = $userServices;
        $this->paymentMethodServices = $paymentMethodServices;
    }
    public function mount()
    {
        $this->DATE_FROM   = $this->dateServices->NowDate();
        $this->DATE_TO     = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();

        $this->locationList      = $this->locationServices->getList();
        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();
        $this->PAYMENT_METHOD_ID = 0;
    }
    public function generateExcel()
    {

        $dataSource     = $this->customerServices->GenerateSales($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, $this->PAYMENT_METHOD_ID);
        $this->dataList = $dataSource;
        if (! $dataSource || count($dataSource) == 0) {
            session()->flash('error', 'Please click generate first ');
            return;
        }
        try {

            $headers     = ['Type', 'Reference No.', 'Date', 'Payment Method', 'Customer', 'OR Number', 'Amount', 'Location']; // Could be dynamic based on UI
            $rowdata     = [];
            $totalAmount = 0;
            foreach ($dataSource as $item) {
                $rowdata[] = [
                    'Type'           => $item->TYPE,
                    'Reference No.'  => $item->CODE,
                    'Date'           => date('M/d/Y', strtotime($item->DATE)),
                    'Payment Method' => $item->PAYMENT_METHOD,
                    'Customer'       => $item->CONTACT_NAME,
                    'OR Number'      => ' ' . $item->OR_NUMBER,
                    'Amount'         => $item->AMOUNT,
                    'Location'       => $item->LOCATION_NAME,
                ];

                $totalAmount += $item->AMOUNT;

            }
            $rowdata[]  = [
                'Type'           => '',
                'Reference No.'  => '',
                'Date'           => '',
                'Payment Method' => '',
                'Customer'       => '',
                'OR Number'      => '',
                'Amount'         => '',
                'Location'       => '',
            ];

            $rowdata[] = [
                'Type'           => '',
                'Reference No.'  => '',
                'Date'           => '',
                'Payment Method' => '',
                'Customer'       => '',
                'OR Number'      => 'TOTAL:',
                'Amount'         => $totalAmount,
                'Location'       => '',
            ];

            return Excel::download(new DynamicExport($headers, $rowdata), 'CustomerSalesReport.xlsx');

        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
        }
    }
    public function generate()
    {
        try {
            $this->TOTAL    = 0;
            $this->dataList = $this->customerServices->GenerateSales($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, $this->PAYMENT_METHOD_ID);
        } catch (\Exception $ex) {
            session()->flash('error', $ex->getMessage());
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function updatedlocationid()
    {
        try {
            $this->dataList = [];
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.customer-report.customer-sales-report');
    }
}
