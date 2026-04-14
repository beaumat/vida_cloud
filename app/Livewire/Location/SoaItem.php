<?php

namespace App\Livewire\Location;

use App\Services\ItemSoaServices;
use App\Services\LocationServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Soa Items')]
class SoaItem extends Component
{

    public int $LOCATION_ID;

    public string $LOCATION_NAME;
    public int $ID;
    public int $TYPE;
    public int $LINE;
    public string $ITEM_NAME = '';
    public string $UNIT_NAME = '';
    public float $RATE = 0;
    public string $DOSAGE = '';
    public string $ROUTE = '';
    public string $FREQUENCY = '';
    public string $BRAND = '';
    public int $GROUP_ID = 0;
    public bool $SC_BASE = false;
    public bool $SOA_BASE = false;
    public string $GENERIC_NAME = '';


    public $locationList = [];
    public $TO_LOCATION_ID;
    public bool $ACTUAL_BASE;
    public $editid = null;
    public int $editTYPE;
    public string $editITEM_NAME;
    public string $editUNIT_NAME;
    public float $editRATE;
    public int $editLINE = 0;
    public bool $editACTUAL_BASE;

    public string $editDOSAGE;
    public string $editROUTE;
    public string $editFREQUENCY;
    public string $editBRAND;
    public int $editGROUP_ID;
    public bool $editSC_BASE;
    public bool $editSOA_BASE;
    public string $editGENERIC_NAME;

    public int $editFIX_QTY;

    public bool $editITEM_CONTROL_A;
    public bool $editITEM_CONTROL_B;
    public bool $editITEM_HIDE;

    public $dataList = [];
    public $search;
    public $typeList = [];
    private $itemSoaServices;
    private $locationServices;
    public function boot(ItemSoaServices $itemSoaServices, LocationServices $locationServices)
    {
        $this->itemSoaServices = $itemSoaServices;
        $this->locationServices = $locationServices;
    }

