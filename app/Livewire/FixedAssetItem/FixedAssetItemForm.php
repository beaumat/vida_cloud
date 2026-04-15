<?php
namespace App\Livewire\FixedAssetItem;

use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\DepreciationServices;
use App\Services\FixedAssetItemServices;
use App\Services\ItemServices;
use Livewire\Attributes\On;
use Livewire\Component;

class FixedAssetItemForm extends Component
{

    public string $ITEM_NAME;

    public int $ID;
    public int $ITEM_ID;
    public int $LOCATION_ID;
    public int $ACCUMULATED_ACCOUNT_ID;
    public int $DEPRECIATION_ACCOUNT_ID;
    public string $PO_NUMBER;
    public string $SERIAL_NO;
    public string $WARRANTIY_EXPIRED;
    public bool $PERSONAL_PROPERTY_RETURN;
    public bool $IS_NEW;
    public string $OTHER_DESCRIPTION;
    public int $YEAR_PURCHASE;
    public string $PO_DATE;
    public int $YEAR_MODEL;
    public int $QUANTITY;
    public float $AQ_COST   = 0;
    public int $USEFUL_LIFE = 1;
    public bool $showModal  = false;
    private $fixedAssetItemServices;
    private $accountServices;
    private $itemServices;
    private $dateServices;
    public $accountList = [];
    public bool $INACTIVE;
    private $depreciationServices;
    public float $PER_YEAR;
    public float $PER_MONTH;

    public function boot(
        FixedAssetItemServices $fixedAssetItemServices,
        AccountServices $accountServices,
        ItemServices $itemServices,
        DepreciationServices $depreciationServices,
        DateServices $dateServices
    ) {
        $this->fixedAssetItemServices = $fixedAssetItemServices;
        $this->accountServices        = $accountServices;
        $this->itemServices           = $itemServices;
        $this->depreciationServices   = $depreciationServices;
        $this->dateServices           = $dateServices;
    }
    
