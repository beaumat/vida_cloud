<div class='row'>
    <div class="col-md-6">
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-header bg-secondary text-xs">
                </div>
                <div class="card-body p-2">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class=col-md-8>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class='text-sm'>SPECIAL ORDER <i
                                                            class="text-primary text-xs">Current</i></label>
                                                    <textarea class="form-control form-control-sm" rows='6' wire:model='SE_DETAILS'
                                                        @if ($Modify == false) disabled @endif></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class='text-sm'>SPECIAL ORDER : <i
                                                            class="text-info text-xs">Next</i></label>
                                                    <textarea class="form-control form-control-sm " rows='6' wire:model='SE_DETAILS_NEXT'
                                                        @if ($Modify == false) disabled @endif></textarea>
                                                </div>
                                                <div class="col-12 col-md-12 bg-warning m-1">

                                                    NOTE : <span class="text-xs"> use <b
                                                            class="text-lg text-danger">&nbsp;[&nbsp;]&nbsp;</b> <span
                                                            class="text-danger">Bracket</span> to red text
                                                        and <b class="text-lg text-primary"> &nbsp; ; </b><span
                                                            class="text-primary">Semicolon</span> to
                                                        next line
                                                    </span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class=col-md-4>
                                            <label class='text-sm'>STANDING ORDER</label>
                                            <textarea class="form-control form-control-sm" rows='6' wire:model='SO_DETAILS'
                                                @if ($Modify == false) disabled @endif></textarea>

                                            <div class="form-group text-sm">
                                                <label wire:click.live='orderUseNext()'>Use for next treatment
                                                    :</label>
                                                <input type="radio" name="ORDER_USE_NEXT" class="mt-2"
                                                    wire:model="ORDER_USE_NEXT" value="true"
                                                    @if ($Modify == false) disabled @endif />
                                                Yes
                                                <input type="radio" name="ORDER_USE_NEXT" class="mt-2"
                                                    wire:model="ORDER_USE_NEXT" value="false"
                                                    @if ($Modify == false) disabled @endif />
                                                No
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pb-2">
                            <div class="row ">
                                @can('full-treatment-sheet')
                                    <div class="col-3 col-md-1">
                                        <label class='text-xs'>UF GOAL </label>
                                    </div>
                                    <div class="col-9 col-md-8">
                                        <input type='text' maxlength='80' class='form-control form-control-sm text-xs'
                                            wire:model='UF_GOAL' @if ($Modify == false) disabled @endif />
                                    </div>

                                    <div class="col-3 col-md-1">
                                        <label class='text-xs' style="width:200px;">Machine #</label>
                                    </div>
                                    <div class="col-9 col-md-2 text-left">
                                        <input type='number' maxlength='3' class='text-xs form-control form-control-sm'
                                            wire:model='MACHINE_NO' @if ($Modify == false) disabled @endif />
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 text-right"> <label class='text-xs '>DRY WEIGHT </label></div>
                                            <div class="col-6"> <input type='text' class='w-75 text-xs'
                                                    wire:model='DRY_WEIGHT'
                                                    @if ($Modify == false) disabled @endif />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 text-right"> <label class='text-xs '>BFR </label></div>
                                            <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='BFR'
                                                    @if ($Modify == false) disabled @endif />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 text-right "> <label class='text-xs'>DFR </label></div>
                                            <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='DFR'
                                                    @if ($Modify == false) disabled @endif />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 text-right"> <label class='text-xs '>DURATION </label></div>
                                            <div class="col-6"> <input type='number' class='w-50 text-xs'
                                                    wire:model='DURATION'
                                                    @if ($Modify == false) disabled @endif />
                                                <i class="text-xs">hrs</i>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 text-right"> <label class='text-xs '>DIALYZER </label></div>
                                            <div class="col-6"> <input type='text' class='w-75 text-xs'
                                                    wire:model='DIALYZER' maxlength='10'
                                                    @if ($Modify == false) disabled @endif /> </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6 text-right"> <label class='text-xs '>REUSED NO. </label>
                                            </div>
                                            <div class="col-6"> <input type='text' class='w-50 text-xs'
                                                    wire:model='REUSE_NO' maxlength='10'
                                                    @if ($Modify == false) disabled @endif /> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 text-right"> <label class='text-xs text-info'
                                                    style="width:100px;">NEXT REUSED NO.
                                                </label>
                                            </div>
                                            <div class="col-6">
                                                <input type='text' class='w-50 text-xs' wire:model='REUSE_NEXT'
                                                    maxlength='10' @if ($Modify == false) disabled @endif />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 text-right"> <label class='text-xs'>HEPARIN </label></div>
                                            <div class="col-6"> <input type='text' class='w-75 text-xs'
                                                    wire:model='HEPARIN' maxlength='10'
                                                    @if ($Modify == false) disabled @endif /> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 text-right"> <label class='text-xs'>FLUSHING </label></div>
                                            <div class="col-6"> <input type='text' class='w-75 text-xs'
                                                    wire:model='FLUSHING' maxlength='10'
                                                    @if ($Modify == false) disabled @endif /> </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-8 ">
                                <div class='form-group p-2'>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-6 text-right">
                                                        <strong class="text-xs">RML :</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type='date' class='text-xs w-100' wire:model='RML'
                                                            @if ($Modify == false) disabled @endif />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-6 text-right">
                                                        <strong class="text-xs">HEPA PROFILE :</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type='date' class='text-xs w-100'
                                                            wire:model='HEPA_PROFILE'
                                                            @if ($Modify == false) disabled @endif />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">

                                        </div>
                                        <div class="col-6">
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-6 text-right">
                                                        <strong class="text-xs">CXR :</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type='date' class='text-xs w-100' wire:model='CXR'
                                                            @if ($Modify == false) disabled @endif />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">

                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    @can('full-treatment-sheet')
                                        <div class="col-md-8">
                                            <div class="card card-sm card-teal">
                                                <div class="card-header text-xs">
                                                    <strong> SAFETY CHECK</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 text-left">
                                                            <label class='text-xs'>
                                                                <input type='checkbox' wire:model='SC_MACHINE_TEST'
                                                                    @if ($Modify == false) disabled @endif />
                                                                &nbsp;MACHINE TEST
                                                            </label> <br />

                                                            <label class='text-xs'>
                                                                <input type='checkbox' wire:model='SC_SECURED_CONNECTIONS'
                                                                    @if ($Modify == false) disabled @endif />
                                                                &nbsp;SECURED CONNECTIONS
                                                            </label> <br />

                                                            <label class='text-xs'>
                                                                <input type='checkbox'
                                                                    wire:model='SC_SALINE_LINE_DOUBLE_CLAMP'
                                                                    @if ($Modify == false) disabled @endif />
                                                                &nbsp;SALINE LINE DOUBLE CLAMP
                                                            </label>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <div class="col-4 text-right">
                                                                    <label class='text-xs'>CONDUCTIVITY </label>
                                                                </div>
                                                                <div class="col-8">
                                                                    <input type='text' class='w-12 text-xs'
                                                                        wire:model='SC_CONDUCTIVITY' maxlength='7'
                                                                        @if ($Modify == false) disabled @endif />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-5 text-right">
                                                                    <label class='text-xs' style="width:100px;">DIALYSATE TEMP
                                                                    </label>
                                                                </div>
                                                                <div class="col-7">
                                                                    <input type='text' class='w-12 text-xs'
                                                                        wire:model='SC_DIALYSATE_TEMP' maxlength='7'
                                                                        @if ($Modify == false) disabled @endif />
                                                                </div>
                                                            </div>

                                                            <label class='text-xs'>
                                                                <b>RESIDUAL TEST</b>
                                                                <input type='checkbox' wire:model='SC_RESIDUAL_TEST_NEGATIVE'
                                                                    @if ($Modify == false) disabled @endif />
                                                                &nbsp;:<b>NEGATIVE</b>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endcan
                                    <div class="col-md-4">
                                        <div class="card card-sm card-cyan">
                                            @can('full-treatment-sheet')
                                                <div class="card-header text-xs">
                                                    <strong> DIALYSATE BATH </strong>
                                                </div>
                                            @endcan
                                            <div class="card-body">
                                                <div class="row">
                                                    @can('full-treatment-sheet')
                                                        <div class="col-12 text-left">
                                                            <label class='text-xs'>
                                                                <input type='checkbox' wire:model='DB_STANDARD_HCOA'
                                                                    @if ($Modify == false) disabled @endif />
                                                                &nbsp;STANDARD HCOA
                                                            </label>
                                                        </div>

                                                        <div class="col-12 text-left">
                                                            <label class='text-xs'>
                                                                <input type='checkbox' wire:model='DB_ACID'
                                                                    @if ($Modify == false) disabled @endif />
                                                                &nbsp;ACID
                                                            </label>
                                                        </div>
                                                    @endcan
                                                    <div class="col-4 text-right"> <label class='text-xs'>NA </label>
                                                    </div>
                                                    <div class="col-8"> <input type='text' class='w-75 text-xs'
                                                            wire:model='DIALSATE_N' maxlength='7'
                                                            @if ($Modify == false) disabled @endif /> </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 text-right"> <label class='text-xs'>K+ </label>
                                                    </div>
                                                    <div class="col-8"> <input type='text' class='w-75 text-xs'
                                                            wire:model='DIALSATE_K' maxlength='7'
                                                            @if ($Modify == false) disabled @endif /> </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 text-right"> <label class='text-xs'>Ca+ </label>
                                                    </div>
                                                    <div class="col-8"> <input type='text' class='w-75 text-xs'
                                                            wire:model='DIALSATE_C' maxlength='7'
                                                            @if ($Modify == false) disabled @endif /> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </section>
        </div>
        @can('full-treatment-sheet')
            <div class="col-md-6">
                <section class="content">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class='form-group row'>
                                        <div class="col-md-6">
                                            <div class="card card-sm card-purple">
                                                <div class="card-header text-xs">
                                                    <strong> FISTULA / GRAFT ACCESS </strong>
                                                </div>
                                                <div class="card-body">
                                                    <table class="w-100">
                                                        <thead class="text-xs">
                                                            <tr>
                                                                <td>ACCESS TYPE</td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='AT_FISTULA'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    FISTULA
                                                                </td>

                                                                <td>
                                                                    <input type='checkbox' wire:model='AT_GRAFT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    GRAFT
                                                                </td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='AT_RIGHT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    R
                                                                    &nbsp;&nbsp;
                                                                    <input type='checkbox' wire:model='AT_LEFT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    L
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-xs">
                                                            <tr>
                                                                <td>BRUIT</td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='B_STRONG'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    STRONG
                                                                </td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='B_WEEK'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    WEEK
                                                                </td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='B_ABSENT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    ABSENT
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>THRILL</td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='T_STRONG'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    STRONG
                                                                </td>

                                                                <td>
                                                                    <input type='checkbox' wire:model='T_WEAK'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    WEEK
                                                                </td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='T_ABSENT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    ABSENT
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>HEMATOMA</td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='H_PRESENT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    PRESENT
                                                                </td>

                                                                <td>
                                                                    <input type='checkbox' wire:model='H_ABSENT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    ABSENT
                                                                </td>
                                                                <td>

                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    <div class="form-group mt-4">
                                                        <div class="row">
                                                            <div class="col-3 text-right">
                                                                <strong class="text-xs">OTHERS</strong>
                                                            </div>
                                                            <div class="col-9">
                                                                <input type='text' maxlength='50' class='text-xs w-100'
                                                                    wire:model='H_OTHER_NOTES'
                                                                    @if ($Modify == false) disabled @endif />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card card-sm card-orange">
                                                <div class="card-header text-xs">
                                                    <strong> CVC ACCESS </strong>
                                                </div>
                                                <div class="card-body">
                                                    <table class="w-100">
                                                        <thead class="text-xs">
                                                            <tr>
                                                                <td>

                                                                    <input type='checkbox' wire:model='CVC_SUBCATH'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    SUBCATH


                                                                </td>

                                                                <td>
                                                                    <input type='checkbox' wire:model='CVC_JUGCATH'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    JUGCATH
                                                                </td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='CVC_FEMCATCH'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    FEMCATCH
                                                                </td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='CVC_PERMACATH'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    PERMACATH
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-xs">
                                                            <tr>
                                                                <td>LOCATION:</td>
                                                                <td>
                                                                    <input type='checkbox' wire:model='CVC_RIGHT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    R
                                                                    &nbsp;&nbsp;
                                                                    <input type='checkbox' wire:model='CVC_LEFT'
                                                                        @if ($Modify == false) disabled @endif />&nbsp;
                                                                    L
                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td>

                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>

                                                    <div class="form-group ">
                                                        <table class='w-100 mt-2'>
                                                            <thead class="text-xs">
                                                                <tr>
                                                                    <td class="text-right">CATHETER PORTS</td>
                                                                    <td class='text-center'>ARTERIAL</td>
                                                                    <td class='text-center'>VENOUS</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="text-xs">
                                                                <tr>
                                                                    <td class="text-right">GOOD FLOW</td>
                                                                    <td class='text-center'> <input type='checkbox'
                                                                            wire:model='CVC_GOOD_FLOW_A'
                                                                            @if ($Modify == false) disabled @endif />
                                                                    </td>
                                                                    <td class='text-center'> <input type='checkbox'
                                                                            wire:model='CVC_GOOD_FLOW_V'
                                                                            @if ($Modify == false) disabled @endif />
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td class="text-right">W/ RESISTANCE</td>
                                                                    <td class='text-center'> <input type='checkbox'
                                                                            wire:model='CVC_W_RESISTANCE_A'
                                                                            @if ($Modify == false) disabled @endif />
                                                                    </td>
                                                                    <td class='text-center'> <input type='checkbox'
                                                                            wire:model='CVC_W_RESISTANCE_V'
                                                                            @if ($Modify == false) disabled @endif />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-right">CLOTTED/NON PATENT</td>
                                                                    <td class='text-center'> <input type='checkbox'
                                                                            wire:model='CVC_CLOTTED_A'
                                                                            @if ($Modify == false) disabled @endif />
                                                                    </td>
                                                                    <td class='text-center'> <input type='checkbox'
                                                                            wire:model='CVC_CLOTTED_V'
                                                                            @if ($Modify == false) disabled @endif />
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='form-group'>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card card-info">
                                                    <div class="card-header text-xs">
                                                        <strong> PRE-HEMODIALYSIS ASSESSMENT </strong>
                                                    </div>
                                                    <div class="card-body" style="overflow-y: auto;">
                                                        <div style="width:600px;">
                                                            <table class="w-100">
                                                                <thead class="text-xs">
                                                                    <tr>
                                                                        <th>MOBILIZATION </th>
                                                                        <th>LUNGS</th>
                                                                        <th>FLUID STATUS</th>
                                                                        <th>HEART</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-xs">
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_AMBULATORY'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            AMBULATORY
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_CLEAR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            CLEAR
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='PRE_DISTENDED_JUGULAR_VIEW'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            DISTENDED JUGULAR VIEW
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_REGULAR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            REGULAR
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='PRE_AMBULATORY_W_ASSIT'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            AMBULATORY W/ ASSIT
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_CRACKLES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            CRACKLES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_ASCITES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            ASCITES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_IRREGULAR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            IRREGULAR
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='PRE_WHEEL_CHAIR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            WHEEL CHAIR
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_RHONCHI'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            RHONCHI
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_EDEMA'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            EDEMA
                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="text-center">

                                                                            L.O.C
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_WHEEZES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            WHEEZES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_LOCATION'
                                                                                @if ($Modify == false) disabled @endif />
                                                                            LOCATION <input type='text' maxlength='30'
                                                                                wire:model='PRE_LOCATION_NOTES'
                                                                                @if ($Modify == false) disabled @endif />
                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>

                                                                            <input type='checkbox' wire:model='PRE_CONSCIOUS'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            CONSCIOUS


                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_RALES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            RALES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_DEPTH'
                                                                                @if ($Modify == false) disabled @endif />
                                                                            DEPTH <input type='text' maxlength='30'
                                                                                wire:model='PRE_DEPTH_NOTES'
                                                                                @if ($Modify == false) disabled @endif />

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_COHERENT'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            COHERENT

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='PRE_DISORIENTED'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            DISORIENTED

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='PRE_DROWSY'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            DROWSY
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="card card-warning card-sm">
                                                    <div class="card-header text-xs">
                                                        <strong> POST-HEMODIALYSIS ASSESSMENT</strong>
                                                    </div>
                                                    <div class="card-body" style="overflow-y: auto;">
                                                        <div style="width:600px;">


                                                            <table class="w-100">
                                                                <thead class="text-xs">
                                                                    <tr>
                                                                        <th>MOBILIZATION </th>
                                                                        <th>LUNGS</th>
                                                                        <th>FLUID STATUS</th>
                                                                        <th>HEART</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-xs">
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='POST_AMBULATORY'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            AMBULATORY
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_CLEAR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            CLEAR
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='POST_DISTENDED_JUGULAR_VIEW'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            DISTENDED JUGULAR VIEW
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_REGULAR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            REGULAR
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='POST_AMBULATORY_W_ASSIT'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            AMBULATORY W/ ASSIT
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_CRACKLES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            CRACKLES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_ASCITES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            ASCITES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_IRREGULAR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            IRREGULAR
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='POST_WHEEL_CHAIR'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            WHEEL CHAIR
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_RHONCHI'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            RHONCHI
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_EDEMA'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            EDEMA
                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="text-center">

                                                                            L.O.C
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_WHEEZES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            WHEEZES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_LOCATION'
                                                                                @if ($Modify == false) disabled @endif />
                                                                            LOCATION <input type='text' maxlength='30'
                                                                                wire:model='POST_LOCATION_NOTES'
                                                                                @if ($Modify == false) disabled @endif />
                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>

                                                                            <input type='checkbox' wire:model='POST_CONSCIOUS'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            CONSCIOUS


                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_RALES'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            RALES
                                                                        </td>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_DEPTH'
                                                                                @if ($Modify == false) disabled @endif />
                                                                            DEPTH <input type='text' maxlength='30'
                                                                                wire:model='POST_DEPTH_NOTES'
                                                                                @if ($Modify == false) disabled @endif />

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_COHERENT'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            COHERENT

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox'
                                                                                wire:model='POST_DISORIENTED'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            DISORIENTED

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' wire:model='POST_DROWSY'
                                                                                @if ($Modify == false) disabled @endif />&nbsp;
                                                                            DROWSY
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endcan
    </div>