    public function mount($id)
    {
        $data = $this->locationServices->get($id);

        if ($data) {
            $this->LOCATION_NAME = $data->NAME ?? '';
            $this->LOCATION_ID = $id;
            $this->typeList = $this->itemSoaServices->TypeList();
            $this->CleanAdd();
            $this->TO_LOCATION_ID = 0;

            return;
        }

        return Redirect::route('maintenancesettingslocation')->with('Location Not found');
    }
    public function CleanAdd()
    {

        $this->ID = 0;
        $this->TYPE = 0;
        $this->ITEM_NAME = '';
        $this->UNIT_NAME = '';
        $this->RATE = 0;
        $this->ACTUAL_BASE = false;
        $this->LINE = 0;
        $this->BRAND = '';
        $this->GROUP_ID = 0;
        $this->SC_BASE = false;
        $this->GENERIC_NAME = '';
    }
    public function Add()
    {

        $this->validate(
            [
                'TYPE' => 'required|numeric|exists:soa_item_type,id',
                'ITEM_NAME' => 'required|string',
                'RATE' => 'required|numeric',
                'LINE' => 'required|numeric'
            ],
            [],
            [
                'TYPE' => 'Type',
                'ITEM_NAME' => 'Item Name',
                'RATE' => 'Rate',
                'LINE' => 'Line #'
            ]
        );

        try {

            $this->itemSoaServices->Store(
                $this->LOCATION_ID,
                $this->TYPE,
                $this->LINE,
                $this->ITEM_NAME,
                $this->UNIT_NAME,
                $this->RATE,
                $this->ACTUAL_BASE,
                $this->DOSAGE,
                $this->ROUTE,
                $this->FREQUENCY,
                $this->BRAND,
                $this->GROUP_ID,
                $this->SC_BASE,
                $this->SOA_BASE,
                $this->GENERIC_NAME
            );

            $this->CleanAdd();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }



    public function Edit(int $ID)
    {
        $data = $this->itemSoaServices->Get($ID);

        if ($data) {
            $this->editid = (int) $data->ID ?? 0;
            $this->editTYPE = $data->TYPE;
            $this->editITEM_NAME = $data->ITEM_NAME ?? '';
            $this->editUNIT_NAME = $data->UNIT_NAME ?? '';
            $this->editRATE = $data->RATE ?? 0;
            $this->editACTUAL_BASE = $data->ACTUAL_BASE ?? false;
            $this->editLINE = $data->LINE ?? 0;
            $this->editDOSAGE = $data->DOSAGE ?? '';
            $this->editROUTE = $data->ROUTE ?? '';
            $this->editFREQUENCY = $data->FREQUENCTY ?? '';
            $this->editBRAND = $data->BRAND ?? '';
            $this->editGROUP_ID = $data->GROUP_ID ?? 0;
            $this->editSC_BASE = $data->SC_BASE ?? false;
            $this->editSOA_BASE = $data->SOA_BASE ?? false;
            $this->editGENERIC_NAME = $data->GENERIC_NAME ?? '';
            $this->editFIX_QTY = $data->FIX_QTY ?? 0;
            $this->editITEM_CONTROL_A = $data->ITEM_CONTROL_A ?? false;
            $this->editITEM_CONTROL_B = $data->ITEM_CONTROL_B ?? false;
            $this->editITEM_HIDE = $data->ITEM_HIDE ?? false;
        }
    }
    public function Update()
    {

        $this->validate(
            [
                'editTYPE' => 'required|numeric|exists:soa_item_type,id',
                'editITEM_NAME' => 'required|string',
                'editRATE' => 'required|numeric',
                'editLINE' => 'required|numeric',
                'editFIX_QTY' => 'required|numeric'
            ],
            [],
            [
                'editTYPE' => 'Type',
                'editITEM_NAME' => 'Item Name',
                'editRATE' => 'Rate',
                'editLINE' => 'Line #',
                'editFIX_QTY' => 'Fix Qty'
            ]
        );


        try {
            $this->itemSoaServices->update(
                $this->editid,
                $this->editTYPE,
                $this->editLINE,
                $this->editITEM_NAME,
                $this->editUNIT_NAME,
                $this->editRATE,
                $this->editACTUAL_BASE,
                $this->editDOSAGE,
                $this->editROUTE,
                $this->editFREQUENCY,
                $this->editBRAND,
                $this->editGROUP_ID,
                $this->editSC_BASE,
                $this->editSOA_BASE,
                $this->editGENERIC_NAME,
                $this->editFIX_QTY,
                $this->editITEM_CONTROL_A,
                $this->editITEM_CONTROL_B,
                $this->editITEM_HIDE
            );
            
            $this->Canceled();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function StatusActive(int $ID)
    {

        $data = $this->itemSoaServices->Get($ID);
        if ($data->INACTIVE) {
            $this->itemSoaServices->UpdateInactive($ID, false);
        } else {
            $this->itemSoaServices->UpdateInactive($ID, true);
        }

    }

    public function Delete(int $ID)
    {
        $this->itemSoaServices->Delete($ID);
    }
    public function OpenActualBase(int $SOA_ITEM_ID)
    {
        $this->dispatch('open-actual-base', data: ['SOA_ITEM_ID' => $SOA_ITEM_ID]);

    }
    public function Canceled()
    {
        $this->editid = null;
        $this->editTYPE = 0;
        $this->editITEM_NAME = '';
        $this->editUNIT_NAME = '';
        $this->editRATE = 0;
        $this->editLINE = 0;
        $this->editITEM_CONTROL_A = false;
        $this->editITEM_CONTROL_B = false;
        $this->editITEM_HIDE = false;

    }
    private function refreshList()
    {
        $this->dataList = $this->itemSoaServices->Search($this->search, $this->LOCATION_ID);
        $this->locationList = $this->locationServices->getListExcept($this->LOCATION_ID);
    }
    public function CopyMode() // new to_location
    {
        $this->validate([
            'TO_LOCATION_ID' => 'required|exists:location,id'
        ], [], [
            'TO_LOCATION_ID' => 'Transfer Location'
        ]);


        if ($this->itemSoaServices->haveDataExist($this->TO_LOCATION_ID)) {
            session()->flash('error', 'Invalid Copy. location must empty data');
            return;
        }

        DB::beginTransaction();
        try {

            $this->itemSoaServices->copyEntryToAnotherLocation($this->LOCATION_ID, $this->TO_LOCATION_ID);
            DB::commit();
            session()->flash('message', 'successfully copy');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());

        }

    }
    public function render()
    {

        $this->refreshList();
        return view(view: 'livewire.location.soa-item');
    }
}
