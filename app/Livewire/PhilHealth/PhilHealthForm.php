<?php
namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\PhilHealthSoaCustomServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Philhealth')]

class PhilHealthForm extends Component
{
    public bool $Modify = false;
    public string $STATUS_DESCRIPTION;
    public int $STATUS;
    public int $ID       = 0;
    public string $tab   = "soa";
    public $patientList  = [];
    public $locationList = [];
    public int $LOCATION_ID;
    public int $CONTACT_ID;
    public string $CODE;
    public float $PAYMENT_AMOUNT = 0.00;
    public $DATE;
    public $DATE_ADMITTED;
    public $TIME_ADMITTED;
    public $DATE_DISCHARGED;
    public $TIME_DISCHARGED;
    public $TIME_HIDE;
    public bool $IS_HIDE = false;
    public string $FINAL_DIAGNOSIS;
    public string $OTHER_DIAGNOSIS;
    public string $FIRST_CASE_RATE;
    public string $SECOND_CASE_RATE;
    public int $STATUS_ID;
    public bool $isPaid = false;
    public string $AR_DATE;
    public string $AR_NO;

    private $philHealthServices;
    private $hemoServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $philHealthSoaCustomServices;
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    #[On('ar-form-data')]
    public function arForm($ar)
    {
        $this->AR_DATE = $ar['AR_DATE'];
        $this->AR_NO   = $ar['AR_NO'];
    }
    public function boot(
        PhilHealthServices $philHealthServices,
        HemoServices $hemoServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        PhilHealthSoaCustomServices $philHealthSoaCustomServices
    ) {
        $this->philHealthServices          = $philHealthServices;
        $this->hemoServices                = $hemoServices;
        $this->contactServices             = $contactServices;
        $this->locationServices            = $locationServices;
        $this->userServices                = $userServices;
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
    }
    public function UpdatedContactId()
    {

        $data = $this->hemoServices->getDateTime($this->CONTACT_ID, $this->LOCATION_ID);

        if ($data) {

            $this->DATE_ADMITTED   = $data['FIRST_DATE'];
            $this->TIME_ADMITTED   = $data['FIRST_TIME'];
            $this->DATE_DISCHARGED = $data['LAST_DATE'];
            $this->TIME_DISCHARGED = $data['LAST_TIME'];

            return;
        }
        $this->DATE_ADMITTED   = '';
        $this->TIME_ADMITTED   = '';
        $this->DATE_DISCHARGED = '';
        $this->TIME_DISCHARGED = '';
    }
    private function LoadDropDown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->patientList  = $this->contactServices->getList(3);
    }
    private function GotHide()
    {
        $data = $this->philHealthSoaCustomServices->GetFirst($this->LOCATION_ID);
        if ($data) {
            if ($data->HIDE_FEE > 0) {
                $this->IS_HIDE = true;
                return;
            }
        }
        $this->IS_HIDE = false;
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->philHealthServices->get($id);
            if ($data) {
                $this->LoadDropDown();
                $this->ID = $data->ID;

                $this->isPaid = $this->philHealthServices->isPaid($this->ID);

                $this->CODE        = $data->CODE;
                $this->DATE        = $data->DATE;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->GotHide();
                $this->CONTACT_ID       = $data->CONTACT_ID;
                $this->DATE_ADMITTED    = $data->DATE_ADMITTED;
                $this->TIME_ADMITTED    = $data->TIME_ADMITTED;
                $this->DATE_DISCHARGED  = $data->DATE_DISCHARGED;
                $this->TIME_DISCHARGED  = $data->TIME_DISCHARGED;
                $this->TIME_HIDE        = $data->TIME_HIDE ?? '';
                $this->FINAL_DIAGNOSIS  = $data->FINAL_DIAGNOSIS;
                $this->OTHER_DIAGNOSIS  = $data->OTHER_DIAGNOSIS;
                $this->FIRST_CASE_RATE  = $data->FIRST_CASE_RATE;
                $this->SECOND_CASE_RATE = $data->SECOND_CASE_RATE;
                $this->STATUS_ID        = $data->STATUS_ID;
                $this->AR_DATE          = $data->AR_DATE ?? '';
                $this->AR_NO            = $data->AR_NO ?? '';
                $this->PAYMENT_AMOUNT   = $data->PAYMENT_AMOUNT ?? 0.00;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientsphic')->with('error', $errorMessage);
        }
        $this->LoadDropDown();
        $this->ID          = 0;
        $this->CODE        = '';
        $this->DATE        = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->GotHide();
        $this->CONTACT_ID       = 0;
        $this->DATE_ADMITTED    = null;
        $this->TIME_ADMITTED    = null;
        $this->DATE_DISCHARGED  = null;
        $this->TIME_DISCHARGED  = null;
        $this->TIME_HIDE        = null;
        $this->FINAL_DIAGNOSIS  = '';
        $this->OTHER_DIAGNOSIS  = '';
        $this->FIRST_CASE_RATE  = '';
        $this->SECOND_CASE_RATE = '';
        $this->AR_DATE          = '';
        $this->AR_NO            = '';
        $this->STATUS_ID        = 0;
        $this->Modify           = true;
        $this->PAYMENT_AMOUNT   = 0.00;
    }
    public function print()
    {
        if( $this->ID == 0) {

        return;
        }


        $ds = $this->philHealthServices->get($this->ID);
        if ($ds) {
            // if (! empty($ds->AR_NO)) {
            //     session()->flash('error', 'cannot be print. this document already set AR info');
            //     return;
            // }
            // if (floatval($ds->PAYMENT_AMOUNT ?? 0) > 0) {
            //     session()->flash('error', 'cannot be print. this document having a payment collection');
            //     return;
            // }

            // restriction end
            $data = [
                'PHILHEALTH_ID' => $this->ID,
            ];

            $this->dispatch('philhealth-print-data', result: $data);
        }
    }
    public function updateCancel()
    {
        return Redirect::route('patientsphic_edit', ['id' => $this->ID]);
    }
    public function save()
    {

        $this->validate(
            [
                'CONTACT_ID'      => 'required|not_in:0|exists:contact,id',
                'DATE'            => 'required|date',
                'LOCATION_ID'     => 'required|exists:location,id',
                'DATE_ADMITTED'   => 'required|date',
                'TIME_ADMITTED'   => 'required',
                'DATE_DISCHARGED' => 'required|date',
                'TIME_DISCHARGED' => 'required',
            ],
            [],
            [
                'CONTACT_ID'      => 'Patient',
                'DATE'            => 'Date',
                'LOCATION_ID'     => 'Location',
                'DATE_ADMITTED'   => 'Date Admitted',
                'TIME_ADMITTED'   => 'Time Admiited',
                'DATE_DISCHARGED' => 'Date Discharged',
                'TIME_DISCHARGED' => 'Time Discharged',
            ]
        );

        if ($this->ID == 0) {

            $this->ID = $this->philHealthServices->preSave(
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_DISCHARGED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE
            );
            $this->philHealthServices->DefaultEntry($this->ID);
            $this->Modify = false;
            return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully created');
        } else {

            $this->philHealthServices->preUpdate(
                $this->ID,
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_DISCHARGED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE
            );

            $this->philHealthServices->DefaultEntry($this->ID);
            $this->Modify = false;
            return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully updated');
        }
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function getARForm()
    {
        $data = [
            'PHILHEALTH_ID' => $this->ID,
        ];

        $this->dispatch('ar-form-show', result: $data);
    }
    public function getChangeDoctor()
    {
        $this->dispatch('call-open-update-pf');

    }
    public function getComputation()
    {
        $this->philHealthServices->DefaultEntry($this->ID);
        return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully updated');

    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function finder()
    {
        $this->dispatch('open-finder');
    }
    public function render()
    {
        return view('livewire.phil-health.phil-health-form');
    }
}
