<?php

namespace App\Livewire\Option;

use App\Models\PaymentMethods;
use App\Models\PaymentTerms;
use App\Models\SystemSetting;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsSales extends Component
{

    public $systemSetting = [];
    public $paymentMethodList = [];
    public $paymentTermList = [];
    public $creditLimitPolicyList = [];
    public $arAgingList = [];
    public int $DefaultPaymentTermsId, $DefaultPaymentMethodId, $CreditLimitPolicy, $ArAgingLimit;
    public bool $AutoApplyPayments, $AutoCalcPayments, $UseUndepositedFunds;
    public bool $AllowPriceOverride, $AllowPriceLevel, $AllowBlankInSellingPrice, $WarnWhenPriceBelowCost, $EnableBatchNumberInSalesOrder, $HideInactiveCustomer;
    public bool $ShowInvoiceDetailsOnStatement, $CreateStatementWithZeroBalance, $PrintDueDateOnStatement, $ShowPostdatedTransactions;

    private $systemSettingServices;
    public function boot(SystemSettingServices $systemSettingServices)
    {   
        $this->systemSettingServices = $systemSettingServices;
    }
    public function mount()
    {   
        // $this->systemSetting = $this->systemSettingServices->GetList();


        $this->LoadDropdown();
        $this->DefaultPaymentTermsId = (int) $this->returnArray('DefaultPaymentTermsId');
        $this->DefaultPaymentMethodId = (int) $this->returnArray('DefaultPaymentMethodId');
        $this->CreditLimitPolicy = (int) $this->returnArray('CreditLimitPolicy');
        $this->ArAgingLimit = (int) $this->returnArray('ArAgingLimit');

        $this->AutoApplyPayments = (bool) $this->returnArray('AutoApplyPayments');
        $this->AutoCalcPayments = (bool) $this->returnArray('AutoCalcPayments');
        $this->UseUndepositedFunds = (bool) $this->returnArray('UseUndepositedFunds');

        $this->AllowPriceOverride = (bool) $this->returnArray('AllowPriceOverride');
        $this->AllowPriceLevel = (bool) $this->returnArray('AllowPriceLevel');
        $this->AllowBlankInSellingPrice = (bool) $this->returnArray('AllowBlankInSellingPrice');
        $this->WarnWhenPriceBelowCost = (bool) $this->returnArray('WarnWhenPriceBelowCost');
        $this->EnableBatchNumberInSalesOrder = (bool) $this->returnArray('EnableBatchNumberInSalesOrder');
        $this->HideInactiveCustomer = (bool) $this->returnArray('HideInactiveCustomer');

        $this->ShowInvoiceDetailsOnStatement = (bool) $this->returnArray('ShowInvoiceDetailsOnStatement');
        $this->CreateStatementWithZeroBalance = (bool) $this->returnArray('CreateStatementWithZeroBalance');
        $this->PrintDueDateOnStatement = (bool) $this->returnArray('PrintDueDateOnStatement');
        $this->ShowPostdatedTransactions = (bool) $this->returnArray('ShowPostdatedTransactions');
    }
    public function save()
    {
        if ($this->DefaultPaymentTermsId != (int) $this->returnArray('DefaultPaymentTermsId')) {
            $this->saveOn("DefaultPaymentTermsId", $this->DefaultPaymentTermsId);
        }
        if ($this->DefaultPaymentMethodId != (int) $this->returnArray('DefaultPaymentMethodId')) {
            $this->saveOn("DefaultPaymentMethodId", $this->DefaultPaymentMethodId);
        }
        if ($this->CreditLimitPolicy != (int) $this->returnArray('CreditLimitPolicy')) {
            $this->saveOn("CreditLimitPolicy", $this->CreditLimitPolicy);
        }
        if ($this->ArAgingLimit != (int) $this->returnArray('ArAgingLimit')) {
            $this->saveOn("ArAgingLimit", $this->ArAgingLimit);
        }
        if ($this->AutoApplyPayments != (bool) $this->returnArray('AutoApplyPayments')) {
            $this->saveOn("AutoApplyPayments", $this->AutoApplyPayments);
        }

        if ($this->AutoCalcPayments != (bool) $this->returnArray('AutoCalcPayments')) {
            $this->saveOn("AutoCalcPayments", $this->AutoCalcPayments);
        }
        if ($this->UseUndepositedFunds != (bool) $this->returnArray('UseUndepositedFunds')) {
            $this->saveOn("UseUndepositedFunds", $this->UseUndepositedFunds);
        }

        if ($this->AllowPriceOverride != (bool) $this->returnArray('AllowPriceOverride')) {
            $this->saveOn("AllowPriceOverride", $this->AllowPriceOverride);
        }
        if ($this->AllowPriceLevel != (bool) $this->returnArray('AllowPriceLevel')) {
            $this->saveOn("AllowPriceLevel", $this->AllowPriceLevel);
        }
        if ($this->AllowBlankInSellingPrice != (bool) $this->returnArray('AllowBlankInSellingPrice')) {
            $this->saveOn("AllowBlankInSellingPrice", $this->AllowBlankInSellingPrice);
        }
        if ($this->WarnWhenPriceBelowCost != (bool) $this->returnArray('WarnWhenPriceBelowCost')) {
            $this->saveOn("WarnWhenPriceBelowCost", $this->WarnWhenPriceBelowCost);
        }
        if ($this->EnableBatchNumberInSalesOrder != (bool) $this->returnArray('EnableBatchNumberInSalesOrder')) {
            $this->saveOn("EnableBatchNumberInSalesOrder", $this->EnableBatchNumberInSalesOrder);
        }
        if ($this->HideInactiveCustomer != (bool) $this->returnArray('HideInactiveCustomer')) {
            $this->saveOn("HideInactiveCustomer", $this->HideInactiveCustomer);
        }
        if ($this->ShowInvoiceDetailsOnStatement != (bool) $this->returnArray('ShowInvoiceDetailsOnStatement')) {
            $this->saveOn("ShowInvoiceDetailsOnStatement", $this->ShowInvoiceDetailsOnStatement);
        }
        if ($this->CreateStatementWithZeroBalance != (bool) $this->returnArray('CreateStatementWithZeroBalance')) {
            $this->saveOn("CreateStatementWithZeroBalance", $this->CreateStatementWithZeroBalance);
        }
        if ($this->PrintDueDateOnStatement != (bool) $this->returnArray('PrintDueDateOnStatement')) {
            $this->saveOn("PrintDueDateOnStatement", $this->PrintDueDateOnStatement);
        }
        if ($this->ShowPostdatedTransactions != (bool) $this->returnArray('ShowPostdatedTransactions')) {
            $this->saveOn("ShowPostdatedTransactions", $this->ShowPostdatedTransactions);
        }

        $this->dispatch('resetValue');
        session()->flash('message', 'Save!');

    }
    public function LoadDropdown()
    {
        $this->paymentMethodList = PaymentMethods::query()->select(['ID', 'DESCRIPTION'])->get();
        $this->paymentTermList = PaymentTerms::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->get();
        $this->creditLimitPolicyList = [['ID' => 0, 'NAME' => 'Policy warning message only'], ['ID' => 1, 'NAME' => 'Enforce transaction blocking']];
        $this->arAgingList = [
            ['ID' => 0, 'NAME' => 'None'],
            ['ID' => 1, 'NAME' => 'Current balance only'],
            ['ID' => 2, 'NAME' => '1-30 days past due'],
            ['ID' => 3, 'NAME' => '30-60 days past due'],
            ['ID' => 4, 'NAME' => '60-90 days past due']
        ];
    }
    public function returnArray($name): string
    {
        foreach ($this->systemSetting as $list) {
            if (Str::lower($list->NAME) == Str::lower($name)) {
                return $list->VALUE;
            }
        }
     
        $this->systemSettingServices->NewValue($name);
        dd("record not found : " . $name);
        return '';
    }
    public function saveOn($name, $value)
    {
        SystemSetting::where('NAME', $name)->update(['VALUE' => $value]);
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
        return view('livewire.option.option-settings-sales');
    }
}
