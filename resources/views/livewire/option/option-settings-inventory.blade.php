<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-12">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Items Default</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group row">
                            <label class="col-md-4  col-form-label-sm">
                                Stock Type :
                            </label>
                            <div class="col-md-8 input-group input-group-sm">
                                <select wire:model.live.debounce='DefaultItemStockType' name="DefaultItemStockType"
                                    id="DefaultItemStockType" class="form-control form-control-sm">
                                    @foreach ($stockTypeList as $list)
                                        <option value="{{ $list->ID }}">{{ $list->DESCRIPTION }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">

                                <input wire:model.live='DefaultItemTaxable' type="checkbox" id="IncRefNoByLocation1">
                                <label>
                                    Taxable
                                </label>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <div class="col-md-6">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Forcasting</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-md-4  col-form-label-sm">
                                Type :
                            </label>
                            <div class="col-md-8 input-group input-group-sm">
                                <select wire:model.live.debounce='DefaultForecastingType' name="DefaultForecastingType"
                                    id="DefaultForecastingType" class="form-control form-control-sm">
                                    @foreach ($forcastType as $list)
                                        <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class=" col-md-4 col-label-form " for="SafetyStockPctLevel">
                                Safety Stock Level (%) :
                            </label>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <input wire:model.live.debounce='SafetyStockPctLevel' type="number"
                                        name="SafetyStockPctLevel" id="SafetyStockPctLevel"
                                        class="form-control form-control-sm text-right">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Item Finder</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">

                                <input wire:model.live='ShowStockBin' type="checkbox" id="IncRefNoByLocation1">
                                <label>
                                    Show stock bin
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input wire:model.live='ShowBatchNo' type="checkbox" id="IncRefNoByLocation1">
                                <label>
                                    Show batch number
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input wire:model.live='ShowUnitCost' type="checkbox" id="IncRefNoByLocation1">
                                <label>
                                    Show unit cost
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input wire:model.live='ShowExpiryDate' type="checkbox" id="ShowExpiryDate">
                                <label>
                                    Show expired date
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input wire:model.live='ShowLastPurchaseInfo' type="checkbox" id="ShowLastPurchaseInfo">
                                <label>
                                    Show last purchase info
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input wire:model.live='ShowQtyOnSO' type="checkbox" id="ShowQtyOnSO">
                                <label>
                                    Show sales order qty
                                </label>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input wire:model.live='SkipInventoryEntry' type="checkbox" id="SkipInventoryEntry">
                <label>
                    Skip Inventory Entry
                </label>

            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input wire:model.live='AllowZeroOnHand' type="checkbox" id="AllowZeroOnHand">
                <label>
                    Allow negative quantity
                </label>

            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input wire:model.live='LockQtyNeededInBuildAssembly' type="checkbox"
                    id="LockQtyNeededInBuildAssembly">
                <label>
                    Lock quantity needed in build assembly
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2 text-right">
        <button wire:click='save' class="btn btn-sm btn-success">Save</button>
    </div>
</div>
