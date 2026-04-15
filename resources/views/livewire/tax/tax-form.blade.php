<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancefinancialtax_list') }}"> Tax List </a></h5>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <livewire:text-input name="NAME" titleName="Name" wire:model='NAME' :isDisabled='false' />
                                        </div>
                                        <div class="col-md-4" @if ($ID > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <livewire:select-option name="TAX_TYPE" :options="$taxTypes" :zero="false" :isDisabled='false'
                                                titleName="Tax Type" wire:model.live='TAX_TYPE' :key="$taxTypes->pluck('ID')->join('_')" />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:number-input name="RATE" titleName="Rate" wire:model='RATE'  :isDisabled='false' />
                                        </div>

                                        @if ($TAX_TYPE == 0)
                                            <div class="col-md-4">
                                                <livewire:select-option name="TaxAcctID" :options="$accountList"
                                                    :zero="true" titleName="Tax Account" :isDisabled='false'
                                                    wire:model.live='TAX_ACCOUNT_ID' />
                                            </div>
                                        @elseif($TAX_TYPE == 1)
                                            <div class="col-md-4">
                                                <livewire:select-option name="TaxAcctID1" :options="$accountList"
                                                    :zero="true" titleName="Tax Account" :isDisabled='false'
                                                    wire:model.live='TAX_ACCOUNT_ID' />
                                            </div>
                                        @elseif($TAX_TYPE == 2)
                                            <div class="col-md-4">
                                                <livewire:select-option name="TaxAcctID" :options="$accountList"
                                                    :zero="true" titleName="Tax Account" :isDisabled='false'
                                                    wire:model.live='TAX_ACCOUNT_ID' />
                                            </div>
                                            <div class="col-md-4">
                                                <livewire:select-option name="AssetAcctID" :options="$accountList2"
                                                    :zero="true" titleName="Tax Credit Account" :isDisabled='false'
                                                    wire:model.live='ASSET_ACCOUNT_ID' />
                                            </div>
                                        @elseif($TAX_TYPE == 3)
                                            <div class="col-md-4">
                                                <livewire:select-option name="VAT_METHOD" :options="$vatMethod"
                                                    :zero="false" titleName="Vat Method" :isDisabled='false'
                                                    wire:model.live='VAT_METHOD' :key="$vatMethod->pluck('ID')->join('_')" />
                                            </div>
                                            <div class="col-md-4">
                                                <livewire:select-option name="TaxAcctID" :options="$accountList"
                                                    :zero="true" titleName="Output Tax Account" :isDisabled='false'
                                                    wire:model.live='TAX_ACCOUNT_ID' />
                                            </div>

                                            <div class="col-md-4">
                                                <livewire:select-option name="AssetAcctID" :options="$accountList2"
                                                    :zero="true" titleName="Input Tax Account" :isDisabled='false'
                                                    wire:model.live='ASSET_ACCOUNT_ID' />
                                            </div>
                                        @elseif($TAX_TYPE == 4)
                                            <div class="col-md-4">
                                                <livewire:select-option name="TaxAcctID" :options="$accountList"
                                                    :zero="true" titleName="Tax Account" :isDisabled='false'
                                                    wire:model.live='TAX_ACCOUNT_ID' />
                                            </div>
                                        @endif
                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="INACTIVE" titleName="Inactive" :isDisabled='false'
                                                wire:model='INACTIVE' />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="submit"
                                            class="btn btn-sm btn-success">{{ $ID === 0 ? 'Save' : 'Update' }}</button>
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0)
                                            <a id="new" title="Create"
                                                href="{{ route('maintenancefinancialtax_list_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
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




</div>
