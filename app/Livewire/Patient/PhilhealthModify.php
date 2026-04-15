<?php
namespace App\Livewire\Patient;

use App\Services\DateServices;
use App\Services\PhilhealthItemAdjustmentServices;
use App\Services\PhilHealthServices;
use App\Services\ServiceChargeServices;
use App\Services\UploadServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithFileUploads;

class PhilhealthModify extends Component
{
    use WithFileUploads;

    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public bool $showModal = false;
    public int $YEAR;
    public int $NO_OF_USED;
    public int $NO_OF_ITEM;
    public string $NOTES;

    public $E_ID = null;
    public $E_YEAR = 0;
    public $E_NO_OF_USED;
    public $E_NOTES;
    public int $E_NO_OF_ITEM;
    public int $TOTAL;
    public $dataList = [];

    public $PDF = null;
    public $NEW_PDF = null;

    private $philhealthItemAdjustmentServices;

    private $dateServices;
    private $serviceChargeServices;
    private $philHealthServices;
    private $uploadServices;
    public function boot(
        PhilhealthItemAdjustmentServices $philhealthItemAdjustmentServices,
        PhilHealthServices $philHealthServices,
        DateServices $dateServices,
        ServiceChargeServices $serviceChargeServices,
        UploadServices $uploadServices
    ) {
        $this->dateServices                     = $dateServices;
        $this->philhealthItemAdjustmentServices = $philhealthItemAdjustmentServices;
        $this->serviceChargeServices            = $serviceChargeServices;
        $this->philHealthServices               = $philHealthServices;
        $this->uploadServices                   = $uploadServices;
    }
    public function Add()
    {

        $this->validate(
            [
                'YEAR'       => 'required|numeric|not_in:0',
                'NO_OF_USED' => 'required|numeric|not_in:0',
                'NO_OF_ITEM' => 'required|numeric',
            ],
            [],
            [
                'YEAR'       => 'Year',
                'NO_OF_USED' => 'No of Used',
                'NO_OF_ITEM' => 'No of Dialyzer',
            ]
        );

        try {
            $ID = $this->philhealthItemAdjustmentServices->ItemAdjustStore(
                $this->PATIENT_ID,
                $this->LOCATION_ID,
                $this->NO_OF_USED,
                $this->YEAR,
                $this->NOTES,
                $this->NO_OF_ITEM
            );

            if ($this->NEW_PDF) {
                $this->getDocumentProccess($ID, $this->NEW_PDF);
                $this->NEW_PDF = null;
            }

            $this->NO_OF_USED = 0;
            $this->YEAR       = $this->dateServices->NowYear();
            $this->NOTES      = '';
            $this->NO_OF_ITEM = 0;
            session()->flash('message', 'Successfully added');
        } catch (\Exception $e) {
            session()->flash('error', 'Error :' . $e->getMessage());
        }
    }

    private function PhicCount(): int
    {
        $count = $this->serviceChargeServices->GetCountByYear(
            $this->philHealthServices->PHIL_HEALTH_ITEM_ID,
            $this->dateServices->NowYear(),
            $this->PATIENT_ID,
            $this->LOCATION_ID
        );

        $countAdjust = $this->philhealthItemAdjustmentServices->ItemAdjustGet(
            $this->PATIENT_ID,
            $this->LOCATION_ID,
            $this->dateServices->NowYear()
        );
        return $count + $countAdjust;
    }
    public function Delete(int $ID)
    {

        $this->philhealthItemAdjustmentServices->ItemAdjustDelete($ID);
    }
    public function Canceled()
    {
        $this->E_ID = null;
    }
    public function getDocumentProccess(int $ID, $PDF)
    {
        $returnData = $this->uploadServices->Availment($PDF);
        $this->philhealthItemAdjustmentServices->UpdateFile(
            $ID,
            $returnData['filename'] . '.' . $returnData['extension'],
            $returnData['new_path']
        );
    }
    public function Edit(int $ID)
    {
        $data = $this->philhealthItemAdjustmentServices->GetItemAdjust($ID);
        if ($data) {
            $this->E_ID         = $data->ID;
            $this->E_YEAR       = $data->YEAR;
            $this->E_NO_OF_USED = $data->NO_OF_USED ?? 0;
            $this->E_NOTES      = $data->NOTES ?? '';
            $this->E_NO_OF_ITEM = $data->NO_OF_ITEM ?? 0;
            $this->PDF          = null;
        }
    }
    public function Update()
    {
        $this->validate(
            [
                'E_YEAR'       => 'required|numeric|not_in:0',
                'E_NO_OF_USED' => 'required|numeric|not_in:0',
                'E_NO_OF_ITEM' => 'required|numeric',
            ],
            [],
            [
                'E_YEAR'       => 'Year',
                'E_NO_OF_USED' => 'No of Used',
                'E_NO_OF_ITEM' => 'No of Dialyzer',
            ]
        );

        try {
            $this->philhealthItemAdjustmentServices->ItemAdjustUpdate($this->E_ID, $this->E_YEAR, $this->E_NO_OF_USED, $this->E_NOTES, $this->E_NO_OF_ITEM);
            if ($this->PDF) {
                $this->getDocumentProccess($this->E_ID, $this->PDF);
                $this->PDF = null;
            }
            $this->E_ID = null;
            session()->flash('message', 'Successfully update');
        } catch (\Exception $e) {
            session()->flash('error', 'Error :' . $e->getMessage());
        }
    }

    #[On('open-philhealth-modifiy')]
    public function openModal()
    {
        $this->NO_OF_USED = 0;
        $this->NOTES      = '';
        $this->YEAR       = $this->dateServices->NowYear();
        $this->showModal  = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {

        if ($this->showModal) {

            $this->dataList = $this->philhealthItemAdjustmentServices->ItemAdjustList($this->PATIENT_ID, $this->LOCATION_ID);
            $this->TOTAL    = $this->PhicCount();
        }
        return view('livewire.patient.philhealth-modify');
    }
}
