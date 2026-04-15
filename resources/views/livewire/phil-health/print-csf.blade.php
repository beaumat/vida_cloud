<div class="font-weight-light p-3">
    <div class="row">
        <div class="col-12 blackbox2">
            <div class="row" style="height: 150px;">
                <div class="col-3 text-center">
                    <img class="print-logo" style="width:220px;position:relative;top:25px;"
                        src="{{ asset('dist/logo/philhealth_logo.png') }}" />
                </div>
                <div class="col-6 text-center">
                    <div style="padding-top:15px;">
                        <i class="times-new-roman">Republic of the Philippines</i><br />
                        <b style="font-size: 22px;position: absolute;width:1000px; left:-200px;top:30px;"
                            class="times-new-roman">PHILIPPINE HEALTH INSURANCE CORPORATION</b>
                        <p class="times-new-roman"
                            style="padding: 0;position: absolute;top:62px;left:100px; line-height: 1;">
                            Citystate Centre 709 Shaw Boulevard, Pasig City <br />
                            Call Center (02) 441-7442 &#8226; Trunkline (02) 441-7444<br />
                            www.philhealth.gov.ph <br />
                            email: actioncenter@philhealth.gov.ph <br />
                        </p>
                    </div>
                </div>
                <div class="col-3">
                    <div class=" float-right text-sm text-center mt-1 text-xs" style="padding-right:2px;">
                        This form may be reproduced and
                        <br /> is NOT FOR SALE<br />
                        <b class="text-center noto font-weight-bold"
                            style="font-size: 65px;top:25px;position:absolute;right:60px;">CSF</b><br />
                        <b class="text-center noto font-weight-bold"
                            style="font-size:20px; top:100px;position: absolute;right:5px;">
                            (Claim Signature Form)</b> <br />
                        <p class="text-center text-sm" style="top:130px;position: absolute;right:40px;">Revised
                            September 2018</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 ubox2 font-weight-light" style="height: 90px;">
            <p style="font-size:14.4px; line-height: 1.3; " class="float-left mt-2">
                <b class="font-weight-bold">IMPORTANT REMINDERS:</b>
                <br />
                PLEASE WRITE IN CAPITAL <b class="font-weight-bold">LETTERS</b> AND <b
                    class="font-weight-bold">CHECK</b>
                THE APPROPRIATE BOXES.<br />
                All information required in this form are necessary. Claim forms with incomplete information
                shall not be processed.<br />
                <b class="font-weight-bold">FALSE/INCORRECT INFORMATION OF MISINTERPRETATION SHALL BE SUBJECT TO
                    CRIMINAL, CIVIL OR
                    ADMINISTRATIVE
                    LIABILITIES.</b>
            </p>
            <div class="float-right mt-2">
                <div class="row" style="position: absolute;right:1px;width:310px;">
                    <p class="mt-2"> Series#&nbsp;</p>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                    <div class="box   font-weight-bold">&nbsp;</div>
                </div>
            </div>

        </div>
        <div class="col-12 text-center font-weight-light bgBlack">
            <b class="text-white arial font-weight-bold" style="font-size: 19px">
                PART I - MEMBER AND PATIENT INFORMATION AND CERTIFICATION
            </b>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class="row">
                <div class="col-12" style="height: 30px; ">
                    <label> 1. PhilHealth Identification Number (PIN) of Member: </label>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 0, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 1, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <label class="px-1">&nbsp;-</label>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 2, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 3, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 4, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 5, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 6, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 7, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 8, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 9, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 10, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <label class="px-1">&nbsp;-</label>
                    <div class="box   font-weight-bold">
                        @if ($PIN)
                            {{ substr($PIN, 11, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                </div>
                <div class="col-12" style="line-height: 1.2; ">
                    <div class="row">
                        <div class="col-9  text-sm">
                            <label> 2. Name of Member:</label>
                            <div class="row">
                                <div class="col-3 text-center">
                                    <b class="  font-weight-bold h6">{{ $MEMBER_LAST_NAME }}&nbsp;</b>
                                    <div class="w-100 top-line2 "></div>
                                    Last Name
                                </div>
                                <div class="col-3 text-center">
                                    <b class="  font-weight-bold h6">{{ $MEMBER_FIRST_NAME }}&nbsp;</b>
                                    <div class="w-100 top-line2"></div>
                                    First Name
                                </div>
                                <div class="col-2 text-center">
                                    <b class="  font-weight-bold h6">{{ $MEMBER_EXTENSION }}&nbsp;</b>
                                    <div class="w-100 top-line2"></div>
                                    Name Extension <br />
                                    (JR/SR/III)

                                </div>
                                <div class="col-4 text-center">
                                    <b class="  font-weight-bold h6">{{ $MEMBER_MIDDLE_NAME }}&nbsp;</b>
                                    <div class="w-100 top-line2"></div>
                                    Middle Name<br />
                                    (ex: DELA CRUZ JUAN JR SIPAG)

                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div style="position: absolute;left:0px; width:300px;">
                                <label> 3. Member's Date of Birth: </label>
                                <div class="form-group">
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 5, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 6, 1) }}
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 8, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 9, 1) }}
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 0, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 1, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 2, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($MEMBER_BIRTH_DATE, 3, 1) }}
                                    </div>
                                    <br>
                                    <p style="position: absolute;top:57px;">month &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        year</p>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-12" style="height: 30px; ">
                    <label> 4. PhilHealth Identification Number (PIN) of Dependent: </label>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 0, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 1, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <label class="px-1">&nbsp;-</label>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 2, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 3, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 4, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 5, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 6, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 7, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 8, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 9, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 10, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <label class="px-1">&nbsp;-</label>
                    <div class="box   font-weight-bold">
                        @if ($PIN_DEPENDENT)
                            {{ substr($PIN_DEPENDENT, 11, 1) }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                </div>
                <div class="col-12 " style="line-height: 1.2; ">
                    <div class="row">
                        <div class="col-9">
                            <label> 5. Name of Patient:</label>
                            <div class="row">
                                <div class="col-3 text-center">
                                    <b class="  font-weight-bold h6">{{ $PATIENT_LASTNAME }}&nbsp;</b>
                                    <div class="w-100 top-line2"></div>
                                    Last Name
                                </div>
                                <div class="col-3 text-center">
                                    <b class="  font-weight-bold h6">{{ $PATIENT_FIRSTNAME }}&nbsp;</b>
                                    <div class="w-100 top-line2"></div>
                                    First Name
                                </div>
                                <div class="col-2 text-center">
                                    <b class="  font-weight-bold h6">{{ $PATIENT_EXTENSION }}&nbsp;</b>
                                    <div class="w-100 top-line2"></div>
                                    Name Extension </br>
                                    (JR/SR/III)

                                </div>
                                <div class="col-4 text-center">
                                    <b class="  font-weight-bold h6">{{ $PATIENT_MIDDLENAME }}&nbsp;</b>
                                    <div class="w-100 top-line2"></div>
                                    Middle Name<br />
                                    (ex: DELA CRUZ JUAN JR SIPAG)

                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div style="position: absolute;left:0px; width:300px;">
                                <label> 6. Relationship to Member: </label>
                                <div class="form-group">
                                    <div class="box   font-weight-bold">
                                        @if ($MEMBER_IS_CHILD)
                                            &#10004;
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    &nbsp;&nbsp;child
                                    <div class="box   font-weight-bold">
                                        @if ($MEMBER_IS_PARENT)
                                            &#10004;
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    &nbsp;&nbsp;parent
                                    <div class="box   font-weight-bold">
                                        @if ($MEMBER_IS_SPOUSE)
                                            &#10004;
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    &nbsp;&nbsp;spouse

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-12 " style="line-height: 1.2; ">
                    <div class="row">
                        <div class="col-9">
                            <label> 7. Confinement Period:</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row ">
                                        <div class="col-4 text-md">
                                            <div class="form-group mt-2 text-sm">
                                                a. Date Admitted
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group">
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 5, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 6, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 8, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 9, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 0, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 1, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 2, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_ADMITTED, 3, 1) }}
                                                </div>
                                                <br>
                                                <p style="position: absolute;top:27px;">month
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    year</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4 text-md">
                                            <div class="form-group mt-2 text-sm"
                                                style="position:absolute; width:300px;">
                                                b. Date Discharged
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group">

                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 5, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 6, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 8, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 9, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 0, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 1, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 2, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 3, 1) }}
                                                </div>
                                                <br>
                                                <p style="position: absolute;top:27px;">month
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    year</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div style="position: absolute;left:0px; width:300px;">
                                <label> 8. Patient`s Date of Birth: </label>
                                <div class="form-group">
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 5, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 6, 1) }}
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 8, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 9, 1) }}
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 0, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 1, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 2, 1) }}
                                    </div>
                                    <div class="box   font-weight-bold">
                                        {{ substr($PATIENT_BIRTH_DATE, 3, 1) }}
                                    </div>
                                    <br>
                                    <p style="position: absolute;top:57px;">month &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        year</p>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <label> 9. CERTIFICATION OF MEMBER:
                    </label>
                    <div class="row">
                        <div class="col-12 text-center">
                            <b><i>Under the penalty of law, I attest that the information I provided in this form are
                                    true and accurate to the best of my knowledge.
                                </i></b>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-4 text-center text-sm">
                            <b class="h5   font-weight-bold">

                                {{ $MEMBER_FIRST_NAME }} @if ($MEMBER_MIDDLE_NAME)
                                    {{ substr($MEMBER_MIDDLE_NAME, 0, 1) }}.
                                    @endif {{ $MEMBER_LAST_NAME }} @if ($MEMBER_EXTENSION)
                                        {{ $MEMBER_EXTENSION . '.' }}
                                    @endif
                                    &nbsp;
                            </b>
                            <div class="top-line2">
                            </div>
                            <span>Signature Over Printed Name of Member</span>
                            <div class="row ">
                                <div class="col-4 text-md">
                                    <div class="form-group mt-2 text-sm">
                                        <p class="float-right"> Date Signed</p>
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="form-group text-md" style="width:300px;">
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 5, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 6, 1) }}
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 8, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 9, 1) }}
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 0, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 1, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 2, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 3, 1) }}
                                        </div>
                                        <br>
                                        <p style="position: absolute;top:27px;">month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            year</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                        </div>
                        <div class="col-5 text-center text-sm text-secondary">
                            <b class="h5   font-weight-bold">
                                {{ $NAME_REPRESENTATIVE }}&nbsp;</b>
                            <div class="top-line2 " style="width: 95%"></div>
                            Signature Over Printed Name of Member’s Representative
                            <div class="row">
                                <div class="col-4 text-md">
                                    <div class="form-group mt-2 text-sm">
                                        <p class="float-right"> Date Signed</p>
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="form-group text-md">
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        <div class="box   font-weight-bold">&nbsp;</div><label
                                            class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        <div class="box   font-weight-bold">&nbsp;</div><label
                                            class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        <br>
                                        <p style="position: absolute;top:27px;">month &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            year</p>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-7">
                                    <p class="text-sm" style="width: 400px;">
                                        If member/representative is unable to write, put<br />
                                        right thumbmark. Member/Representative <br />
                                        should be assisted by an HCI representative. <br />
                                        Check the appropriate box.
                                    <div style="margin-top:-20px;">
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Member&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Representative
                                    </div>
                                    </p>

                                </div>
                                <div class="col-5">
                                    <div class="blackbox   font-weight-bold2 w-75">
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-6">
                            <div class="row">
                                <div class="col-6 float-right">
                                    <p style="padding-left:60px; width:400px" class="text-left text-sm">Relationship
                                        of the <br />
                                        representative to the member
                                    </p>
                                    <p style="padding-left:60px; width:400px;" class="mt-2 text-left text-sm">
                                        Reason for signing on behalf of <br />
                                        the member
                                    </p>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Spouse&nbsp;
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Child&nbsp;
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Parent<br />
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Sibling&nbsp;&nbsp;
                                        <div class="box   font-weight-bold" style="margin-left:1px;">&nbsp;</div>
                                        &nbsp;&nbsp;Others,
                                        Specify&nbsp;&nbsp;<br />
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Member is
                                        incapacitated&nbsp;&nbsp;<br />
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Other reasons:&nbsp;&nbsp;
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center bgBlack text-white pt-1">
                    <b class=" arial font-weight-bold" style="font-size: 19px">
                        PART II - EMPLOYER’S CERTIFICATION
                    </b>
                    (for employed members only)
                </div>
                <div class="col-12" style="height: 190px;  ">
                    <div class="row">
                        <div class="col-7">
                            <label> 1. PhilHealth Employer Number (PEN): </label>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 0, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 1, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <label class="px-1">&nbsp;-</label>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 2, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 3, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 4, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 5, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 6, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 7, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 8, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 9, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 10, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                            <label class="px-1">&nbsp;-</label>
                            <div class="box   font-weight-bold">
                                @if ($PEN)
                                    {{ substr($PEN, 11, 1) }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-4">
                                    <label class="mt-1"> 2. Contact No.: &nbsp; </label>
                                </div>
                                <div class="col-8">
                                    <div style="right: 30px; position: absolute; "
                                        class="float-left bottom-line2 w-100 ">
                                        <b class="h5   font-weight-bold ">
                                            {{ $PEN_CONTACT }}&nbsp;</b>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2">
                                    <label> 3. Business Name: </label>
                                </div>
                                <div class="col-10 text-center">
                                    <div style="right: 30px; position: absolute; top:-8px;"
                                        class="float-left bottom-line2 w-100 mt-1">
                                        <b class="h5   font-weight-bold ">{{ $COMPANY_NAME }}
                                            &nbsp;</b>
                                    </div>
                                    <p class="text-sm pt-4"> Business Name of Employer</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" style="top: -30px;">
                            <div class="row">
                                <div class="col-12">
                                    <label> 4. CERTIFICATION OF EMPLOYER: </label>
                                </div>
                                <div class="col-12 arial text-sm px-4" style="line-height: 1.1;">
                                    <i class="px-3">
                                        “This is to certify that the required 3/6 monthly premium contributions plus at
                                        least 6 months contributions preceding the 3 months qualifying contributions
                                        within
                                        12 month period prior to the first day of confinement (sufficient regularity)
                                        have been regularly remitted to PhilHealth. Moreover, the information supplied
                                        by the
                                        member or his/her representative on Part I are consistent with our available
                                        records.”
                                    </i>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-5 text-sm text-center">
                                            <b class="h5   font-weight-bold "> &nbsp;
                                                {{-- under employer officer only  --}}
                                                {{-- @if ($PEN)
                                                    @if (!empty($AUTORIZE_REP_NAME2))
                                                        {{ $AUTORIZE_REP_NAME2 }}
                                                    @else
                                                        {{ $MEMBER_FIRST_NAME }} @if ($MEMBER_MIDDLE_NAME)
                                                            {{ substr($MEMBER_MIDDLE_NAME, 0, 1) }}.
                                                            @endif {{ $MEMBER_LAST_NAME }} @if ($MEMBER_EXTENSION)
                                                                {{ $MEMBER_EXTENSION . '.' }}
                                                            @endif
                                                        @endif
                                                    @endif --}}
                                            </b>
                                            <div class="top-line2"></div>
                                            Signature Over Printed Name of Employer/Authorized Representative
                                        </div>
                                        <div class="col-3 text-xs text-center">
                                            <b style="width:300px;" class="h5   font-weight-bold ">
                                                &nbsp;<span class="text-md">
                                                    @if ($PEN)
                                                        {{ $MEMBER_POSITION }}
                                                    @endif
                                                </span></b>
                                            <div class="top-line2"></div>
                                            Official Capacity/Designation
                                        </div>
                                        <div class="col-4 mb-2">
                                            <div class="row">
                                                <div class="col-4 text-md">
                                                    <div class="form-group mt-2 text-sm  text-right"
                                                        style="position:absolute; width:100px;">
                                                        Date Signed
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    @if (empty($COMPANY_NAME))
                                                        <div class="form-group">
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div>
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div><label class="px-1">&nbsp;-</label>
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div>
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div><label class="px-1">&nbsp;-</label>
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div>
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div>
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div>
                                                            <div class="box   font-weight-bold">
                                                                &nbsp;</div>
                                                            <br>
                                                            <p style="position: absolute;top:27px;">month
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                year</p>

                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 5, 1) }}
                                                            </div>
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 6, 1) }}
                                                            </div>
                                                            <label class="px-1">&nbsp;-</label>
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 8, 1) }}
                                                            </div>
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 9, 1) }}
                                                            </div>
                                                            <label class="px-1">&nbsp;-</label>
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 0, 1) }}
                                                            </div>
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 1, 1) }}
                                                            </div>
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 2, 1) }}
                                                            </div>
                                                            <div class="box   font-weight-bold">
                                                                {{ substr($DATE_DISCHARGED, 3, 1) }}
                                                            </div>
                                                            <br>
                                                            <p style="position: absolute;top:27px;">month
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                year</p>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center bgBlack text-white pt-1 mt-1">
                    <b class="arial font-weight-bold" style="font-size: 19px">
                        PART III - CONSENT TO ACCESS PATIENT RECORD/S
                    </b>
                </div>
                <div class="col-12" style="height: 80px; ">
                    <div class="row ">
                        <div class="col-12">
                            <label style="font-size: 13.5px;padding-left:10px;">
                                <i>
                                    I hereby consent to the submission and examination of the patient’s pertinent
                                    medical
                                    records for the purpose of verifying the veracity of this claim to effect efficient
                                    processing of benefit payment.
                                </i>
                            </label>
                        </div>
                        <div class="col-12" style="top:-10px;">
                            <label style="font-size: 13.5px;padding-left:10px;">
                                <i>
                                    I hereby hold PhilHealth or any of its officers, employees and/or representatives
                                    free from any legal liabilities relative to the herein-mentioned consent which I
                                    have
                                    voluntarily and willingly given in connection with this claim for reimbursement
                                    before PhilHealth.
                                </i>
                            </label>
                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-6 text-center text-sm">
                            <b class="h5   font-weight-bold ">
                                @if (!empty($AUTORIZE_REP_NAME1))
                                    {{ $AUTORIZE_REP_NAME1 }} &nbsp;
                                @else
                                    {{ $MEMBER_FIRST_NAME }} @if ($MEMBER_MIDDLE_NAME)
                                        {{ substr($MEMBER_MIDDLE_NAME, 0, 1) }}.
                                        @endif {{ $MEMBER_LAST_NAME }} @if ($MEMBER_EXTENSION)
                                            {{ $MEMBER_EXTENSION . '.' }}
                                        @endif
                                        &nbsp;

                                    @endif
                            </b>
                            <div class="top-line2">
                            </div>
                            <span> Signature Over
                                Printed Name of Member/Patient/Authorized Representative </span>
                        </div>
                        <div class="col-1">
                        </div>
                        <div class="col-4 text-center text-sm text-secondary">
                            <div class="row">
                                <div class="col-4 text-md">
                                    <div class="form-group mt-2 text-sm">
                                        <p class="float-right"> Date Signed</p>
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="form-group text-md " style="width:300px;">
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 5, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 6, 1) }}
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 8, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 9, 1) }}
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 0, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 1, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 2, 1) }}
                                        </div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($DATE_DISCHARGED, 3, 1) }}
                                        </div>
                                        <br>
                                        <p style="position: absolute;top:27px;">month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            year</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-7">
                                    <p class="text-sm " style="width: 400px;">
                                        If member/representative is unable to write,<br />
                                        put right thumbmark. Member/Representative <br />
                                        should be assisted by an HCI representative. <br />
                                        Check the appropriate box.
                                    <div style="margin-top:-20px;">
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Member&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Representative
                                    </div>
                                    </p>

                                </div>
                                <div class="col-5">
                                    <div class="blackbox   font-weight-bold2 w-75">
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6 float-right">
                                    <p style="padding-left:60px; width:400px" class="text-left text-sm">Relationship
                                        of the <br />
                                        representative to the member
                                    </p>
                                    <p style="padding-left:60px; width:400px;" class="mt-2 text-left text-sm">
                                        Reason for signing on behalf of <br />
                                        the member
                                    </p>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Spouse&nbsp;
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Child&nbsp;
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Parent<br />
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Sibling&nbsp;&nbsp;
                                        <div class="box   font-weight-bold" style="margin-left:1px;">&nbsp;</div>
                                        &nbsp;&nbsp;Others,
                                        Specify&nbsp;&nbsp;<br />
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Member is
                                        incapacitated&nbsp;&nbsp;<br />
                                        <div class="box   font-weight-bold">&nbsp;</div>
                                        &nbsp;&nbsp;Other reasons:&nbsp;&nbsp;
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center bgBlack text-white pt-1">
                    <b class="arial font-weight-bold" style="font-size: 19px">
                        PART IV - HEALTH CARE PROFESSIONAL INFORMATION
                    </b>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-5 text-sm">
                                    Accreditation No.
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 0, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 1, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 2, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 3, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 4, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 5, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 6, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 7, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 8, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 9, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 10, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_2_AN)
                                            {{ substr($HCP_2_AN, 11, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3 text-center">
                                    <b class="h6   font-weight-bold">
                                        {{ $HCP_2_NAME }}&nbsp;
                                    </b>
                                    <div class="top-line2"></div>
                                    <p class="text-xs">Signature Over Printed Name</p>
                                </div>
                                <div class="col-4 text-right">
                                    <div class="row" style="padding-right:10px;">
                                        <div class="col-3 text-md">
                                            <div class="form-group mt-2 text-sm  text-right" style="width:110px;">
                                                Date Signed
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <br>
                                                <p style="position: absolute;top:27px;right:0">month
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="always_input">
                            <div class="row">
                                <div class="col-5 text-sm">
                                    Accreditation No.
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 0, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 1, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 2, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 3, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 4, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 5, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 6, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 7, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 8, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 9, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 10, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_1_AN)
                                            {{ substr($HCP_1_AN, 11, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3 text-center">
                                    <b class="h6   font-weight-bold">
                                        <div style="position: absolute;width:330px;" class="text-left">
                                            {{ $HCP_1_NAME }}</div> &nbsp;
                                    </b>
                                    <div class="top-line2"></div>
                                    <p class="text-xs">Signature Over Printed Name</p>
                                </div>
                                <div class="col-4 text-right">
                                    <div class="row" style="padding-right:10px;">
                                        <div class="col-3 text-md">
                                            <div class="form-group mt-2 text-sm  text-right" style="width:110px;">
                                                Date Signed
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 5, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 6, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 8, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 9, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 0, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 1, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 2, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 3, 1) }}
                                                </div>
                                                <br>
                                                <p style="position: absolute;top:27px;right:0">month
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-5 text-sm">
                                    Accreditation No.
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 0, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 1, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 2, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 3, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 4, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 5, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 6, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 7, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 8, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 9, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 10, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                    <label class="px-1">&nbsp;-</label>
                                    <div class="box   font-weight-bold">
                                        @if ($HCP_3_AN)
                                            {{ substr($HCP_3_AN, 11, 1) }}
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3 text-center">
                                    <b class="h6   font-weight-bold">
                                        {{ $HCP_3_NAME }}&nbsp;
                                    </b>
                                    <div class="top-line2"></div>
                                    <p class="text-xs">Signature Over Printed Name</p>
                                </div>
                                <div class="col-4 text-right">
                                    <div class="row" style="padding-right:10px;">
                                        <div class="col-3 text-md">
                                            <div class="form-group mt-2 text-sm  text-right" style="width:110px;">
                                                Date Signed
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <div class="box   font-weight-bold">&nbsp;</div>
                                                <br>
                                                <p style="position: absolute;top:27px;right:0">month
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center bgBlack text-white pt-1">
                    <b class="arial font-weight-bold" style="font-size: 19px">
                        PART V - PROVIDER INFORMATION AND CERTIFICATION
                    </b>
                </div>
                <div class="col-12 mt-1">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2">
                                    <label style="position: absolute;">1. PhilHealth Benefits:</label>
                                </div>
                                <div class="col-2 text-right">
                                    <label class="text-sm">ICD 10 or RVS Code:</label>
                                </div>
                                <div class="col-4 text-left">

                                    <div class="row text-sm">
                                        <div class="col-3">
                                            <div style="position: absolute;width:300px;">
                                                1. First Case Rate:
                                            </div>

                                        </div>
                                        <div class="col-9">
                                            <div style="left:55px; position: absolute;" class="bottom-line2 w-75 ">
                                                <b class="h5   font-weight-bold text-sm ">
                                                    90935 </b>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-4 text-left">
                                    <div class="row text-sm">
                                        <div class="col-3">
                                            <div style="position: absolute;width:300px;">
                                                2. Second Case Rate:
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div style="left:55px; position: absolute;" class="bottom-line2 w-75 ">
                                                <b class="h5   font-weight-bold text-sm ">
                                                    &nbsp; </b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-sm text-center">
                            <label><i>
                                    I certify that services rendered were recorded in the patient’s chart and health
                                    care institution records and that the herein information given are true and correct.
                                </i>
                            </label>
                        </div>
                        <div class="col-12" style="top:-10px;">
                            <div class="row">
                                <div class="col-5 text-sm text-center">
                                    <b class="h5   font-weight-bold">
                                        {{ $HCI_NAME }}&nbsp; </b>
                                    <div class="top-line2"></div>
                                    Signature Over Printed Name of Authorized HCI Representative
                                </div>
                                <div class="col-3 text-sm text-center">
                                    <b class="h5   font-weight-bold">{{ $HCI_POSITION }}&nbsp;</b>
                                    <div class="top-line2"></div>
                                    Official Capacity/Designation
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-3 text-md">
                                            <div class="form-group mt-2 text-sm  text-left" style="width:100px;">
                                                Date Signed
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">

                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 5, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 6, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 8, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 9, 1) }}
                                                </div>
                                                <label class="px-1">&nbsp;-</label>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 0, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 1, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 2, 1) }}
                                                </div>
                                                <div class="box   font-weight-bold">
                                                    {{ substr($DATE_DISCHARGED, 3, 1) }}
                                                </div>
                                                <br>
                                                <p style="position: absolute;top:27px;">month
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    year</p>
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
    </div>
</div>
