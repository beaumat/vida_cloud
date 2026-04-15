<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-7 mt-2">
        <div class="form-group row">
            <label class=" col-md-4 col-label-form text-right text-danger" for="company_nane">
                <i>Warning if Transaction are :</i>
            </label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='DateWarningDaysPast' type="number" name="before_past_day"
                        id="before_past_day" class="form-control form-control-sm">
                
                </div>
            </div>
            <label class="col-md-4 col-label-form text-info" for="company_nane">Days in Past</label>
        </div>
    </div>
    <div class="col-md-7 mt-2">

        <div class="form-group row">
            <label class=" col-md-4 col-label-form text-right text-danger" for="company_nane">
                <i>Warning if Transaction are :</i>
            </label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='DateWarningDaysFuture' type="number" name="DateWarningDaysFuture"
                        id="DateWarningDaysFuture" class="form-control form-control-sm">
                 
                </div>
            </div>
            <label class=" col-md-4 col-label-form text-info" for="company_nane">Days in future</label>
        </div>
    </div>
    <div class="col-md-7 mt-2">
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-right" for="company_nane">Closing Date :</label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='ClosingDate' type="date" name="ClosingDate" id="ClosingDate"
                        class="form-control form-control-sm">
                 
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7 mt-2">
        <div class="form-group row">
            <label class=" col-md-4 col-label-form text-right " for="SmallestCurrencyValue">
                Round amount to nearest :
            </label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='SmallestCurrencyValue' type="number" name="SmallestCurrencyValue"
                        id="SmallestCurrencyValue" class="form-control form-control-sm text-right">    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2">
        <!-- checkbox -->
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" wire:model.live='SkipJournalEntry'>
                <label>Skip Journal Entry</label>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2 text-right">
        <button wire:click='save' class="btn btn-sm btn-success">Save</button>
    </div>

</div>
