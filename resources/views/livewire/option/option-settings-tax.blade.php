<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-6 col-form-label-sm">
        <div class="form-group row">
            <label for="company_nane" class="col-md-4  col-form-label-sm text-right">Taxplayer ID No.</label>
            <div class="col-md-8 input-group input-group-sm">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='CompanyTin' type="text" name="CompanyTin" id="CompanyTin"
                        class="form-control form-control-sm">
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-md-4  col-form-label-sm text-right">
                Output Tax :
            </label>
            <div class="col-md-8 input-group input-group-sm">
                <select wire:model.live.debounce='OutputTaxId' name="OutputTaxId" id="OutputTaxId"
                    class="form-control form-control-sm">
                    @foreach ($taxList as $list)
                        <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4  col-form-label-sm text-right">
                Input Tax :
            </label>
            <div class="col-md-8 input-group input-group-sm">
                <select wire:model.live.debounce='InputTaxId' name="InputTaxId" id="InputTaxId"
                    class="form-control form-control-sm">
                    @foreach ($taxList as $list)
                        <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2 text-right">
        <button wire:click='save' class="btn btn-sm btn-success">Save</button>
    </div>
</div>
