<?php
namespace App\Livewire\ReceivableReport;

use App\Services\AgingServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Customer Balance Report")]
class CustomerBalance extends Component
{

    public bool $IS_SUMMARY = true;
    public string $DATE;
    public int $LOCATION_ID;
    public $dataList     = [];
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $agingServices;
    public $DATE_FROM;
    public $DATE_TO;
    private $dateServices;
    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        AgingServices $agingServices,
        DateServices $dateServices
    ) {

        $this->locationServices = $locationServices;
        $this->userServices     = $userServices;
        $this->agingServices    = $agingServices;
        $this->dateServices     = $dateServices;
    }

    public function mount()
    {
        $this->DATE         = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();

        $this->DATE_TO   = $this->userServices->getTransactionDateDefault();
        $this->DATE_FROM = $this->dateServices->GetFirstDay_Month($this->DATE_TO);

    }
    public function generate()
    {
        $this->IS_SUMMARY = true;
        $this->dataList   = $this->agingServices->CustomerBalance($this->DATE, $this->LOCATION_ID, []);
    }
    public function generateDetails()
    {
        $this->IS_SUMMARY = false;
        $this->dataList   = $this->agingServices->CustomerBalanceDetails($this->DATE, $this->LOCATION_ID, []);
    }
    public function generateByRange()
    {
        $this->IS_SUMMARY = true;
        $this->dataList   = $this->agingServices->CustomerBalanceByRange($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, []);
    }
    public function generateByRangeDetails()
    {
        $this->IS_SUMMARY = false;
        $this->dataList   = $this->agingServices->CustomerBalanceDetailsByRange($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, []);
    }
    public function render()
    {
        return view('livewire.receivable-report.customer-balance');
    }
}
