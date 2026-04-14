<?php
use App\Services\UserServices;
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-8 col-sm-6">
                                    <a class="text-white" href="{{ route('patientshemo') }}">
                                        @if ($ID == 0)
                                            Create
                                        @endif
                                        Hemodialysis Treatment
                                    </a>
                                </div>
                                <div class="col-4 col-sm-6 text-right">
                                    @if ($ID > 0)
                                        <i> {{ $STATUS_DESCRIPTION }} <strong class="text-white"> Created On :
                                                {{ $RECORDED_ON }}</strong></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <livewire:select-option name="CUSTOMER_ID" titleName="Patient Name"
                                                        :options="$patientList" :zero="true" :isDisabled=true
                                                        wire:model='CUSTOMER_ID' />

                                                </div>
                                                <div class="col-md-12 text-left">
                                                    <label class="text-xs text-primary forn-weight-bold">Encoded by :
                                                        @if ($EMPLOYEE_NAME)
                                                            <span type="button" wire:click='setNullEmployee()'
                                                                wire:confirm="Are you sure you want to remove this?">
                                                                {{ $EMPLOYEE_NAME }}</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="row">
                                                <div class="col-4 col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date Treatment"
                                                        wire:model='DATE' :isDisabled="true" />
                                                </div>
                                                <div class="col-4 col-md-4">
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        :isDisabled=true wire:model='CODE' />
                                                </div>
                                                <div class="col-4 col-md-4">
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false" :isDisabled=true
                                                        wire:model='LOCATION_ID' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        @if ($Modify)
                                            @if ($STATUS == 4)
                                                <button name="btnSavePosted" type="submit"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Save & Posted
                                                </button>
                                                <button name="btnCanceled" type='button' wire:click='updateCancel'
                                                    class="btn btn-sm btn-secondary"><i class="fa fa-ban"
                                                        aria-hidden="true"></i> Cancel</button>
                                            @else
                                                <button name="btnSave" type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Save' }}</button>
                                                <button name="btnCanceled" type='button' wire:click='updateCancel'
                                                    class="btn btn-sm btn-secondary"><i class="fa fa-ban"
                                                        aria-hidden="true"></i> Cancel</button>
                                            @endif
                                        @else
                                            @if ($STATUS == 1 || $STATUS == 4)
                                                <button name="btnModify" type='button' wire:click='getModify()'
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Edit
                                                </button>

                                                @if (($ID > 0 && $STATUS == 1) || ($ID > 0 && $STATUS == 4))
                                                    @if (UserServices::GetUserRightAccess('patient.treatment.update'))
                                                        <button name="btnPosted" type='button' wire:click='getPosted()'
                                                            class="btn btn-sm btn-success"
                                                            wire:confirm="Are you sure you want to post?">
                                                            <i class="fa fa-lock" aria-hidden="true"></i> Posted
                                                        </button>
                                                    @endif
                                                @endif
                                                
                                                <button name="btnPosted" type='button' wire:click='openForm()'
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Agreement Form
                                                </button>

                                            @endif

                                            @if ($STATUS == 3)
                                                @can('patient.treatment.update')
                                                    <button name="btnUnposted" type='button' class="btn btn-sm btn-warning"
                                                        wire:confirm="Are you sure you want to un-posted?"
                                                        wire:click='getUnposted()'>
                                                        <i class="fa fa-unlock" aria-hidden="true"></i>
                                                        Unposted
                                                    </button>
                                                @endcan
                                            @endif

                                            @if ($STATUS != 3)
                                                @can('patient.treatment.delete')
                                                    <button name="btnCanceled" type='button' class="btn btn-sm btn-danger"
                                                        wire:confirm="Are you sure you want to void? please make sure the item include manual deleted."
                                                        wire:click='getCanceled()'>
                                                        <i class="fa fa-times" aria-hidden="true"></i> Void
                                                    </button>
                                                @endcan
                                            @endif
                                        @endif

                                        @can('full-treatment-sheet')
                                            @if (!$Modify)
                                                <button wire:click='showNotes()' name="btnNotes" type='button'
                                                    class="btn btn-sm btn-dark"> <i class="fa fa-list-ol"
                                                        aria-hidden="true"></i> Notes</button>
                                            @endif
                                        @endcan

                                        @can('patient.treatment.print')
                                            @if (!$Modify)
                                                <a target="_blank" href="{{ route('patientshemo_print', ['id' => $ID]) }}"
                                                    class="btn btn-sm btn-dark"><i class="fa fa-print"
                                                        aria-hidden="true"></i> Print</a>
                                            @endif
                                        @endcan

                                        @if ($STATUS == 2)
                                            @can('patient.treatment.journal')
                                                <button wire:click='openJournal()' name="btnJournal" type='button'
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                    Preview Journal</button>

                                                <button wire:click='reJournal' wire:confirm='Are you sure?'>
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                    Re-Journal
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($ID > 0)
        <section class="content">
            <div class="container-fluid bg-light">
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group ">
                            <section class="content">
                                <!-- Default box -->
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <table class="table  table-sm table-bordered table-hover">
                                                    <thead class="text-xs bg-primary">
                                                        <tr>
                                                            <th class="">Title</th>
                                                            <th class="text-center col-4">
                                                                Last Treatment
                                                            </th>
                                                            <th class="text-center col-6">
                                                                Today Treatment
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <thead class="text-xs">
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-center">
                                                                <div class="row">
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        PRE
                                                                    </div>
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        POST
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th class="text-center">
                                                                <div class="row">
                                                                    <div class="col-6 col-md-6">
                                                                        PRE
                                                                    </div>
                                                                    <div class="col-6 col-md-6">
                                                                        POST
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-xs">
                                                        <tr>
                                                            <td class="font-weight-bold">WEIGHT</td>
                                                            <td>
                                                                <div class="row" id="LAST">
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_PRE_WEIGHT }}
                                                                    </div>
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_POST_WEIGHT }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="row" id="TODAY">
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='PRE_WEIGHT'
                                                                            class="text-xs w-100 text-right" />

                                                                    </div>
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='POST_WEIGHT'
                                                                            class="text-xs w-100 text-right" />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-xs">BLOOD PRESSURE</td>
                                                            <td>
                                                                <div class="row" id="LAST">
                                                                    <div
                                                                        class="col-6 col-md-6 text-center right-line2">
                                                                        {{ $OLD_PRE_BLOOD_PRESSURE }}/
                                                                        {{ $OLD_PRE_BLOOD_PRESSURE2 }}
                                                                    </div>
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_POST_BLOOD_PRESSURE }}/
                                                                        {{ $OLD_POST_BLOOD_PRESSURE2 }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="row" id="TODAY">
                                                                    <div class="col-6 col-md-6">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-6">
                                                                                <input
                                                                                    @if (!$Modify) disabled @endif
                                                                                    wire:model='PRE_BLOOD_PRESSURE'
                                                                                    type="number"
                                                                                    class="text-xs w-100 text-right"
                                                                                    placeholder="BP[1]" />
                                                                            </div>

                                                                            <div class="col-12 col-sm-6">
                                                                                <input
                                                                                    @if (!$Modify) disabled @endif
                                                                                    wire:model='PRE_BLOOD_PRESSURE2'
                                                                                    type="number"
                                                                                    class="text-xs w-100 text-right"
                                                                                    placeholder="BP[2]" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-md-6">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-6">
                                                                                <input
                                                                                    @if (!$Modify) disabled @endif
                                                                                    wire:model='POST_BLOOD_PRESSURE'
                                                                                    type="number"
                                                                                    class="text-xs w-100 text-right"
                                                                                    placeholder="BP[1]" />
                                                                            </div>
                                                                            <div class="col-12 col-sm-6">
                                                                                <input
                                                                                    @if (!$Modify) disabled @endif
                                                                                    wire:model='POST_BLOOD_PRESSURE2'
                                                                                    type="number"
                                                                                    class="text-xs w-100 text-right"
                                                                                    placeholder="BP[2]" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold">HEART RATE</td>
                                                            <td>
                                                                <div class="row" id="LAST">
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_PRE_HEART_RATE }}
                                                                    </div>
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_POST_HEART_RATE }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="row" id="TODAY">
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='PRE_HEART_RATE'
                                                                            class="text-xs w-100 text-right" />
                                                                    </div>
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='POST_HEART_RATE'
                                                                            class="text-xs w-100 text-right" />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold">O2 SATURATION</td>
                                                            <td>
                                                                <div class="row" id="LAST">
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_PRE_O2_SATURATION }}
                                                                    </div>
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_POST_O2_SATURATION }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="row" id="TODAY">
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='PRE_O2_SATURATION'
                                                                            class="text-xs w-100 text-right" />
                                                                    </div>
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='POST_O2_SATURATION'
                                                                            class="text-xs w-100 text-right" />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold">TEMPERATURE</td>
                                                            <td>
                                                                <div class="row" id="LAST">
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_PRE_TEMPERATURE }}
                                                                    </div>
                                                                    <div class="col-6 col-md-6 text-center">
                                                                        {{ $OLD_POST_TEMPERATURE }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="row" id="TODAY">
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='PRE_TEMPERATURE'
                                                                            class="text-xs w-100 text-right" />
                                                                    </div>
                                                                    <div class="col-6 col-md-6">
                                                                        <input type="number"
                                                                            @if (!$Modify) disabled @endif
                                                                            wire:model='POST_TEMPERATURE'
                                                                            class="text-xs w-100 text-right" />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-6 col-md-4">
                                                        <div class="row form-group mt-1 text-left">
                                                            <div class="col-md-12">
                                                                <label class="text-xs">TIME START :</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="time"
                                                                    @if (!$Modify) disabled @endif
                                                                    wire:model='TIME_START'
                                                                    class="form-control form-control-sm" />
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-6 col-md-4">
                                                        <div class="row form-group mt-1 text-left">
                                                            <div class="col-md-12">
                                                                <label class="text-xs">TIME END :</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="time"
                                                                    @if (!$Modify) disabled @endif
                                                                    wire:model='TIME_END'
                                                                    class="form-control form-control-sm" />
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <div class="form-group mt-1 text-center">
                                                            <input @if (!$Modify) disabled @endif
                                                                type="checkbox" wire:model='IS_INCOMPLETE' />
                                                            <label class="text-xs text-danger"> INCOMPLETE </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-md-12"
                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                @livewire('Hemodialysis.InventoryTreatment', ['HEMO_ID' => $ID, 'STATUS' => $STATUS, 'LOCATION_ID' => $LOCATION_ID, 'ActiveRequired' => $ActiveRequired])
                            </div>
                            <div class="col-md-12">
                                @if ($IsDocmentUploaded)
                                    <div class="row form-group">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="fileUpload" class="text-xs">Image
                                                    document
                                                    file
                                                    @if ($IMAGE)
                                                        <i class="fa fa-check-circle text-success"
                                                            aria-hidden="true"></i>
                                                    @endif
                                                </label>
                                                <div class="input-group input-group-sm">
                                                    <div class="custom-file text-xs">
                                                        <input type="file" class="custom-file-input text-xs"
                                                            id="fileUpload" wire:model.live='IMAGE'>
                                                        <label class="custom-file-label text-xs" for="fileUpload">
                                                            @if ($IMAGE)
                                                                {{ $IMAGE->getClientOriginalName() }}
                                                            @else
                                                                Choose file
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <button wire:click='uploaddoc' class="mt-4 btn btn-dark btn-sm">
                                                <i class="fa fa-upload" aria-hidden="true"></i> Upload
                                            </button>
                                            @if ($FILE_PATH)
                                                <a target="_blank" href="{{ asset('storage/' . $FILE_PATH) }}"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    Preview
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12">
                                @livewire('Hemodialysis.DoctorsOrder', ['HEMO_ID' => $ID, 'DOCTOR_DES_PROMP' => $DOCTOR_DES_PROMP])
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-12">
                        @if ($USE_OTHER_DETAILS == true)
                            <div class="form-group">
                                @livewire('Hemodialysis.OtherDetails', ['HEMO_ID' => $ID, 'Modify' => $Modify, 'STATUS' => $STATUS])

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
    @livewire('PincodeEnter')
    @livewire('Hemodialysis.NurseNotes')
    @livewire('ServiceCharge.AgreementFormModal');
</div>
