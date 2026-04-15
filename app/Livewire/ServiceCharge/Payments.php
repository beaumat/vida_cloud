<?php
namespace App\Livewire\ServiceCharge;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Payments extends Component
{

    #[Reactive]
    public int $SERVICE_CHARGES_ID;
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public int $openStatus = 0;
    public $data = [];
    public float $ORG_AMOUNT = 0;
    private $patientPaymentServices;
    private $serviceChargeServices;
    public function boot(PatientPaymentServices $patientPaymentServices, ServiceChargeServices $serviceChargeServices)
    {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->serviceChargeServices  = $serviceChargeServices;
    }
    public function mount()
    {
        $data = $this->serviceChargeServices->get($this->SERVICE_CHARGES_ID);
        if ($data) {
            $this->ORG_AMOUNT = (float) $data->AMOUNT;
        }
    }
    public function delete(int $ID, int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID)
    {

        DB::beginTransaction();
        try {
            //code...
            $this->patientPaymentServices->PaymentChargesDelete(
                $ID,
                $PATIENT_PAYMENT_ID,
                $SERVICE_CHARGES_ITEM_ID
            );
            DB::commit();
            $this->patientPaymentServices->UpdatePaymentChargesApplied($PATIENT_PAYMENT_ID);
            $this->serviceChargeServices->updateServiceChargesItemPaid($SERVICE_CHARGES_ITEM_ID);

            $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->dispatch('update-status');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }

    public function render()
    {
        $this->data = $this->patientPaymentServices->ServiceChargesPaymentList($this->SERVICE_CHARGES_ID, $this->PATIENT_ID);
        return view('livewire.service-charge.payments');
    }
}
