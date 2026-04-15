<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\OtherServices;
use App\Services\PhilhealthItemAdjustmentServices;
use App\Services\ServiceChargeServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PhilhealthAvailment extends Component
{
    public string $REPORT_HEADER_1;
    public string $LOGO_FILE;
    public int $id; // Patient ID
    public string $PHIC_NO;
    public string $CONTACT_NAME;
    public string $DONE_DATE;
    public string $FINAL_DIAGNOSIS;
    public int $TOTAL_DAYS;
    public int $YEAR;
    public string $BRANCH_NAME;
    public string $USER_NAME;

    public string $BUSINESS_NAME;
    public string $DATE;
    public int $TOTAL_OTHER = 0;
    public int $TOTAL_MAIN = 0;
    public int $TOTAL_ITEM = 0;

    public $dataList = [];
    private $contactServices;
    private $serviceChargeServices;
    private $locationServices;
    private $otherServices;
    private $dateServices;
    private $philhealthItemAdjustmentServices;
    private $itemServices;
    public function boot(
        ContactServices $contactServices,
        ServiceChargeServices $serviceChargeServices,
        LocationServices $locationServices,
        OtherServices $otherServices,
        DateServices $dateServices,
        PhilhealthItemAdjustmentServices $philhealthItemAdjustmentServices,
        ItemServices $itemServices
    ) {

        $this->contactServices = $contactServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->locationServices = $locationServices;
        $this->otherServices = $otherServices;
        $this->dateServices = $dateServices;
        $this->philhealthItemAdjustmentServices = $philhealthItemAdjustmentServices;
        $this->itemServices = $itemServices;
    }
    public function getDialyzer(int $LOCATION_ID, int $YEAR): int
    {
        $groupList = $this->itemServices->GetAllItemByGroup(1);
        $list = array_column($groupList, 'ID');

        $count = DB::table('service_charges')
            ->join('service_charges_items', 'service_charges_items.SERVICE_CHARGES_ID', '=', 'service_charges.ID')
            ->where('service_charges.PATIENT_ID', $this->id) // Replace with actual ID
            ->whereIn('service_charges_items.ITEM_ID', $list) // $list is an array
            ->where('service_charges.LOCATION_ID', $LOCATION_ID)
            ->whereYear('service_charges.DATE', $YEAR)
            ->count();

        return $count;


    }
    public function mount($id = null, int $year, int $locationid)
    {
        $contact = $this->contactServices->get($id, 3);
        if ($contact) {
            $this->id = $contact->ID;
            $extend = $contact->SALUTATION != '' ? $contact->SALUTATION . ', ' : ', ';
            $this->CONTACT_NAME = $contact->LAST_NAME . ' ' . $extend . $contact->FIRST_NAME . ' ' . $contact->MIDDLE_NAME;
            if ($contact->PIN) {
                $this->PHIC_NO = $this->otherServices->PhilHlealthDigitFormat($contact->PIN);
            }

            $this->FINAL_DIAGNOSIS = $contact->FINAL_DIAGNOSIS ?? '';
            $this->YEAR = $year;
            $this->TOTAL_DAYS = (int) $this->serviceChargeServices->getAvailmentTotal($contact->ID, $year, $locationid);
            $lastData = $this->serviceChargeServices->getLastAvailment($id, $year, $locationid);


            $this->TOTAL_MAIN = $this->TOTAL_DAYS;
            $this->TOTAL_OTHER = $this->philhealthItemAdjustmentServices->ItemTotalOther($contact->ID, $locationid, $year);
            $this->TOTAL_ITEM = $this->getDialyzer($locationid, $year) + $this->philhealthItemAdjustmentServices->ItemTotalOther1($contact->ID, $locationid, $year);
            $this->DATE = $this->dateServices->NowDate();
            $this->DONE_DATE = $this->otherServices->formatSpecialDate($this->dateServices->NowDate());

            $locData = $this->locationServices->get($locationid);
            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
                $this->BRANCH_NAME = $locData->NAME_OF_BUSINESS ?? '';

                $user = $this->contactServices->get($locData->PHIC_INCHARGE2_ID > 0 ? $locData->PHIC_INCHARGE2_ID : Auth()->user()->contact_id, 2);
                if ($user) {
                    $this->USER_NAME = $user->PRINT_NAME_AS ?? ' ';


                }
            }
            $this->dataList = $this->serviceChargeServices->getAvailList($contact->ID, $year, $locationid);
        }
    }

    public function render()
    {
        return view('livewire.patient.philhealth-availment');
    }
}
