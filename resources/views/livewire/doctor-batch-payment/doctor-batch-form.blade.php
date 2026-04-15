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
                                    <a class="text-white" href="{{ route('patientsdoctor_batch') }}"> Doctor Batch
                                        Payment
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if ($ID == 0)
                                        <i> New</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if ($Modify)
                                                @if ($doctorRefresh)
                                                    <livewire:select-option-type name="CONTACT_ID1" titleName="Doctor"
                                                        :options="$contactList" :zero="true" :isDisabled=false
                                                        wire:model='DOCTOR_ID' />
                                                @else
                                                    <livewire:select-option-type name="CONTACT_ID0" titleName="Doctor"
                                                        :options="$contactList" :zero="true" :isDisabled=false
                                                        wire:model='DOCTOR_ID' />
                                                @endif
                                            @else
                                                <livewire:select-option-type name="CONTACT_ID2" titleName="Doctor"
                                                    :options="$contactList" :zero="true" :isDisabled=true
                                                    wire:model='DOCTOR_ID' />
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">

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
                                                    @if ($Modify)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model.live='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model.live='LOCATION_ID' />
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">

                                        @if ($Modify)
                                            <button type="submit" class="btn btn-sm btn-primary"> <i
                                                    class="fa fa-floppy-o" aria-hidden="true"></i>
                                                {{ $ID == 0 ? 'Save' : 'Update' }}</button>

                                            @if ($ID > 0)
                                                <button type="button" wire:click='updateCancel'
                                                    class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                        aria-hidden="true"></i> Cancel</button>
                                            @endif
                                        @else
                                            @can('patient.doctor.batch.edit')
                                                <button type="button" wire:click='getModify()' class="btn btn-sm btn-info">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                            @endcan
                                        @endif



                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0)
                                            @can('patient.doctor.batch.print')
                                                <a type="button" target="_BLANK"
                                                    href="{{ route('patientsdoctor_batch_sum_print', ['id' => $ID]) }}"
                                                    class="btn btn-sm btn-dark">
                                                    <i class="fa fa-print" aria-hidden="true"></i> Print Summary
                                                </a>
                                                <a type="button" target="_BLANK"
                                                    href="{{ route('patientsdoctor_batch_print', ['id' => $ID]) }}"
                                                    class="btn btn-sm btn-dark">
                                                    <i class="fa fa-print" aria-hidden="true"></i> Print Details
                                                </a>
                                            @endcan
                                            @can('patient.doctor.batch.create')
                                                <a id="new" title="Create"
                                                    href="{{ route('patientsdoctor_batch_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endcan
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
        <section class="content" @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
            <div class="container-fluid bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-four-item-tab" data-toggle="pill"
                                            href="#custom-tabs-four-item" role="tab"
                                            aria-controls="custom-tabs-four-item" aria-selected="true">
                                            Pay Bills
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade show active " id="custom-tabs-four-item"
                                        role="tabpanel" aria-labelledby="custom-tabs-four-item-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @livewire('DoctorBatchPayment.DoctorBatchPaidList', ['DOCTOR_BATCH_ID' => $ID])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-6">
                                        @can('patient.doctor.batch.create')
                                            @livewire('DoctorBatchPayment.BillPaymentListModal', ['DOCTOR_BATCH_ID' => $ID])
                                        @endcan

                                    </div>
                                    <div class="col-6 text-right">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
