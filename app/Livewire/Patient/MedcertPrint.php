<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\MedCertServices;
use App\Services\OtherServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Medical Certificate')]

class MedcertPrint extends Component
{
    public $id;
    public $LOCATION_ID;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public $BRANCH_NAME;
    public $LOGO_FILE;

    public $FULLNAME;
    public $AGE;
    public $GENDER;
    public $ADDRESS;
    public $FINAL_DIAGNOSIS;

    public $SCHED_SHORT_DESC;
    public $SCHED_FULL_DESC;
    public $PX_LASTNAME;
    public $NURSE_NAME;
    public $LIC_NO;

    public $DATE;
    private $contactServices;
    private $dateServices;

    private $locationServices;
    private $otherServices;
    public function boot(ContactServices $contactServices, DateServices $dateServices, LocationServices $locationServices, OtherServices $otherServices)
    {
        $this->contactServices = $contactServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->otherServices = $otherServices;
    }
    public function mount($id = null)
    {

        $this->DATE = date('F j, Y', strtotime($this->dateServices->NowDate()));
        $this->id = $id;
        $data = $this->contactServices->getPatientByMed($id);
        if ($data) {

            $this->FULLNAME = $data->NAME ?? '';
            $this->AGE = $this->contactServices->calculateUserAge($data->DATE_OF_BIRTH);
            $this->GENDER = $data->GENDER == 'Male' ? 'Male' : 'Female';
            $this->LOCATION_ID = $data->LOCATION_ID ?? 0;

            $address = [
                $data->ADDRESS_UNIT_ROOM_FLOOR,    // Unit/Room/Floor
                $data->ADDRESS_BUILDING_NAME,      // Building Name
                $data->ADDRESS_LOT_BLK_HOUSE_BLDG, // Lot/Block/House/Building Number
                $data->ADDRESS_STREET,             // Street
                $data->ADDRESS_SUB_VALL,           // Subdivision/Village
                $data->ADDRESS_BRGY,               // Barangay
                $data->ADDRESS_CITY_MUNI,          // City/Municipality
                $data->ADDRESS_PROVINCE            // Province
            ];

            $this->ADDRESS = implode(', ', array_filter($address));

            if ($this->GENDER == "Male") {
                $this->PX_LASTNAME = 'Mr. ' . $data->LAST_NAME;
            } else {
                $this->PX_LASTNAME = 'Ms. ' . $data->LAST_NAME;
            }
            $this->FINAL_DIAGNOSIS = $data->FINAL_DIAGNOSIS ?? '';

            $count = 0;
            if ($data->FIX_MON) {
                $this->insertWeek("Monday");
                $count++;
            }
            if ($data->FIX_TUE) {
                $this->insertWeek("Tuesday");
                $count++;
            }
            if ($data->FIX_WEN) {
                $this->insertWeek("Wednesday");
                $count++;
            }
            if ($data->FIX_THU) {
                $this->insertWeek("Thursday");
                $count++;
            }
            if ($data->FIX_FRI) {
                $this->insertWeek("Friday");
                $count++;
            }
            if ($data->FIX_SAT) {
                $this->insertWeek("Saturday");
                $count++;
            }
            if ($data->FIX_SUN) {
                $this->insertWeek("Sunday");
                $count++;
            }



            $this->SCHED_SHORT_DESC = $this->otherServices->numberToWordWeeks($count);
            $this->NURSE_NAME = $data->NURSE_NAME ?? '';
            $this->LIC_NO = $data->LIC_NUMBER ?? '';

            $locData = $this->locationServices->get($data->LOCATION_ID);

            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
                $this->BRANCH_NAME = $locData->NAME_OF_BUSINESS ?? '';
            }


            $this->dispatch('preview_print');
        }
    }
    private function insertWeek(string $Desc)
    {
        if ($this->SCHED_FULL_DESC == "") {
            $this->SCHED_FULL_DESC = $Desc;
        } else {
            $this->SCHED_FULL_DESC = $this->SCHED_FULL_DESC . ", " . $Desc;
        }
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {

        return view('livewire.patient.medcert-print');
    }
}
