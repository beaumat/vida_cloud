<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document"
                style="margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary">
                            Medical Certificate
                        </h6>
                        <button type="button" class="close" wire:click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        </div>
                        <div class="form-group">
                            {{-- <livewire:select-option name="MED_CERT_SCHED_ID" titleName="Schedule Description"
                                :options="$medCertScheduleList" :zero="true" wire:model.live='MED_CERT_SCHED_ID'
                                :vertical="false" :withLabel="true" isDisabled="{{ false }}" /> --}}
                            <livewire:custom-check-box name="FIX_MON" titleName="Monday" :isDisabled="false"
                                wire:model.live='FIX_MON' />
                            <livewire:custom-check-box name="FIX_TUE" titleName="Tuesday" :isDisabled="false"
                                wire:model.live='FIX_TUE' />
                            <livewire:custom-check-box name="FIX_WEN" titleName="Wednesday" :isDisabled="false"
                                wire:model.live='FIX_WEN' />
                            <livewire:custom-check-box name="FIX_THU" titleName="Thursday" :isDisabled="false"
                                wire:model.live='FIX_THU' />
                            <livewire:custom-check-box name="FIX_FRI" titleName="Friday" :isDisabled="false"
                                wire:model.live='FIX_FRI' />
                            <livewire:custom-check-box name="FIX_SAT" titleName="Saturday" :isDisabled="false"
                                wire:model.live='FIX_SAT' />
                            <livewire:custom-check-box name="FIX_SUN" titleName="Sunday" :isDisabled="false"
                                wire:model.live='FIX_SUN' />

                        </div>

                        <div class="form-group">
                            <livewire:select-option name="MED_CERT_NURSE_ID" titleName="Duty Physician"
                                :options="$contactList" :zero="true" wire:model.live='MED_CERT_NURSE_ID'
                                :vertical="false" :withLabel="true" isDisabled="{{ false }}" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    @if ($MED_CERT_NURSE_ID > 0)
                                        <a type="button"
                                            href="{{ route('maintenancecontactprint_medical_cert', ['id' => $PATIENT_ID]) }}"
                                            class="btn btn-sm btn-warning" target="_blank"> Print</a>
                                    @endif

                                </div>
                                <div class="col-6 text-right">
                                    {{-- <button type="button" class="btn btn-success btn-sm" wire:click="SaveChange()">Save
                                    </button> --}}
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        wire:click="closeModal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
