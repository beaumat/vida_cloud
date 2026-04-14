<?php

namespace App\Livewire\Patient;

use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\ServiceChargeServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class TreatmentRecord extends Component
{


    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Reactive]
    public int $CONTACT_ID;
    #[Reactive]
    public int $LOCK_LOCATION_ID;
    public $search = '';
    private $hemoServices;
    private $serviceChargeServices;
    private $accountServices;
    private $priceLevelLineServices;
    private $locationServices;
    public function boot(
        HemoServices $hemoServices,
        ServiceChargeServices $serviceChargeServices,
        PriceLevelLineServices $priceLevelLineServices,
        LocationServices $locationServices
    ) {
        $this->hemoServices = $hemoServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->locationServices = $locationServices;
    }
    public function CreateServiceCharge(string $DATE)
    {

        try {
            $isExist = $this->serviceChargeServices->ServicesChargesExists($DATE, $this->CONTACT_ID, $this->LOCK_LOCATION_ID);
            if ($isExist) {
                session()->flash('error', "Invalid service charge on $DATE. already exists");
                return;
            }

            $this->validate([
                'CONTACT_ID' => 'required|exists:contact,ID',
                'LOCK_LOCATION_ID' => 'required|exists:location,ID',
            ], [], [
                'CONTACT_ID' => 'Patient',
                'LOCK_LOCATION_ID' => 'Location'
            ]);

            DB::beginTransaction();
            $SERVICE_CHARGE_ID = (int) $this->serviceChargeServices->Store(
                '',
                $DATE,
                $this->CONTACT_ID,
                $this->LOCK_LOCATION_ID,
                "Create on Patient Profile",
                4,
                0,
                12,
                0,
                0,
                28,
                false,
                false
            );


            $PRICE_LEVEL_ID = 0;
            $dataItem = $this->hemoServices->ItemListWithIsCashier($this->CONTACT_ID, $this->LOCK_LOCATION_ID, $DATE);



            $dataLoc = $this->locationServices->get($this->LOCK_LOCATION_ID);
            if ($dataLoc) {
                if ($dataLoc->PRICE_LEVEL_ID > 0) {
                    $PRICE_LEVEL_ID = (int) $dataLoc->PRICE_LEVEL_ID ?? 0;
                }
            }

            foreach ($dataItem as $list) {
                $RATE = 0;
                if ($PRICE_LEVEL_ID > 0) {
                    $RATE = (float) $this->priceLevelLineServices->GetPriceByLocation($this->LOCK_LOCATION_ID, $list->ITEM_ID);
                } else {
                    $RATE = (float) $list->RATE ?? 0;
                }

                $AMOUNT = $list->QUANTITY * $RATE;

                $SC_ITEM_ID = $this->serviceChargeServices->ItemStore(
                    $SERVICE_CHARGE_ID,
                    $list->ITEM_ID,
                    $list->QUANTITY,
                    $list->UNIT_ID ?? 0,
                    $list->UNIT_BASE_QUANTITY ?? 1,
                    $RATE ?? 0,
                    0,
                    $AMOUNT,
                    $list->TAXABLE,
                    0,
                    0,
                    $list->COGS_ACCOUNT_ID ?? 0,
                    $list->ASSET_ACCOUNT_ID ?? 0,
                    0,
                    0,
                    false,
                    $PRICE_LEVEL_ID
                );
                $this->hemoServices->ItemUpdateSC_ITEM_ID($list->ID, $list->HEMO_ID, $list->ITEM_ID, $SC_ITEM_ID);
            }
            $this->serviceChargeServices->ReComputed($SERVICE_CHARGE_ID);
            DB::commit();
            session()->flash('message', 'Successfully created');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash("error", $th->getMessage());
        }
    }
    public function TransferRecordTo(int $HEMO_ID)
    {
        $this->dispatch('open-transfer-contact', result: [
            'TRANSACTION_ID' => $HEMO_ID,
            'LOCATION_ID' => $this->LOCK_LOCATION_ID,
            'IS_TREATMENT' => true
        ]);
    }
    #[On('refresh-treatment-record')]
    public function render()
    {
        $dataList = $this->hemoServices->PatientRecord($this->search, $this->CONTACT_ID, 15, $this->LOCK_LOCATION_ID);
        return view('livewire.patient.treatment-record', ['dataList' => $dataList]);
    }
}
