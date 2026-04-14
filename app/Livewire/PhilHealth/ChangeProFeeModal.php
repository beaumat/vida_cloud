<?php

namespace App\Livewire\PhilHealth;

use App\Services\BillingServices;
use App\Services\DoctorLocationServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ChangeProFeeModal extends Component
{

    #[Reactive]
    public int $PHILHEALTH_ID;
    #[Reactive()]
    public int $LOCATION_ID;
    public bool $showModal = false;
    public $doctorList = [];
    public int $doctorid;
    public int $BILL_ID;
    private $philHealthServices;
    private $doctorLocationServices;
    private $billingServices;
    private $philHealthProfFeeServices;
    public function boot(PhilHealthServices $philHealthServices, DoctorLocationServices $doctorLocationServices, BillingServices $billingServices, PhilHealthProfFeeServices $philHealthProfFeeServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->doctorLocationServices = $doctorLocationServices;
        $this->billingServices = $billingServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
    }

    #[On('call-open-update-pf')]
    public function openModal()
    {


        $data = $this->philHealthProfFeeServices->getProfFeeFirst($this->PHILHEALTH_ID);
        if ($data) {
            $this->doctorid = $data->CONTACT_ID;
            $this->BILL_ID = (int) $data->BILL_ID ?? 0;
            $this->showModal = true;
        }

    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function update()
    {

        DB::beginTransaction();
        try {
            $this->philHealthProfFeeServices->UpdatePFContact($this->PHILHEALTH_ID, $this->doctorid);
            if ($this->BILL_ID > 0) {
                $this->billingServices->billChangeVendor($this->BILL_ID, $this->doctorid);
            }
            DB::commit();
            return Redirect::route('patientsphic_edit', ['id' => $this->PHILHEALTH_ID])->with('message', 'PF Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error:' . $th->getMessage());
        }
    }
    public function render()
    {

        if ($this->showModal) {
            $this->doctorList = $this->doctorLocationServices->ViewList($this->LOCATION_ID);
        }


        return view('livewire.phil-health.change-pro-fee-modal');
    }
}
