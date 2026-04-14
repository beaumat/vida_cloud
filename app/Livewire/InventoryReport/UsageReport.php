<?php
namespace App\Livewire\InventoryReport;

use App\Services\DateServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Inventory Usage Report')]
class UsageReport extends Component
{

  public $dataList = [];
    public $DATE;
    public int $LOCATION_ID;
    public string $REPORT_TYPE = 'MONTHLY';
    public int $SelectYear;
    public int $SelectMonth;

    public $yearList = [];
    public $monthList  = [];
    public $locationList = [];
    private $itemInventoryServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    public function boot(ItemInventoryServices $itemInventoryServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->itemInventoryServices = $itemInventoryServices;
        $this->userServices = $userServices;
        $this->locationServices = $locationServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->DATE = $this->dateServices->NowDate();
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->yearList = $this->dateServices->YearList();
        $this->monthList = $this->dateServices->MonthList();

        $defaultDate = $this->userServices->getTransactionDateDefault();

        $this->SelectMonth = date('m', strtotime($defaultDate));
        $this->SelectYear = date('Y', strtotime($defaultDate));
    }
    public function Generate()
    {
        if ($this->REPORT_TYPE == 'MONTHLY') {
            $this->dataList =  $this->itemInventoryServices->UsageReportMonthly($this->SelectYear, $this->SelectMonth, $this->LOCATION_ID);
        } else {
            $this->dataList =  $this->itemInventoryServices->UsageReportDaily($this->DATE, $this->LOCATION_ID);
        }
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    public function render()
    {
        return view('livewire.inventory-report.usage-report');
    }
}
