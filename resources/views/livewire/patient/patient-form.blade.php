<?php
use App\Services\UserServices;
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactpatients') }}"> Patients</a></h5>
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
                                {{-- Top Details --}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <livewire:text-input name="NAME" titleName="Patients Name"
                                                isDisabled="{{ $modify }}" wire:model='NAME' :isDisabled='true'
                                                maxlength='100' />
                                        </div>
                                        <div class="col-md-1">
                                            <livewire:text-input name="ACCOUNT_NO" titleName="Profile No."
                                                isDisabled="{{ $modify }}" wire:model='ACCOUNT_NO' isDisabled=true
                                                maxlength='20' />
                                        </div>
                                        <div class="col-md-1">
                                            <livewire:date-input name="DATE_ADMISSION" titleName="Date Diagnosis"
                                                isDisabled="{{ $modify }}" wire:model='DATE_ADMISSION' />
                                        </div>

                                        <div class="col-md-2"
                                            @if (Auth::user()->locked_location) style="opacity:
                                            0.5;pointer-events: none;" @endif>
                                            <livewire:select-option name="LOCATION_ID" :options="$locationList"
                                                isDisabled="{{ $modify }}" :zero="false" titleName="Branch"
                                                wire:model='LOCATION_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="CLASS_ID" :options="$patientClassList"
                                                isDisabled="{{ $modify }}" :zero="true"
                                                titleName="Classification" wire:model='CLASS_ID' />
                                        </div>
                                        <div class="col-md-1">
                                            <livewire:text-input name="NICKNAME" titleName="Account No."
                                                isDisabled="{{ $modify }}" wire:model='NICKNAME' maxlength='20' />
                                        </div>
                                        <div class="col-md-1 ">

                                            <livewire:date-input name="DATE_EXPIRED" titleName="Date Expired"
                                                isDisabled="{{ $modify }}" wire:model='DATE_EXPIRED' />
                                        </div>
                                        <div class="col-md-1">
                                            <div class="row">
                                                <div class="col-md-12"><br /></div>
                                                <div class="col-md-12 text-left">
                                                    <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                                        isDisabled="{{ $modify }}" wire:model='INACTIVE' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- TAB --}}
                                <div class="card card-primary card-outline card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0" wire:loading.class='loading-form'>
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
                                                <a wire:click="SelectTab('phic')"
                                                    class="nav-link @if ($selectTab == 'phic') active @endif"
                                                    id="custom-content-below-phic-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-phic-info" role="tab"
                                                    aria-controls="custom-content-below-phic-info"
                                                    aria-selected="false">
                                                    Philhealth Info
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('doctor')"
                                                    class="nav-link @if ($selectTab == 'doctor') active @endif"
                                                    id="custom-content-below-doctor-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-doctor-info" role="tab"
                                                    aria-controls="custom-content-below-doctor-info"
                                                    aria-selected="false">
                                                    Nephro & Diagnosis
                                                </a>
                                            </li>
                                            @can('contact.patient.update')
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('requirements')"
                                                        class="nav-link @if ($selectTab == 'requirements') active @endif"
                                                        id="custom-content-below-requirements-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-requirements-info" role="tab"
                                                        aria-controls="custom-content-below-requirements-info"
                                                        aria-selected="false">
                                                        Requirements
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('charges')"
                                                        class="nav-link @if ($selectTab == 'charges') active @endif"
                                                        id="custom-content-below-charges-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-charges-info" role="tab"
                                                        aria-controls="custom-content-below-charges-info"
                                                        aria-selected="false">
                                                        Service Charges Record
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('assistance')"
                                                        class="nav-link @if ($selectTab == 'assistance') active @endif"
                                                        id="custom-content-below-assistance-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-assistance-info" role="tab"
                                                        aria-controls="custom-content-below-assistance-info"
                                                        aria-selected="false">
                                                        Assistance Record
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('treatment')"
                                                        class="nav-link @if ($selectTab == 'treatment') active @endif"
                                                        id="custom-content-below-treatment-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-treatment-info" role="tab"
                                                        aria-controls="custom-content-below-treatment-info"
                                                        aria-selected="false">
                                                        Treatment Record
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('philhealth')"
                                                        class="nav-link @if ($selectTab == 'philhealth') active @endif"
                                                        id="custom-content-below-philhealth-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-philhealth-info" role="tab"
                                                        aria-controls="custom-content-below-philhealth-info"
                                                        aria-selected="false">
                                                        Philhealth Record
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('confinement')"
                                                        class="nav-link @if ($selectTab == 'confinement') active @endif"
                                                        id="custom-content-below-confinement-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-confinement-info" role="tab"
                                                        aria-controls="custom-content-below-confinement-info"
                                                        aria-selected="false">
                                                        Confinement
                                                    </a>
                                                </li>

                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('transfer')"
                                                        class="nav-link @if ($selectTab == 'transfer') active @endif"
                                                        id="custom-content-below-transfer-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-transfer-info" role="tab"
                                                        aria-controls="custom-content-below-transfer-info"
                                                        aria-selected="false">
                                                        Transfer
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('sales-receipt')"
                                                        class="nav-link @if ($selectTab == 'sales-receipt') active @endif"
                                                        id="custom-content-below-sales-receipt-info-tab"
                                                        data-toggle="pill" href="#custom-content-below-sales-receipt-info"
                                                        role="tab"
                                                        aria-controls="custom-content-below-sales-receipt-info"
                                                        aria-selected="false">
                                                        Sales receipts
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('invoice')"
                                                        class="nav-link @if ($selectTab == 'invoice') active @endif"
                                                        id="custom-content-below-invoice-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-invoice-info" role="tab"
                                                        aria-controls="custom-content-below-invoice-info"
                                                        aria-selected="false">
                                                        Invoices
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('payment')"
                                                        class="nav-link @if ($selectTab == 'payment') active @endif"
                                                        id="custom-content-below-payment-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-payment-info" role="tab"
                                                        aria-controls="custom-content-below-payment-info"
                                                        aria-selected="false">
                                                        Payments
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a wire:click="SelectTab('tax-credit')"
                                                        class="nav-link @if ($selectTab == 'tax-credit') active @endif"
                                                        id="custom-content-below-tax-credit-info-tab" data-toggle="pill"
                                                        href="#custom-content-below-tax-credit-info" role="tab"
                                                        aria-controls="custom-content-below-tax-credit-info"
                                                        aria-selected="false">
                                                        Tax Credits
                                                    </a>
                                                </li>
                                            @endcan
                                            <li wire:loading.delay>
                                                <span class='spinner'></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="tab-content text-sm" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade @if ($selectTab == 'gen') show active @endif"
                                                id="custom-content-below-general-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-general-info-tab">
                                                @if ($selectTab == 'gen' || $selectTab == 'phic' || $selectTab == 'doctor')
                                                    <div class="container-fluid text-xs">
                                                        <div class="row">
                                                            <div class="col-md-3 mb-2">
                                                                <livewire:text-input name="FIRST_NAME"
                                                                    titleName="First Name" wire:model='FIRST_NAME'
                                                                    maxlength='50'
                                                                    isDisabled="{{ $modify }}" />
                                                            </div>
                                                            <div class="col-md-3">
                                                                <livewire:text-input name="LAST_NAME"
                                                                    titleName="Last Name" wire:model='LAST_NAME'
                                                                    maxlength='50'
                                                                    isDisabled="{{ $modify }}" />
                                                            </div>
                                                            <div class="col-md-3">
                                                                <livewire:text-input name="MIDDLE_NAME"
                                                                    titleName="Middle Name" wire:model='MIDDLE_NAME'
                                                                    maxlength='50'
                                                                    isDisabled="{{ $modify }}" />
                                                            </div>

                                                            <div class="col-md-2">
                                                                <livewire:text-input name="SALUTATION"
                                                                    titleName="Extension (ex. JR,SR,III)"
                                                                    wire:model='SALUTATION' maxlength='10'
                                                                    isDisabled="{{ $modify }}" />
                                                            </div>
                                                            <div class="col-md-1">
                                                                <livewire:number-input name="HEIGHT"
                                                                    titleName="Height (cm)" wire:model='HEIGHT'
                                                                    isDisabled="{{ $modify }}" />
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group mt-2">
                                                                    <label>Gender</label>
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input"
                                                                                type="radio" wire:model="GENDER"
                                                                                value="0" />
                                                                            Male
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input"
                                                                                type="radio" wire:model="GENDER"
                                                                                value="1" />
                                                                            Female
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <livewire:date-input name="DATE_OF_BIRTH"
                                                                            titleName="Date of Birth"
                                                                            wire:model.live='DATE_OF_BIRTH'
                                                                            isDisabled="{{ $modify }}" />
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <div class="form-group mt-2">
                                                                            <label class=" col-form-label">Age</label>
                                                                            <div class="text-center">
                                                                                <i> {{ $age }}</i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <livewire:text-input name="CONTACT_PERSON"
                                                                    isDisabled="{{ $modify }}"
                                                                    titleName="Patients Representative Name"
                                                                    wire:model='CONTACT_PERSON' maxlength='50' />
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:text-input name="EMAIL" titleName="Email"
                                                                    wire:model='EMAIL' maxlength='100'
                                                                    isDisabled="{{ $modify }}" />
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:text-input name="TELEPHONE_NO"
                                                                    titleName="Telephone No."
                                                                    wire:model='TELEPHONE_NO'
                                                                    isDisabled="{{ $modify }}"
                                                                    maxlength='150' />
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:text-input name="MOBILE_NO"
                                                                    titleName="Mobile No." wire:model='MOBILE_NO'
                                                                    isDisabled="{{ $modify }}"
                                                                    maxlength='20' />
                                                            </div>


                                                            <div class="col-md-12 border pt-2 pb-2">
                                                                <div class="row">
                                                                    <div class="col-md-12 ">
                                                                        <label class="text-info text-xs">Patient
                                                                            Address:</label>
                                                                    </div>
                                                                    <div class="col-md-2 pb-2">
                                                                        <livewire:text-input
                                                                            name="ADDRESS_UNIT_ROOM_FLOOR"
                                                                            titleName="Unit/Room#/Floor"
                                                                            wire:model='ADDRESS_UNIT_ROOM_FLOOR'
                                                                            isDisabled="{{ $modify }}"
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input
                                                                            name="ADDRESS_BUILDING_NAME"
                                                                            titleName="Building Name"
                                                                            wire:model='ADDRESS_BUILDING_NAME'
                                                                            isDisabled="{{ $modify }}"
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <livewire:text-input
                                                                            name="ADDRESS_LOT_BLK_HOUSE_BLDG"
                                                                            titleName="Lot/Blk/House/Bldg No."
                                                                            wire:model='ADDRESS_LOT_BLK_HOUSE_BLDG'
                                                                            isDisabled="{{ $modify }}"
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <livewire:text-input name="ADDRESS_STREET"
                                                                            titleName="Street"
                                                                            wire:model='ADDRESS_STREET'
                                                                            isDisabled="{{ $modify }}"
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input name="ADDRESS_SUB_VALL"
                                                                            isDisabled="{{ $modify }}"
                                                                            titleName="Subdivision/Village"
                                                                            wire:model='ADDRESS_SUB_VALL'
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-3 ">
                                                                        <livewire:text-input name="ADDRESS_BRGY"
                                                                            titleName="Barangay"
                                                                            wire:model='ADDRESS_BRGY'
                                                                            isDisabled="{{ $modify }}"
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input name="ADDRESS_CITY_MUNI"
                                                                            titleName="City/Municipality"
                                                                            isDisabled="{{ $modify }}"
                                                                            wire:model='ADDRESS_CITY_MUNI'
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <livewire:text-input name="ADDRESS_PROVINCE"
                                                                            titleName="Province"
                                                                            wire:model='ADDRESS_PROVINCE'
                                                                            isDisabled="{{ $modify }}"
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input name="ADDRESS_COUNTRY"
                                                                            titleName="Country"
                                                                            isDisabled="{{ $modify }}"
                                                                            wire:model='ADDRESS_COUNTRY'
                                                                            maxlength='60' />
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input name="ADDRESS_ZIP_CODE"
                                                                            titleName="Zip Code"
                                                                            isDisabled="{{ $modify }}"
                                                                            wire:model='ADDRESS_ZIP_CODE'
                                                                            maxlength='10' />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'phic') show active @endif"
                                                id="custom-content-below-phic-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-phic-info-tab">
                                                @if ($selectTab == 'gen' || $selectTab == 'phic' || $selectTab == 'doctor')
                                                    <div class="container-fluid text-xs">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <livewire:text-input name="PIN"
                                                                    titleName="PhilHealth Number of Member"
                                                                    isDisabled="{{ $modify }}" wire:model='PIN'
                                                                    :isDisabled='false' maxlength='12' />
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:text-input name="FIRST_CASE_RATE"
                                                                    titleName="First Case Rate"
                                                                    isDisabled="{{ $modify }}"
                                                                    wire:model='FIRST_CASE_RATE' maxlength='20' />
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:text-input name="SECOND_CASE_RATE"
                                                                    titleName="PDD Registration No."
                                                                    isDisabled="{{ $modify }}"
                                                                    wire:model='SECOND_CASE_RATE' maxlength='50' />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <livewire:text-input name="CUSTOM_FIELD5"
                                                                    titleName="Authorized Representative"
                                                                    isDisabled="{{ $modify }}"
                                                                    wire:model='CUSTOM_FIELD5' maxlength='100' />
                                                            </div>
                                                            <div class="col-md-3">
                                                                <livewire:custom-check-box name="IS_PATIENT"
                                                                    titleName="Patient is the member?"
                                                                    isDisabled="{{ $modify }}"
                                                                    wire:model.live='IS_PATIENT' />
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-2">
                                                                @if ($IS_PATIENT == false)
                                                                    <livewire:custom-check-box name="IS_DEPENDENT"
                                                                        titleName="Patient is dependent?"
                                                                        isDisabled="{{ $modify }}"
                                                                        wire:model.live='IS_DEPENDENT' />
                                                                @endif
                                                            </div>

                                                            <div class="col-md-2">
                                                                @if ($IS_PATIENT == false && $IS_DEPENDENT == true)
                                                                    <livewire:text-input name="PIN_DEPENDENT"
                                                                        isDisabled="{{ $modify }}"
                                                                        titleName="PhilHealth Identification Number (PIN) of Dependent"
                                                                        wire:model='PIN_DEPENDENT'
                                                                        isDisabled="{{ $modify }}"
                                                                        maxlength='12' />
                                                                @endif
                                                            </div>
                                                            @if (!$IS_PATIENT)
                                                                <div class="col-md-12 p-1 mt-2 border-top ">
                                                                    <label class="text-info text-xs">
                                                                        Member Information:</label>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <livewire:text-input
                                                                                name="MEMBER_FIRST_NAME"
                                                                                titleName="First Name"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_FIRST_NAME'
                                                                                maxlength='50' />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <livewire:text-input
                                                                                name="MEMBER_LAST_NAME"
                                                                                titleName="Last Name"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_LAST_NAME'
                                                                                maxlength='50' />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <livewire:text-input
                                                                                name="MEMBER_MIDDLE_NAME"
                                                                                titleName="Middle Name"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_MIDDLE_NAME'
                                                                                maxlength='50' />
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <livewire:text-input
                                                                                name="MEMBER_EXTENSION"
                                                                                titleName="Extension (ex. JR,SR,III)"
                                                                                wire:model='MEMBER_EXTENSION'
                                                                                isDisabled="{{ $modify }}"
                                                                                maxlength='10' />
                                                                        </div>

                                                                        <div class="col-md-2">
                                                                            <div class="form-group mt-2">
                                                                                <label>Gender</label>
                                                                                <div class="form-check">
                                                                                    <label class="form-check-label">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            isDisabled="{{ $modify }}"
                                                                                            wire:model="MEMBER_GENDER"
                                                                                            value="0" />
                                                                                        Male
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <label class="form-check-label">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            isDisabled="{{ $modify }}"
                                                                                            wire:model="MEMBER_GENDER"
                                                                                            value="1" />
                                                                                        Female
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <livewire:date-input
                                                                                        name="MEMBER_BIRTH_DATE"
                                                                                        titleName="Date of Birth"
                                                                                        isDisabled="{{ $modify }}"
                                                                                        wire:model.live='MEMBER_BIRTH_DATE' />
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group mt-2">
                                                                                        <label
                                                                                            class=" col-form-label ">Age</label>
                                                                                        <div class="row">
                                                                                            <label
                                                                                                class="col-12 form-check-label">{{ $memberage }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="row">
                                                                                <div class="col-md-12 text-left">
                                                                                    <label
                                                                                        class="text-info text-xs mt-3">
                                                                                        Patient Relationship to
                                                                                        Member:</label>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <livewire:custom-check-box
                                                                                        name="MEMBER_IS_CHILD"
                                                                                        titleName="Child"
                                                                                        isDisabled="{{ $modify }}"
                                                                                        wire:model='MEMBER_IS_CHILD' />
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <livewire:custom-check-box
                                                                                        name="MEMBER_IS_PARENT"
                                                                                        titleName="Parent"
                                                                                        isDisabled="{{ $modify }}"
                                                                                        wire:model='MEMBER_IS_PARENT' />
                                                                                </div>
                                                                                <div class="col-md-2">

                                                                                    <livewire:custom-check-box
                                                                                        name="MEMBER_IS_SPOUSE"
                                                                                        titleName="Spouse"
                                                                                        isDisabled="{{ $modify }}"
                                                                                        wire:model='MEMBER_IS_SPOUSE' />
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        {{-- --}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 p-1 mt-2 border-top ">
                                                                    <label class="text-info text-xs"> Member
                                                                        Address:</label>
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <livewire:text-input
                                                                                name="MEMBER_UNIT_ROOM_FLOOR"
                                                                                titleName="Unit/Room#/Floor"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_UNIT_ROOM_FLOOR'
                                                                                maxlength='40' />
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <livewire:text-input
                                                                                name="MEMBER_BUILDING_NAME"
                                                                                titleName="Building Name"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_BUILDING_NAME'
                                                                                maxlength='40' />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <livewire:text-input
                                                                                name="MEMBER_LOT_BLK_HOUSE_BLDG"
                                                                                titleName="Lot/Blk/House/Bldg No."
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_LOT_BLK_HOUSE_BLDG'
                                                                                maxlength='40' />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <livewire:text-input name="MEMBER_STREET"
                                                                                titleName="Street"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_STREET'
                                                                                maxlength='60' />
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <livewire:text-input name="MEMBER_SUB_VALL"
                                                                                titleName="Subdivision/Village"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_SUB_VALL'
                                                                                maxlength='60' />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <livewire:text-input name="MEMBER_BRGY"
                                                                                titleName="Barangay"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_BRGY'
                                                                                maxlength='60' />
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <livewire:text-input
                                                                                name="MEMBER_CITY_MUNI"
                                                                                titleName="City/Municipality"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_CITY_MUNI'
                                                                                maxlength='60' />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <livewire:text-input name="MEMBER_PROVINCE"
                                                                                titleName="Province"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_PROVINCE'
                                                                                maxlength='60' />
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <livewire:text-input name="MEMBER_COUNTRY"
                                                                                titleName="Country"
                                                                                wire:model='MEMBER_COUNTRY'
                                                                                isDisabled="{{ $modify }}"
                                                                                maxlength='60' />
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <livewire:text-input name="MEMBER_ZIP_CODE"
                                                                                titleName="Zip Code"
                                                                                isDisabled="{{ $modify }}"
                                                                                wire:model='MEMBER_ZIP_CODE'
                                                                                maxlength='10' />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 p-1 mt-2 border-top ">
                                                                    <label class="text-info text-xs"> Member
                                                                        Contact:</label>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <livewire:text-input name="MEMBER_TEL_NO"
                                                                                titleName="Tel No."
                                                                                wire:model='MEMBER_TEL_NO'
                                                                                isDisabled="{{ $modify }}"
                                                                                maxlength='20' />
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <livewire:text-input name="MEMBER_MOBILE"
                                                                                titleName="Mobile No."
                                                                                wire:model='MEMBER_MOBILE'
                                                                                isDisabled="{{ $modify }}"
                                                                                maxlength='20' />
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <livewire:text-input name="MEMBER_EMAIL"
                                                                                titleName="Email Address"
                                                                                wire:model='MEMBER_EMAIL'
                                                                                isDisabled="{{ $modify }}"
                                                                                maxlength='20' />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 p-1 mt-2 border-top ">
                                                                    <label class="text-info text-xs">
                                                                        Member`s Certification
                                                                    </label>

                                                                    <livewire:custom-check-box name="IS_REPRESENTATIVE"
                                                                        titleName="Is Representative?"
                                                                        isDisabled="{{ $modify }}"
                                                                        wire:model.live='IS_REPRESENTATIVE' />
                                                                </div>
                                                            @endif

                                                            <div class="col-md-12 p-1 border-top mt-2">
                                                                <label class="text-info text-xs">
                                                                    Employer`s Certification
                                                                </label>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input name="PEN"
                                                                            titleName="PhilHealth Employer Number (PEN):"
                                                                            wire:model='PEN' :isDisabled='false'
                                                                            isDisabled="{{ $modify }}"
                                                                            maxlength='12' />
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input name="PEN_CONTACT"
                                                                            titleName="Contact No."
                                                                            isDisabled="{{ $modify }}"
                                                                            wire:model='PEN_CONTACT' maxlength='20' />
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <livewire:text-input name="COMPANY_NAME"
                                                                            titleName="Business Name"
                                                                            isDisabled="{{ $modify }}"
                                                                            wire:model='COMPANY_NAME'
                                                                            maxlength='100' />
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <livewire:text-input name="CUSTOM_FIELD3"
                                                                            titleName="Official Capacitiy /Designation"
                                                                            isDisabled="{{ $modify }}"
                                                                            wire:model='CUSTOM_FIELD3'
                                                                            maxlength='22' />
                                                                    </div>
                                                                    <div class="col-md-5 mt-2">
                                                                        <livewire:text-input name="CUSTOM_FIELD4"
                                                                            titleName="Authorized Representative"
                                                                            isDisabled="{{ $modify }}"
                                                                            wire:model='CUSTOM_FIELD4'
                                                                            maxlength='20' />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'doctor') show active @endif"
                                                id="custom-content-below-doctor-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-doctor-info-tab">
                                                <div class="container-fluid"
                                                    @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>

                                                    @livewire('Patient.DoctorPanel', ['ID' => $ID, 'LOCATION_ID' => $LOCATION_ID])
                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <livewire:text-input name="FINAL_DIAGNOSIS"
                                                                titleName="Final Diagnosis"
                                                                isDisabled="{{ $modify }}"
                                                                wire:model='FINAL_DIAGNOSIS' maxlength='60' />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="OTHER_DIAGNOSIS"
                                                                titleName="Other Diagnosis"
                                                                isDisabled="{{ $modify }}"
                                                                wire:model='OTHER_DIAGNOSIS' maxlength='60' />
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{-- Addition Treatment No. --}}
                                                            <livewire:number-input name="CUSTOM_FIELD2"
                                                                titleName="Additional No. of Treatment"
                                                                isDisabled="{{ $modify }}"
                                                                wire:model='CUSTOM_FIELD2' />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @can('contact.patient.update')
                                                <div class="tab-pane fade @if ($selectTab == 'requirements') show active @endif"
                                                    id="custom-content-below-requirements-info" role="tabpanel"
                                                    aria-labelledby="custom-content-below-requirements-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>

                                                        @if ($selectTab == 'requirements')
                                                            @if ($refreshToggle)
                                                                @livewire('Patient.RequirementPanel', ['CONTACT_ID' => $ID], 'p1')
                                                            @else
                                                                @livewire('Patient.RequirementPanel', ['CONTACT_ID' => $ID], 'p2')
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade @if ($selectTab == 'charges') show active @endif"
                                                    id="custom-content-below-charges-info" role="tabpanel"
                                                    aria-labelledby="custom-content-below-charges-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'charges')
                                                            @livewire('Patient.ChargesRecord', ['CONTACT_ID' => $ID, 'LOCATION_ID' => $LOCATION_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade @if ($selectTab == 'assistance') show active @endif"
                                                    id="custom-content-below-assistance-info" role="tabpanel"
                                                    aria-labelledby="custom-content-below-assistance-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'assistance')
                                                            @livewire('Patient.AssistanceRecord', ['CONTACT_ID' => $ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade @if ($selectTab == 'treatment') show active @endif"
                                                    id="custom-content-below-treatment-info" role="tabpanel"
                                                    aria-labelledby="custom-content-below-treatment-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'treatment')
                                                            @livewire('Patient.TreatmentRecord', ['CONTACT_ID' => $ID, 'LOCK_LOCATION_ID' => $LOCATION_ID])
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade @if ($selectTab == 'philhealth') show active @endif"
                                                    id="custom-content-below-philhealth-info" role="tabpanel"
                                                    aria-labelledby="custom-content-below-philhealth-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'philhealth')
                                                            @livewire('Patient.PhilhealthRecord', ['CONTACT_ID' => $ID, 'LOCATION_ID' => $LOCATION_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade @if ($selectTab == 'confinement') show active @endif"
                                                    id="custom-content-below-confinement-info" role="tabpanel"
                                                    aria-labelledby="custom-content-below-confinement-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'confinement')
                                                            @livewire('Patient.ConfinementRecord', ['PATIENT_ID' => $ID])
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade @if ($selectTab == 'transfer') show active @endif"
                                                    id="custom-content-below-transfer-info" role="tabpanel"
                                                    aria-labelledby="custom-below-transfer-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'transfer')
                                                            @livewire('Patient.TransferRecord', ['PATIENT_ID' => $ID])
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade @if ($selectTab == 'sales-receipt') show active @endif"
                                                    id="custom-content-below-sales-receipt-info" role="tabpanel"
                                                    aria-labelledby="custom-sales-receipt-below-sales-receipt-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'sales-receipt')
                                                            @livewire('Customer.CustomerSalesReceipt', ['id' => $ID])
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade @if ($selectTab == 'invoice') show active @endif"
                                                    id="custom-content-below-invoice-info" role="tabpanel"
                                                    aria-labelledby="custom-invoice-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'invoice')
                                                            @livewire('Customer.CustomerInvoice', ['id' => $ID])
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade @if ($selectTab == 'payment') show active @endif"
                                                    id="custom-content-below-payment-info" role="tabpanel"
                                                    aria-labelledby="custom-payment-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'payment')
                                                            @livewire('Customer.CustomerPayments', ['id' => $ID])
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade @if ($selectTab == 'tax-credit') show active @endif"
                                                    id="custom-content-below-tax-credit-info" role="tabpanel"
                                                    aria-labelledby="custom-tax-credit-info-tab">
                                                    <div class="container-fluid"
                                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        @if ($selectTab == 'tax-credit')
                                                            @livewire('Customer.CustomerTaxCredit', ['id' => $ID])
                                                        @endif
                                                    </div>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" wire:loading.class='loading-form'>
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            @if ($selectTab == 'gen' || $selectTab == 'phic' || $selectTab == 'doctor')
                                                @if ($ID == 0 && UserServices::GetUserRightAccess('contact.patient.create'))
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success">Save</button>
                                                @elseif ($ID > 0 && UserServices::GetUserRightAccess('contact.patient.update'))
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success">Update</button>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="text-right col-6 col-md-6">
                                            @if ($ID > 0)
                                                <button type="button" wire:click='openMedCert()'
                                                    class="btn btn-sm btn-warning"> <i class="fa fa-print"
                                                        aria-hidden="true"></i> Medical Certificate</button>
                                                @can('contact.patient.create')
                                                    <a id="new" title="Create"
                                                        href="{{ route('maintenancecontactpatients_create') }}"
                                                        class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i>
                                                        New</a>
                                                @endcan
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
    @livewire('Patient.MedcertModal', ['PATIENT_ID' => $ID])
    @livewire('Patient.TransferRecordModal')
</div>
