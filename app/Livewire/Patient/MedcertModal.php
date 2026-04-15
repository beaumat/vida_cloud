<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\MedCertServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;




class MedcertModal extends Component
{
    #[Reactive]
    public int $PATIENT_ID;
    public bool $showModal = false;

    public int $MED_CERT_NURSE_ID;
    public $medCertScheduleList = [];
    public $contactList = [];

    public bool $FIX_MON;
    public bool $FIX_TUE;
    public bool $FIX_WEN;
    public bool $FIX_THU;
    public bool $FIX_FRI;
    public bool $FIX_SAT;
    public bool $FIX_SUN;



    private $contactServices;
    private $medCertServices;
    public function boot(ContactServices $contactServices, MedCertServices $medCertServices)
    {
        $this->contactServices = $contactServices;
        $this->medCertServices = $medCertServices;
    }

    #[On('open-med-cert')]
    public function openModal()
    {
        $data = $this->contactServices->getPatientByMed($this->PATIENT_ID);
        if ($data) {

            $this->MED_CERT_NURSE_ID = $data->MED_CERT_NURSE_ID ?? 0;
            $this->contactList = $this->contactServices->getList(4);
            $this->medCertScheduleList = $this->medCertServices->GetList();


            $this->FIX_MON = $data->FIX_MON ?? false;
            $this->FIX_TUE = $data->FIX_TUE ?? false;
            $this->FIX_WEN = $data->FIX_WEN ?? false;
            $this->FIX_THU = $data->FIX_THU ?? false;
            $this->FIX_FRI = $data->FIX_FRI ?? false;
            $this->FIX_SAT = $data->FIX_SAT ?? false;
            $this->FIX_SUN = $data->FIX_SUN ?? false;
            $this->showModal = true;
        }
    }


    public function closeModal()
    {
        $this->showModal = false;
    }
    // public function SaveChange()
    // {

    //     $this->validate(
    //         [

    //             'MED_CERT_NURSE_ID' => 'required|exists:contact,id',

    //         ],
    //         [],
    //         [
    //             'MED_CERT_NURSE_ID' => 'Duty Physician'
    //         ]
    //     );

    //     try {
    //         $this->medCertServices->UpdatePatientMedCert(
    //             $this->PATIENT_ID,
    //             $this->MED_CERT_NURSE_ID,
    //             $this->FIX_MON,
    //             $this->FIX_TUE,
    //             $this->FIX_WEN,
    //             $this->FIX_THU,
    //             $this->FIX_FRI,
    //             $this->FIX_SAT,
    //             $this->FIX_SUN
    //         );
    //         session()->flash('message', 'Successfully updated');
    //     } catch (\Exception $th) {
    //         session()->flash('error', $th->getMessage());
    //     }
    // }
    public function updatedFixMon()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['FIX_MON' => $this->FIX_MON]);
    }
    public function updatedFixTue()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['FIX_TUE' => $this->FIX_TUE]);
    }
    public function updatedFixWen()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['FIX_WEN' => $this->FIX_WEN]);
    }
    public function updatedFixThu()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['FIX_THU' => $this->FIX_THU]);
    }
    public function updatedFixFri()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['FIX_FRI' => $this->FIX_FRI]);
    }
    public function updatedFixSat()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['FIX_SAT' => $this->FIX_SAT]);
    }
    public function updatedFixSun()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['FIX_SUN' => $this->FIX_SUN]);
    }
    public function updatedMEDCERTNURSEID()
    {
        $this->medCertServices->updateParamater($this->PATIENT_ID, ['MED_CERT_NURSE_ID' => $this->MED_CERT_NURSE_ID]);
    }
    public function render()
    {

        return view('livewire.patient.medcert-modal');
    }
}
