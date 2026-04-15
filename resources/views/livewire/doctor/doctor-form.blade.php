<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactdoctors') }}"> Doctor </a></h5>
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
                                            <livewire:text-input name="NAME" titleName="Name" wire:model='NAME'
                                                maxlength='50' isDisabled="{{ false }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:text-input name="ACCOUNT_NO" titleName="Doctor ID"
                                                wire:model='ACCOUNT_NO' isDisabled="{{ false }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:text-input name="PIN" titleName="Accreditation No."
                                                wire:model='PIN' isDisabled="{{ false }}" />
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

                                    </div>
                                </div>
                                <div class="card  card-tabs mt-2">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs text-xs p-1" id="custom-content-below-tab"
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
                                                <a wire:click="SelectTab('patient')"
                                                    class="nav-link @if ($selectTab == 'patient') active @endif"
                                                    id="custom-content-below-patient-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-patient-info" role="tab"
                                                    aria-controls="custom-content-below-patient-info"
                                                    aria-selected="false">Patients
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('bill')"
                                                    class="nav-link @if ($selectTab == 'bill') active @endif"
                                                    id="custom-content-below-bill-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-bill-info" role="tab"
                                                    aria-controls="custom-content-below-bill-info"
                                                    aria-selected="false">Bills
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('payment')"
                                                    class="nav-link @if ($selectTab == 'payment') active @endif"
                                                    id="custom-content-below-payment-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-payment-info" role="tab"
                                                    aria-controls="custom-content-below-payment-info"
                                                    aria-selected="false">Bill Payments
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('wtax')"
                                                    class="nav-link @if ($selectTab == 'wtax') active @endif"
                                                    id="custom-content-below-wtax-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-wtax-info" role="tab"
                                                    aria-controls="custom-content-below-wtax-info"
                                                    aria-selected="false">Withholding Tax
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('general-journal')"
                                                    class="nav-link @if ($selectTab == 'general-journal') active @endif"
                                                    id="custom-content-below-general-journal-info-tab"
                                                    data-toggle="pill"
                                                    href="#custom-content-below-general-journal-info" role="tab"
                                                    aria-controls="custom-content-below-general-journal-info"
                                                    aria-selected="false">General Journal
                                                </a>
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
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="FIRST_NAME"
                                                                titleName="First Name" wire:model='FIRST_NAME'
                                                                isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <livewire:text-input name="MIDDLE_NAME" titleName="M.I"
                                                                wire:model='MIDDLE_NAME'
                                                                isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="LAST_NAME"
                                                                titleName="Last Name" wire:model='LAST_NAME'
                                                                isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <livewire:text-input name="PRINT_NAME_AS"
                                                                titleName="Print As" wire:model='PRINT_NAME_AS'
                                                                maxlength='50' isDisabled="{{ false }}" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="mt-2">
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
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="SSS"
                                                                                titleName="SSS No."
                                                                                wire:model='SSS_NO'
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="mt-2">
                                                                                <label for="dob"
                                                                                    class="text-sm">Date Of Birth
                                                                                </label>
                                                                                <input type="date"
                                                                                    class="form-control form-control-sm"
                                                                                    wire:model='DATE_OF_BIRTH'
                                                                                    isDisabled="{{ false }}" />
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
                                                                                isDisabled="{{ false }}" />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="TAXPAYER_ID"
                                                                                titleName="License No."
                                                                                isDisabled="{{ false }}"
                                                                                wire:model='TAXPAYER_ID' />

                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="MOBILE_NO"
                                                                                titleName="Mobile Number"
                                                                                isDisabled="{{ false }}"
                                                                                wire:model='MOBILE_NO' />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mt-2">
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
                                                                                titleName="Nickname"
                                                                                isDisabled="{{ false }}"
                                                                                wire:model='NICKNAME' />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'patient') show active @endif"
                                                id="custom-content-below-patient-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-patient-info-tab">
                                                @livewire('Doctor.DoctorPatients', ['id' => $ID])
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'bill') show active @endif"
                                                id="custom-content-below-bill-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-bill-info-tab">
                                                @livewire('Vendor.VendorBill', ['id' => $ID])
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'payment') show active @endif"
                                                id="custom-content-below-payment-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-payment-info-tab">
                                                @livewire('Vendor.VendorBillPayment', ['id' => $ID])
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'wtax') show active @endif"
                                                id="custom-content-below-wtax-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-wtax-info-tab">
                                                @livewire('Vendor.VendorWtax', ['id' => $ID])
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'general-journal') show active @endif"
                                                id="custom-content-below-general-journal-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-general-journal-info-tab">
                                                <div class="container-fluid">
                                                    @livewire('GeneralJournal.ListEntry', ['id' => $ID])
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
                                                    href="{{ route('maintenancecontactdoctors_create') }}"
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
