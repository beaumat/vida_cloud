<div class="font-weight-light p-3">
    <div class="row">
        <div class="col-12 blackbox2">
            <div class="row" style="height: 190px;">
                <div class="col-3 text-center">
                    <img class="print-logo" style="width:220px;position:relative;top:60px;"
                        src="{{ asset('dist/logo/philhealth_logo.png') }}" />
                </div>
                <div class="col-6 text-center">
                    <div style="padding-top:15px;" class="text-center">
                        <div style="position:absolute;width:500px;top:30px;">
                            <i class="times-new-roman" style="left:50px;position:relative;font-size:16px;">Republic of
                                the Philippines</i><br />
                            <b style="font-size: 25px;position: absolute;width:1000px; left:-200px;top:20px;"
                                class="times-new-roman font-weight-bold">PHILIPPINE HEALTH INSURANCE CORPORATION</b>
                            <p class="times-new-roman"
                                style="padding: 0;position: absolute;top:55px;left:125px; line-height: 1.2;font-size: 15px;">
                                Citystate Centre 709 Shaw Boulevard, Pasig City <br />
                                Call Center (02) 441-7442 &#8226; Trunkline (02) 441-7444<br />
                                www.philhealth.gov.ph <br />
                                email: actioncenter@philhealth.gov.ph <br />
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class=" float-right text-sm text-center mt-1 text-xs" style="padding-right:2px;">
                        This form may be reproduced and
                        <br /> is NOT FOR SALE<br />
                        <b class="text-center noto font-weight-bold"
                            style="font-size: 65px;top:25px;position:absolute;right:60px;">CF-2</b><br />
                        <b class="text-center noto font-weight-bold"
                            style="font-size:18px; top:105px;position: absolute;right:65px;">
                            (Claim Form 2)</b> <br />
                        <p class="text-center text-sm" style="top:130px;position: absolute;right:40px;">Revised
                            September 2018</p>
                    </div>

                </div>
                <div class="col-12">
                    <div class="float-right mt-4">
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
            </div>

        </div>

        <div class="col-12 ubox2 font-weight-light" style="height: 105px;">
            <p style="font-size:14.4px; line-height: 1.3; " class="float-left mt-2">
                <b class="font-weight-bold">IMPORTANT REMINDERS:</b>
                <br />
                PLEASE WRITE IN CAPITAL <b class="font-weight-bold">LETTERS</b> AND <b
                    class="font-weight-bold">CHECK</b>
                THE APPROPRIATE BOXES.<br />
                This form together with other supporting documents should be filed within sixty (60) calendar days from
                date of discharge <br />
                All information, fields and trick boxes required in this form are necessary. Claim forms with incomplete
                information shall not be processed.<br />
                <b class="font-weight-bold">FALSE/INCORRECT INFORMATION OF MISINTERPRETATION SHALL BE SUBJECT TO
                    CRIMINAL, CIVIL OR
                    ADMINISTRATIVE
                    LIABILITIES.</b>
            </p>
        </div>
        <div class="col-12 text-center font-weight-light bgBlack">
            <b class="text-white arial font-weight-bold" style="font-size: 19px">
                PART I - HEALTH CARE INSTITUTION (HCI) INFORMATION
            </b>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class="form-group row">
                <div class="col-12 mb-1" style="height: 10px; ">
                    <label> 1. PhilHealth Accreditation Number (PAN) of Health Care Institution: </label>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 0, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 1, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 2, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 3, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 4, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 5, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 6, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 7, 1) }}
                    </div>
                    <div class="box   font-weight-bold">
                        {{ substr($ACCREDITATION_NO, 8, 1) }}
                    </div>

                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 mb-3 mt-2" style="height: 5px; ">
                    <label class="font-weight-bold"> 2. Name of Health Care Institution:
                        <span class="bottom-line2" style="width:77%; position:absolute;">
                            &nbsp;&nbsp;<b class="h4">{{ $NAME_OF_BUSINESS }}</b></span> </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 mb-4">
                    <label> <span class="font-weight-bold">3. Address:</span>
                        <span style="width:90%; position:absolute;">
                            <div class="row">
                                <div class="col-4">
                                    <div class="row px-1">
                                        <div class="col-12 bottom-line2 text-center h5">
                                            <div style="position:relative;width:400px;">{{ $BLDG_NAME_LOT_BLOCK }}
                                                {{ $STREET_SUB_VALL }}</div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <span class="font-weight-light"> Building Number and Street Name</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="row px-1">
                                        <div class="col-12 bottom-line2  text-center h5">
                                            {{ $BRGY_CITY_MUNI }}
                                        </div>
                                        <div class="col-12 text-center">
                                            <span class="font-weight-light"> City/Municipality</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row px-1">
                                        <div class="col-12 bottom-line2 text-center h5">
                                            {{ $PROVINCE }}
                                        </div>
                                        <div class="col-12 text-center">
                                            <span class="font-weight-light"> Province</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="row px-1">
                                        <div class="col-12 bottom-line2 text-center h5">
                                            {{ $ZIP_CODE }}
                                        </div>
                                        <div class="col-12 text-center">
                                            <span class="font-weight-light"> Zipcode</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-12 text-center bgBlack text-white pt-1">
            <b class="arial font-weight-bold" style="font-size: 19px">
                PART II - PATIENT CONFINEMENT INFORMATION
            </b>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class="row pt-2">
                <div class="col-2">
                    <label style='font-size:17px;'> 1. Name of Patient:</label>
                </div>
                <div class="col-10">
                    <div class="row">
                        <div class="col-3 text-center">
                            <b class="  font-weight-bold h5">
                                &nbsp;{{ $PATIENT_LASTNAME }}
                            </b>
                            <div class="w-100 top-line2 "></div>
                            Last Name
                        </div>
                        <div class="col-3 text-center">
                            <b class="  font-weight-bold h5">

                                &nbsp;{{ $PATIENT_FIRSTNAME }}

                            </b>
                            <div class="w-100 top-line2"></div>
                            First Name
                        </div>
                        <div class="col-2 text-center">
                            <b class="  font-weight-bold h5">
                                &nbsp;{{ $PATIENT_EXTENSION }}
                            </b>
                            <div class="w-100 top-line2"></div>
                            Name Extension <br />
                            (JR/SR/III)

                        </div>
                        <div class="col-4 text-center">
                            <b class="  font-weight-bold h5">
                                &nbsp;{{ $PATIENT_MIDDLENAME }}
                            </b>
                            <div class="w-100 top-line2"></div>
                            Middle Name<br />
                            (ex: DELA CRUZ JUAN JR SIPAG)

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label style='font-size:17px;'> 2. Was patient referred by another Health Care Institution
                        (HCI)?</label>
                </div>
                <div class="col-2">
                    <div style="position: absolute;left:0px; width:300px;">
                        <div class="form-group" style="margin-left:20px;">
                            <div class="box   font-weight-bold">
                                &#10004;
                            </div>
                            &nbsp;&nbsp;NO
                            <div class="box   font-weight-bold" style="margin-left:10px;">
                                {{-- &#10004; --}} &nbsp;
                            </div>
                            &nbsp;&nbsp;YES
                        </div>
                    </div>
                </div>
                <div class="col-10">
                    <div class="row">
                        <div class="col-4 text-center">
                            <b class="  font-weight-bold h6">&nbsp;</b>
                            <div class="w-100 top-line2 "></div>
                            Name of referring Health Care Institution
                        </div>
                        <div class="col-3 text-center">
                            <b class="  font-weight-bold h6">&nbsp;</b>
                            <div class="w-100 top-line2"></div>
                            Building Number and Street Name
                        </div>
                        <div class="col-2 text-center">
                            <b class="  font-weight-bold h6">&nbsp;</b>
                            <div class="w-100 top-line2"></div>
                            City/Municipality

                        </div>
                        <div class="col-2 text-center">
                            <b class=" font-weight-bold h6">&nbsp;</b>
                            <div class="w-100 top-line2"></div>
                            Province
                        </div>
                        <div class="col-1 text-center">
                            <b class=" font-weight-bold h6">&nbsp;</b>
                            <div class="w-100 top-line2"></div>
                            Zipcode
                        </div>
                    </div>
                </div>



            </div>
            <div class="row mt-2">
                <div class="col-2">
                    <label style='font-size:17px;width:200px;'> 3. Confinement Period:</label>
                </div>
                <div class="col-10 ">
                    <div class="row mt-1" style="margin-left:20px; margin-bottom:-16px;">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-5 text-md">
                                    <div class="form-group text-md">
                                        <p class="font-weight-normal" style="width: 300px;">
                                            a. Date Admitted:
                                        </p>
                                    </div>
                                </div>
                                <div class="col-7 text-left">
                                    <div class="form-group text-md" style="width:300px;">
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 5, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">

                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 6, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 8, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 9, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 0, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 1, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 2, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_ADMITTED)
                                                {{ substr($DATE_ADMITTED, 3, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <br>
                                        <p style="position: absolute;top:27px;" class="text-xs"> &nbsp; &nbsp;month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp; &nbsp;&nbsp;
                                            year</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-6">
                            <div class="row mt-1">
                                <div class="col-4 text-md">
                                    <div class="form-group">
                                        <p class="font-weight-normal" style="width: 300px;">
                                            b. Time Admitted:
                                        </p>
                                    </div>
                                </div>
                                <div class="col-8 text-left ">
                                    <div class="form-group text-md" style="width:300px;">
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED)
                                                {{ substr($TIME_ADMITTED, 0, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED)
                                                {{ substr($TIME_ADMITTED, 1, 1) }}
                                            @else
                                                &nbsp;
                                            @endif

                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED)
                                                {{ substr($TIME_ADMITTED, 3, 1) }}
                                            @else
                                                &nbsp;
                                            @endif

                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED)
                                                {{ substr($TIME_ADMITTED, 4, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;</label>


                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED && substr($TIME_ADMITTED, 6, 1) == 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        AM
                                        &nbsp;
                                        <div class="box   font-weight-bold">

                                            @if ($TIME_ADMITTED && substr($TIME_ADMITTED, 6, 1) != 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        PM

                                        <br>
                                        <p style="position: absolute;top:27px;" class="text-xs">
                                            &nbsp;&nbsp;&nbsp;hour
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mn
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-left:20px;height:30px;">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-5 text-md">
                                    <div class="form-group ">
                                        <p class="font-weight-normal" style="width: 300px;">
                                            c. Date Discharge:
                                        </p>
                                    </div>
                                </div>
                                <div class="col-7 text-left">
                                    <div class="form-group text-md" style="width:300px;">
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 5, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">

                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 6, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 8, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 9, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 0, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 1, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 2, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 3, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <br>
                                        <p style="position: absolute;top:27px;" class="text-xs"> &nbsp; &nbsp;month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp; &nbsp;&nbsp;
                                            year</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-6">
                            <div class="row mt-1">
                                <div class="col-4 text-md">
                                    <div class="form-group">
                                        <p class="font-weight-normal" style="width: 300px;">
                                            d. Time Discharge:
                                        </p>
                                    </div>
                                </div>
                                <div class="col-8 text-left ">
                                    <div class="form-group text-md" style="width:300px;">
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_DISCHARGED)
                                                {{ substr($TIME_DISCHARGED, 0, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_DISCHARGED)
                                                {{ substr($TIME_DISCHARGED, 1, 1) }}
                                            @else
                                                &nbsp;
                                            @endif

                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_DISCHARGED)
                                                {{ substr($TIME_DISCHARGED, 3, 1) }}
                                            @else
                                                &nbsp;
                                            @endif

                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_DISCHARGED)
                                                {{ substr($TIME_DISCHARGED, 4, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;</label>


                                        <div class="box   font-weight-bold">
                                            @if ($TIME_DISCHARGED && substr($TIME_DISCHARGED, 6, 1) == 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        AM
                                        &nbsp;
                                        <div class="box   font-weight-bold">

                                            @if ($TIME_DISCHARGED && substr($TIME_DISCHARGED, 6, 1) != 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        PM

                                        <br>
                                        <p style="position: absolute;top:27px;" class="text-xs">
                                            &nbsp;&nbsp;&nbsp;hour
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mn
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-left">
                    <label style='font-size:17px;width:180px;'>4. Patient Disposition:</label><span
                        class="">(select only 1)</span>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-4">
                                    <div class="box   font-weight-bold">
                                        &#10004;
                                    </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    a. Improved
                                </div>
                                <div class="col-8">
                                    <div style="position:absolute; right:88px;">
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        e. Expired &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <div style="position:relative;left:-80px;">
                                <div class="row">
                                    <div class='col-6'>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>

                                        <p style="position:absolute;top:30px;" class="text-xs"> &nbsp;
                                            &nbsp;month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp; &nbsp;&nbsp;
                                            year</p>
                                    </div>

                                    <div class='col-6'>
                                        <span>Time:</span>

                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div> <label class="px-1">&nbsp;-</label>

                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>
                                        &nbsp; &nbsp; &nbsp; &nbsp;
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>


                                        &nbsp;
                                        AM
                                        &nbsp;
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>&nbsp;
                                        PM
                                        <p style="position:absolute;top:30px;right:220px;" class="text-xs">
                                            &nbsp;&nbsp;&nbsp;hour
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mn
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class='col-5'>
                            <div class="row">
                                <div class="col-4">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    b. Recovered
                                </div>
                                <div class="col-8 ">
                                    <div style="position:absolute; right:0px;">
                                        <div class="box   font-weight-bold">
                                            &nbsp;
                                        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        f. Transferred/Referred &nbsp;&nbsp;
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class='col-7 text-center'>
                            <div class="bottom-line2">
                                &nbsp;
                            </div>
                            <div class="text-sm text-center"> Name of Referral Health Care Institution </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-5">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            c. Home/Discharged Against Medical Advise
                        </div>
                        <div class="col-7">
                            <div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="w-100 bottom-line2">&nbsp;</div>
                                        <div class="w-100 text-center text-sm"> Building Number and Street Name</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="w-100 bottom-line2">&nbsp;</div>
                                        <div class="w-100 text-center text-sm"> City/Municipality</div>
                                    </div>
                                    <div class="col-3">
                                        <div class="w-100 bottom-line2">&nbsp;</div>
                                        <div class="w-100 text-center text-sm"> Province</div>
                                    </div>
                                    <div class="col-1">
                                        <div class="w-100 bottom-line2">&nbsp;</div>
                                        <div class="w-100 text-center text-sm"> Zipcode</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-12'>
                    <div class="row">
                        <div class="col-3">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            d. Absconded
                        </div>
                        <div class="col-8">
                            <div class="row">
                                <div class="col-4 text-right"> <span> Reason/s for referral/transfer:</span> </div>
                                <div class="col-7 bottom-line2">

                                    &nbsp;
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2 ">
                    <label class="mt-1" style='font-size:17px;width:200px;'> 5. Type of Accomodation:</label>
                </div>
                <div class="col-10">

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="box   font-weight-bold">
                        &#10004;
                    </div> &nbsp;&nbsp;
                    Private
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <div class="box   font-weight-bold">
                        &nbsp;
                    </div> &nbsp;&nbsp;
                    Non-Private (Charity/Service)
                </div>
            </div>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class="form-group">
                <label style='font-size:17px;width:400px;'> 6. Admission Diagnosis/es:</label>
                <div class='row'>
                    <div class='col-2'>

                    </div>
                    <div class="col-6">
                        <label class="h5"> {{ $HISTORY_OF_PRESENT_ILLNESS }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class="form-group" style='height:175px;'>
                <label style='font-size:17px;'>7. Discharge Diagnosis/es</label>
                <span class="text-sm"> (Use additional CF2 if necessary):</span>
                <div class="row" style="top:-5px;position:relative">
                    <div class="col-2 text-center">Diagnosis </div>
                    <div class="col-2 text-center"> ICD-10 Code/s </div>
                    <div class="col-3 text-center">Related Procedure/s (if there’s any) </div>
                    <div class="col-1 text-center">RVS Code </div>
                    <div class="col-2 text-center">Date of Procedure </div>
                    <div class="col-2 text-center text-left"> <span style="position:absolute;width:230px;left:-20px;">
                            Laterality (check applicable box) </span> </div>
                </div>
                <div class="row" style="position:absolute;width:100%;height:30px;top:50px;">
                    <div class="col-2 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                a.
                            </div>
                            <div class='col-11 text-left'>
                                <div class="bottom-line2">
                                    <span class="text-sm w-100 font-weight-bold">
                                        {{ $DEFAULT_SEC_TO }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2">
                            <span class="text-sm w-100 font-weight-bold">
                                &nbsp;
                                @if (!empty($treatdata[0]))
                                    <span class="text-sm w-100  font-weight-bold">
                                        {{ $ICD_CODE }}
                                        <span class="text-sm w-100  font-weight-bold">
                                @endif
                            </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                <span>i.</span>
                            </div>
                            <div class='col-11'>
                                <div class="bottom-line2">

                                    &nbsp;
                                    @if (!empty($treatdata[0]))
                                        <span class="text-sm w-100  font-weight-bold">
                                            {{ $RELATED_PROCEDURE }}
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <div class="bottom-line2">
                            &nbsp;
                            @if (!empty($treatdata[0]))
                                <span class="text-sm w-100  font-weight-bold">
                                    {{ $FIRST_CASE_RATE }}
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2">
                            &nbsp; @if (!empty($treatdata[0]))
                                <span class="text-sm w-100 font-weight-bold">
                                    {{ $treatdata[0] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div style="position:absolute;width:300px;left:-50px;">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            left &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            right &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            both
                        </div>
                    </div>
                </div>
                <div class="row " style="position:absolute;width:100%;height:30px;top:75px;">
                    <div class="col-2 text-center">
                        <div class='row'>
                            <div class='col-1 text-center'>
                                &nbsp;
                            </div>
                            <div class='col-11 text-center'>
                                <div class="bottom-line2 " style="height:25px;">
                                    <div class="text-sm w-100 font-weight-bold text-left">
                                        <div style="position:absolute;width:500px;"> {{ $FINAL_DIAGNOSIS }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                <span>ii.</span>
                            </div>
                            <div class='col-11'>
                                <div class="bottom-line2">
                                    &nbsp;
                                    @if (!empty($treatdata[1]))
                                        <span class="text-sm w-100  font-weight-bold">
                                            {{ $RELATED_PROCEDURE }}
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <div class="bottom-line2"> &nbsp;
                            @if (!empty($treatdata[1]))
                                <span class="text-sm w-100  font-weight-bold">
                                    {{ $FIRST_CASE_RATE }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; @if (!empty($treatdata[1]))
                                <span class="text-sm w-100 font-weight-bold">
                                    {{ $treatdata[1] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div style="position:absolute;width:300px;left:-50px;">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            left &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            right &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            both
                        </div>
                    </div>
                </div>
                <div class="row " style="position:absolute;width:100%;height:30px;top:95px;">
                    <div class="col-2 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                &nbsp;
                            </div>
                            <div class='col-11 text-center'>
                                <div class="bottom-line2"> &nbsp; </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                <span>iii.</span>
                            </div>
                            <div class='col-11'>
                                <div class="bottom-line2">
                                    &nbsp;
                                    @if (!empty($treatdata[2]))
                                        <span class="text-sm w-100  font-weight-bold">
                                            {{ $RELATED_PROCEDURE }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <div class="bottom-line2"> &nbsp;
                            @if (!empty($treatdata[2]))
                                <span class="text-sm w-100  font-weight-bold">
                                    {{ $FIRST_CASE_RATE }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; @if (!empty($treatdata[2]))
                                <span class="text-sm w-100 font-weight-bold">
                                    {{ $treatdata[2] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div style="position:absolute;width:300px;left:-50px;">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            left &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            right &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            both
                        </div>
                    </div>
                </div>
                <div class="row " style="position:absolute;width:100%;height:30px;top:115px;">
                    <div class="col-2 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                b.
                            </div>
                            <div class='col-11 text-center'>
                                <div class="bottom-line2"> &nbsp; </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                <span>i.</span>
                            </div>
                            <div class='col-11'>
                                <div class="bottom-line2">
                                    &nbsp;
                                    @if (!empty($treatdata[3]))
                                        <span class="text-sm w-100  font-weight-bold">
                                            {{ $RELATED_PROCEDURE }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <div class="bottom-line2"> &nbsp;
                            @if (!empty($treatdata[3]))
                                <span class="text-sm w-100  font-weight-bold">
                                    {{ $FIRST_CASE_RATE }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; @if (!empty($treatdata[3]))
                                <span class="text-sm w-100 font-weight-bold">
                                    {{ $treatdata[3] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div style="position:absolute;width:300px;left:-50px;">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            left &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            right &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            both
                        </div>
                    </div>
                </div>
                <div class="row " style="position:absolute;width:100%;height:30px;top:135px;">
                    <div class="col-2 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                &nbsp;
                            </div>
                            <div class='col-11 text-center'>
                                <div class="bottom-line2"> &nbsp; </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                <span>ii.</span>
                            </div>
                            <div class='col-11'>
                                <div class="bottom-line2">
                                    &nbsp;
                                    @if (!empty($treatdata[4]))
                                        <span class="text-sm w-100  font-weight-bold">
                                            {{ $RELATED_PROCEDURE }}
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <div class="bottom-line2"> &nbsp;
                            @if (!empty($treatdata[4]))
                                <span class="text-sm w-100  font-weight-bold">
                                    {{ $FIRST_CASE_RATE }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; @if (!empty($treatdata[4]))
                                <span class="text-sm w-100 font-weight-bold">
                                    {{ $treatdata[4] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div style="position:absolute;width:300px;left:-50px;">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            left &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            right &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            both
                        </div>
                    </div>
                </div>
                <div class="row " style="position:absolute;width:100%;height:30px;top:155px;">
                    <div class="col-2 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                &nbsp;
                            </div>
                            <div class='col-11 text-center'>
                                <div class="bottom-line2"> &nbsp; </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class='row'>
                            <div class='col-1 text-left'>
                                <span>iii.</span>
                            </div>
                            <div class='col-11'>
                                <div class="bottom-line2">
                                    &nbsp;
                                    @if (!empty($treatdata[5]))
                                        <span class="text-sm w-100  font-weight-bold">
                                            {{ $RELATED_PROCEDURE }}
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <div class="bottom-line2">
                            &nbsp;
                            @if (!empty($treatdata[5]))
                                <span class="text-sm w-100  font-weight-bold">
                                    {{ $FIRST_CASE_RATE }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div class="bottom-line2"> &nbsp; @if (!empty($treatdata[5]))
                                <span class="text-sm w-100 font-weight-bold">
                                    {{ $treatdata[5] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <div style="position:absolute;width:300px;left:-50px;">
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            left &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            right &nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;
                            both
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <label style='font-size:17px;width:400px;height:20px;'> 8. Special Considerations:</label>
        </div>
        <div class="col-12 ubox2 font-weight-light ">
            <div class="row">
                <div class="col-12">
                    <span>
                        a. For the following repetitive procedures, check box that applies and enumerate the
                        procedure/sessions dates [mm-dd-yyyy]. For chemotherapy, see guidelines.
                    </span>
                </div>
                <div class="col-12">
                    <div class="row px-1 ">
                        <div class='col-6'>
                            <div class='row'>
                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &#10004;
                                    </div> &nbsp;&nbsp;
                                    Hemodialysis
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        <b class=" font-weight-bold">
                                            {{ $allDate }}
                                        </b>
                                    </div>
                                </div>

                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;
                                    Peritoneal Dialysis
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>


                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;
                                    Radiotherapy (LINAC)
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>


                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;
                                    Radiotherapy (COBALT)
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>



                            </div>
                        </div>


                        <div class='col-6'>
                            <div class='row'>
                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;
                                    Blood Transfusion
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>

                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;
                                    Brachytherapy
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>


                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;
                                    Chemotherapy
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>


                                <div class="col-5">
                                    <div class="box   font-weight-bold">
                                        &nbsp;
                                    </div> &nbsp;&nbsp;
                                    Simple Debridement
                                </div>
                                <div class='col-7'>
                                    <div class="w-100 bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-1">
                    <div class="row">
                        <div class="col-3">
                            <span>
                                b. For Z-Benefit Package
                            </span>
                        </div>
                        <div class="col-7">
                            <span class='font-weight-bold'>
                                Z-Benefit Package Code: ___________________________________
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <span>
                        c. For MCP Package (enumerate four dates [mm-dd-year] of pre-natal check-ups)
                    </span>
                    <div class="form-group ">
                        <div class="row mx-2">
                            <div class="col-3">
                                <div class="w-100 bottom-line2 px-4">
                                    <span style="left:-5px;position:absolute;">1.</span> &nbsp;
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="w-100 bottom-line2 px-4">
                                    <span style="left:-5px;position:absolute;">2.</span> &nbsp;
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="w-100 bottom-line2 px-4">
                                    <span style="left:-5px;position:absolute;">3.</span> &nbsp;
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="w-100 bottom-line2 px-4">
                                    <span style="left:-5px;position:absolute;">4.</span> &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row  w-100 mb-2 mt-2" style="top:-15px;position:relative;height:8px">
                        <div class="col-2 ">
                            d. For TB DOTS Package:
                        </div>
                        <div class="col-10">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;&nbsp;
                            Intensive Phase
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="box   font-weight-bold">
                                &nbsp;
                            </div> &nbsp;&nbsp;
                            Maintenance Phase
                        </div>
                    </div>

                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-md">e. For Animal Bite Package (write the dates [mm-dd-year] when the
                                following doses of
                                vaccine were given)</span>
                        </div>
                        <div class="col-4">
                            <span style="margin-left:50px;width:480px;position:absolute;right:0"
                                class="font-weight-bold bottom-line2 top-line2 left-line2 right-line2 p-1 text-md">
                                Note: Anti Rabies Vaccine (ARV), Rabies Immunoglobulin (RIG)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row pt-3 pb-2">
                        <div class="col-2">
                            <div class="row">
                                <div class="col-6 text-right">
                                    <b class="font-weight-bold">Day 0 ARV</b>
                                </div>
                                <div class="col-6">
                                    <div class="bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">

                            <div class="row">
                                <div class="col-6 text-right">
                                    <b class="font-weight-bold">Day 3 ARV</b>
                                </div>
                                <div class="col-6">
                                    <div class="bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">

                            <div class="row">
                                <div class="col-6 text-right">
                                    <b class="font-weight-bold">Day 7 ARV</b>
                                </div>
                                <div class="col-6">
                                    <div class="bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-4 text-right">
                                    <b class="font-weight-bold">RIG</b>
                                </div>
                                <div class="col-8">
                                    <div class="bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-6 text-right">
                                    <b class="font-weight-bold">Others (Specify)</b>
                                </div>
                                <div class="col-6 ">
                                    <div class="bottom-line2">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class='col-2'>
                            <span style="position:absolute;width:300px;">f. For Newborn Care Package</span>
                        </div>
                        <div class="col-2">
                            <div class="box   font-weight-bold "> &nbsp; </div> &nbsp;&nbsp;
                            <span style="position:absolute;width:300px; top:5px;">Essential Newborn Care</span>
                        </div>
                        <div class="col-3">
                            <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;&nbsp;
                            <span style="position:absolute;width:300px;top:5px;">Newborn Hearing Screening Test</span>

                        </div>
                        <div class="col-2">
                            <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                            <span style="position:absolute;width:300px;top:5px;">Newborn Screening Test</span>

                        </div>
                        <div class="col-3">
                            <div class="bottom-line2 top-line2 left-line2 right-line2 p-1"
                                style="width:260px;position:absolute;right:0px;">
                                For Newborn
                                Screening,<br /> <i>please attach NBS
                                    Filter Sitcker here </i>
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <div class="bottom-line2 top-line2 left-line2 right-line2 p-1"
                                style="top:0px;position:absolute">
                                <span class="font-weight-bold"> For Essential Newborn Care, </span>(check applicable
                                boxes)
                            </div>
                            <br />
                        </div>
                        <div class="col-12 mt-3">
                            <div class="row">
                                <div class="col-3">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute"> Immediate drying of newborn</span>
                                </div>
                                <div class="col-2">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">Timely cord clamping</span>
                                </div>
                                <div class="col-2">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">Weighing of the newborn</span>
                                </div>
                                <div class="col-2">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">BCG vaccination</span>
                                </div>
                                <div class="col-2">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">Hepatitis B vaccination</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-3">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">Early skin-to-skin contact</span>
                                </div>
                                <div class="col-2">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">Eye Prophylaxis</span>
                                </div>

                                <div class="col-2">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">Vitamin K administration</span>
                                </div>
                                <div class="col-5">
                                    <div class="box   font-weight-bold"> &nbsp; </div> &nbsp;
                                    <span style="position:absolute">Non-separation of mother/baby for early
                                        breastfeeding initiation</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 pb-1">
                            <div class="row">
                                <div class="col-4">
                                    g. For Outpatient HIV/AIDS Treatment Package
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-5 text-right">
                                            <b class='font-weight-bold'> Laboratory Number:</b>
                                        </div>
                                        <div class="col-7">
                                            <div class="bottom-line2">
                                                &nbsp;
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
        <div class="col-12 ubox2 font-weight-light">
            <label style='font-size:17px;width:400px;height:20px;'> 9. PhilHealth Benefits:</label>
            <div class="row  pb-1">

                <div class="col-6">
                    <div class="row">
                        <div class="col-8 text-right">
                            <b class="font-weight-bold">ICD 10 or RVS Code:</b> &nbsp;&nbsp;&nbsp; a. First Case Rate
                        </div>
                        <div class="col-3">
                            <div class="bottom-line2 text-center">
                                <span class="font-weight-bold">{{ $FIRST_CASE_RATE }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-8 text-right">
                            2. Second Case Rate
                        </div>
                        <div class="col-4">
                            <div class="bottom-line2">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
