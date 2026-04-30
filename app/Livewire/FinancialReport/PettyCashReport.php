<?php
namespace App\Livewire\FinancialReport;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Balance Sheet Report')]
class PettyCashReport extends Component
{
    public bool $isDate = true;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public int $YEAR;
    public $locationList = [];
    public $dataList     = [];

    private $locationServices;
    private $userServices;
    private $dateServices;

    public string $tab = "date";
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function boot(

        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices

    ) {

        $this->locationServices = $locationServices;
        $this->userServices     = $userServices;
        $this->dateServices     = $dateServices;

    }
    public function mount()
    {
        $this->YEAR         = $this->dateServices->NowYear();
        $this->DATE_TO      = $this->userServices->getTransactionDateDefault();
        $this->DATE_FROM    = $this->dateServices->GetFirstDay_Year($this->DATE_TO);
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generateMonthly()
    {
        $this->isDate = false;
        $this->dispatch('balance-sheet-monthly', result: ['YEAR' => $this->YEAR, 'LOCATION_ID' => $this->LOCATION_ID]);
    }
    public function generate()
    {
        $this->isDate = true;
        $this->dispatch('balance-sheet-date-range', result: ['DATE_FROM' => $this->DATE_FROM, 'DATE_TO' => $this->DATE_TO, 'LOCATION_ID' => $this->LOCATION_ID]);
    }

    public function exportDaily()
    {
        $this->dispatch('export-daily-request');
    }
    public function exportMonthly()
    {
        $this->dispatch('export-monthly-request');
    }
    public function render()
    {
        return view('livewire.financial-report.petty-cash-report');
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
}
