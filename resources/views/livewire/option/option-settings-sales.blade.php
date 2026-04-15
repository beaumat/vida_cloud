<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-6">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Sales</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label col-form-label-sm" for="DefaultPaymentTermsId">
                                Payment Terms :
                            </label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model='DefaultPaymentTermsId' name="DefaultPaymentTermsId"
                                    id="DefaultPaymentTermsId" class="form-control form-control-sm">
                                    @foreach ($paymentTermList as $item)
                                        <option value="{{ $item->ID }}"> {{ $item->DESCRIPTION }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-md-3  col-form-label col-form-sm" for="DefaultPaymentMethodId">
                                Payment Method :
                            </label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model='DefaultPaymentMethodId' name="DefaultPaymentMethodId"
                                    id="DefaultPaymentMethodId" class="form-control form-control-sm">
                                    @foreach ($paymentMethodList as $item)
                                        <option value="{{ $item->ID }}"> {{ $item->DESCRIPTION }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label col-form-sm" for="CreditLimitPolicy">
                                Credit LimitPolicy :
                            </label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model='CreditLimitPolicy' name="CreditLimitPolicy" id="CreditLimitPolicy"
                                    class="form-control form-control-sm">
                                    @foreach ($creditLimitPolicyList as $item)
                                        <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3  col-form-label col-form-sm" for="ArAgingLimit">A/R Aging Limit
                                :</label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model.live.debounce='ArAgingLimit' name="ArAgingLimit" id="ArAgingLimit"
                                    class="form-control form-control-sm">
                                    @foreach ($arAgingList as $item)
                                        <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Received Payments</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input wire:model='AutoApplyPayments' name="AutoApplyPayments" id="AutoApplyPayments"
                                type="checkbox">
                            <label>Auto Apply Payment</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input wire:model='AutoCalcPayments' name="AutoCalcPayments" id="AutoCalcPayments"
                                type="checkbox">
                            <label>Auto Calculate Payment</label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input wire:model='UseUndepositedFunds' name="UseUndepositedFunds" id="UseUndepositedFunds"
                                type="checkbox">
                            <label>Use Undeposited Funds</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Statement</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input wire:model='ShowInvoiceDetailsOnStatement' name="ShowInvoiceDetailsOnStatement"
                                    id="ShowInvoiceDetailsOnStatement" type="checkbox">
                                <label>Show invoice details on statement</label>
                            </div>
                            <div class="custom-control custom-checkbox">

                                <input wire:model='CreateStatementWithZeroBalance' name="ShowInvoiceDetailsOnStatement"
                                    id="ShowInvoiceDetailsOnStatement" type="checkbox">
                                <label>Create statement with zero balance</label>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <input wire:model='PrintDueDateOnStatement' name="PrintDueDateOnStatement"
                                    id="PrintDueDateOnStatement" type="checkbox">
                                <label>Print due date on statement</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input wire:model='ShowPostdatedTransactions' name="ShowPostdatedTransactions"
                                    id="ShowPostdatedTransactions" type="checkbox">
                                <label>Show postdated transactions</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title">Others</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input wire:model='AllowPriceOverride' name="AllowPriceOverride"
                                    id="AllowPriceOverride" type="checkbox">
                                <label>Allow price override</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input wire:model='AllowBlankInSellingPrice' name="AllowBlankInSellingPrice"
                                    id="AllowBlankInSellingPrice" type="checkbox">
                                <label>Allow zero in selling price</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input wire:model='AllowPriceLevel' name="AllowPriceLevel" id="AllowPriceLevel"
                                    type="checkbox">
                                <label>Allow price level</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input wire:model='WarnWhenPriceBelowCost' name="WarnWhenPriceBelowCost"
                                    id="WarnWhenPriceBelowCost" type="checkbox">
                                <label>Warning when selling price is below cost</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input wire:model='EnableBatchNumberInSalesOrder' name="EnableBatchNumberInSalesOrder"
                                    id="EnableBatchNumberInSalesOrder" type="checkbox">
                                <label>Enable Bath Number in sales order</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input wire:model='HideInactiveCustomer' name="HideInactiveCustomer"
                                    id="HideInactiveCustomer" type="checkbox">
                                <label>Hide inactive customer</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2 text-right">
        <button wire:click='save' type="button" name="save" class="btn btn-sm btn-success">Save</button>
    </div>
</div>
