<?php

namespace App\Livewire\PatientReport;

use App\Exports\DynamicExport;
use App\Services\DateServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\PatientReportServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Patient Inventory Report')]
class PatientInventoryReport extends Component
{

    public bool $refreshComponent = false;
    public $DATE_FROM;
    public $DATE_TO;
    public int $LOCATION_ID;
    public int $ITEM_ID = 0;
    public $locationList = [];
    public $dataList = [];
    public $itemList = [];
    private $patientReportServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $itemServices;
    public function boot(
        PatientReportServices $patientReportServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        ItemServices $itemServices
    ) {
        $this->patientReportServices = $patientReportServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->itemServices = $itemServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
        $this->itemList = $this->itemServices->getInventoryItem(false);
    }
    public function generate()
    {

        $this->dataList = $this->patientReportServices->getInventoryList(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->ITEM_ID
        );
    }
    public function generateExcel()
    {

        if (!$this->dataList) {
            session()->flash('error', 'Please click geenerate first ');
            return;
        }


        try {

            $headers = ['Name', 'Item Code', 'Item Name', 'Quantity', 'Unit', 'Post', 'Date', 'Reference', 'Location']; // Could be dynamic based on UI
            $rowdata = [];
            foreach ($this->dataList as $item) {
                $rowdata[] = [
                    'Name' => $item->PATIENT_NAME,
                    'Item Code' => $item->ITEM_CODE,
                    'Item Name' => $item->ITEM_NAME,
                    'Quantity' => $item->QUANTITY,
                    'Unit' => $item->UNIT,
                    'Post' => $item->POST ? 'Yes' : 'No',
                    'Date' => $item->DATE,
                    'Reference' => $item->REFERENCE,
                    'Location' => $item->LOCATION_NAME
                ];
            }

            return Excel::download(new DynamicExport($headers, $rowdata), 'PatientInventoryExport.xlsx');

        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
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
        return view('livewire.patient-report.patient-inventory-report');
    }
}
