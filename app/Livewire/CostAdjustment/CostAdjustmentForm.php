<?php

namespace App\Livewire\CostAdjustment;

use App\Services\CostAdjustmentServices;
use App\Services\DocumentStatusServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cost Adjustment')]
class CostAdjustmentForm extends Component
{

    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $LOCATION_ID;
    public $locationList = [];
    public bool $Modify = false;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION = '';
    private $costAdjustmentServices;
    private $locationServices;
    private $userServices;
    private $documentStatusServices;
    private $priceLevelLineServices;
    private $hemoServices;
    public function boot(
        CostAdjustmentServices $costAdjustmentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        PriceLevelLineServices $priceLevelLineServices,
        HemoServices $hemoServices
    ) {
        $this->costAdjustmentServices = $costAdjustmentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->hemoServices = $hemoServices;
    }
    private function loadList()
    {
        $this->locationList = $this->locationServices->getList();
    }
    public function mount($id = null)
    {
        try {

            if (is_numeric($id)) {
                $data = $this->costAdjustmentServices->Get($id);
                if ($data) {
                    $this->loadList();
                    $this->getInfo($data);
                    $this->Modify = false;
                    return;
                }
                return Redirect::route('companycost_adjustment')->with('error', 'record not found');
            }

            $this->loadList();
            $this->LOCATION_ID = $this->userServices->getLocationDefault();
            $this->DATE = $this->userServices->getTransactionDateDefault();
            $this->CODE = "";
            $this->ID = 0;
            $this->Modify = true;
            $this->STATUS_DESCRIPTION = "";
            $this->STATUS = 0;

        } catch (\Throwable $th) {
            return Redirect::route('companycost_adjustment')->with('error', $th->getMessage());
        }

    }
    public function save()
    {

        $this->validate([
            'DATE' => 'required|date',
            'LOCATION_ID' => 'required|not_in:0|exists:location,id',
            'CODE' => 'nullable|max:20|unique:cost_adjustment,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
        ]);

        try {
            if ($this->ID == 0) {
                $this->ID = $this->costAdjustmentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID
                );
                return redirect::route('companycost_adjustment_edit', ['id' => $this->ID])
                    ->with('message', 'Successfully created!');
            }

            $this->costAdjustmentServices->Update(
                $this->ID,
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID
            );
            $this->Modify = false;
            session()->flash('message', 'Successfully updated');
        } catch (\Throwable $th) {
            session()->flash('error', $th);
        }
    }
    public function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->STATUS = $data->STATUS;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function updateCancel()
    {
        return redirect::route('companycost_adjustment_edit', ['id' => $this->ID]);
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function delete()
    {
        $this->costAdjustmentServices->Delete($this->ID);
    }
    private function GetPriceLevelID()
    {
        $locDate = $this->locationServices->get($this->LOCATION_ID);
        if ($locDate) {
            $PRICE_LEVEL_ID = (int) $locDate->PRICE_LEVEL_ID ?? 0;
            return $PRICE_LEVEL_ID;
        }
        return 0;
    }
    public function posted()
    {

        try {
            $PRICE_LEVEL_ID = $this->GetPriceLevelID();
            if ($PRICE_LEVEL_ID == 0) {
                return;
            }
            $ItemList = $this->costAdjustmentServices->ItemList($this->ID);
            DB::beginTransaction();
            foreach ($ItemList as $list) {
                $PL_ID = (int) $this->priceLevelLineServices->IS_EXIST($list->ITEM_ID, $this->LOCATION_ID);
                if ($PL_ID > 0) {
                    // update
                    $this->priceLevelLineServices->Update($PL_ID, 0, $list->COST);
                } else {
                    $this->priceLevelLineServices->Store($PRICE_LEVEL_ID, $list->ITEM_ID, 0, $list->COST);
                }
            }

            $this->costAdjustmentServices->StatusUpdate($this->ID, 15);
            DB::commit();
            $this->dispatch('re-set-journal');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return;
        }

        return redirect::route('companycost_adjustment_edit', ['id' => $this->ID])->with('message', "Successfully posted!");
    }
    #[On('re-set-journal')]
    public function hemoJournalLoad()
    {
        $dataList = $this->hemoServices->getPostedFrom($this->DATE, $this->LOCATION_ID);
        foreach ($dataList as $list) {
            try {
                DB::beginTransaction();
                $this->hemoServices->getMakeJournal($list->ID);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }

    }
    public function render()
    {
        return view('livewire.cost-adjustment.cost-adjustment-form');
    }
}
