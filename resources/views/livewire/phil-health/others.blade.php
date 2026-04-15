<div>
    <form wire:submit.prevent='saveData'>
        <div class="form-group">
            <label class="text-xs">RR :</label>
            <input type="text" class=" text-xs w-25" wire:model='RR_NO' title="RR :" placeholder="Please enter RR"
                maxlength="3" />
        </div>
        <div class="form-group">
            <label class="text-xs">Chief Complaint :</label>
            <input type="text" class="text-xs w-100" wire:model='CF4_COMPLAINT' title="Chief Complaint"
                placeholder="Please enter Chief Complaint" maxlength="100" />
            <i class='text-xs'> if empty by default:
                <b class="text-primary">({{ $CHIEF_OF_COMPLAINT_DEFAULT }})</b></i>
        </div>
        <div class="form-group">
            <label class="text-xs">Admiited Diagnosis :</label>
            <div class="p-0">
                <textarea class="text-xs w-100" wire:model='CF4_AD_NOTES' title="Admitted Diagnosis"
                    placeholder="Please enter Admitted Diagnosis" rows='4'></textarea>
            </div>
            <i class='text-xs'>
                if empty by default:
                <b class="text-primary">({{ $ADMITTING_DIAGNOSIS_DEFAULT }})</b></i>
        </div>
        <div class="form-group">
            <label class="text-xs">Discharge Diagnosis :</label>
            <div class="p-0">
                <textarea class="text-xs w-100" wire:model='CF4_DD_NOTES' title="Discharge Diagnosis"
                    placeholder="Please enter Dischare Diagnosis" rows='4'></textarea>
            </div>
            <i class='text-xs'>
                if empty by default:
                <b class="text-primary">({{ $FINAL_DIAGNOSIS }})</b></i>
        </div>
        <div class="form-group">
            <label class="text-xs">History of Present Illness :</label>
            <input type="text" class="text-xs w-100" wire:model='CF4_HPI' title="History of Present Illness :"
                placeholder="Please enter History of Present Illness" maxlength="50" />
            <i class='text-xs'> if empty by default:
                <b class="text-primary">({{ $HISTORY_OF_PRESENT_ILLNESS_DEFAULT }})</b></i>
        </div>

        <div class="form-group">
            <label class="text-xs w-100">Patient Past Medical History :</label>
            <input type="text" class="text-xs w-100" wire:model='CF4_PPMH' title="Patient Past Medical History :"
                placeholder="Please enter Patient Past Medical History" maxlength="50" />
            <i class='text-xs'> if empty by default:
                <b class="text-primary">({{ $FINAL_DIAGNOSIS }})</b></i>
        </div>
        <div class="form-group">
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
            <button type="submit" class="btn btn-info btn-sm  w-100"> <i class="fa fa-floppy-o" aria-hidden="true"></i>
                Update
            </button>
        </div>
    </form>


</div>
