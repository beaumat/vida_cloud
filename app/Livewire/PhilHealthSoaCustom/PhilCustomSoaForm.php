<?php

namespace App\Livewire\PhilHealthSoaCustom;

use App\Services\PhilHealthSoaCustomServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Location  | Custom Soa')]
class PhilCustomSoaForm extends Component
{
    public int $LOCATION_ID;
    public $SOA_ID;
    public string $DESCRIPTION;
    public float $DRUG_MED;
    public float $LAB_DIAG;
    public  float $OPERATING_ROOM_FEE;
    public float $SUPPLIES;
    public float $ADMIN_OTHER_FEE;
    public float $DRUG_MED_PK;
    public float $LAB_DIAG_PK;
    public float $OPERATING_ROOM_FEE_PK;
    public float $SUPPLIES_PK;
    public float $ADMIN_OTHER_FEE_PK;
    public bool $INACTIVE = false;

    public float $ACTUAL_FEE;
    public float $HIDE_FEE;


    private $philHealthSoaCustomServices;
    public function boot(PhilHealthSoaCustomServices $philHealthSoaCustomServices)
    {
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
    }
    public function mount($id = null, $custom = null)
    {

        if ($id == null) {
            $errorMessage = 'Error occurred: location not exists ';
            return Redirect::route('maintenancesettingslocation')->with('error', $errorMessage);
        }

        $this->LOCATION_ID = $id;

        if (is_numeric($custom)) {

            $data = $this->philHealthSoaCustomServices->Get($custom, $id);
     
            if ($data) {
                $this->SOA_ID = $data->ID;
                $this->DESCRIPTION = $data->DESCRIPTION ?? '';
                $this->DRUG_MED = $data->DRUG_MED ?? 0;
                $this->LAB_DIAG = $data->LAB_DIAG ?? 0;
                $this->OPERATING_ROOM_FEE = $data->OPERATING_ROOM_FEE ?? 0;
                $this->SUPPLIES = $data->SUPPLIES ?? 0;
                $this->ADMIN_OTHER_FEE = $data->ADMIN_OTHER_FEE ?? 0;
                $this->SUPPLIES_PK = $data->SUPPLIES_PK ?? 0;
                $this->DRUG_MED_PK = $data->DRUG_MED_PK ?? 0;
                $this->LAB_DIAG_PK = $data->LAB_DIAG_PK ?? 0;
                $this->OPERATING_ROOM_FEE_PK = $data->OPERATING_ROOM_FEE_PK ?? 0;
                $this->ADMIN_OTHER_FEE_PK = $data->ADMIN_OTHER_FEE_PK ?? 0;
                $this->INACTIVE = $data->INACTIVE ?? false;
                $this->ACTUAL_FEE = $data->ACTUAL_FEE ?? 0;
                $this->HIDE_FEE = $data->HIDE_FEE ?? 0;
                return;
            }
            if ($this->LOCATION_ID > 0) {
                $errorMessage = 'Error occurred: Record not found. ';
                return Redirect::route('maintenancesettingslocation_custom_soa', ['id' => $this->LOCATION_ID])->with('error', $errorMessage);
            }
        }


        $this->SOA_ID = 0;
        $this->DESCRIPTION = '';
        $this->DRUG_MED = 0;
        $this->LAB_DIAG = 0;
        $this->OPERATING_ROOM_FEE = 0;
        $this->SUPPLIES = 0;
        $this->ADMIN_OTHER_FEE = 0;

        $this->DRUG_MED_PK = 0;
        $this->LAB_DIAG_PK = 0;
        $this->SUPPLIES_PK = 0;
        $this->OPERATING_ROOM_FEE_PK = 0;
        $this->ADMIN_OTHER_FEE_PK = 0;
        $this->INACTIVE = false;
        $this->ACTUAL_FEE = 0;
        $this->HIDE_FEE = 0;
    }
    public function save()
    {
        $this->validate(
            [
                'DESCRIPTION' => 'required',
                'LOCATION_ID' => 'required'
            ],
            [],
            [
                'DESCRIPTION' => 'Description'
            ]
        );


        if ($this->SOA_ID > 0) {

            $this->philHealthSoaCustomServices->Update(
                $this->SOA_ID,
                $this->LOCATION_ID,
                $this->DESCRIPTION,
                $this->DRUG_MED,
                $this->LAB_DIAG,
                $this->OPERATING_ROOM_FEE,
                $this->SUPPLIES,
                $this->ADMIN_OTHER_FEE,
                $this->DRUG_MED_PK,
                $this->LAB_DIAG_PK,
                $this->OPERATING_ROOM_FEE_PK,
                $this->SUPPLIES_PK,
                $this->ADMIN_OTHER_FEE_PK,
                $this->INACTIVE,
                $this->ACTUAL_FEE,
                $this->HIDE_FEE
            );
            session()->flash('message', 'Successfully update');
            return;
        }


        $this->SOA_ID =  $this->philHealthSoaCustomServices->Store(
            $this->LOCATION_ID,
            $this->DESCRIPTION,
            $this->DRUG_MED,
            $this->LAB_DIAG,
            $this->OPERATING_ROOM_FEE,
            $this->SUPPLIES,
            $this->ADMIN_OTHER_FEE,
            $this->DRUG_MED_PK,
            $this->LAB_DIAG_PK,
            $this->OPERATING_ROOM_FEE_PK,
            $this->SUPPLIES_PK,
            $this->ADMIN_OTHER_FEE_PK,
            $this->INACTIVE,
            $this->ACTUAL_FEE,
            $this->HIDE_FEE
        );

        return Redirect::route('maintenancesettingslocation_custom_soa_edit', ['id' => $this->LOCATION_ID, 'custom' => $this->SOA_ID]);
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
        return view('livewire.phil-health-soa-custom.phil-custom-soa-form');
    }
}
