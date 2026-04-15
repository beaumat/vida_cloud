<?php

namespace App\Livewire\Tax;

use App\Models\Accounts;
use App\Models\Tax;
use App\Models\TaxTypes;
use App\Models\VatMethod;
use App\Services\TaxServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tax List')]
class TaxForm extends Component
{

    public int $ID;
    public string $NAME;
    public int $TAX_TYPE;
    public float $RATE;
    public int $VAT_METHOD;
    public int $TAX_ACCOUNT_ID;
    public int $ASSET_ACCOUNT_ID;
    public bool $INACTIVE;
    public $taxTypes = [];
    public $vatMethod = [];
    public $accountList = [];
    public $accountList2 = [];
    public function mount($id = null)
    {
        $this->taxTypes = TaxTypes::all();
        $this->vatMethod = VatMethod::all();
        $acct = Accounts::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
        $this->accountList = $acct;
        $this->accountList2 = $acct;
        if (is_numeric($id)) {

            $tax = Tax::where('ID', $id)->first();

            if ($tax) {
                $this->ID = $tax->ID;
                $this->NAME = $tax->NAME;
                $this->TAX_TYPE = $tax->TAX_TYPE;
                $this->RATE = $tax->RATE ? $tax->RATE : 0;
                $this->VAT_METHOD = $tax->VAT_METHOD ? $tax->VAT_METHOD : 0;
                $this->TAX_ACCOUNT_ID = $tax->TAX_ACCOUNT_ID ?  $tax->TAX_ACCOUNT_ID : 0;
                $this->ASSET_ACCOUNT_ID = $tax->ASSET_ACCOUNT_ID ?  $tax->ASSET_ACCOUNT_ID : 0;
                $this->INACTIVE = $tax->INACTIVE;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancefinancialtax_list')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->NAME = '';
        $this->TAX_TYPE = 0;
        $this->RATE = 0;
        $this->VAT_METHOD = 0;
        $this->TAX_ACCOUNT_ID = 0;
        $this->ASSET_ACCOUNT_ID = 0;
        $this->INACTIVE = false;
    }


    public function save(TaxServices $taxServices)
    {
        $this->validate(
            [
                'NAME' => 'required|max:50|unique:location_group,name,' . $this->ID
            ],
            [],
            [
                'NAME' => 'Name'
            ]
        );

        try {
            if ($this->ID === 0) {
                $this->ID = $taxServices->Store($this->NAME, $this->TAX_TYPE, $this->RATE, $this->VAT_METHOD, $this->TAX_ACCOUNT_ID, $this->ASSET_ACCOUNT_ID, $this->INACTIVE);
                session()->flash('message', 'Successfully created');
            } else {
                $taxServices->Update($this->ID, $this->NAME, $this->TAX_TYPE, $this->RATE, $this->VAT_METHOD, $this->TAX_ACCOUNT_ID, $this->ASSET_ACCOUNT_ID, $this->INACTIVE);
                session()->flash('message', 'Successfully updated');
            }
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
        return view('livewire.tax.tax-form');
    }
}
