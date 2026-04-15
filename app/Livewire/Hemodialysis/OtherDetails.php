<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class OtherDetails extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public bool $Modify;
    #[Reactive]
    public int $STATUS;
    public string $SE_DETAILS;
    public string $SO_DETAILS;
    public int $BFR;
    public int $DFR;
    public int $DURATION;
    public string $DIALYZER;
    public string $DIALSATE_N;
    public string $DIALSATE_K;
    public string $DIALSATE_C;
    public bool $DETAILS_USE_NEXT;
    public bool $ORDER_USE_NEXT;
    public string $SE_DETAILS_NEXT;
    public string $HEPARIN;
    public string $REUSE_NO;
    public string $REUSE_NEXT;
    public string $FLUSHING;

    public bool $SC_MACHINE_TEST;
    public bool $SC_SECURED_CONNECTIONS;
    public bool $SC_SALINE_LINE_DOUBLE_CLAMP;
    public string $SC_CONDUCTIVITY;
    public string $SC_DIALYSATE_TEMP;
    public bool $SC_RESIDUAL_TEST_NEGATIVE;
    public string $UF_GOAL;
    public bool $DB_STANDARD_HCOA;
    public bool $DB_ACID;

    public bool $AT_FISTULA;
    public bool $AT_GRAFT;
    public bool $AT_RIGHT;
    public bool $AT_LEFT;
    public bool $B_STRONG;
    public bool $B_WEEK;
    public bool $B_ABSENT;
    public bool $T_STRONG;
    public bool $T_WEAK;
    public bool $T_ABSENT;
    public bool $H_PRESENT;
    public bool $H_ABSENT;
    public string $H_OTHER_NOTES;

    public bool $CVC_SUBCATH;
    public bool $CVC_JUGCATH;
    public bool $CVC_FEMCATCH;
    public bool $CVC_PERMACATH;
    public bool $CVC_RIGHT;
    public bool $CVC_LEFT;
    public bool $CVC_GOOD_FLOW_A;
    public bool $CVC_GOOD_FLOW_V;
    public bool $CVC_W_RESISTANCE_A;
    public bool $CVC_W_RESISTANCE_V;
    public bool $CVC_CLOTTED_A;
    public bool $CVC_CLOTTED_V;

    public bool $PRE_AMBULATORY;
    public bool $PRE_AMBULATORY_W_ASSIT;
    public bool $PRE_WHEEL_CHAIR;
    public bool $PRE_CONSCIOUS;
    public bool $PRE_COHERENT;
    public bool $PRE_DISORIENTED;
    public bool $PRE_DROWSY;
    public bool $PRE_CLEAR;
    public bool $PRE_CRACKLES;
    public bool $PRE_RHONCHI;
    public bool $PRE_WHEEZES;
    public bool $PRE_RALES;
    public bool $PRE_DISTENDED_JUGULAR_VIEW;
    public bool $PRE_ASCITES;
    public bool $PRE_EDEMA;
    public bool $PRE_LOCATION;
    public string $PRE_LOCATION_NOTES;
    public bool $PRE_DEPTH;
    public string $PRE_DEPTH_NOTES;
    public bool $PRE_REGULAR;
    public bool $PRE_IRREGULAR;


    public bool $POST_AMBULATORY;
    public bool $POST_AMBULATORY_W_ASSIT;
    public bool $POST_WHEEL_CHAIR;
    public bool $POST_CONSCIOUS;
    public bool $POST_COHERENT;
    public bool $POST_DISORIENTED;
    public bool $POST_DROWSY;
    public bool $POST_CLEAR;
    public bool $POST_CRACKLES;
    public bool $POST_RHONCHI;
    public bool $POST_WHEEZES;
    public bool $POST_RALES;
    public bool $POST_DISTENDED_JUGULAR_VIEW;
    public bool $POST_ASCITES;
    public bool $POST_EDEMA;
    public bool $POST_LOCATION;
    public string $POST_LOCATION_NOTES;
    public bool $POST_DEPTH;
    public string $POST_DEPTH_NOTES;
    public bool $POST_REGULAR;
    public bool $POST_IRREGULAR;
    public int $MACHINE_NO;
    public string $DRY_WEIGHT;

    public string $RML;
    public string $HEPA_PROFILE;
    public string $CXR;
    public string $OTHER_INPUT = '';

    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }

    public function mount()
    {
        $this->reload();
    }
    #[On('cancel-other')]
    public function reload()
    {
        $data = $this->hemoServices->Get($this->HEMO_ID);
        if ($data) {
            $this->SE_DETAILS = $data->SE_DETAILS ?? '';
            $this->SO_DETAILS = $data->SO_DETAILS ?? '';
            $this->BFR = $data->BFR ?? 0;
            $this->DFR = $data->DFR ?? 0;
            $this->DURATION = $data->DURATION ?? 0;
            $this->DIALYZER = $data->DIALYZER ?? '';
            $this->DIALSATE_N = $data->DIALSATE_N ?? '';
            $this->DIALSATE_K = $data->DIALSATE_K ?? '';
            $this->DIALSATE_C = $data->DIALSATE_C ?? '';
            $this->DETAILS_USE_NEXT = $data->DETAILS_USE_NEXT ?? false;
            $this->ORDER_USE_NEXT = $data->ORDER_USE_NEXT ?? false;
            $this->SE_DETAILS_NEXT = $data->SE_DETAILS_NEXT ?? '';
            $this->HEPARIN = $data->HEPARIN ?? '';
            $this->REUSE_NO = $data->REUSE_NO ?? '';
            $this->REUSE_NEXT = $data->REUSE_NEXT ?? '';
            $this->FLUSHING = $data->FLUSHING ?? '';
            $this->UF_GOAL = $data->UF_GOAL ?? '';
            $this->DB_STANDARD_HCOA = $data->DB_STANDARD_HCOA ?? false;
            $this->DB_ACID = $data->DB_ACID ?? false;
            $this->SC_MACHINE_TEST = $data->SC_MACHINE_TEST ?? false;
            $this->SC_SECURED_CONNECTIONS = $data->SC_SECURED_CONNECTIONS ?? false;
            $this->SC_SALINE_LINE_DOUBLE_CLAMP = $data->SC_SALINE_LINE_DOUBLE_CLAMP ?? false;
            $this->SC_CONDUCTIVITY = $data->SC_CONDUCTIVITY ?? '';
            $this->SC_DIALYSATE_TEMP = $data->SC_DIALYSATE_TEMP ?? '';
            $this->SC_RESIDUAL_TEST_NEGATIVE = $data->SC_RESIDUAL_TEST_NEGATIVE ?? false;
            $this->AT_FISTULA = $data->AT_FISTULA ?? false;
            $this->AT_GRAFT = $data->AT_GRAFT ?? false;
            $this->AT_RIGHT = $data->AT_RIGHT ?? false;
            $this->AT_LEFT = $data->AT_LEFT ?? false;
            $this->B_STRONG = $data->B_STRONG ?? false;
            $this->B_WEEK = $data->B_WEEK ?? false;
            $this->B_ABSENT = $data->B_ABSENT ?? false;
            $this->T_STRONG = $data->T_STRONG ?? false;
            $this->T_WEAK = $data->T_WEAK ?? false;
            $this->T_ABSENT = $data->T_ABSENT ?? false;
            $this->H_PRESENT = $data->H_PRESENT ?? false;
            $this->H_ABSENT = $data->H_ABSENT ?? false;
            $this->H_OTHER_NOTES = $data->H_OTHER_NOTES ?? '';

            $this->CVC_SUBCATH = $data->CVC_SUBCATH ?? false;
            $this->CVC_JUGCATH = $data->CVC_JUGCATH ?? false;
            $this->CVC_FEMCATCH = $data->CVC_FEMCATCH ?? false;
            $this->CVC_PERMACATH = $data->CVC_PERMACATH ?? false;
            $this->CVC_RIGHT = $data->CVC_RIGHT ?? false;
            $this->CVC_LEFT = $data->CVC_LEFT ?? false;
            $this->CVC_GOOD_FLOW_A = $data->CVC_GOOD_FLOW_A ?? false;
            $this->CVC_GOOD_FLOW_V = $data->CVC_GOOD_FLOW_V ?? false;
            $this->CVC_W_RESISTANCE_A = $data->CVC_W_RESISTANCE_A ?? false;
            $this->CVC_W_RESISTANCE_V = $data->CVC_W_RESISTANCE_V ?? false;
            $this->CVC_CLOTTED_A = $data->CVC_CLOTTED_A ?? false;
            $this->CVC_CLOTTED_V = $data->CVC_CLOTTED_V ?? false;

            $this->PRE_AMBULATORY = $data->PRE_AMBULATORY ?? false;
            $this->PRE_AMBULATORY_W_ASSIT = $data->PRE_AMBULATORY_W_ASSIT ?? false;
            $this->PRE_WHEEL_CHAIR = $data->PRE_WHEEL_CHAIR ?? false;
            $this->PRE_CONSCIOUS = $data->PRE_CONSCIOUS ?? false;
            $this->PRE_COHERENT = $data->PRE_COHERENT ?? false;
            $this->PRE_DISORIENTED = $data->PRE_DISORIENTED ?? false;
            $this->PRE_DROWSY = $data->PRE_DROWSY ?? false;
            $this->PRE_CLEAR = $data->PRE_CLEAR ?? false;
            $this->PRE_CRACKLES = $data->PRE_CRACKLES ?? false;
            $this->PRE_RHONCHI = $data->PRE_RHONCHI ?? false;
            $this->PRE_WHEEZES = $data->PRE_WHEEZES ?? false;
            $this->PRE_RALES = $data->PRE_RALES ?? false;
            $this->PRE_DISTENDED_JUGULAR_VIEW = $data->PRE_DISTENDED_JUGULAR_VIEW ?? false;
            $this->PRE_ASCITES = $data->PRE_ASCITES ?? false;
            $this->PRE_EDEMA = $data->PRE_EDEMA ?? false;
            $this->PRE_LOCATION = $data->PRE_LOCATION ?? false;
            $this->PRE_LOCATION_NOTES = $data->PRE_LOCATION_NOTES ?? '';
            $this->PRE_DEPTH = $data->PRE_DEPTH ?? false;
            $this->PRE_DEPTH_NOTES = $data->PRE_DEPTH_NOTES ?? '';
            $this->PRE_REGULAR = $data->PRE_REGULAR ?? false;
            $this->PRE_IRREGULAR = $data->PRE_IRREGULAR ?? false;

            $this->POST_AMBULATORY = $data->POST_AMBULATORY ?? false;
            $this->POST_AMBULATORY_W_ASSIT = $data->POST_AMBULATORY_W_ASSIT ?? false;
            $this->POST_WHEEL_CHAIR = $data->POST_WHEEL_CHAIR ?? false;
            $this->POST_CONSCIOUS = $data->POST_CONSCIOUS ?? false;
            $this->POST_COHERENT = $data->POST_COHERENT ?? false;
            $this->POST_DISORIENTED = $data->POST_DISORIENTED ?? false;
            $this->POST_DROWSY = $data->POST_DROWSY ?? false;
            $this->POST_CLEAR = $data->POST_CLEAR ?? false;
            $this->POST_CRACKLES = $data->POST_CRACKLES ?? false;
            $this->POST_RHONCHI = $data->POST_RHONCHI ?? false;
            $this->POST_WHEEZES = $data->POST_WHEEZES ?? false;
            $this->POST_RALES = $data->POST_RALES ?? false;
            $this->POST_DISTENDED_JUGULAR_VIEW = $data->POST_DISTENDED_JUGULAR_VIEW ?? false;
            $this->POST_ASCITES = $data->POST_ASCITES ?? false;
            $this->POST_EDEMA = $data->POST_EDEMA ?? false;
            $this->POST_LOCATION = $data->POST_LOCATION ?? false;
            $this->POST_LOCATION_NOTES = $data->POST_LOCATION_NOTES ?? '';
            $this->POST_DEPTH = $data->POST_DEPTH ?? false;
            $this->POST_DEPTH_NOTES = $data->POST_DEPTH_NOTES ?? '';
            $this->POST_REGULAR = $data->POST_REGULAR ?? false;
            $this->POST_IRREGULAR = $data->POST_IRREGULAR ?? false;
            $this->MACHINE_NO = $data->MACHINE_NO ?? 0;
            $this->DRY_WEIGHT = $data->DRY_WEIGHT ?? '';
            $this->RML = $data->RML ?? '';
            $this->HEPA_PROFILE = $data->HEPA_PROFILE ?? '';
            $this->CXR = $data->CXR ?? '';
            // $this->NEXT_HEPA_PROFILE = $data->NEXT_HEPA_PROFILE ?? '';

        }
    }
    #[On('save-other')]
    public function save()
    {
        DB::beginTransaction();
        try {
            //code...
            $this->hemoServices->SaveOthers(
                $this->HEMO_ID,
                $this->SE_DETAILS,
                $this->SO_DETAILS,
                $this->BFR,
                $this->DFR,
                $this->DURATION,
                $this->DIALYZER,
                $this->DIALSATE_N,
                $this->DIALSATE_K,
                $this->DIALSATE_C,
                $this->DETAILS_USE_NEXT,
                $this->ORDER_USE_NEXT,
                $this->SE_DETAILS_NEXT,
                $this->HEPARIN,
                $this->REUSE_NO,
                $this->REUSE_NEXT,
                $this->FLUSHING,
                $this->UF_GOAL,
                $this->DB_STANDARD_HCOA,
                $this->DB_ACID,
                $this->SC_MACHINE_TEST,
                $this->SC_SECURED_CONNECTIONS,
                $this->SC_SALINE_LINE_DOUBLE_CLAMP,
                $this->SC_CONDUCTIVITY,
                $this->SC_DIALYSATE_TEMP,
                $this->SC_RESIDUAL_TEST_NEGATIVE,
                $this->AT_FISTULA,
                $this->AT_GRAFT,
                $this->AT_RIGHT,
                $this->AT_LEFT,
                $this->B_STRONG,
                $this->B_WEEK,
                $this->B_ABSENT,
                $this->T_STRONG,
                $this->T_WEAK,
                $this->T_ABSENT,
                $this->H_PRESENT,
                $this->H_ABSENT,
                $this->H_OTHER_NOTES,
                $this->CVC_SUBCATH,
                $this->CVC_JUGCATH,
                $this->CVC_FEMCATCH,
                $this->CVC_PERMACATH,
                $this->CVC_RIGHT,
                $this->CVC_LEFT,
                $this->CVC_GOOD_FLOW_A,
                $this->CVC_GOOD_FLOW_V,
                $this->CVC_W_RESISTANCE_A,
                $this->CVC_W_RESISTANCE_V,
                $this->CVC_CLOTTED_A,
                $this->CVC_CLOTTED_V,
                $this->PRE_AMBULATORY,
                $this->PRE_AMBULATORY_W_ASSIT,
                $this->PRE_WHEEL_CHAIR,
                $this->PRE_CONSCIOUS,
                $this->PRE_COHERENT,
                $this->PRE_DISORIENTED,
                $this->PRE_DROWSY,
                $this->PRE_CLEAR,
                $this->PRE_CRACKLES,
                $this->PRE_RHONCHI,
                $this->PRE_WHEEZES,
                $this->PRE_RALES,
                $this->PRE_DISTENDED_JUGULAR_VIEW,
                $this->PRE_ASCITES,
                $this->PRE_EDEMA,
                $this->PRE_LOCATION,
                $this->PRE_LOCATION_NOTES,
                $this->PRE_DEPTH,
                $this->PRE_DEPTH_NOTES,
                $this->PRE_REGULAR,
                $this->PRE_IRREGULAR,

                $this->POST_AMBULATORY,
                $this->POST_AMBULATORY_W_ASSIT,
                $this->POST_WHEEL_CHAIR,
                $this->POST_CONSCIOUS,
                $this->POST_COHERENT,
                $this->POST_DISORIENTED,
                $this->POST_DROWSY,
                $this->POST_CLEAR,
                $this->POST_CRACKLES,
                $this->POST_RHONCHI,
                $this->POST_WHEEZES,
                $this->POST_RALES,
                $this->POST_DISTENDED_JUGULAR_VIEW,
                $this->POST_ASCITES,
                $this->POST_EDEMA,
                $this->POST_LOCATION,
                $this->POST_LOCATION_NOTES,
                $this->POST_DEPTH,
                $this->POST_DEPTH_NOTES,
                $this->POST_REGULAR,
                $this->POST_IRREGULAR,
                $this->MACHINE_NO,
                $this->DRY_WEIGHT,
                $this->RML ?? '',
                $this->HEPA_PROFILE ?? '',
                $this->CXR ?? '',
                ''
            );
            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
        //  session()->flash('message','Save change successfully');
    }
    public function detailsUseNext()
    {
        if ($this->STATUS == 2 || $this->STATUS == 3) {
            return;
        }
        if ($this->Modify) {
            return;
        }
        $result = (bool) $this->hemoServices->UpdatedSpecialOrder($this->HEMO_ID);
        $this->DETAILS_USE_NEXT = $result;
        if ($result) {
            session()->flash('message', 'Special order will be used for the next treatment successfully.');
            return;
        }
        session()->flash('error', 'Special order will not be used for the next treatment.');
    }
    public function orderUseNext()
    {
        if ($this->STATUS == 2 || $this->STATUS == 3) {
            return;
        }

        if ($this->Modify) {
            return;
        }
        $result = (bool) $this->hemoServices->UpdatedStandingOrder($this->HEMO_ID);
        $this->ORDER_USE_NEXT = $result;
        if ($result) {
            session()->flash('message', 'Standing order will be used for the next treatment successfully.');
            return;
        }
        session()->flash('error', 'Standing order will not be used for the next treatment.');
    }
    public function render()
    {
        return view('livewire.hemodialysis.other-details');
    }
}
