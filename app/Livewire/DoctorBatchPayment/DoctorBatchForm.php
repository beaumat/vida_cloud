<?php

namespace App\Livewire\DoctorBatchPayment;

use App\Services\ContactServices;
use App\Services\DoctorBatchServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctor Batch Payment')]
class DoctorBatchForm extends Component
{


    public int $ID;
    public string $CODE;
    public int $DOCTOR_ID;
    public bool $doctorRefresh = false;
    public int $LOCATION_ID;
    public bool $Modify = true;
    public $contactList = [];
    public $locationList = [];
    private $doctorBatchServices;
    private $userServices;
    private $locationServices;
    private $contactServices;
    public function boot(DoctorBatchServices $doctorBatchServices, UserServices $userServices, LocationServices $locationServices, ContactServices $contactServices)
    {
        $this->doctorBatchServices = $doctorBatchServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->contactServices = $contactServices;
    }
    private function getInfo($data): void
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DOCTOR_ID = $data->DOCTOR_ID;
        $this->LOCATION_ID = $data->LOCATION_ID;
    }
    public function updatedLocationId()
    {
        $this->contactList = $this->contactServices->getDoctorListByLocation($this->LOCATION_ID);
        $this->doctorRefresh = $this->doctorRefresh ? false : true;
        $this->DOCTOR_ID = 0;



        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function loadList()
    {
        $this->locationList = $this->locationServices->getList();
        $this->contactList = $this->contactServices->getDoctorListByLocation($this->LOCATION_ID);
    }
    public function mount($id = null)
    {

        try {

            if (is_numeric($id)) {
                $data = $this->doctorBatchServices->Get($id);
                if ($data) {
                    $this->getInfo($data);
                    $this->loadList();
                    $this->Modify = false;
                    return;
                }

                return Redirect::route('patientsdoctor_batch')->with('error', 'Record not found.');
            }
            $this->LOCATION_ID = $this->userServices->getLocationDefault();
            $this->loadList();
            $this->ID = 0;
            $this->CODE = "";
            $this->DOCTOR_ID = 0;
            $this->Modify = true;

        } catch (\Throwable $th) {
            return Redirect::route('patientsdoctor_batch')->with('error', $th->getMessage());
        }

    }
    public function save()
    {

        $this->validate(
            [
                'DOCTOR_ID' => 'required|not_in:0|exists:contact,id',
                'LOCATION_ID' => 'required|not_in:0|exists:location,id'

            ],
            [],
            [
                'DOCTOR_ID' => 'Doctor',
                'LOCATION_ID' => 'Location'
            ]
        );


        try {
            if ($this->ID == 0) {
                $this->ID = $this->doctorBatchServices->Store($this->DOCTOR_ID, $this->LOCATION_ID);
                return Redirect::route('patientsdoctor_batch_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {
                $this->doctorBatchServices->Update($this->ID, $this->DOCTOR_ID);
                session()->flash('message', 'Successfull update doctor');
                $this->Modify = false;
            }
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    public function getModify()
    {
        $this->Modify = true;
        $this->contactList = $this->contactServices->getDoctorListByLocation($this->LOCATION_ID);
        $this->doctorRefresh = $this->doctorRefresh ? false : true;
    }
    public function updateCancel()
    {
        return Redirect::route('patientsdoctor_batch_edit', ['id' => $this->ID]);
    }
    public function render()
    {
        return view('livewire.doctor-batch-payment.doctor-batch-form');
    }
}
