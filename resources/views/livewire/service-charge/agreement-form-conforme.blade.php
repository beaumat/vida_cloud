<div>
    <div class="form-group">
      



        <livewire:select-option name="PHIC_INCHARGE_ID" titleName="Philhealth In-charge" :options="$phicList"
            :zero="true" wire:model.live='PHIC_INCHARGE_ID' :vertical="false" :withLabel="true"
            isDisabled="{{ false }}" />
    </div>
    <div class="form-group">
        <livewire:select-option name="HD_FACILITY_REP_ID" titleName="HD Facility Representative" :options="$empList"
            :zero="true" wire:model.live='HD_FACILITY_REP_ID' :vertical="false" :withLabel="true"
            isDisabled="{{ false }}" />
    </div>

    <div class="form-group">
        <livewire:select-option name="WITNESS_ID" titleName="Witness:" :options="$empList" :zero="true"
            wire:model.live='WITNESS_ID' :vertical="false" :withLabel="true" isDisabled="{{ false }}" />
        <label class="text-danger text-xs">
            @if ($PATIENT_REP_NAME != '')
                Note: If left empty, the witness will be assigned to the patient representative by
                {{ $PATIENT_REP_NAME }}
            @else
                Note : if you leave empty the witness will be blank.
            @endif
        </label>
    </div>

    <div class="form-group">


    </div>
</div>