    #[On('open-asset-item')]
    public function openModal($result)
    {
        $ID                = $result['ID'] ?? 0;
        $this->accountList = $this->accountServices->getAccount(false);
        $data              = $this->fixedAssetItemServices->Get($ID);
        if ($data) {
            $this->ID                       = $data->ID;
            $this->ITEM_ID                  = $data->ITEM_ID ?? 0;
            $this->LOCATION_ID              = $data->LOCATION_ID ?? 0;
            $this->ACCUMULATED_ACCOUNT_ID   = $data->ACCUMULATED_ACCOUNT_ID ?? 0;
            $this->DEPRECIATION_ACCOUNT_ID  = $data->DEPRECIATION_ACCOUNT_ID ?? 0;
            $this->PO_NUMBER                = $data->PO_NUMBER ?? '';
            $this->SERIAL_NO                = $data->SERIAL_NO ?? '';
            $this->WARRANTIY_EXPIRED        = $data->WARRANTIY_EXPIRED ?? false;
            $this->PERSONAL_PROPERTY_RETURN = $data->PERSONAL_PROPERTY_RETURN ?? false;
            $this->IS_NEW                   = $data->IS_NEW ?? false;
            $this->OTHER_DESCRIPTION        = $data->OTHER_DESCRIPTION ?? '';

            $this->YEAR_PURCHASE = $data->YEAR_PURCHASE ?? 0;
            $this->YEAR_MODEL    = $data->YEAR_MODEL ?? 0;
            $this->QUANTITY      = $data->QUANTITY ?? 0;
            $this->AQ_COST       = $data->AQ_COST ?? 0;

            $this->USEFUL_LIFE = $data->USEFUL_LIFE ?? 0;
            $this->INACTIVE    = $data->INACTIVE ?? false;

            $this->PO_DATE = $data->PO_DATE ?? '';

        } else {

            $this->ITEM_ID                  = (int) $result['ITEM_ID'];
            $this->LOCATION_ID              = (int) $result['LOCATION_ID'];
            $this->ID                       = 0;
            $this->ACCUMULATED_ACCOUNT_ID   = $this->depreciationServices->ACCUMULATED_ACCOUNT_ID;
            $this->DEPRECIATION_ACCOUNT_ID  = $this->depreciationServices->DEPRECIATION_ACCOUNT_ID;
            $this->PO_NUMBER                = '';
            $this->SERIAL_NO                = '';
            $this->WARRANTIY_EXPIRED        = false;
            $this->PERSONAL_PROPERTY_RETURN = false;
            $this->IS_NEW                   = false;
            $this->OTHER_DESCRIPTION        = '';

            $this->YEAR_PURCHASE = 0;
            $this->YEAR_MODEL    = 0;
            $this->QUANTITY      = 0;
            $this->AQ_COST       = 0;
            $this->USEFUL_LIFE   = 1;
            $this->INACTIVE      = false;
            $this->PO_DATE       =  '' ;
        }

        $this->getDisplay();
        $this->showModal = true;
    }
    public function Recomputed()
    {
        $cost = $this->AQ_COST > 0 ? $this->AQ_COST : 0;
        $use  = $this->USEFUL_LIFE > 0 ? $this->USEFUL_LIFE : 1;

        $this->PER_YEAR = $cost / $use;
        $this->PER_MONTH = $this->PER_YEAR / 12;
    }
    private function getDisplay()
    {
        $data = $this->itemServices->get($this->ITEM_ID);

        if ($data) {
            $this->ITEM_NAME = $data->DESCRIPTION ?? '';
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save()
    {

        $this->validate([
            'ACCUMULATED_ACCOUNT_ID' => 'required|numeric|exists:account,id',
            'QUANTITY'               => 'required|numeric|not_in:0',
            'AQ_COST'                => 'required|numeric|not_in:0',
            'USEFUL_LIFE'            => 'required|numeric|not_in:0',
            'PO_DATE'                => 'required|date',

        ], [], [
            'ACCUMULATED_ACCOUNT_ID' => 'Accumulated Account',
            'QUANTITY'               => 'Quantity',
            'AQ_COST'                => 'Aquisition Cost',
            'USEFUL_LIFE'            => 'Usefull life',
            'PO_DATE'                => 'Purchase Order Date',
        ]);

        $this->YEAR_PURCHASE = $this->dateServices->dateToYear($this->PO_DATE);

        if ($this->ID > 0) {
            $this->fixedAssetItemServices->Update(
                $this->ID,
                $this->ACCUMULATED_ACCOUNT_ID,
                $this->DEPRECIATION_ACCOUNT_ID,
                $this->PO_NUMBER,
                $this->SERIAL_NO,
                $this->WARRANTIY_EXPIRED,
                $this->PERSONAL_PROPERTY_RETURN,
                $this->IS_NEW,
                $this->OTHER_DESCRIPTION,
                $this->YEAR_PURCHASE,
                $this->YEAR_MODEL,
                $this->QUANTITY,
                $this->AQ_COST,
                $this->USEFUL_LIFE,
                $this->INACTIVE,
                $this->PO_DATE
            );
        } else {
            $this->fixedAssetItemServices->store(
                $this->ITEM_ID,
                $this->LOCATION_ID,
                $this->ACCUMULATED_ACCOUNT_ID,
                $this->DEPRECIATION_ACCOUNT_ID,
                $this->PO_NUMBER,
                $this->SERIAL_NO,
                $this->WARRANTIY_EXPIRED,
                $this->PERSONAL_PROPERTY_RETURN,
                $this->IS_NEW,
                $this->OTHER_DESCRIPTION,
                $this->YEAR_PURCHASE,
                $this->YEAR_MODEL,
                $this->QUANTITY,
                $this->AQ_COST,
                $this->USEFUL_LIFE,
                $this->PO_DATE
            );
        }

        $this->dispatch('refresh-list');
        $this->closeModal();
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }

    public function render()
    {
        $this->Recomputed();
        return view('livewire.fixed-asset-item.fixed-asset-item-form');
    }
}
