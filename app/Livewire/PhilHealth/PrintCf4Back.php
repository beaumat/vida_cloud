<?php
namespace App\Livewire\PhilHealth;

use App\Services\Cf4DoctorOrderServices;
use App\Services\ContactServices;
use App\Services\DoctorOrderDefaultServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\PhilhealthDrugsMedicineServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Component;

class PrintCf4Back extends Component
{

    public int $LOCATION_ID;
    private $philHealthServices;
    private $philhealthDrugsMedicineServices;
    private $hemoServices;
    private $locationServices;
    private $philHealthProfFeeServices;
    public string $DR_NAME;
    public $dateList = [];
    public $dataMed  = [];
    public $DATE_DISCHARGED;
    public $DOCTOR_ORDER = "UNDERGO HEMODIALYSIS TREATMENT WITH NO COMPLICATIONS";
    private $patientDoctorServices;
    private $cf4DoctorOrderServices;
    private $doctorOrderDefaultServices;
    private $userServices;
    public bool $PRE_SIGN_DATA    = false;
    public bool $OUTPUT_SIGN      = false;
    public bool $PHIC_FORM_MODIFY = false;

    public function boot(
        PhilHealthServices $philHealthServices,
        PhilhealthDrugsMedicineServices $philhealthDrugsMedicineServices,
        HemoServices $hemoServices,
        ContactServices $contactServices,
        PatientDoctorServices $patientDoctorServices,
        LocationServices $locationServices,
        Cf4DoctorOrderServices $cf4DoctorOrderServices,
        DoctorOrderDefaultServices $doctorOrderDefaultServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices,
        UserServices $userServices
    ) {
        $this->philHealthServices              = $philHealthServices;
        $this->philhealthDrugsMedicineServices = $philhealthDrugsMedicineServices;
        $this->hemoServices                    = $hemoServices;
        $this->patientDoctorServices           = $patientDoctorServices;
        $this->locationServices                = $locationServices;
        $this->cf4DoctorOrderServices          = $cf4DoctorOrderServices;
        $this->doctorOrderDefaultServices      = $doctorOrderDefaultServices;
        $this->philHealthProfFeeServices       = $philHealthProfFeeServices;
        $this->userServices                    = $userServices;
    }
    public function mount($id = null, int $PATIENT_ID = 0, bool $OUTPUT = true)
    {

        $this->OUTPUT_SIGN = $OUTPUT;
        $getData           = ['GENERIC_NAME' => '', 'QUANTITY' => '', 'DOSSAGE' => '', 'ROUTE' => '', 'FREQUENCY' => '', 'TOTAL_COST' => '', 'CONT_GENERIC_NAME' => '', 'CONT_QUANTITY' => '', 'CONT_DOSSAGE' => '', 'CONT_ROUTE' => '', 'CONT_FREQUENCY' => '', 'CONT_TOTAL_COST' => ''];

        $this->dataMed = [
            [$getData],
            [$getData],
            [$getData],
            [$getData],
            [$getData],
            [$getData],
            [$getData],
        ];

        if ($id > 0) {

            $this->PRE_SIGN_DATA = false;
            $this->getMed($id);
            $data = $this->philHealthServices->get($id);
            if ($data) {
                $this->LOCATION_ID = (int) $data->LOCATION_ID;
                $dataLoc           = $this->locationServices->get($this->LOCATION_ID);
                if ($dataLoc) {
                    $this->PHIC_FORM_MODIFY = $dataLoc->PHIC_FORM_MODIFY ?? false;
                    $this->DOCTOR_ORDER     = $dataLoc->DOCTOR_ORDER_DEFAULT ?? 'UNDERGO HEMODIALYSIS TREATMENT WITH NO COMPLICATIONS';
                }

                $this->DATE_DISCHARGED = $data->DATE_DISCHARGED ?? '';
                $r                     = 0;
                $KEEP_ORDER            = '';
                $KEEP_DATE             = '';
                $KEEP_MODIFY           = false;

                $getData    = $this->hemoServices->GetSummary($data->CONTACT_ID, $data->LOCATION_ID, $data->DATE_ADMITTED ?? '', $data->DATE_DISCHARGED ?? '');
                $getIsExist = $this->doctorOrderDefaultServices->HaveAData($data->LOCATION_ID);
                // Make it

                foreach ($getData as $item) {

                    if ($getIsExist) {
                        $KEEP_DATE = $item->DATE;
                        $orderList = $this->cf4DoctorOrderServices->GetList($item->ID);
                        foreach ($orderList as $list) {
                            $this->dateList[$r] = [
                                'DATE'         => $KEEP_DATE,
                                'DOCTOR_ORDER' => $list->DESCRIPTION,
                            ];
                            $KEEP_DATE = '';
                            $r++;
                        }
                    } else {
                        $this->dateList[$r] = [
                            'DATE'         => $item->DATE,
                            'DOCTOR_ORDER' => empty($item->DOCTOR_ORDER) ? $this->DOCTOR_ORDER : $item->DOCTOR_ORDER ?? '',
                        ];
                        $r++;
                    }
                }

                for ($i = $r; $i < 15; $i++) {
                    $this->dateList[$i] = [
                        'DATE'         => '',
                        'DOCTOR_ORDER' => '',
                    ];
                }
            }
            $fee = $this->philHealthProfFeeServices->getProfFee($id);
            foreach ($fee as $list) {
                $this->DR_NAME = strtoupper($list->NAME);
                return;
            }

        } else {
            $this->makeTemp();
            $this->getMed(0);
        }
        if ($PATIENT_ID > 0) {
            $this->PRE_SIGN_DATA = true;
            $this->LOCATION_ID   = $this->userServices->getLocationDefault();
            $fee                 = $this->patientDoctorServices->GetList($PATIENT_ID, $this->LOCATION_ID);
            foreach ($fee as $list) {
                $this->DR_NAME = strtoupper($list->NAME);
                return;
            }
        }
    }
    public function makeTemp()
    {
        for ($i = 0; $i < 15; $i++) {
            $this->dateList[$i] = [
                'DATE'         => "",
                'DOCTOR_ORDER' => " ",
            ];
        }
    }
    public function getMed(int $ID)
    {
        for ($i = 0; $i < 12; $i++) {
            // first Initialize default value.
            $this->dataMed[$i]['GENERIC_NAME'] = '';
            $this->dataMed[$i]['QUANTITY']     = '';
            $this->dataMed[$i]['DOSSAGE']      = '';
            $this->dataMed[$i]['ROUTE']        = '';
            $this->dataMed[$i]['FREQUENCY']    = '';
            $this->dataMed[$i]['TOTAL_COST']   = '';

            $this->dataMed[$i]['CONT_GENERIC_NAME'] = '';
            $this->dataMed[$i]['CONT_QUANTITY']     = '';
            $this->dataMed[$i]['CONT_DOSSAGE']      = '';
            $this->dataMed[$i]['CONT_ROUTE']        = '';
            $this->dataMed[$i]['CONT_FREQUENCY']    = '';
            $this->dataMed[$i]['CONT_TOTAL_COST']   = '';
        }

        if ($ID > 0) {
            $dt = $this->philhealthDrugsMedicineServices->DrugMedicineList($ID);
            $r  = 0;

            foreach ($dt as $list) {
                if ($r == 12) {
                    return;
                }
                $this->dataMed[$r]['GENERIC_NAME'] = $list->GENERIC_NAME ?? '';
                $this->dataMed[$r]['QUANTITY']     = number_format($list->QUANTITY ?? 0, 0);
                $this->dataMed[$r]['DOSSAGE']      = $list->DOSSAGE ?? '';
                $this->dataMed[$r]['ROUTE']        = $list->ROUTE ?? '';
                $this->dataMed[$r]['FREQUENCY']    = $list->FREQUENCY ?? '';
                $this->dataMed[$r]['TOTAL_COST']   = number_format($list->TOTAL_COST, 2);

                $this->dataMed[$r]['CONT_GENERIC_NAME'] = $list->CONT_GENERIC_NAME;
                $this->dataMed[$r]['CONT_QUANTITY']     = number_format($list->CONT_QUANTITY, 0);
                $this->dataMed[$r]['CONT_DOSSAGE']      = $list->CONT_DOSSAGE;
                $this->dataMed[$r]['CONT_ROUTE']        = $list->CONT_ROUTE;
                $this->dataMed[$r]['CONT_FREQUENCY']    = $list->CONT_FREQUENCY;
                $this->dataMed[$r]['CONT_TOTAL_COST']   = number_format($list->CONT_TOTAL_COST, 2);
                $r++;
            }
        }
    }
    public function render()
    {
        return view('livewire.phil-health.print-cf4-back');
    }
}
