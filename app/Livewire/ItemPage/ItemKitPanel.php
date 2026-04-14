<?php

namespace App\Livewire\ItemPage;

use App\Models\Items;
use App\Services\ItemKitServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemKitPanel extends Component
{

    #[Reactive()]
    public int $itemId = 0;

    public int $LOCATION_ID;
    public string $itemTypeName;
    public bool $saveSuccess = false;
    public bool $codeBase = false;
    public int $COMPONENT_ID;
    public float $QUANTITY = 1;
    public $itemDescList = [];
    public $itemCodeList = [];
    public string $search = '';
    public $componentList = [];
    public $locationList = [];
    public $editItemId = null;
    public float $newQty;
    private $itemKitServices;
    private $locationServices;
    private $userServices;
    public function boot(ItemKitServices $itemKitServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->itemKitServices = $itemKitServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }

    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = Items::query()->select(['ID', 'CODE'])->where('INACTIVE', '0')->whereIn('TYPE', ['0', '2', '3', '4', '7'])
                ->get();
            return;
        }
        $this->itemDescList = Items::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->whereIn('TYPE', ['0', '2', '3', '4', '7'])
            ->get();
    }

    public function saveItem()
    {
        $this->validate(
            [
                'COMPONENT_ID' => [
                    'required',
                    'not_in:0',
                    Rule::unique('item_kits', 'component_id')->where(function ($query) {
                        return $query->where('item_id', $this->itemId);
                    }),
                ],
                'QUANTITY' => 'required|not_in:0',
            ],
            [],
            [
                'COMPONENT_ID' => 'Item',
            ]
        );

        try {
            $this->itemKitServices->Store(
                $this->itemId,
                $this->COMPONENT_ID,
                $this->LOCATION_ID,
                $this->QUANTITY ? $this->QUANTITY : 0

            );

            $this->COMPONENT_ID = 0;
            $this->QUANTITY = 1;
            $this->updatedcodeBase();
            $this->saveSuccess = $this->saveSuccess ? false : true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount($itemId, $itemTypeName)
    {
        $this->itemId = intval($itemId);
        $this->itemTypeName = $itemTypeName;
        $this->updatedcodeBase();
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
    }
    public function editItem($id, $newQty)
    {
        $this->newQty = $newQty;
        $this->editItemId = $id;
    }
    public function updateItem($id)
    {
        $this->validate(
            [
                'newQty' => 'required|not_in:0'
            ],
            [],
            [
                'newQty' => 'Quantity',
            ]
        );

        $this->itemKitServices->Update($id, $this->newQty);
        $this->editItemId = null;

    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }
    public function deleteItem($id)
    {
        $this->itemKitServices->Delete($id);
    
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
        $this->componentList = $this->itemKitServices->List($this->itemId, $this->LOCATION_ID);
        return view('livewire.item-page.item-kit-panel');
    }
}
