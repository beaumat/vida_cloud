<?php

namespace App\Livewire\PhilHealth;

use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use Livewire\Component;

class StatementOfAccount extends Component
{

    public $i = 0;
    public int $ID;
    public int $NO_OF_TREATMENT;
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

    public float $CHARGE_TOTAL;
    public float $VAT_TOTAL;
    public float $SP_TOTAL;
    public float $GOV_TOTAL;
    public float $P1_TOTAL;
    public float $P2_TOTAL;
    public float $OP_TOTAL;
    public int $PREPARED_BY_ID;
    public string $DATE_SIGNED;
    public string $OTHER_NAME;

    public float $P1_ROOM_N_BOARD = 0;
    public float $P1_DRUG_N_MEDICINE = 0;
    public float $P1_LAB_N_DIAGNOSTICS = 0;
    public float $P1_OPERATING_ROOM_FEE = 0;
    public float $P1_SUPPLIES = 0;
    public float $P1_OTHERS = 0;

    public float $AD_SUB_TOTAL;
    public float $AD_TOTAL = 0;
    private $philHealthServices;
    private $philHealthProfFeeServices;
    public $feeList = [];

    public function boot(PhilHealthServices $philHealthServices, PhilHealthProfFeeServices $philHealthProfFeeServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
    }

