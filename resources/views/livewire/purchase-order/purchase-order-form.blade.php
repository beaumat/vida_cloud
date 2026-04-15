<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{ $ID == 0 ? 'Create' : '' }}
                                    <a class="text-white" href="{{ route('vendorspurchase_order') }}"> Purchase Order
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if ($ID > 0)
                                        <i> {{ $STATUS_DESCRIPTION }}</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if ($Modify && $ID == 0)
                                                <livewire:select-option name="VENDOR_ID" titleName="Vendor"
                                                    :options="$vendorList" :zero="true" :isDisabled=false
                                                    wire:model='VENDOR_ID' />
                                            @else
                                                <livewire:select-option name="VENDOR_ID" titleName="Vendor"
                                                    :options="$vendorList" :zero="true" :isDisabled=true
                                                    wire:model='VENDOR_ID' />
                                            @endif

                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:select-option name="SHIP_VIA_ID" titleName="Ship Via"
                                                            :options="$shipViaList" :isDisabled=false :zero="false"
                                                            wire:model='SHIP_VIA_ID' />
                                                    @else
                                                        <livewire:select-option name="SHIP_VIA_ID" titleName="Ship Via"
                                                            :options="$shipViaList" :isDisabled=true :zero="false"
                                                            wire:model='SHIP_VIA_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:date-input name="DATE_EXPECTED" :isDisabled=false
                                                            titleName="Date Expected" wire:model='DATE_EXPECTED' />
                                                    @else
                                                        <livewire:date-input name="DATE_EXPECTED" :isDisabled=true
                                                            titleName="Date Expected" wire:model='DATE_EXPECTED' />
                                                    @endif

                                                </div>
                                                <div class="col-md-4">

                                                    @if ($Modify)
                                                        <livewire:select-option name="INPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled=false
                                                            wire:model='INPUT_TAX_ID' />
                                                    @else
                                                        <livewire:select-option name="INPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled=true
                                                            wire:model='INPUT_TAX_ID' />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if ($Modify && auth()->user()->date_enabled)
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=true wire:model='CODE' />
                                                    @endif

                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    @if ($Modify && $AMOUNT == 0)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model='LOCATION_ID' />
                                                    @endif
                                                </div>

                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:select-option name="PAYMENT_TERMS_ID"
                                                            :isDisabled=false titleName="Payment Terms"
                                                            :options="$paymentTermList" :zero="false"
                                                            wire:model='PAYMENT_TERMS_ID' />
                                                    @else
                                                        <livewire:select-option name="PAYMENT_TERMS_ID" :isDisabled=true
                                                            titleName="Payment Terms" :options="$paymentTermList"
                                                            :zero="false" wire:model='PAYMENT_TERMS_ID' />
                                                    @endif

                                                </div>
                                                <div class="col-md-8">
                                                    @if ($Modify)
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=false wire:model='NOTES' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=true wire:model='NOTES' :vertical="false" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer"
                                @if ($PO_ALREADY_BILL) style="opacity: 0.5;pointer-events: none;" @endif>
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS == 0)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info"
                                                    @if ($STATUS > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>

                                                @if ($STATUS == 0)
                                                    <button type="button" wire:click='getPosted()'
                                                        class="btn btn-sm btn-warning"
                                                        wire:confirm="Are you sure you want to post?">
                                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                    </button>
                                                @endif

                                                @if ($STATUS == 15)
                                                    <button class="btn btn-sm btn-danger" wire:click='getVoid()'
                                                        wire:confirm="Are you sure you want to void?">
                                                        Void
                                                    </button>
                                                @endif

                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0 && $STATUS > 0)
                                            <a  target="_blank" href="{{ route('vendorspurchase_order_print', ['id' => $ID]) }}"
                                                type="button" class="btn btn-sm btn-primary">
                                               Print
                                            </a>

                                            <button type="button" class="btn btn-sm btn-success"
                                                wire:click='makeBill()'>
                                                Make Bill
                                            </button>

                                            <a id="new" title="Create"
                                                href="{{ route('vendorspurchase_order_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($ID > 0)
        <section class="content">
            <div class="container-fluid bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-four-item-tab" data-toggle="pill"
                                            href="#custom-tabs-four-item" role="tab"
                                            aria-controls="custom-tabs-four-item" aria-selected="true">Items</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade show active " id="custom-tabs-four-item"
                                        role="tabpanel" aria-labelledby="custom-tabs-four-item-tab">
                                        <div class="row">
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @livewire('PurchaseOrder.PurchaseOrderFormItems', ['PO_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $INPUT_TAX_ID])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        @if ($PO_ALREADY_BILL)
                                            <label class="text-sm text-primary">Already make bill</label>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-8 text-right">
                                                <label class="text-sm">Input Tax:</label>
                                                <label
                                                    class="text-info text-lg">{{ number_format($INPUT_TAX_AMOUNT, 2) }}</label>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <label class="text-sm">Total:</label>
                                                <label
                                                    class="text-primary text-lg">{{ number_format($AMOUNT, 2) }}</label>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif

    @livewire('PurchaseOrder.MakeBill')
</div>
