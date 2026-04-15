<?php

namespace App\Livewire\ItemPage;

use App\Models\Accounts;
use App\Models\Contacts;
use App\Models\ItemClass;
use App\Models\ItemGroup;
use App\Models\ItemSubClass;
use App\Models\ItemType;
use App\Models\Manufacturers;
use App\Models\RateType;
use App\Models\StockType;
use App\Models\UnitOfMeasures;
use App\Services\AccountServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use App\Services\ItemServices;
use App\Services\PhicAgreementFormServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Items - Form')]
class ItemsForm extends Component
{

    public string $activeTab = 'gen';
    public bool $isAdmin = true;
    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public string $PURCHASE_DESCRIPTION;
    public int $GROUP_ID;
    public int $SUB_CLASS_ID;
    public int $TYPE = 0;
    public int $STOCK_TYPE;
    public int $GL_ACCOUNT_ID;
    public int $COGS_ACCOUNT_ID;
    public int $ASSET_ACCOUNT_ID;
    public bool $TAXABLE;
    public int $PREFERRED_VENDOR_ID;
    public int $MANUFACTURER_ID;
    public float $RATE;
    public float $COST;
    public int $RATE_TYPE;
    public int $PAYMENT_METHOD_ID;
    public string $NOTES;
    public int $BASE_UNIT_ID;
    public int $PURCHASES_UNIT_ID;
    public int $SHIPPING_UNIT_ID;
    public int $SALES_UNIT_ID;
    public bool $PRINT_INDIVIDUAL_ITEMS;
    public bool $INACTIVE;
    public bool $NON_HEMO;
    public bool $HEMO_NON_INVENTORY;
    public bool $IS_KIT;
    public bool $NON_PULL_OUT;
    public $itemType = [];
    public $stockType = [];
    public $itemGroup = [];
    public $CLASS_ID;
    public $itemClass = [];
    public $itemClass2 = [];
    public $itemSubClass = [];
    public $vendors = [];
    public $manufacturers = [];
    public $accounts = [];
    public $rateType = [];
    public $units = [];
    public int $PHIC_AGREEMENT_FORM_TITLE_ID;
    public $paftList = [];
    private $itemServices;
    private $accountServices;
    private $phicAgreementFormServices;
    public function boot(ItemServices $itemServices, AccountServices $accountServices, PhicAgreementFormServices $phicAgreementFormServices)
    {
        $this->itemServices = $itemServices;
        $this->accountServices = $accountServices;
        $this->phicAgreementFormServices = $phicAgreementFormServices;
    }
    public function SelectTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function LoadDropdown()
    {
        $this->itemSubClass = [];
        $this->itemGroup = ItemGroup::where('ITEM_TYPE', $this->TYPE)->get();
        $this->itemClass = ItemClass::all();
        $this->itemClass2 = $this->itemClass;
        $this->itemType = ItemType::whereIn('ID', ['0', '1', '2', '4', '6', '7'])->get();
        $this->stockType = StockType::all();
        $this->manufacturers = Manufacturers::all();
        $this->vendors = Contacts::query()->select(['ID', 'PRINT_NAME_AS as NAME'])->where('TYPE', '0')->where('INACTIVE', '0')->orderBy('PRINT_NAME_AS', 'asc')->get();
        $this->accounts = Accounts::query()->select(['ID', 'NAME as DESCRIPTION'])->where('INACTIVE', '0')->get();
        $this->rateType = RateType::all();
        $this->units = UnitOfMeasures::where('INACTIVE', '0')->get();
        $this->paftList = $this->phicAgreementFormServices->getTitleList();
    }
    public function ClearField()
    {
        $this->ID = 0;
        $this->CODE = "";
        $this->DESCRIPTION = "";
        $this->PURCHASE_DESCRIPTION = "";
        $this->GROUP_ID = 0;
        $this->SUB_CLASS_ID = 0;
        $this->TYPE = 0;
        $this->STOCK_TYPE = 0;

        $this->TAXABLE = false;
        $this->PREFERRED_VENDOR_ID = 0;
        $this->MANUFACTURER_ID = 0;
        $this->RATE = 0;
        $this->COST = 0;
        $this->RATE_TYPE = 0;
        $this->PAYMENT_METHOD_ID = 0;
        $this->NOTES = "";
        $this->BASE_UNIT_ID = 0;
        $this->PURCHASES_UNIT_ID = 0;
        $this->SHIPPING_UNIT_ID = 0;
        $this->SALES_UNIT_ID = 0;
        $this->PRINT_INDIVIDUAL_ITEMS = true;
        $this->INACTIVE = false;
        $this->NON_HEMO = false;
        $this->HEMO_NON_INVENTORY = false;
        $this->IS_KIT = false;
        $this->NON_PULL_OUT = false;
        $this->PHIC_AGREEMENT_FORM_TITLE_ID = 0;
        $this->AccountInsert();
    }
    public function mount($id = null)
    {

        $this->LoadDropdown();

        if (is_numeric($id)) {

            $item = $this->itemServices->get($id);

            if ($item) {
                $this->ID = $item->ID;
                $this->CODE = $item->CODE;
                $this->DESCRIPTION = $item->DESCRIPTION ? $item->DESCRIPTION : '';
                $this->PURCHASE_DESCRIPTION = $item->PURCHASE_DESCRIPTION ? $item->PURCHASE_DESCRIPTION : '';
                $this->GROUP_ID = $item->GROUP_ID ? $item->GROUP_ID : 0;
                $this->SUB_CLASS_ID = $item->SUB_CLASS_ID ? $item->SUB_CLASS_ID : 0;
                $this->TYPE = $item->TYPE;
                $this->STOCK_TYPE = $item->STOCK_TYPE ? $item->STOCK_TYPE : 0;
                $this->GL_ACCOUNT_ID = $item->GL_ACCOUNT_ID ? $item->GL_ACCOUNT_ID : 0;
                $this->COGS_ACCOUNT_ID = $item->COGS_ACCOUNT_ID ? $item->COGS_ACCOUNT_ID : 0;
                $this->ASSET_ACCOUNT_ID = $item->ASSET_ACCOUNT_ID ? $item->ASSET_ACCOUNT_ID : 0;
                $this->TAXABLE = $item->TAXABLE ? $item->TAXABLE : false;
                $this->PREFERRED_VENDOR_ID = $item->PREFERRED_VENDOR_ID ? $item->PREFERRED_VENDOR_ID : 0;
                $this->MANUFACTURER_ID = $item->MANUFACTURER_ID ? $item->MANUFACTURER_ID : 0;
                $this->RATE = $item->RATE ? $item->RATE : 0;
                $this->COST = $item->COST ? $item->COST : 0;
                $this->RATE_TYPE = $item->RATE_TYPE ? $item->RATE_TYPE : 0;
                $this->PAYMENT_METHOD_ID = $item->PAYMENT_METHOD_ID ? $item->PAYMENT_METHOD_ID : 0;
                $this->NOTES = $item->NOTES ? $item->NOTES : '';
                $this->BASE_UNIT_ID = $item->BASE_UNIT_ID ? $item->BASE_UNIT_ID : 0;
                $this->PURCHASES_UNIT_ID = $item->PURCHASES_UNIT_ID ? $item->PURCHASES_UNIT_ID : 0;
                $this->SHIPPING_UNIT_ID = $item->SHIPPING_UNIT_ID ? $item->SHIPPING_UNIT_ID : 0;
                $this->SALES_UNIT_ID = $item->SALES_UNIT_ID ? $item->SALES_UNIT_ID : 0;
                $this->PRINT_INDIVIDUAL_ITEMS = $item->PRINT_INDIVIDUAL_ITEMS ? $item->PRINT_INDIVIDUAL_ITEMS : false;
                $this->INACTIVE = $item->INACTIVE;
                $this->NON_HEMO = $item->NON_HEMO ?? false;
                $this->HEMO_NON_INVENTORY = $item->HEMO_NON_INVENTORY ?? false;
                $this->IS_KIT = $item->IS_KIT ?? false;
                $this->NON_PULL_OUT = $item->NON_PULL_OUT ?? false;
                $this->PHIC_AGREEMENT_FORM_TITLE_ID = $item->PHIC_AGREEMENT_FORM_TITLE_ID ?? 0;

                $getSubClass = ItemSubClass::where('ID', $this->SUB_CLASS_ID)->first();

                if ($getSubClass) {
                    $this->CLASS_ID = $getSubClass->CLASS_ID;
                    $this->updatedCLASSID();
                }

                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryitem')->with('error', $errorMessage);
        }

        $this->ClearField();
    }
    private function AccountInsert()
    {

        switch ($this->TYPE) {
            case 0: // inventory item
                $this->GL_ACCOUNT_ID = $this->accountServices->getByName('Sales');
                $this->COGS_ACCOUNT_ID = $this->accountServices->getByName('Cost of Goods Sold');
                $this->ASSET_ACCOUNT_ID = $this->accountServices->getByName('Inventory Asset');
                break;
            case 1: // item assembly
                $this->GL_ACCOUNT_ID = $this->accountServices->getByName('Sales');
                $this->COGS_ACCOUNT_ID = $this->accountServices->getByName('Cost of Goods Sold');
                $this->ASSET_ACCOUNT_ID = $this->accountServices->getByName('Inventory Asset');
                break;

            case 2: // non-inventory item
                $this->GL_ACCOUNT_ID = $this->accountServices->getByName('Sales');
                $this->COGS_ACCOUNT_ID = 0;
                $this->ASSET_ACCOUNT_ID = 0;
                break;

            case 3: // services
                $this->GL_ACCOUNT_ID = $this->accountServices->getByName('Sales');
                $this->COGS_ACCOUNT_ID = 0;
                $this->ASSET_ACCOUNT_ID = 0;
                break;

            case 4: // Others
                $this->GL_ACCOUNT_ID = $this->accountServices->getByName('Sales');
                $this->COGS_ACCOUNT_ID = 0;
                $this->ASSET_ACCOUNT_ID = 0;
                break;

            case 5: // Sub Total
                $this->GL_ACCOUNT_ID = 0;
                $this->COGS_ACCOUNT_ID = 0;
                $this->ASSET_ACCOUNT_ID = 0;
                break;

            case 6: // group
                $this->GL_ACCOUNT_ID = 0;
                $this->COGS_ACCOUNT_ID = 0;
                $this->ASSET_ACCOUNT_ID = 0;
                break;

            case 7: // discount
                $this->GL_ACCOUNT_ID = $this->accountServices->getByName('Sales>Discounts Given');
                $this->COGS_ACCOUNT_ID = 0;
                $this->ASSET_ACCOUNT_ID = 0;

                break;

            case 8:
                $this->GL_ACCOUNT_ID = 0;
                $this->COGS_ACCOUNT_ID = 0;
                $this->ASSET_ACCOUNT_ID = 0;

                break;
        }
    }
    public function updatedTYPE()
    {
        $this->itemGroup = ItemGroup::where('ITEM_TYPE', $this->TYPE)->get();
        $this->AccountInsert();
    }
    public function updatedCLASSID()
    {

        try {
            if ($this->CLASS_ID) {
                $this->itemSubClass = ItemSubClass::where('CLASS_ID', $this->CLASS_ID)->get();
            } else {
                $this->itemSubClass = [];
            }
        } catch (\Exception $e) {
            $this->itemSubClass = [];
        }
    }
    public function save()
    {
        if ($this->ID == 0) {

            $this->validate(
                [
                    'DESCRIPTION' => 'required|max:100|unique:item,description,' . $this->ID,
                    'TYPE' => 'required'
                ],
                [],
                [
                    'DESCRIPTION' => 'Description',
                    'TYPE' => 'Type'
                ]
            );
        } else {

            $this->validate(
                [
                    'CODE' => 'required|max:10|unique:item,code,' . $this->ID,
                    'DESCRIPTION' => 'required|max:100|unique:item,description,' . $this->ID,
                    'TYPE' => 'required'
                ],
                [],
                [
                    'CODE' => 'Code',
                    'DESCRIPTION' => 'Description',
                    'TYPE' => 'Type'
                ]
            );
        }


        try {
            $Message = '';
            if ($this->ID == 0) {

                $this->ID = $this->itemServices->Store(
                    $this->CODE,
                    $this->DESCRIPTION,
                    $this->PURCHASE_DESCRIPTION,
                    $this->GROUP_ID,
                    $this->SUB_CLASS_ID,
                    $this->TYPE,
                    $this->STOCK_TYPE,
                    $this->GL_ACCOUNT_ID,
                    $this->COGS_ACCOUNT_ID,
                    $this->ASSET_ACCOUNT_ID,
                    $this->TAXABLE,
                    $this->PREFERRED_VENDOR_ID,
                    $this->MANUFACTURER_ID,
                    $this->RATE,
                    $this->COST,
                    $this->RATE_TYPE,
                    $this->PAYMENT_METHOD_ID,
                    $this->NOTES,
                    $this->BASE_UNIT_ID,
                    $this->PURCHASES_UNIT_ID,
                    $this->SHIPPING_UNIT_ID,
                    $this->SALES_UNIT_ID,
                    $this->PRINT_INDIVIDUAL_ITEMS,
                    $this->INACTIVE,
                    $this->NON_HEMO,
                    $this->HEMO_NON_INVENTORY,
                    $this->IS_KIT,
                    $this->NON_PULL_OUT,
                    $this->PHIC_AGREEMENT_FORM_TITLE_ID
                );

                $Message = 'Successfully created.';
                return Redirect::route('maintenanceinventoryitem_edit', ['id' => $this->ID])->with('message', $Message);

            } else {

                $this->itemServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DESCRIPTION,
                    $this->PURCHASE_DESCRIPTION,
                    $this->GROUP_ID,
                    $this->SUB_CLASS_ID,
                    $this->TYPE,
                    $this->STOCK_TYPE,
                    $this->GL_ACCOUNT_ID,
                    $this->COGS_ACCOUNT_ID,
                    $this->ASSET_ACCOUNT_ID,
                    $this->TAXABLE,
                    $this->PREFERRED_VENDOR_ID,
                    $this->MANUFACTURER_ID,
                    $this->RATE,
                    $this->COST,
                    $this->RATE_TYPE,
                    $this->PAYMENT_METHOD_ID,
                    $this->NOTES,
                    $this->BASE_UNIT_ID,
                    $this->PURCHASES_UNIT_ID,
                    $this->SHIPPING_UNIT_ID,
                    $this->SALES_UNIT_ID,
                    $this->PRINT_INDIVIDUAL_ITEMS,
                    $this->INACTIVE,
                    $this->NON_HEMO,
                    $this->HEMO_NON_INVENTORY,
                    $this->IS_KIT,
                    $this->NON_PULL_OUT,
                    $this->PHIC_AGREEMENT_FORM_TITLE_ID

                );
                $Message = 'Successfully updated.';
            }
            session()->flash('message', $Message);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
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

        return view('livewire.item-page.items-form');
    }
}
