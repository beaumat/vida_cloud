<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-6 mt-2">
        <label for="company_nane">Company Name</label>
        <div class="input-group input-group-sm">
            <input wire:model.live.debounce='CompanyName' type="text" name="comany_name" id="company_name"
                class="form-control form-control-sm">
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <label for="company_nane">Business Type</label>
        <div class="input-group input-group-sm">
            <input type="text" name="business_type" id="busness_type" class="form-control form-control-sm">
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <label for="company_nane">Postal Address</label>
        <div class="input-group input-group-sm">
            <textarea wire:model.live.debounce='CompanyAddress' class="form-control form-control-sm" name="postal_address"
                id="postal-address" rows="4">
            </textarea>
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <label for="legal_name">Legal Name</label>
        <div class="input-group input-group-sm">
            <input type="text" name="legal_name" id="legal_name" class="form-control form-control-sm">
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <label for="legal_name">Email</label>
        <div class="input-group input-group-sm">
            <input wire:model.live.debounce='CompanyEmailAddress' type="email" name="email" id="email"
                class="form-control form-control-sm">
        </div>

        <div class="row">

            <div class="col-md-6 mt-2">
                <label for="phone">Phone</label>
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='CompanyPhoneNo' type="tel" name="phone" id="phone"
                        class="form-control form-control-sm">

                </div>

            </div>
            <div class="col-md-6 mt-2">
                <label for="fax">Fax</label>
                <div class="input-group input-group-sm">

                    <input wire:model.live.debounce='CompanyFaxNo' type="tel" name="fax_number" id="fax_number"
                        class="form-control form-control-sm">

                </div>

            </div>
            <div class="col-md-6 mt-2">
                <label for="fax">Mobile Number</label>
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='CompanyMobileNo' type="tel" name="mobile_number"
                        id="mobile_number" class="form-control form-control-sm">
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2 text-right">
        <button wire:click='save' class="btn btn-sm btn-success">Save</button>
    </div>
</div>
