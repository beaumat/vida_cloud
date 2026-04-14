<?php

namespace App\Livewire\ReceivableReport;

use App\Exports\ARAgingExportReport;
use App\Services\AgingServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title("Accounts Receivable Aging Reports")]
class AccountReceivableAging extends Component
{
    public bool $D_CURRENT = false;
    public bool $D_1_30 = false;
    public bool $D_31_60 = false;
    public bool $D_61_90 = false;
    public bool $D_91_OVER = false;

    public bool $isSummary = true;
    public string $DATE;
    public int $LOCATION_ID;
    public $locationList = [];
    public $summaryList = [];
    public $detailList = [];
    private $locationServices;
    private $userServices;
    private $agingServices;
    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        AgingServices $agingServices
    ) {

        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->agingServices = $agingServices;
    }
    public function mount()
    {
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function summary()
    {
        $this->isSummary = true;
        $this->summaryList = $this->agingServices->ARAgingSummary($this->DATE, $this->LOCATION_ID, []);
    }
    public function details()
    {
        $this->D_CURRENT = false;
        $this->D_1_30 = false;
        $this->D_31_60 = false;
        $this->D_61_90 = false;
        $this->D_91_OVER = false;
        $this->isSummary = false;
        $this->detailList =  $this->agingServices->ARAgingDetais($this->DATE, $this->LOCATION_ID, []);
    }

    public function export()
    {
        if ($this->isSummary) {
            if (!$this->summaryList) {
                return;
            }
            return Excel::download(new ARAgingExportReport(
                $this->summaryList,
                $this->isSummary
            ), 'ar-report-summary-export.xlsx');
        }
        if (!$this->detailList) {
            return;
        }
        return Excel::download(new ARAgingExportReport(
            $this->detailList,
            $this->isSummary
        ), 'ar-report-details-export.xlsx');
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
        return view('livewire.receivable-report.account-receivable-aging');
    }
}
