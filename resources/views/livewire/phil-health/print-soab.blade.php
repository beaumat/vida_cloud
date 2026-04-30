<div>
    <section class="content">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12 top-line2 right-line2 left-line2 bottom-line2 ">
                    <div class="row  text-center p-3 ">
                        <div class="col-12 text-center " @if ($HEADER) style="opacity: 0.0" @endif>
                            {{-- @if (empty($LOGO_FILE))
                        <img class="print-logo" style="width:500px;" src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="text-center">
                            <b class="print-address1 text-center">
                                {{ $REPORT_HEADER_1 }} <br />
                                {{ $REPORT_HEADER_2 }}<br />
                                {{ $REPORT_HEADER_3 }}</b>
                        </div>
                    @else
                        <img class="w-50" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif --}}
                            <h4>STATEMENT OF ACCOUNT</h4>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-7">
                                    <b class="bottom-line2"
                                        @if ($HEADER) style="opacity: 0.0" @endif>

                                    </b>
                                </div>

                                <div class="col-5">
                                    <div class="row">
                                        <div @if ($HEADER) style="opacity: 0.0" @endif
                                            class="col-6 text-right">
                                            SOA Reference No. : </div>
                                        <div class="col-6 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                            <b>{{ $CODE }}</b>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12">

                                    <h5>LIFESTREAM DIALYSIS CENTER OPC</h5>
                                    {{ $REPORT_HEADER_1 }} <br />
                                    {{ $REPORT_HEADER_2 }}<br />
                                    {{ $REPORT_HEADER_3 }}</b>

                                </div>
                                <div class="col-12">
                                    &nbsp;
                                </div>
                                <div class="col-7">
                                    <div class="row">


                                        <div class="col-3 text-left"
                                            @if ($HEADER) style="opacity: 0.0" @endif>
                                            Patient Name :
                                        </div>
                                        <div class="col-9 "
                                            @if ($HEADER) style="opacity: 0.0" @endif>
                                            <div class="row text-left">
                                                <div class="col-10 bottom-line2"> &nbsp; {{ $PATIENT_NAME }}</div>
                                                <div class="col-2">
                                                    <div class="row">
                                                        <div class="col-6 text-left"> Age:</div>
                                                        <div class="col-6 bottom-line2">{{ $AGE }}</div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div @if ($HEADER) style="opacity: 0.0" @endif
                                            class="col-3 text-left">
                                            Address : </div>
                                        <div @if ($HEADER) style="opacity: 0.0" @endif
                                            class="col-9 bottom-line2 text-sm text-left"> &nbsp; {{ $ADDRESS1 }}
                                            {{ $ADDRESS2 }}</div>

                                        <div @if ($HEADER) style="opacity: 0.0" @endif
                                            class="col-12 text-left">
                                            Final Diagnosis (ICD-10/RVS): <span class=" bottom-line2 text-sm"> &nbsp;
                                                N18.5 /
                                                90935 &nbsp;&nbsp;&nbsp; </span>
                                        </div>

                                        <div @if ($HEADER) style="opacity: 0.0" @endif
                                            class="col-12 text-left">
                                            Other Diagnosis (ICD-10/RVS): <span class=" bottom-line2 text-sm">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-5 ">
                                    <div class="row">

                                        <div @if ($HEADER) style="opacity: 0.0" @endif
                                            class="col-7 text-right">Date
                                            & Time Admitted:</div>
                                        <div class="col-5 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                            {{ $DATE_ADMITTED ? \Carbon\Carbon::parse($DATE_ADMITTED)->format('m/d/Y') : '' }}
                                            {{ $TIME_ADMITTED ? \Carbon\Carbon::parse($TIME_ADMITTED)->format('h:i A') : '' }}
                                        </div>
                                        <div @if ($HEADER) style="opacity: 0.0" @endif
                                            class="col-7 text-right">Date
                                            & Time Discharged:</div>
                                        <div class="col-5 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                            {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : '' }}
                                            {{ $TIME_DISCHARGED ? \Carbon\Carbon::parse($TIME_DISCHARGED)->format('h:i A') : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="details"
                            @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                            @livewire('PhilHealth.PrintSummaryBizbox', ['ID' => $PRINT_ID, 'PRE_SIGN_DATA' => $PRE_SIGN_DATA, 'PATIENT_ID' => $PATIENT_ID])
                            @livewire('PhilHealth.PrintItemized4', ['num' => $NO_OF_TREATMENT, 'locationid' => $LOCATION_ID, 'date' => $DATE_ADMITTED ?? null, 'breakDownDate' => $breakDownDate, 'patientId' => $CONTACT_ID, 'OUTPUT_SIGN' => $OUTPUT_SIGN])
                        </div>
                        <div class="col-12 ">
                            <div class="row mt-2">
                                <div class="col-5 text-left">
                                    <div @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>Prepared by:
                                    </div>
                                    <div class="form-group row  mt-4">
                                        <div class="col-7 text-center bottom-line2">
                                            <strong @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                                {{ $USER_NAME }}</strong>
                                        </div>
                                        <div class="col-7 text-center"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>PHIC
                                            IN-Charge</div>
                                        <div class="col-12 mt-2 text-left">
                                            <span @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                                Date Signed:
                                            </span>
                                            <span>
                                                {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                            </span>
                                        </div>
                                        <div class="col-12 text-left" USER_CONTACT
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                            CONTACT No. {{ $USER_CONTACT }}</div>
                                    </div>
                                </div>
                                <div class="col-2"></div>
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-12 text-left"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                            Conforme:</div>
                                        <div class="col-12 mt-4 text-center bottom-line2 font-weight-bold"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                            {{ $PATIENT_NAME }}
                                        </div>
                                        <div class="col-12 text-center"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                            Member/Patient/Authorized Representative</div>
                                        <div class="col-12  text-center"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                            (Signature over printed name)</div>
                                        <div class="col-12 text-sm text-center"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                            <i>Relationship of
                                                member of authorized representative</i>
                                        </div>
                                        <div class="col-12 mt-1 bottom-line2"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>&nbsp;</div>

                                        <div class="col-12 text-left">
                                            <div @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                                Date Signed:
                                                </span>
                                                {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                            </div>

                                        </div>
                                        <div class="col-12 text-left"
                                            @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                            CONTACT No. {{ $PATIENT_CONTACT }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- @endif --}}
                        </div>
                    </div>
                </div>

            </div>

    </section>

</div>
