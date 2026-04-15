<div class="font-weight-light verdana p-3">
    <div class="row">
        <div class="col-12 " style="position: relative; top:30px;">
            <div class="row">
                <div class="col-3 text-center">
                    <img class="print-logo" style="width:270px;position: static;"
                        src="{{ asset('dist/logo/philhealth_logo.png') }}" />
                </div>

                <div class="col-9 text-right float-right">
                    <div style="position: static;width:420px;font-size:12px;" class="float-right">
                        <p style="line-height: 1.3;" class="text-center">
                            <b class="font-weight-bold text-sm" style="top:10px;position: relative;"> This form may be
                                reproduced and is NOT FOR SALE</b><br />
                            <b class="text-center verdana font-weight-bold"
                                style="font-size: 35px;top:-25px;">CF4</b><br />
                            <b class="font-weight-bold text-sm">(Claim Form 4)</b> <br />
                            <b class="font-weight-bold text-sm">February 2020</b>
                        </p>
                    </div>
                </div>


            </div>
        </div>
        <div class="col-12">
            <div class="row" style="top:15px;position: relative;">
                <div id='important' class="col-6  font-weight-light">
                    <p style="font-size:12px; line-height: 1.3; width:1000px;" class="float-left mt-2">
                        <b class="font-weight-bold">IMPORTANT REMINDERS:</b>
                        <br />
                        PLEASE FILL OUT APPROPRIATE FIELDS. WRITE IN CAPITAL LETTERS AND CHECK THE APPROPRIATE
                        BOXES.<br />
                        This form, together with other supporting documents, should be filed within <b
                            class="font-weight-bold">sixty (60) calendar days</b> from date of discharge. <br />
                        All information, fields and tick boxes in this form are necessary. <b
                            class="font-weight-bold">Claim forms Wth incon-pete inlbnmtion shall not be processed.</b>
                        <br />
                        <b class="font-weight-bold">FALSE/INCORRECT INFORMATION OF MISINTERPRETATION SHALL BE SUBJECT TO
                            CRIMINAL, CIVIL OR ADMINISTRATIVE LIABILITIES.</b>
                    </p>
                </div>
                <div class="col-6">
                    <div class="float-right ">
                        <div class="row" style="position: absolute;right:10px;width:400px;">
                            <p class="font-weight-bold text-sm"> Series#&nbsp;</p>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                            <div class="box2   font-weight-bold">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id='part_id' class="col-12 text-center left-line2 right-line2 bottom-line2 top-line2">
            <b class="verdana font-weight-bold" style="font-size: 14.4">
                I. HEALTH CARE INSTITUTION (HCI) INFORMATION
            </b>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class="row">

                <div class="col-12 bottom-line2" style="line-height: 1.2;">
                    <div class="row">

                        <div id="name_of_member" class="col-8 ">
                            <label class="text-xs"> 1. Name of HCI</label>
                            <h4 class="p-0 m-0 times-new-roman">&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $NAME_OF_BUSINESS }}</b>
                                &nbsp;</h4>
                        </div>

                        <div id="Accreditation" class="col-4 left-line2">
                            <div class="pl-0">
                                <label class="text-xs"> 2. Accreditation Number </label>
                                <h4 class="p-0 m-0 times-new-roman">
                                    &nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $ACCREDITATION_NO }}</b> &nbsp;</h4>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-12 " style="height: 60px;">
                    <div class="row">
                        <div id="name_of_member" class="col-12">
                            <label class="text-xs"> 3. Address of HCI</label>
                            <h5 class="p-0 m-0 times-new-roman">&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $BLDG_NAME_LOT_BLOCK }}
                                    {{ $STREET_SUB_VALL }} {{ $BRGY_CITY_MUNI }} {{ $PROVINCE }}
                                    {{ $ZIP_CODE }}</b> &nbsp;</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center top-line2 bottom-line2 text-xs">
                    <div class="row">
                        <div class="col-3">
                            Bldg No. and Name/ Lot/Block
                        </div>
                        <div class="col-2  left-line2">
                            Street/Subdivision/Village
                        </div>
                        <div class="col-3 left-line2">
                            Barangay/City/ Municipality
                        </div>
                        <div class="col-2 left-line2">
                            Province
                        </div>
                        <div class="col left-line2">
                            Zipcode
                        </div>
                    </div>
                </div>
                <div id='part_id' class="col-12 text-center bottom-line2">
                    <b class="verdana font-weight-bold" style="font-size: 14.4">
                        II. PATIENT'S DATA
                    </b>
                </div>
                <div class="col-12 text-xs">
                    <div class="row">
                        <div class="col-9 bottom-line2">
                            <label> 1. Name of Patient</label><br />
                            <h5 class="text-uppercase times-new-roman">
                                <b>
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            {{ $PATIENT_LASTNAME }}
                                            @if ($PATIENT_EXTENSION != '')
                                                &nbsp;{{ $PATIENT_EXTENSION }}
                                            @endif
                                        </div>
                                        <div class="col-4 text-center">
                                            {{ $PATIENT_FIRSTNAME }}
                                        </div>
                                        <div class="col-4 text-center">

                                            {{ $PATIENT_MIDDLENAME }}

                                        </div>
                                    </div>

                                </b>
                            </h5>

                        </div>
                        <div class="col-3 left-line2 bottom-line2">
                            <label> 2. PIN</label> <br />
                            <h5 class="text-uppercase times-new-roman"> <b class="">
                                    {{ substr($PIN_DEPENDENT, 0, 1) }}
                                    {{ substr($PIN_DEPENDENT, 1, 1) }} -
                                    {{ substr($PIN_DEPENDENT, 2, 1) }}
                                    {{ substr($PIN_DEPENDENT, 3, 1) }}
                                    {{ substr($PIN_DEPENDENT, 4, 1) }}
                                    {{ substr($PIN_DEPENDENT, 5, 1) }}
                                    {{ substr($PIN_DEPENDENT, 6, 1) }}
                                    {{ substr($PIN_DEPENDENT, 7, 1) }}
                                    {{ substr($PIN_DEPENDENT, 8, 1) }}
                                    {{ substr($PIN_DEPENDENT, 9, 1) }}
                                    {{ substr($PIN_DEPENDENT, 10, 1) }} -
                                    {{ substr($PIN_DEPENDENT, 11, 1) }}
                                </b></h5>

                        </div>
                    </div>
                </div>
                <div class="col-12 text-xs">
                    <div class="row">
                        <div class="col-9">
                            <div class="row text-xs text-center ">
                                <div class="col-4 bottom-line2">
                                    Last Name
                                </div>
                                <div class="col-4 left-line2 bottom-line2">
                                    First Name
                                </div>
                                <div class="col-4 left-line2 bottom-line2">
                                    Middle Name
                                </div>
                            </div>
                            <div class="col-12">
                                <label> 5. Chief Complaint</label>

                                @if ($CF4_COMPLAINT)
                                    <h4 class="p-0 m-0 times-new-roman">
                                        &nbsp;&nbsp;<b>{{ $CF4_COMPLAINT }}</b> &nbsp;</h4>
                                @else
                                    <h4 class="p-0 m-0 times-new-roman">
                                        &nbsp;&nbsp;<b>{{ $CHIEF_OF_COMPLAINT }}</b> &nbsp;</h4>
                                @endif

                            </div>
                        </div>
                        <div class="col-3 left-line2 ">
                            <div class="row">
                                <div class="col-12 bottom-line2 pb-3" style="height: 30px;">
                                    <label> 3. AGE</label> <br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b style="position: absolute;top:0px;left:60px;"
                                        class="text-uppercase times-new-roman p-0 m-0 text-lg">
                                        {{ $AGE }}
                                    </b>

                                </div>
                                <div class="col-12 pb-3">
                                    <label style="margin-right: 20px;"> 4. SEX</label>
                                    <div class="box   font-weight-bold">
                                        @if ($PATIENT_GENDER == 0)
                                            &#10004;
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>&nbsp; Male
                                    <div class="box   font-weight-bold">
                                        @if ($PATIENT_GENDER == 1)
                                            &#10004;
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>&nbsp;
                                    Female
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 top-line2 text-xs">
                    <div class="row">
                        <div class="col-9">
                            <div class="row">
                                <div class="col-6 right-line2" style="height: 200px;">
                                    <label> 6. Admitting Diagnosis</label>
                                    @if ($CF4_AD_NOTES)
                                        <h5 class="p-0 m-0 times-new-roman">
                                            &nbsp;{{ $CF4_AD_NOTES }}</h5>
                                    @else
                                        <h4 class="p-0 m-0 times-new-roman">
                                            &nbsp;&nbsp;<b>{{ $ADMITTING_DIAGNOSIS }}</b> &nbsp;</h4>
                                    @endif


                                </div>
                                <div class="col-6">
                                    <label> 7. Discharge Diagnosis</label><br />
                                    @if ($CF4_DD_NOTES)
                                        <h6 class="p-0 m-0 times-new-roman">
                                            &nbsp;{{ $CF4_DD_NOTES }}</h6>
                                    @else
                                        <h4 class="p-0 m-0 times-new-roman ">
                                            &nbsp;&nbsp;<b>{{ $FINAL_DIAGNOSIS }}</b> &nbsp;</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-3 left-line2">
                            <div class="row">
                                <div class="col-12 bottom-line2 pb-3">
                                    <label style="top:0; position: absolute;"> 8. a. 1st Case Rate Code </label> <label
                                        style="position: relative; top:2px;left:170px;" class="h4"> <b
                                            class="times-new-roman " style="margin-top: 10px;">
                                            {{ $FIRST_CASE_RATE }}</b></label>

                                </div>
                                <div class="col-12 pb-3">
                                    <label> 8. b. 2nd Case Rate Code </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 top-line2 text-xs">
                    <div class="row">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-5 text-md">
                                    <div class="form-group mt-2 text-sm">
                                        <p class="font-weight-bold" style="width: 300px;font-size:13.5px;"> 9. a. Date
                                            Admitted: </p>
                                    </div>
                                </div>
                                <div class="col-7 text-left">
                                    <div class="form-group text-md">
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
                                        <p style="position: absolute;top:27px;" class="text-xs">month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            year</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-5 text-md">
                                    <div class="form-group mt-2 text-sm">
                                        <p class="font-weight-bold" style="width: 300px;font-size:13.5px;"> 9. b. Time
                                            Admitted:</p>
                                    </div>
                                </div>
                                <div class="col-7 text-left ">
                                    <div class="form-group text-md">
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_ADMITTED, 0, 1) }}</div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_ADMITTED, 1, 1) }}</div><label
                                            class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_ADMITTED, 3, 1) }}</div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_ADMITTED, 4, 1) }}</div><label
                                            class="px-1">&nbsp;</label>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED && substr($TIME_ADMITTED, 6, 1) == 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        AM
                                        <div class="box   font-weight-bold">

                                            @if ($TIME_ADMITTED && substr($TIME_ADMITTED, 6, 1) != 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        PM

                                        <br>
                                        <p style="position: absolute;top:27px;" class="text-xs">&nbsp;&nbsp;hour
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mn
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 top-line2 text-xs">
                    <div class="row">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-5 text-md">
                                    <div class="form-group mt-2 text-sm">
                                        <p class="font-weight-bold" style="width: 300px;font-size:13.5px;"> 10. a.
                                            Date Discharged:</p>
                                    </div>
                                </div>
                                <div class="col-7 text-left">
                                    <div class="form-group text-md">
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
                                        <p style="position: absolute;top:27px;" class="text-xs">month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            year</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-5 text-md">
                                    <div class="form-group mt-2 text-sm">
                                        <p class="font-weight-bold " style="width: 300px;font-size:13.5px;"> 10. b.
                                            Time Discharged:</p>
                                    </div>
                                </div>
                                <div class="col-7 text-left">
                                    <div class="form-group text-md">
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_DISCHARGED, 0, 1) }}</div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_DISCHARGED, 1, 1) }}</div><label
                                            class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_DISCHARGED, 3, 1) }}</div>
                                        <div class="box   font-weight-bold">
                                            {{ substr($TIME_DISCHARGED, 4, 1) }}</div><label
                                            class="px-1">&nbsp;</label>
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED && substr($TIME_DISCHARGED, 6, 1) == 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        AM
                                        <div class="box   font-weight-bold">
                                            @if ($TIME_ADMITTED && substr($TIME_DISCHARGED, 6, 1) != 'A')
                                                &#10004;
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>&nbsp;
                                        PM

                                        <br>
                                        <p style="position: absolute;top:27px;" class="text-xs">&nbsp;&nbsp;hour
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mn
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id='part_id' class="col-12 text-center bottom-line2 top-line2">
                    <b class="verdana font-weight-bold" style="font-size: 14.4">
                        III. REASON FOR ADMISSION
                    </b>
                </div>
                <div class="col-12 bottom-line2" style="height: 150px;">
                    <label class="text-xs">1. History of Present Illness:</label>

                    @if ($CF4_HPI)
                        <br />
                        <h5 class="p-0 m-0 times-new-roman">
                            &nbsp;&nbsp;<b>{{ $CF4_HPI }}</b> &nbsp;</h5>
                    @else
                        <br />
                        <br />
                        <h4 class="p-0 m-0 times-new-roman">
                            &nbsp;&nbsp;<b>{{ $HISTORY_OF_PRESENT_ILLNESS }}</b> &nbsp;</h4>
                    @endif


                </div>
                <div class="col-12 bottom-line2" style="height: 150px;">
                    <label class="text-xs">2.a Pertinent Past Medical History: </label>

                    @if ($CF4_PPMH)
                        <h6 class="p-0 m-0 times-new-roman">
                            &nbsp;&nbsp;<b>{{ $CF4_PPMH }}</b> &nbsp;</h6>
                    @else
                        <h4 class="p-0 m-0 times-new-roman">
                            &nbsp;&nbsp;<b>{{ $FINAL_DIAGNOSIS }}</b> &nbsp;</h4>
                    @endif


                    <div class="" style="bottom: -10px; position: absolute;">
                        <label class="text-xs">2.b OB/GYN History </label>
                        <p class="text-xs">G ___ P ___ ( ___ - ___ - ____ - ____ ) LMP: ________________________ <b
                                class="box   font-weight-bold">&#10004;</b> &nbsp;NA</p>
                    </div>
                </div>
                <div class="col-12 bottom-line2 text-xs">
                    <label>3. Pertinent Signs and Symptoms on Admission (tick applicable box/es):</label>

                    <div class="row p-2 ">
                        <div class="col-3">
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Altered mental
                            sensorium<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Abdominal
                            cramp/pain<br />
                            <b class="box   font-weight-bold">&#10004;</b> &nbsp;Anorexia<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Bleeding
                            gums<br />
                            <b class="box   font-weight-bold">&#10004;</b> &nbsp;Body
                            weakness<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Blurring of
                            vision<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Chest
                            pain/discomfort<br />
                            <b class="box   font-weight-bold">&nbsp;</b>
                            &nbsp;Constipation<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Cough<br />
                        </div>
                        <div class="col-3">
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Diarrhea <br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Dizziness<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Dysphagia<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Dyspnea<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Dysuria<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Epistaxis<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Fever<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Frequency of
                            urination<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Headache<br />
                        </div>
                        <div class="col-3">
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Hematemesis
                            <br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Hematuria<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Hemoptysis<br />
                            <b class="box   font-weight-bold">&nbsp;</b>
                            &nbsp;Irritability<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Jaundice<br />
                            <b class="box   font-weight-bold">&#10004;</b> &nbsp;Lower extremity
                            edema<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Myalgia<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Orthopnea<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Pain,
                            ________________ (site)<br />
                        </div>
                        <div class="col-3">
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Palpitations
                            <br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Seizures<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Skin rashes<br />
                            <b class="box   font-weight-bold">&nbsp;</b>
                            &nbsp;Stool,bloody/black tarry/mucoid<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Sweating<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Urgency<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Vomiting<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Weight loss<br />
                            <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Others
                            ______________________
                        </div>
                    </div>
                </div>
                <div class="col-12 bottom-line2  text-left text-xs pt-2">
                    <label> 4. Referred from another health care institution (HCI):</label>
                    <b class="box   font-weight-bold">&#10004;</b> &nbsp;No
                    <b class="box   font-weight-bold">&nbsp;</b> &nbsp;Yes , Specity Reason
                    _______________________________________________________________<br />
                    <div class="text-right mt-2 pb-1">
                        Name of Originating HCI _____________________________________________________________
                    </div>


                </div>
                <div class="col-12 bottom-line2 pb-2">
                    <label class="text-xs">5. Physical Examination on Admission (Pertinent Findings per
                        System):</label>
                    <div class="row text-sm">
                        <div class="col-2">General Survey</div>
                        <div class="col-2"><b class="box   font-weight-bold">&#10004;</b>
                            &nbsp;Awake and alert</div>
                        <div class="col-5"><b class="box   font-weight-bold">&nbsp;</b>
                            &nbsp;Altered sensorium: ___________________________ </div>
                        <div class="col-3">
                            <div class="m-2 p-2 blackbox2" style="top:-30px;position: relative;height:60px;">
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-3"> Height:</div>
                                        <div class="col-4">
                                            <div class="w-100 bottom-line2 text-center">{{ $HEIGHT }}&nbsp;
                                            </div>
                                        </div>
                                        <div class="col-2">(cm)</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3"> Weight:</div>
                                        <div class="col-4">
                                            <div class="w-100 bottom-line2 text-center">{{ $POST_WEIGHT }}&nbsp;
                                            </div>
                                        </div>
                                        <div class="col-2">(kg)</div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="row text-sm pb-2">
                        <div class="col-2">Vital Signs</div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-3">
                                    BP:
                                </div>
                                <div class="col-3 bottom-line2">
                                    {{ $POST_BLOOD_PRESSURE }}&nbsp;
                                </div>
                                <div class="col-1">/</div>
                                <div class="col-3 bottom-line2">
                                    {{ $POST_BLOOD_PRESSURE2 }}&nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-3">
                                    HR:
                                </div>
                                <div class="col-6 bottom-line2">
                                    {{ $POST_HEART_RATE }} &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-3">
                                    RR:
                                </div>
                                <div class="col-8 bottom-line2">
                                    {{ $RR_NO }} &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-3">
                                    Temp:
                                </div>
                                <div class="col-6 bottom-line2">
                                    {{ $POST_TEMPERATURE }} &nbsp;
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row text-sm">
                        <div class="col-2">HEENT</div>
                        <div class="col-2">
                            <b
                                class="box   font-weight-bold">&#10004;</b>&nbsp;&nbsp;Essentially
                            normal
                            <br />
                            <b class="box   font-weight-bold">&nbsp;</b>&nbsp;&nbsp;Icteric
                            sclerae

                        </div>
                        <div class="col-3">
                            <b class="box   font-weight-bold">&nbsp;</b>&nbsp;&nbsp;Abnormal
                            pupillary
                            reaction
                            <br />
                            <b class="box   font-weight-bold">&nbsp;</b>&nbsp;&nbsp;Pale
                            conjunctivae
                        </div>
                        <div class="col-3">
                            <b class="box   font-weight-bold">&nbsp;</b>&nbsp;&nbsp;Cervical
                            lymphadenopahy
                            <br />
                            <b class="box   font-weight-bold">&nbsp;</b>&nbsp;&nbsp;Sunken
                            eyeballs
                        </div>
                        <div class="col-2">
                            <div style="width: 300px;margin-left: -30px;">
                                <b class="box   font-weight-bold ">&nbsp;</b>&nbsp;&nbsp;Dry
                                mucous membrane
                                <br />
                                <b class="box   font-weight-bold">&nbsp;</b>&nbsp;&nbsp;Sunken
                                fontanelle
                            </div>

                        </div>
                        <div class="col-2"></div>
                        <div class="col-10 mt-3">
                            Others: ___________________________________________

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
