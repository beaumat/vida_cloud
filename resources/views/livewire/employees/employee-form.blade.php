<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactemployees') }}"> Employees </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <livewire:text-input name="NAME" titleName="Name" wire:model.live.lazy.150ms='NAME'
                                                maxlength='60' isDisabled="{{ false }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:text-input name="ACCOUNT_NO" titleName="Employee ID"
                                                wire:model='ACCOUNT_NO' isDisabled="{{ false }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12"><br /></div>
                                                <div class="col-md-12 text-right">
                                                    <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                                        wire:model='INACTIVE' isDisabled="{{ false }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="text-sm">Branch/Location</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <select wire:model='LOCATION_ID'
                                                        class="form-control form-control-sm">
                                                        <option value='0'>&nbsp;</option>
                                                        @foreach ($locationList as $list)
                                                            <option value='{{ $list->ID }}'>{{ $list->NAME }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card  card-tabs mt-1">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs text-sm p-1" id="custom-content-below-tab"
                                            role="tablist">
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('gen')"
                                                    class="nav-link @if ($selectTab == 'gen') active @endif"
                                                    id="custom-content-below-general-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-general-info" role="tab"
                                                    aria-controls="custom-content-below-general-info"
                                                    aria-selected="true">General Info</a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('tax')"
                                                    class="nav-link @if ($selectTab == 'tax') active @endif"
                                                    id="custom-content-below-tax-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-tax-info" role="tab"
                                                    aria-controls="custom-content-below-tax-info"
                                                    aria-selected="false">Tax
                                                    Info</a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('add')"
                                                    class="nav-link @if ($selectTab == 'add') active @endif"
                                                    id="custom-content-below-add-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-add-info" role="tab"
                                                    aria-controls="custom-content-below-add-info"
                                                    aria-selected="false">Employment
                                                    Info</a>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="tab-content text-sm" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade @if ($selectTab == 'gen') show active @endif"
                                                id="custom-content-below-general-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-general-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-2">

                                                            <div class="mt-1">
                                                                <label for="title" class="text-sm">Title</label>
                                                                <select wire:model='SALUTATION'
                                                                    class="form-control form-control-sm"
                                                                    name="SALUTATION">
                                                                    <option value=""></option>
                                                                    <option value="Dr">Dr</option>
                                                                    <option value="Miss">Miss</option>
                                                                    <option value="Mr.">Mr.</option>
                                                                    <option value="Mr.">Ms.</option>
                                                                    <option value="Mr.">Prof</option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="FIRST_NAME"
                                                                titleName="First Name" wire:model='FIRST_NAME'
                                                                maxlength='60' isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <livewire:text-input name="MIDDLE_NAME" titleName="M.I"
                                                                wire:model='MIDDLE_NAME' maxlength='60'
                                                                isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="LAST_NAME"
                                                                titleName="Last Name" wire:model='LAST_NAME'
                                                                maxlength='60' isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <livewire:text-input name="PRINT_NAME_AS"
                                                                titleName="Print As" wire:model='PRINT_NAME_AS'
                                                                maxlength='60' isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="mt-1">
                                                                                <label for="postal-address"
                                                                                    class="text-sm">Postal
                                                                                    Address</label>
                                                                                <textarea type="text" autocomplete="off" wire:model='POSTAL_ADDRESS' class="text-sm form-control form-control-sm"
                                                                                    id="pos_tal_address" rows="3"></textarea>
                                                                            </div>

                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="EMAIL"
                                                                                titleName="Email" wire:model='EMAIL'
                                                                                maxlength='60'
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="SSS"
                                                                                titleName="SSS No."
                                                                                wire:model='SSS_NO'
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="mt-1">
                                                                                <label for="dob"
                                                                                    class="text-sm">Date Of
                                                                                    Birth
                                                                                </label>
                                                                                <input type="date"
                                                                                    class="form-control form-control-sm"
                                                                                    wire:model='DATE_OF_BIRTH' />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="TELEPHONE_NO"
                                                                                titleName="Telephone Number"
                                                                                wire:model='TELEPHONE_NO'
                                                                                maxlength='60'
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="FAX_NO"
                                                                                titleName="Fax Number"
                                                                                wire:model='FAX_NO' maxlength='60'
                                                                                isDisabled="{{ false }}" />

                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="MOBILE_NO"
                                                                                titleName="Mobile Number"
                                                                                wire:model='MOBILE_NO' maxlength='60'
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mt-1">
                                                                                <label for="gender"
                                                                                    class="text-sm">Gender</label>
                                                                                <select wire:model='GENDER'
                                                                                    class="form-control form-control-sm"
                                                                                    name="GENDER">
                                                                                    <option value="-1"></option>
                                                                                    @foreach ($genders as $list)
                                                                                        <option
                                                                                            value="{{ $list->ID }}">
                                                                                            {{ $list->DESCRIPTION }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="Nickname"
                                                                                titleName="Position"
                                                                                wire:model='NICKNAME' maxlength='60'
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'tax') show active @endif"
                                                id="custom-content-below-tax-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-tax-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <livewire:text-input name="TAXPAYER_ID"
                                                                titleName="Reference ID No." wire:model='TAXPAYER_ID'
                                                                maxlength='60' isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'add') show active @endif"
                                                id="custom-content-below-add-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-add-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="mt-1">
                                                                <label for="dob" class="text-sm">Hire Date
                                                                </label>
                                                                <input type="date" name="hireDate"
                                                                    class="form-control form-control-sm"
                                                                    wire:model='HIRE_DATE' />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="mt-1">
                                                                <label for="dob" class="text-sm">Pin Code
                                                                </label>
                                                                <input type="text" name="PIN"
                                                                    class="form-control form-control-sm"
                                                                    wire:model='PIN'
                                                                    isDisabled="{{ false }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                    href="{{ route('maintenancecontactemployees_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                            @endif
                                        </div>
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
