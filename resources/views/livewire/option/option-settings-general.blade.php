<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-8 mt-2">
        <div class="form-group row">
            <label class="col-md-4  col-form-label-sm" for="location_default">
                Default Location :
            </label>
            <div class="col-md-8 input-group input-group-sm">
                <select wire:model.live.debounce='DefaultLocationId' name="DefaultLocationId" id="DefaultLocationId"
                    class="form-control form-control-sm">
                    <option value="0"></option>
                    @foreach ($locationList as $list)
                        <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                    @endforeach
                </select>
               
            </div>
        </div>
    </div>
    <div class="col-md-8 mt-2">
        <div class="form-group row">
            <label class="col-md-4  col-form-label col-from-label-sm" for="NewTransactionsDefaultDate">
                New Transaction Default Date :
            </label>
            <div class="col-md-8 input-group input-group-sm">
                <select wire:model.live.debounce='NewTransactionsDefaultDate' name="NewTransactionsDefaultDate"
                    id="NewTransactionsDefaultDate" class="form-control form-control-sm">
                    @foreach ($defaultDate as $list)
                        <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                    @endforeach
                </select>
              
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <!-- checkbox -->
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input wire:model='LockDefaultLocation' type="checkbox" id="LockDefaultLocation1">
                <label>
                    Limit transaction to default location
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input wire:model='IncRefNoByLocation' type="checkbox" id="IncRefNoByLocation1">
                <label>
                    Increment reference number by location
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2 text-right">
        <button wire:click='save' class="btn btn-sm btn-success">Save</button>
    </div>
</div>
