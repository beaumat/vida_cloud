<?php
namespace App\Livewire\FinancialReport;

use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\NumberServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Income Statement Report')]
class IncomeStatementReport extends Component
{
    public bool $isDate = true;
    public int $YEAR;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];

    private $financialStatementServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $numberServices;
    public function boot(
        FinancialStatementServices $financialStatementServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        NumberServices $numberServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->dateServices               = $dateServices;
        $this->locationServices           = $locationServices;
        $this->userServices               = $userServices;
        $this->numberServices             = $numberServices;
    }
    public function mount()
    {
        $this->YEAR         = $this->dateServices->NowYear();
        $this->DATE_TO      = $this->userServices->getTransactionDateDefault();
        $this->DATE_FROM    = $this->dateServices->GetFirstDay_Month($this->DATE_TO);
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public string $tab = "date";
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function generate()
    {
        $this->isDate = true;
        $this->dispatch('income-date-range', result: ['DATE_FROM' => $this->DATE_FROM, 'DATE_TO' => $this->DATE_TO, 'LOCATION_ID' => $this->LOCATION_ID]);

    }
    public function generateMonthly()
    {

        $this->isDate = false;
        $this->dispatch('income-monthly', result: ['YEAR' => $this->YEAR, 'LOCATION_ID' => $this->LOCATION_ID]);

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
        return view('livewire.financial-report.income-statement-report');
    }
}
