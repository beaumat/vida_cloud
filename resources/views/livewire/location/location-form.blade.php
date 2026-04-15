<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> Location </a></h5>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <div>
                                <a class="btn btn-dark btn-xs"
                                    href="{{ route('maintenancesettingsdoctor_notes', ['id' => $ID]) }}">
                                    <i class="fa fa-commenting" aria-hidden="true"></i> Doctor Notes
                                </a>
                                <a title="Doctor Location"
                                    href="{{ route('maintenancesettingslocation_doctor', ['id' => $ID]) }}"
                                    class="btn btn-success btn-xs">
                                    <i class="fas fa-user" aria-hidden="true"></i> Doctor Link
                                </a>
                                <a title="Soa Item"
                                    href="{{ route('maintenancesettingssoa_item', ['id' => $ID]) }}"
                                    class="btn btn-secondary btn-xs">
                                    <i class="fas fa-sitemap" aria-hidden="true"></i> Soa Item
                                </a>
                                <a title="Custom Soa"
                                    href="{{ route('maintenancesettingslocation_custom_soa', ['id' => $ID]) }}"
                                    class="btn btn-xs btn-warning">
                                    <i class="fa fa-book" aria-hidden="true"></i> Custom Soa
                                </a>
                            </div>
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
                                            <livewire:text-input name="NAME" titleName="Name" wire:model='NAME'
                                                isDisabled="{{ false }}" maxlength='50' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="PRICE_LEVEL_ID" :options="$priceLevels"
                                                :zero="true" titleName="Price Level"
                                                isDisabled="{{ false }}" wire:model.live='PRICE_LEVEL_ID'
                                                :key="$priceLevels->pluck('ID')->join('_')" />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="GROUP_ID" :options="$locationGroups" :zero="true"
                                                isDisabled="{{ false }}" titleName="Group"
                                                wire:model.live='GROUP_ID' :key="$locationGroups->pluck('ID')->join('_')" />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                                isDisabled="{{ false }}" wire:model='INACTIVE' />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group border-top border-secondary">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <livewire:text-input name="NAME_OF_BUSINESS"
                                                isDisabled="{{ false }}" titleName="Branch/Business Title:"
                                                wire:model='NAME_OF_BUSINESS' maxlength='50' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:text-input name="ACCREDITATION_NO" titleName="Accreditation No."
                                                isDisabled="{{ false }}" wire:model='ACCREDITATION_NO'
                                                maxlength='20' />
                                        </div>

                                        <div class="col-md-4">

                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="PF_TAX_ID" :options="$taxList" :zero="true"
                                                isDisabled="{{ false }}" titleName="Tax PF"
                                                wire:model.live='PF_TAX_ID' :key="$taxList->pluck('ID')->join('_')" />
                                        </div>
                                        <div class="col-md-3">
                                            <livewire:text-input name="BLDG_NAME_LOT_BLOCK"
                                                titleName="Bldg No./Name/Lot/Block" wire:model='BLDG_NAME_LOT_BLOCK'
                                                isDisabled="{{ false }}" maxlength='50' />
                                        </div>
                                        <div class="col-md-3">
                                            <livewire:text-input name="STREET_SUB_VALL"
                                                titleName="Street/Subdivision/Village" wire:model='STREET_SUB_VALL'
                                                isDisabled="{{ false }}" maxlength='50' />
                                        </div>
                                        <div class="col-md-3">
                                            <livewire:text-input name="BRGY_CITY_MUNI"
                                                titleName="Barangay/City/Municipality" wire:model='BRGY_CITY_MUNI'
                                                isDisabled="{{ false }}" maxlength='50' />
                                        </div>
                                        <div class="col-md-3">
                                            <livewire:text-input name="PROVINCE" titleName="Province"
                                                isDisabled="{{ false }}" wire:model='PROVINCE'
                                                maxlength='50' />
                                        </div>
                                        <div class="col-md-3">
                                            <livewire:text-input name="ZIP_CODE" titleName="Zip Code"
                                                isDisabled="{{ false }}" wire:model='ZIP_CODE'
                                                maxlength='10' />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group border-top border-secondary">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <livewire:select-option name="HCI_MANAGER_ID" :options="$managerList"
                                                isDisabled="{{ false }}" :zero="true"
                                                titleName="HCI Manager" wire:model.live='HCI_MANAGER_ID' />
                                        </div>
                                        {{-- HCI_MANAGER_TREATMENT_ID --}}
                                        <div class="col-md-2">
                                            <livewire:select-option name="HCI_MANAGER_TREATMENT_ID" :options="$managerList"
                                                isDisabled="{{ false }}" :zero="true"
                                                titleName="HCI (Treatment Sum)"
                                                wire:model.live='HCI_MANAGER_TREATMENT_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="PHIC_INCHARGE_ID" :options="$inchargeList"
                                                isDisabled="{{ false }}" :zero="true"
                                                titleName="Phic In-charge" wire:model.live='PHIC_INCHARGE_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="PHIC_INCHARGE2_ID" :options="$inchargeList"
                                                isDisabled="{{ false }}" :zero="true"
                                                titleName="Phic In-charge(other form)"
                                                wire:model.live='PHIC_INCHARGE2_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="PREPARED_BY_ID" :options="$preparedByList"
                                                isDisabled="{{ false }}" :zero="true"
                                                titleName="Prepared By (Treatment Summary)"
                                                wire:model.live='PREPARED_BY_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            {{-- HD_FACILITY_REP_ID --}}
                                            <livewire:select-option name="HD_FACILITY_REP_ID" :options="$hdFacilityRepList"
                                                isDisabled="{{ false }}" :zero="true"
                                                titleName="HD Facility Representative"
                                                wire:model.live='HD_FACILITY_REP_ID' />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group border-top border-secondary">
                                    <h6 class="text-primary">Report Headers</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <livewire:text-input name="REPORT_HEADER_1" titleName="Header 1"
                                                isDisabled="{{ false }}" wire:model='REPORT_HEADER_1'
                                                maxlength='60' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="REPORT_HEADER_2" titleName="Header 2"
                                                isDisabled="{{ false }}" wire:model='REPORT_HEADER_2'
                                                maxlength='60' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="REPORT_HEADER_3" titleName="Header 3"
                                                isDisabled="{{ false }}" wire:model='REPORT_HEADER_3'
                                                maxlength='60' />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group border-top border-secondary">
                                    <h6 class="text-primary">Format</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <livewire:text-input name="PHIC_SOA_FORMAT" titleName="SOA FORMAT"
                                                isDisabled="{{ false }}" wire:model='PHIC_SOA_FORMAT'
                                                maxlength='20' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:custom-check-box name="PHIC_FORM_MODIFY"
                                                isDisabled="{{ false }}" titleName="Phic Form is Modify"
                                                wire:model='PHIC_FORM_MODIFY' />

                                            <livewire:custom-check-box name="IS_DAILY"
                                                isDisabled="{{ false }}" titleName="Daily Transmittal"
                                                wire:model='IS_DAILY' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="LOGO_FILE" titleName="Logo File"
                                                isDisabled="{{ false }}" wire:model='LOGO_FILE'
                                                maxlength='50' />

                                            <livewire:custom-check-box name="USED_DRY_WEIGHT"
                                                isDisabled="{{ false }}" titleName="Use Dry Weight"
                                                wire:model='USED_DRY_WEIGHT' />

                                            <livewire:custom-check-box name="ITEMIZED_BASE"
                                                isDisabled="{{ false }}" titleName="Itemized Base"
                                                wire:model='ITEMIZED_BASE' />
                                        </div>
                                        <div class="col-md-6">
                                            <livewire:text-input name="DOCTOR_ORDER_DEFAULT"
                                                isDisabled="{{ false }}" titleName="DOCTOR ORDER DEFAULT :"
                                                wire:model='DOCTOR_ORDER_DEFAULT' maxlength='500' />
                                        </div>
                                        <div class="col-md-3">
                                            <livewire:custom-check-box name="LEAVE_BLANK_AG_ADMIN_OFFICE_FEE"
                                                isDisabled="{{ false }}"
                                                titleName="Leave Blank AG Administrative and Office Fee"
                                                wire:model='LEAVE_BLANK_AG_ADMIN_OFFICE_FEE' />
                                        </div>
                                        <div class="col-md-3">
                                            <livewire:custom-check-box name="OTHER_SIGN"
                                                isDisabled="{{ false }}"
                                                titleName="Watcher Sign on Treatment Sheet" wire:model='OTHER_SIGN' />
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
                                                href="{{ route('maintenancesettingslocation_create') }}"
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
