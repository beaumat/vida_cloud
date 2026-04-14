<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Philhealth Agreement Form</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-4">
                                    @livewire('ServiceCharge.AgreementFormDetails', ['HEMO_ID' => $HEMO_ID])
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-12">
                                            @livewire('ServiceCharge.AgreementFormItems', ['HEMO_ID' => $HEMO_ID])
                                        </div>
                                        <div class="col-12">
                                            @livewire('ServiceCharge.AgreementFormConforme', ['HEMO_ID' => $HEMO_ID])
                                        </div>
                                        @if ($SHOW_DIALYZER_COUNT == true)
                                            <div class="col-12">

                                                <div class="card card-sm card-outline card-info">
                                                    <div class="card-header">
                                                        <h6 class="card-title text-sm">Dialyzer Breakdown List
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @livewire('ServiceCharge.AgreementFormDialyzer', ['LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE, 'PATIENT_ID' => $PATIENT_ID])
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-12 text-right">
                                <a target="_BLANK" href="{{ route('patientsagreement_form', ['id' => $HEMO_ID]) }}"
                                    class="btn btn-sm btn-info">Preview</a>

                                <button type="button" class="btn btn-secondary btn-sm m-1"
                                    wire:click="closeModal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
