<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-1" @if ($HEADER) style="opacity: 0.0" @endif>
                    @if (empty($LOGO_FILE))
                        <img class="print-logo" style="width:500px;" src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="text-center">
                            <b class="print-address1 text-center">
                                {{ $REPORT_HEADER_1 }} <br />
                                {{ $REPORT_HEADER_2 }}<br />
                                {{ $REPORT_HEADER_3 }}</b>
                        </div>
                    @else
                        {{-- nothing customize --}}
                        <img class="w-50" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">
                            <b class="bottom-line2" @if ($HEADER) style="opacity: 0.0" @endif>
                                PHILHEALTH ACCREDITED :
                            </b>
                        </div>
                        <div class="col-4 text-center mb-1">
                            <h4>STATEMENT OF ACCOUNT</h4>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-5 text-right">
                                    SOA No. : </div>
                                <div class="col-5 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                    <b>{{ $CODE }}</b>
                                </div>
                                <div class="col-2">

                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6">

                                </div>
                                <div class="col-6 text-right">
                                    <i>DOB</i>: {{ $DATE_BIRTH }}
                                </div>
                                <div class="col-4" @if ($HEADER) style="opacity: 0.0" @endif>
                                    PATIENT`S NAME :
                                </div>
                                <div class="col-8 bottom-line2"
                                    @if ($HEADER) style="opacity: 0.0" @endif>
                                    <div class="row">
                                        <div class="col-9"> &nbsp; {{ $PATIENT_NAME }}</div>
                                        <div class="col-3 text-right">Age: {{ $AGE }}</div>
                                    </div>
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    ADDRESS : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-xs"> &nbsp; {{ $ADDRESS1 }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    &nbsp;</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-xs"> &nbsp; {{ $ADDRESS2 }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    FINAL DIAGNOSIS :
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-xs"> &nbsp; {{ $FINAL_DIAGNOSIS }}</div>

                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    TREATMENT DATES : </div>
                                <div class="col-8 @if (!$PRE_SIGN_DATA) bottom-line2 @endif text-sm">
                                    &nbsp;
                                    {{ $allDate }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">

                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">
                                    PHILHEALTH No. :
                                </div>
                                <div class="col-7 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                    {{ substr($PIN, 0, 1) . substr($PIN, 1, 1) . '-' . substr($PIN, 2, 1) . substr($PIN, 3, 1) . substr($PIN, 4, 1) . substr($PIN, 5, 1) . substr($PIN, 6, 1) . substr($PIN, 7, 1) . substr($PIN, 8, 1) . substr($PIN, 9, 1) . substr($PIN, 10, 1) . '-' . substr($PIN, 11, 1) }}

                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">DATE
                                    & TIME ADMITTED :</div>
                                <div class="col-7 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                    {{ $DATE_ADMITTED ? \Carbon\Carbon::parse($DATE_ADMITTED)->format('m/d/Y') : '' }}
                                    {{ $TIME_ADMITTED ? \Carbon\Carbon::parse($TIME_ADMITTED)->format('h:i:s A') : '' }}
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">DATE
                                    & TIME DISCHARGED :</div>
                                <div class="col-7 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                    {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : '' }}
                                    {{ $TIME_DISCHARGED ? \Carbon\Carbon::parse($TIME_DISCHARGED)->format('h:i:s A') : '' }}
                                </div>

                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">
                                    FIRST CASE RATE :</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-7 bottom-line2"> {{ $FIRST_CASE_RATE }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">
                                    SECOND CASE RATE :</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-7 bottom-line2"> &nbsp; </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">NO.
                                    OF TREATMENT :</div>
                                <div
                                    class="col-7  @if ($OUTPUT_SIGN) bottom-line2 @endif text-center font-weight-bold ">
                                    {{ $NO_OF_TREATMENT > 0 ? $NO_OF_TREATMENT : '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <b class="text-lg">SUMMARY OF FEES asdasdas</b>
                </div>

                @livewire('PhilHealth.PrintSummaryLuzon', ['ID' => $PRINT_ID, 'PRE_SIGN_DATA' => $PRE_SIGN_DATA, 'PATIENT_ID' => $PATIENT_ID])
                <div @if ($breakDownDate == []) style="opacity: 0.0" @endif class="col-12">
                    @livewire('PhilHealth.PrintItemized', ['num' => $NO_OF_TREATMENT ?? 0, 'locationid' => $LOCATION_ID, 'date' => $DATE_ADMITTED ?? '', 'breakDownDate' => $breakDownDate, 'patientId' => $CONTACT_ID ?? 0])
                </div>
                {{-- @if ($OUTPUT_SIGN) --}}
                <div class="col-12  pt-1">
                    <div class="row">
                        <div class="col-5">
                            <div @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>Prepared by:</div>
                            <div class="form-group row  mt-4">
                                <div class="col-7 text-center bottom-line2">
                                    <strong @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                        {{ $USER_NAME }}</strong>
                                </div>
                                <div class="col-7 text-center"
                                    @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>PHIC IN-Charge</div>
                                <div class="col-12 mt-4">
                                    <span @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                        Date Signed:
                                    </span>
                                    <span>
                                        {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                    </span>
                                </div>
                                <div class="col-12" @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    CONTACT No. {{ $USER_CONTACT }}</div>
                            </div>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-5">
                            <div @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>Conforme:</div>
                            <div class="form-group row ">
                                <div class="col-12 text-center bottom-line2"
                                    @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    <b>{{ $PATIENT_NAME }}</b>
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
                                <div class="col-12 mt-4 bottom-line2"
                                    @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>&nbsp;</div>

                                <div class="col-12 ">
                                    <span @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                        Date Signed:
                                    </span>
                                    {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                </div>
                                <div class="col-12" @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    CONTACT No. {{ $PATIENT_CONTACT }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}

            </div>
        </div>
    </section>

</div>
