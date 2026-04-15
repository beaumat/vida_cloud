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
                                    <a class="text-white"
                                        href="{{ route('maintenancesettingslocation_custom_soa', ['id' => $LOCATION_ID]) }}">
                                        Location | Custom Soa </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <livewire:text-input name="DESCRIPTION" titleName="Description:" vertical=true
                                            :isDisabled=false wire:model='DESCRIPTION' />
                                    </div>
                                    <div class='col-md-6'>
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="DRUG_MED" titleName="Drugs & Medicine:"
                                            vertical=true :isDisabled=false wire:model='DRUG_MED' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="DRUG_MED_PK" titleName="Drugs & Medicine (PK):"
                                            vertical=true :isDisabled=false wire:model='DRUG_MED_PK' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="LAB_DIAG" titleName="Laboratory & Diagnostics:"
                                            vertical=true :isDisabled=false wire:model='LAB_DIAG' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="LAB_DIAG_PK"
                                            titleName="Laboratory & Diagnostics (PK):" vertical=true :isDisabled=false
                                            wire:model='LAB_DIAG_PK' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="OPERATING_ROOM_FEE" titleName="Operating Room Fee:"
                                            vertical=true :isDisabled=false wire:model='OPERATING_ROOM_FEE' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="OPERATING_ROOM_FEE_PK:"
                                            titleName="Operating Room Fee (PK)" vertical=true :isDisabled=false
                                            wire:model='OPERATING_ROOM_FEE_PK' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="SUPPLIES" titleName="Supplies" vertical=true
                                            :isDisabled=false wire:model='SUPPLIES' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="SUPPLIES_PK" titleName="Supplies (PK):"
                                            vertical=true :isDisabled=false wire:model='SUPPLIES_PK' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="ADMIN_OTHER_FEE"
                                            titleName="Administrative & Other Fees:" vertical=true :isDisabled=false
                                            wire:model='ADMIN_OTHER_FEE' />
                                    </div>
                                    <div class="col-md-6">
                                        <livewire:number-input name="ADMIN_OTHER_FEE_PK"
                                            titleName="Administrative & Other Fees (PK):" vertical=true
                                            :isDisabled=false wire:model='ADMIN_OTHER_FEE_PK' />
                                    </div>
                                    <div class="col-6">
                                        <livewire:number-input name="ACTUAL_FEE" titleName="Actual Fee:" vertical=true
                                            :isDisabled=false wire:model='ACTUAL_FEE' />
                                    </div>
                                    <div class="col-6">
                                        <livewire:number-input name="HIDE_FEE" titleName="Hide Fee:" vertical=true
                                            :isDisabled=false wire:model='HIDE_FEE' />
                                    </div>

                                    <div class="col-md-1">
                                        <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                            :isDisabled=false wire:model='INACTIVE' />
                                    </div>


                                    <div class="col-md-12 text-left pt-4">
                                        <button class="btn btn-success btn-sm" type='submit'>
                                            @if ($SOA_ID > 0)
                                                Update
                                            @else
                                                Save
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="card-footer">
                            @if ($SOA_ID > 0)
                                @livewire('PhilhealthSoaCustom.PhilCustomSoaFormItem', ['id' => $SOA_ID])
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
