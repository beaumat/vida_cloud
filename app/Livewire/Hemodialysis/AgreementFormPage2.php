<?php
namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhicAgreementFormServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormPage2 extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public $HD_FACILITY_REP_NAME = "";
    public $HD_FACILITY_REP_POS = "";
    public $PATIENT_NAME = "";
    public $WITNESS_NAME = "";
    public string $DATE;
    public int $LOCATION_ID;
    public bool $LEAVE_BLANK_AG_ADMIN_OFFICE_FEE;
    public $typeFiveList = [];
    public $typeSixList = [];
    public int $QTY = 0;
    public $itemList = [];
    private $hemoServices;
    private $locationServices;
    private $phicAgreementFormServices;
    private $contactServices;
    public function boot(HemoServices $hemoServices, LocationServices $locationServices, PhicAgreementFormServices $phicAgreementFormServices, ContactServices $contactServices)
    {
        $this->hemoServices              = $hemoServices;
        $this->locationServices          = $locationServices;
        $this->phicAgreementFormServices = $phicAgreementFormServices;
        $this->contactServices           = $contactServices;
    }

    public function mount()
    {
        $data = $this->hemoServices->Get($this->HEMO_ID);
        if ($data) {
            $this->PATIENT_NAME = $this->contactServices->getName($data->CUSTOMER_ID);
            $this->DATE         = $data->DATE;
            $this->LOCATION_ID  = $data->LOCATION_ID;
            $this->getWithNess($data->CUSTOMER_ID);
            $dataLoc = $this->locationServices->get($this->LOCATION_ID);
            if ($this->locationServices->AgreementFormQtyAllowed($this->LOCATION_ID) == true) {
                $this->QTY = $this->contactServices->getPatientAvailmentListDialyzerQty($data->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE);
            }
            if ($dataLoc) {
                $this->LEAVE_BLANK_AG_ADMIN_OFFICE_FEE = $dataLoc->LEAVE_BLANK_AG_ADMIN_OFFICE_FEE ?? false;
                $hdcon                                 = $this->contactServices->get($dataLoc->HD_FACILITY_REP_ID ?? 0, 2);
                if ($hdcon) {
                    $this->HD_FACILITY_REP_NAME = $hdcon->NAME ?? '';
                    $this->HD_FACILITY_REP_POS  = $hdcon->NICKNAME ?? '';
                }

            }

            $this->TypeFive();
            $this->TypeSix();
            $this->itemlistLoad();
        }

    }
    private function getWithNess(int $CONTACT_ID)
    {
        $con = $this->contactServices->get2($CONTACT_ID);
        if ($con) {
            $wit_ID             = $con->WITNESS_ID > 0 ? $con->WITNESS_ID : 0;
            $this->WITNESS_NAME = $wit_ID > 0 ? $this->contactServices->getName2($wit_ID) : $con->CONTACT_PERSON;

        }
    }
    private function itemlistLoad()
    {
        $this->itemList = $this->phicAgreementFormServices->getItemList($this->HEMO_ID);
    }
    private function TypeFive()
    {
        $this->typeFiveList = $this->phicAgreementFormServices->getTitleByType(5, $this->HEMO_ID);
    }
    private function TypeSix()
    {
        $this->typeSixList = $this->phicAgreementFormServices->getTitleByType(6, $this->HEMO_ID);
    }
    public function render()
    {
        return view('livewire.hemodialysis.agreement-form-page2');
    }
}
