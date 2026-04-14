<?php

namespace App\Livewire\PhilHealth;

use App\Services\PatientDoctorServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintSummaryBizbox extends Component
{


    public float $CHARGES_ROOM_N_BOARD;
    public float $CHARGES_DRUG_N_MEDICINE;
    public float $CHARGES_LAB_N_DIAGNOSTICS;
    public float $CHARGES_OPERATING_ROOM_FEE;
    public float $CHARGES_SUPPLIES;
    public float $CHARGES_OTHERS;
    public float $CHARGES_SUB_TOTAL;
    public string $OTHER_SPECIFY;
    public float $VAT_ROOM_N_BOARD;
    public float $VAT_DRUG_N_MEDICINE;
    public float $VAT_LAB_N_DIAGNOSTICS;
    public float $VAT_OPERATING_ROOM_FEE;
    public float $VAT_SUPPLIES;
    public float $VAT_OTHERS;
    public float $VAT_SUB_TOTAL;
    public float $SP_ROOM_N_BOARD;
    public float $SP_DRUG_N_MEDICINE;
    public float $SP_LAB_N_DIAGNOSTICS;
    public float $SP_OPERATING_ROOM_FEE;
    public float $SP_SUPPLIES;
    public float $SP_OTHERS;
    public float $SP_SUB_TOTAL;
    public float $GOV_ROOM_N_BOARD;
    public float $GOV_DRUG_N_MEDICINE;
    public float $GOV_LAB_N_DIAGNOSTICS;
    public float $GOV_OPERATING_ROOM_FEE;
    public float $GOV_SUPPLIES;
    public float $GOV_OTHERS;
    public float $GOV_SUB_TOTAL;
    public bool $GOV_PCSO;
    public bool $GOV_DSWD;
    public bool $GOV_DOH;
    public bool $GOV_HMO;
    public bool $GOV_LINGAP;
    public float $P1_SUB_TOTAL;
    public float $P2_SUB_TOTAL;
    public float $OP_ROOM_N_BOARD;
    public float $OP_DRUG_N_MEDICINE;
    public float $OP_LAB_N_DIAGNOSTICS;
    public float $OP_OPERATING_ROOM_FEE;
    public float $OP_SUPPLIES;
    public float $OP_OTHERS;
    public float $OP_SUB_TOTAL;
    public float $PROFESSIONAL_FEE_SUB_TOTAL;
    public float $PROFESSIONAL_DISCOUNT_SUB_TOTAL;
    public float $PROFESSIONAL_P1_SUB_TOTAL;
    public float $CHARGE_TOTAL;
    public float $VAT_TOTAL;
    public float $SP_TOTAL;
    public float $GOV_TOTAL;
    public float $P1_TOTAL;
    public float $P2_TOTAL;
    public float $OP_TOTAL;
    public float $AD_SUB_TOTAL;
    public float $AD_TOTAL = 0;
    public bool $PRE_SIGN_DATA = false;
    public bool $OUTPUT_SIGN = false;
    public bool $HEADER = true; // default TRUE;


    public float $P1_ROOM_N_BOARD = 0;
    public float $P1_DRUG_N_MEDICINE = 0;
    public float $P1_LAB_N_DIAGNOSTICS = 0;
    public float $P1_OPERATING_ROOM_FEE = 0;
    public float $P1_SUPPLIES = 0;
    public float $P1_OTHERS = 0;

    public $feeList = [];
    public $i;
    private $philHealthServices;
    private $philHealthProfFeeServices;
    private $patientDoctorServices;

    public function boot(PhilHealthServices $philHealthServices, PhilHealthProfFeeServices $philHealthProfFeeServices, PatientDoctorServices $patientDoctorServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
        $this->patientDoctorServices = $patientDoctorServices;
    }
    public function mount(int $ID = 0, bool $PRE_SIGN_DATA = false, $PATIENT_ID  = null)
    {
        $this->PRE_SIGN_DATA = $PRE_SIGN_DATA;
        if ($PATIENT_ID) {
            $this->i = 0;
            $this->feeList = $this->patientDoctorServices->GetbyTemp($PATIENT_ID);
        } else {
            $this->PreLoad($ID);
            $this->profFeeList($ID);
        }



    }
    public function profFeeList(int $PHIC_ID)
    {
        $this->i = 0;

        if ($this->PRE_SIGN_DATA) {
            $this->feeList = ['ID' => 0, 'CONTACT_ID' => 'ok'];
        } else {
            $this->feeList = $this->philHealthProfFeeServices->getProfFee($PHIC_ID);
        }
    }
    public function PreLoad($ID)
    {
        if (is_numeric($ID)) {
            $data = $this->philHealthServices->get($ID);
            if ($data) {



                $this->CHARGES_ROOM_N_BOARD = $data->CHARGES_ROOM_N_BOARD;
                $this->CHARGES_DRUG_N_MEDICINE = $data->CHARGES_DRUG_N_MEDICINE;
                $this->CHARGES_LAB_N_DIAGNOSTICS = $data->CHARGES_LAB_N_DIAGNOSTICS;
                $this->CHARGES_OPERATING_ROOM_FEE = $data->CHARGES_OPERATING_ROOM_FEE;
                $this->CHARGES_SUPPLIES = $data->CHARGES_SUPPLIES;
                $this->CHARGES_OTHERS = $data->CHARGES_OTHERS;
                $this->CHARGES_SUB_TOTAL = $data->CHARGES_SUB_TOTAL;
                $this->OTHER_SPECIFY = $data->OTHER_SPECIFY ?? '';
                $this->VAT_ROOM_N_BOARD = $data->VAT_ROOM_N_BOARD;
                $this->VAT_DRUG_N_MEDICINE = $data->VAT_DRUG_N_MEDICINE;
                $this->VAT_LAB_N_DIAGNOSTICS = $data->VAT_LAB_N_DIAGNOSTICS;
                $this->VAT_OPERATING_ROOM_FEE = $data->VAT_OPERATING_ROOM_FEE;
                $this->VAT_SUPPLIES = $data->VAT_SUPPLIES;
                $this->VAT_OTHERS = $data->VAT_OTHERS;
                $this->VAT_SUB_TOTAL = $data->VAT_SUB_TOTAL;
                $this->SP_ROOM_N_BOARD = $data->SP_ROOM_N_BOARD;
                $this->SP_DRUG_N_MEDICINE = $data->SP_DRUG_N_MEDICINE;
                $this->SP_LAB_N_DIAGNOSTICS = $data->SP_LAB_N_DIAGNOSTICS;
                $this->SP_OPERATING_ROOM_FEE = $data->SP_OPERATING_ROOM_FEE;
                $this->SP_SUPPLIES = $data->SP_SUPPLIES;
                $this->SP_OTHERS = $data->SP_OTHERS;
                $this->SP_SUB_TOTAL = $data->SP_SUB_TOTAL;
                $this->GOV_ROOM_N_BOARD = $data->GOV_ROOM_N_BOARD;
                $this->GOV_DRUG_N_MEDICINE = $data->GOV_DRUG_N_MEDICINE;
                $this->GOV_LAB_N_DIAGNOSTICS = $data->GOV_LAB_N_DIAGNOSTICS;
                $this->GOV_OPERATING_ROOM_FEE = $data->GOV_OPERATING_ROOM_FEE;
                $this->GOV_SUPPLIES = $data->GOV_SUPPLIES;
                $this->GOV_OTHERS = $data->GOV_OTHERS;
                $this->GOV_SUB_TOTAL = $data->GOV_SUB_TOTAL;
                $this->GOV_PCSO = $data->GOV_PCSO;
                $this->GOV_DSWD = $data->GOV_DSWD;
                $this->GOV_DOH = $data->GOV_DOH;
                $this->GOV_HMO = $data->GOV_HMO;
                $this->GOV_LINGAP = $data->GOV_LINGAP;
                $this->P1_ROOM_N_BOARD = $data->P1_ROOM_N_BOARD;
                $this->P1_DRUG_N_MEDICINE = $data->P1_DRUG_N_MEDICINE;
                $this->P1_LAB_N_DIAGNOSTICS = $data->P1_LAB_N_DIAGNOSTICS;
                $this->P1_OPERATING_ROOM_FEE = $data->P1_OPERATING_ROOM_FEE;
                $this->P1_SUPPLIES = $data->P1_SUPPLIES;
                $this->P1_OTHERS = $data->P1_OTHERS;
                $this->P1_SUB_TOTAL = $data->P1_SUB_TOTAL;
                // $this->P2_ROOM_N_BOARD = $data->P2_ROOM_N_BOARD;
                // $this->P2_DRUG_N_MEDICINE = $data->P2_DRUG_N_MEDICINE;
                // $this->P2_LAB_N_DIAGNOSTICS = $data->P2_LAB_N_DIAGNOSTICS;
                // $this->P2_OPERATING_ROOM_FEE = $data->P2_OPERATING_ROOM_FEE;
                // $this->P2_SUPPLIES = $data->P2_SUPPLIES;
                // $this->P2_OTHERS = $data->P2_OTHERS;
                $this->P2_SUB_TOTAL = $data->P2_SUB_TOTAL;
                $this->OP_ROOM_N_BOARD = $data->OP_ROOM_N_BOARD;
                $this->OP_DRUG_N_MEDICINE = $data->OP_DRUG_N_MEDICINE;
                $this->OP_LAB_N_DIAGNOSTICS = $data->OP_LAB_N_DIAGNOSTICS;
                $this->OP_OPERATING_ROOM_FEE = $data->OP_OPERATING_ROOM_FEE;
                $this->OP_SUPPLIES = $data->OP_SUPPLIES;
                $this->OP_OTHERS = $data->OP_OTHERS;
                $this->OP_SUB_TOTAL = $data->OP_SUB_TOTAL;
                $this->PROFESSIONAL_FEE_SUB_TOTAL = $data->PROFESSIONAL_FEE_SUB_TOTAL;
                $this->PROFESSIONAL_DISCOUNT_SUB_TOTAL = $data->PROFESSIONAL_DISCOUNT_SUB_TOTAL;
                $this->PROFESSIONAL_P1_SUB_TOTAL = $data->PROFESSIONAL_P1_SUB_TOTAL;
                $this->CHARGE_TOTAL = $data->CHARGE_TOTAL;
                $this->VAT_TOTAL = $data->VAT_TOTAL;
                $this->SP_TOTAL = $data->SP_TOTAL;
                $this->GOV_TOTAL = $data->GOV_TOTAL;
                $this->P1_TOTAL = $data->P1_TOTAL;
                $this->P2_TOTAL = $data->P2_TOTAL;
                $this->OP_TOTAL = $data->OP_TOTAL;
                $this->AD_SUB_TOTAL = $data->AD_SUB_TOTAL;
                $this->AD_TOTAL = $data->AD_TOTAL;
            }
        }
    }


    public function render()
    {
        return view('livewire.phil-health.print-summary-bizbox');
    }
}
