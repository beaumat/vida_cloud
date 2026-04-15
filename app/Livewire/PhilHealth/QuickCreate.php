<?php

namespace App\Livewire\PhilHealth;

use App\Services\Cf4DoctorOrderServices;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\DoctorOrderDefaultServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;


class QuickCreate extends Component
{
    #[Reactive]
    public int $LOCATION_ID;
    public $patientSelected = [];
    public bool $SelectAll = false;
    public bool $isDaily = false;
    public $locationList = [];

    public bool $showModal = false;
    private $locationServices;

    public $DATE_FROM;
    public $DATE_TO;
    public $search;
    public $dataList = [];
    private $hemoServices;
    private $philHealthServices;
    private $contactServices;
    private $dateServices;
    private $cf4DoctorOrderServices;
    private $doctorOrderDefaultServices;
    public object $dataLoc;
    private $DATE_ADMITTED = '';
    private $TIME_ADMITTED = '';
    private $DATE_DISCHARGED = '';
    private $TIME_DISCHARGED = '';
    private string $FINAL_DIAGNOSIS = '';
    private string $OTHER_DIAGNOSIS = '';
    private string $FIRST_CASE_RATE = '';
    private string $SECOND_CASE_RATE = '';
    public bool $PHIC_FORM_MODIFY = false;
    public bool $showExists = true;
    public function boot(
        LocationServices $locationServices,
        HemoServices $hemoServices,
        PhilHealthServices $philHealthServices,
        ContactServices $contactServices,
        DateServices $dateServices,
        Cf4DoctorOrderServices $cf4DoctorOrderServices,
        DoctorOrderDefaultServices $doctorOrderDefaultServices
    ) {
        $this->locationServices = $locationServices;
        $this->hemoServices = $hemoServices;
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
        $this->dateServices = $dateServices;
        $this->cf4DoctorOrderServices = $cf4DoctorOrderServices;
        $this->doctorOrderDefaultServices = $doctorOrderDefaultServices;
    }
    public function ResetValue()
    {

        $this->SelectAll = false;
        $this->reset('patientSelected');
    }
    public function updatedDateFrom()
    {
        $this->ResetValue();
    }
    public function updatedDateTo()
    {
        $this->ResetValue();
    }
    public function updatedLocationId()
    {
        $this->ResetValue();
    }
    public function mount()
    {
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            foreach ($this->dataList as $list) {
                $this->patientSelected[$list->ID] = true;
            }
        } else {

            $this->reset('patientSelected');
        }
    }

    public function openModal()
    {
        $this->dataLoc = $this->locationServices->get($this->LOCATION_ID);
        if ($this->dataLoc) {
            $this->isDaily = (bool) $this->dataLoc->IS_DAILY ?? false;
            $this->DATE_FROM = $this->dateServices->NowDate();
            $this->DATE_TO = $this->dateServices->NowDate();
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    private function generateDateTime($CONTACT_ID): bool
    {

        if ($this->isDaily) {
            $data = $this->hemoServices->getDateTimeByDaily($CONTACT_ID, $this->LOCATION_ID, $this->DATE_FROM);
            if ($data) {

                $this->DATE_ADMITTED = $data['FIRST_DATE'];
                $this->TIME_ADMITTED = $data['FIRST_TIME'];
                $this->DATE_DISCHARGED = $data['LAST_DATE'];
                $this->TIME_DISCHARGED = $data['LAST_TIME'];
                return true;
            }
        } else {
            $data = $this->hemoServices->getDateTimeByRange($CONTACT_ID, $this->LOCATION_ID, $this->DATE_FROM, $this->DATE_TO);

            if ($data) {
                $this->DATE_ADMITTED = $data['FIRST_DATE'];
                $this->TIME_ADMITTED = $data['FIRST_TIME'];
                $this->DATE_DISCHARGED = $data['LAST_DATE'];
                $this->TIME_DISCHARGED = $data['LAST_TIME'];
                return true;
            }
        }
        return false;
    }
    private function resetMethod()
    {
        $this->dispatch('reload-list');
        $this->ResetValue();
        $this->showModal = false;
    }
    public function generateRemarks($CONTACT_ID)
    {
        $contact = $this->contactServices->get($CONTACT_ID, 3);

        if ($contact) {
            $this->FINAL_DIAGNOSIS = $contact->FINAL_DIAGNOSIS ?? '';
            $this->OTHER_DIAGNOSIS = $contact->OTHER_DIAGNOSIS ?? '';
            $this->FIRST_CASE_RATE = $contact->FIRST_CASE_RATE ?? '';
            $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
        }
    }
    public function create()
    {


        $this->PHIC_FORM_MODIFY = false;
        if ($this->dataLoc) {
            $this->PHIC_FORM_MODIFY = $this->dataLoc->PHIC_FORM_MODIFY ?? false;
        }


        DB::beginTransaction();
        try {
            $gotSelected = false;
            foreach ($this->patientSelected as $patientID => $isSelected) {
                if ($isSelected) {
                    $gotSelected = true;
                    if ($this->generateDateTime($patientID)) {
                 
                        if (empty($this->DATE_ADMITTED) == false && empty($this->DATE_DISCHARGED) == false) {
                           
                            $this->generateRemarks($patientID);
                            $ID = (int) $this->philHealthServices->preSave(
                                '',
                                $this->dateServices->NowDate(),
                                $this->LOCATION_ID,
                                $patientID,
                                $this->DATE_ADMITTED,
                                $this->TIME_ADMITTED,
                                $this->DATE_DISCHARGED,
                                $this->TIME_DISCHARGED,
                                $this->FINAL_DIAGNOSIS,
                                $this->OTHER_DIAGNOSIS,
                                $this->FIRST_CASE_RATE,
                                $this->SECOND_CASE_RATE
                            );

                            $this->philHealthServices->DefaultEntry($ID);
                            if ($this->DATE_ADMITTED == $this->DATE_DISCHARGED && $this->PHIC_FORM_MODIFY == true) {
                                $HEMO_ID = (int) $this->hemoServices->GetHemoID(
                                    $this->DATE_DISCHARGED,
                                    $patientID,
                                    $this->LOCATION_ID
                                );
                                $this->AutoDoctorOrder($HEMO_ID);
                            }
                        }
                    }
                }
            }

            if ($gotSelected == true) {
                $this->resetMethod();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }
    public function AutoDoctorOrder(int $HEMO_ID)
    {   // one time only
        // check have item
        if ($this->cf4DoctorOrderServices->dataIsExists($HEMO_ID)) {
            return;
        }
        $data = $this->doctorOrderDefaultServices->getListByLocation($this->LOCATION_ID);
        foreach ($data as $item) {
            $this->cf4DoctorOrderServices->Store(
                $HEMO_ID,
                $item->DESCRIPTION
            );
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function render()
    {
        if ($this->showModal)
            if ($this->isDaily) {
                $this->dataList = $this->hemoServices->QuickFilterByDaily(
                    $this->DATE_FROM,
                    $this->LOCATION_ID,
                    $this->search
                );
            } else {
                $this->dataList = $this->hemoServices->QuickFilterByDateRange(
                    $this->DATE_FROM,
                    $this->DATE_TO,
                    $this->LOCATION_ID,
                    $this->search,
                    $this->showExists
                );
            }

        return view('livewire.phil-health.quick-create');
    }
}