    public function PreLoad($ID)
    {
        if (is_numeric($ID)) {
            $data = $this->philHealthServices->get($ID);
            if ($data) {
                $this->ID = $ID;
                // $this->NO_OF_TREATMENT = $this->philHealthServices->getNumberOfTreatment($data->CONTACT_ID, $data->LOCATION_ID, $data->DATE_ADMITTED, $data->DATE_DISCHARGED);
                $this->CHARGES_ROOM_N_BOARD = $data->CHARGES_ROOM_N_BOARD;
                $this->CHARGES_DRUG_N_MEDICINE = $data->CHARGES_DRUG_N_MEDICINE;
                $this->CHARGES_LAB_N_DIAGNOSTICS = $data->CHARGES_LAB_N_DIAGNOSTICS;
                $this->CHARGES_OPERATING_ROOM_FEE = $data->CHARGES_OPERATING_ROOM_FEE;

                $this->CHARGES_SUPPLIES = $data->CHARGES_SUPPLIES;
                $this->CHARGES_OTHERS = $data->CHARGES_OTHERS;
                $this->CHARGES_SUB_TOTAL = $data->CHARGES_SUB_TOTAL;
                // $this->OTHER_SPECIFY = $data->OTHER_SPECIFY;
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
                $this->CHARGE_TOTAL = $data->CHARGE_TOTAL;
                $this->VAT_TOTAL = $data->VAT_TOTAL;
                $this->SP_TOTAL = $data->SP_TOTAL;
                $this->GOV_TOTAL = $data->GOV_TOTAL;
                $this->P1_TOTAL = $data->P1_TOTAL;
                $this->P2_TOTAL = $data->P2_TOTAL;
                $this->OP_TOTAL = $data->OP_TOTAL;
                $this->PREPARED_BY_ID = $data->PREPARED_BY_ID ?? 0;
                $this->DATE_SIGNED = $data->DATE_SIGNED ?? '';
                $this->AD_SUB_TOTAL = $data->AD_SUB_TOTAL ?? 0;
                $this->AD_TOTAL = $data->AD_TOTAL ?? 0;
                // $this->OTHER_NAME = $data->OTHER_NAME;
                return;
            }
        }
        $this->CHARGES_ROOM_N_BOARD = 0;
        $this->CHARGES_DRUG_N_MEDICINE = 0;
        $this->CHARGES_LAB_N_DIAGNOSTICS = 0;
        $this->CHARGES_OPERATING_ROOM_FEE = 0;
        $this->CHARGES_SUPPLIES = 0;
        $this->CHARGES_OTHERS = 0;
        $this->CHARGES_SUB_TOTAL = 0;
        $this->OTHER_SPECIFY = '';
        $this->VAT_ROOM_N_BOARD = 0;
        $this->VAT_DRUG_N_MEDICINE = 0;
        $this->VAT_LAB_N_DIAGNOSTICS = 0;
        $this->VAT_OPERATING_ROOM_FEE = 0;
        $this->VAT_SUPPLIES = 0;
        $this->VAT_OTHERS = 0;
        $this->VAT_SUB_TOTAL = 0;
        $this->SP_ROOM_N_BOARD = 0;
        $this->SP_DRUG_N_MEDICINE = 0;
        $this->SP_LAB_N_DIAGNOSTICS = 0;
        $this->SP_OPERATING_ROOM_FEE = 0;
        $this->SP_SUPPLIES = 0;
        $this->SP_OTHERS = 0;
        $this->SP_SUB_TOTAL = 0;
        $this->GOV_ROOM_N_BOARD = 0;
        $this->GOV_DRUG_N_MEDICINE = 0;
        $this->GOV_LAB_N_DIAGNOSTICS = 0;
        $this->GOV_OPERATING_ROOM_FEE = 0;
        $this->GOV_SUPPLIES = 0;
        $this->GOV_OTHERS = 0;
        $this->GOV_SUB_TOTAL = 0;
        $this->GOV_PCSO = false;
        $this->GOV_DSWD = false;
        $this->GOV_DOH = false;
        $this->GOV_HMO = false;
        $this->GOV_LINGAP = false;
        // $this->P1_ROOM_N_BOARD = 0;
        // $this->P1_DRUG_N_MEDICINE = 0;
        // $this->P1_LAB_N_DIAGNOSTICS = 0;
        // $this->P1_OPERATING_ROOM_FEE = 0;
        // $this->P1_SUPPLIES = 0;
        // $this->P1_OTHERS = 0;
        $this->P1_SUB_TOTAL = 0;
        // $this->P2_ROOM_N_BOARD = 0;
        // $this->P2_DRUG_N_MEDICINE = 0;
        // $this->P2_LAB_N_DIAGNOSTICS = 0;
        // $this->P2_OPERATING_ROOM_FEE = 0;
        // $this->P2_SUPPLIES = 0;
        // $this->P2_OTHERS = 0;
        $this->P2_SUB_TOTAL = 0;
        $this->OP_ROOM_N_BOARD = 0;
        $this->OP_DRUG_N_MEDICINE = 0;
        $this->OP_LAB_N_DIAGNOSTICS = 0;
        $this->OP_OPERATING_ROOM_FEE = 0;
        $this->OP_SUPPLIES = 0;
        $this->OP_OTHERS = 0;
        $this->OP_SUB_TOTAL = 0;
        $this->PROFESSIONAL_FEE_SUB_TOTAL = 0;
        $this->PROFESSIONAL_DISCOUNT_SUB_TOTAL = 0;
        $this->CHARGE_TOTAL = 0;
        $this->VAT_TOTAL = 0;
        $this->SP_TOTAL = 0;
        $this->GOV_TOTAL = 0;
        $this->P1_TOTAL = 0;
        $this->P2_TOTAL = 0;
        $this->OP_TOTAL = 0;
        $this->PREPARED_BY_ID = 0;
        $this->DATE_SIGNED = '';
        $this->OTHER_NAME = '';
        $this->AD_SUB_TOTAL = 0;
        $this->AD_TOTAL = 0;
    }
    public function UpdatedID()
    {

        $this->PreLoad($this->ID);
    }

    public function mount($ID)
    {

        $this->profFeeList($ID);
        $this->PreLoad($this->ID);
    }
    public function profFeeList($PHIC_ID)
    {
        $this->i = 0;
        $this->feeList = $this->philHealthProfFeeServices->getProfFee($PHIC_ID);
    }
    public function changeDoctor()
    {
        // $this->dispatch('open-change-pf');

    }
    public function render()
    {
        return view('livewire.phil-health.statement-of-account');
    }
}
