<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center " @if ($HEADER) style="opacity: 0.0" @endif>
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
                        <div class="col-7">
                            <b class="bottom-line2" @if ($HEADER) style="opacity: 0.0" @endif>
                                PHILHEALTH ACCREDITED :
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
                        <div class="col-7">
                            <div class="row">

                                <div class="col-12 text-right"  @if ($HEADER) style="opacity: 0.0" @endif>
                                    <i>DATEBIRTH</i>: <b>{{ $DATE_BIRTH }}</b>
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
                                    class="col-8 bottom-line2 text-sm"> &nbsp; {{ $ADDRESS1 }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    &nbsp;</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-sm"> &nbsp; {{ $ADDRESS2 }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    FINAL DIAGNOSIS :
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-sm"> &nbsp; {{ $FINAL_DIAGNOSIS }}</div>
                                {{-- <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    OTHER DIAGNOSIS : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-xs"> &nbsp; {{ $OTHER_DIAGNOSIS }}</div> --}}
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    TREATMENT DATES : </div>
                                <div class="col-8 @if (!$PRE_SIGN_DATA) bottom-line2 @endif text-sm">
                                    &nbsp;
                                    {{ $allDate }}
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-6">
                                    PHILHEALTH NO. :
                                </div>
                                <div class="col-6 @if ($OUTPUT_SIGN) bottom-line2 @endif"   @if ($HEADER) style="opacity: 0.0" @endif>
                                    {{ substr($PIN, 0, 1) . substr($PIN, 1, 1) . '-' . substr($PIN, 2, 1) . substr($PIN, 3, 1) . substr($PIN, 4, 1) . substr($PIN, 5, 1) . substr($PIN, 6, 1) . substr($PIN, 7, 1) . substr($PIN, 8, 1) . substr($PIN, 9, 1) . substr($PIN, 10, 1) . '-' . substr($PIN, 11, 1) }}

                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-6">DATE
                                    & TIME ADMITTED :</div>
                                <div class="col-6 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                    {{ $DATE_ADMITTED ? \Carbon\Carbon::parse($DATE_ADMITTED)->format('m/d/Y') : '' }}
                                    {{ $TIME_ADMITTED ? \Carbon\Carbon::parse($TIME_ADMITTED)->format('h:i A') : '' }}
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-6">DATE
                                    & TIME DISCHARGED :</div>
                                <div class="col-6 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                    {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : '' }}
                                    {{ $TIME_DISCHARGED ? \Carbon\Carbon::parse($TIME_DISCHARGED)->format('h:i A') : '' }}
                                </div>

                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-6">
                                    FIRST CASE RATE :</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-6 bottom-line2"> {{ $FIRST_CASE_RATE }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-6">
                                    SECOND CASE RATE :</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-6 bottom-line2"> &nbsp; </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-6">NO.
                                    OF TREATMENT :</div>
                                <div
                                    class="col-6  @if ($OUTPUT_SIGN) bottom-line2 @endif text-center font-weight-bold ">
                                    {{ $NO_OF_TREATMENT > 0 ? $NO_OF_TREATMENT : '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" id="details" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <div class="row top-line2 right-line2 left-line2 text-center mt-1 ">
                        <div class="col-12 font-weight-bold text-danger text-sm">SUMMARY OF FEES</div>
                    </div>
                    <div class="row top-line2 right-line2 left-line2">
                        <div class="col-3">
                        </div>
                        <div class="col-1 left-line2">
                        </div>
                        <div class="col-5 text-center  left-line2 bottom-line2 text-sm">
                            AMOUNT OF DISCOUNTS
                        </div>
                        <div class="col-2  text-center left-line2 bottom-line2 text-sm">
                            PHILHEALTH BENEFITS
                        </div>
                        <div class="col-1  left-line2">
                        </div>
                    </div>
                    <div class="row bottom-line2 right-line2 left-line2 text-sm">
                        <div class="col-3 text-center ">
                            <div class="top" style="top:0"> PARTICULARS </div>
                            <div class="bottom font-weight-bold" style="bottom: 0;">
                                <br />
                                HCI FEES
                            </div>
                        </div>
                        <div class="col-1 text-center left-line2 ">
                            ACTUAL <br /> CHARGES
                        </div>
                        <div class="col-1 text-center left-line2">
                            VAT EXEMPT
                        </div>
                        <div class="col-1 text-center left-line2">
                            SENIOR CITIZEN / PWD
                        </div>
                        <div class="col-2 text-center  left-line2">
                            OTHER FUNDING SOURCE/ FACILITY SUBSIDY
                        </div>
                        <div class="col-1 text-center left-line2">
                            AMOUNT AFTER DISCOUNT
                        </div>
                        <div class="col-1  left-line2 text-center ">
                            FIRST CASE RATE AMOUNT
                        </div>
                        <div class="col-1 left-line2 text-center ">
                            SECOND CASE RATE AMOUNT
                        </div>
                        <div class="col-1 text-center left-line2">
                            OUT OF POCKET OF PATIENT
                        </div>
                    </div>
                    @if ($CHARGES_ROOM_N_BOARD > 0 )
                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                ROOM AND BOARD
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2">
                                @if ($CHARGES_ROOM_N_BOARD > 0)
                                    {{ number_format($CHARGES_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line2">
                                @if ($VAT_ROOM_N_BOARD > 0)
                                    {{ number_format($VAT_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line2">
                                @if ($SP_ROOM_N_BOARD > 0)
                                    {{ number_format($SP_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                                @if ($GOV_ROOM_N_BOARD > 0)
                                    {{ number_format($GOV_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-1 text-right  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-right "> </div>
                            <div id="p-second" class="col-1 left-line2 text-right "> </div>
                            <div id="p-pocket" class="col-1 text-center left-line2"> </div>
                        </div>
                    @endif
                    @if ($CHARGES_DRUG_N_MEDICINE > 0 || $PRE_SIGN_DATA == true)
                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                DRUGS AND MEDICINES
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2 font-italic">
                                @if ($CHARGES_DRUG_N_MEDICINE > 0)
                                    {{ number_format($CHARGES_DRUG_N_MEDICINE, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line2">
                                @if ($VAT_DRUG_N_MEDICINE > 0)
                                    {{ number_format($VAT_DRUG_N_MEDICINE, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line2">
                                @if ($SP_DRUG_N_MEDICINE > 0)
                                    {{ number_format($SP_DRUG_N_MEDICINE, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                                @if ($GOV_DRUG_N_MEDICINE > 0)
                                    {{ number_format($GOV_DRUG_N_MEDICINE, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">

                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">

                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">

                            </div>
                        </div>
                    @endif
                    @if ($CHARGES_SUPPLIES > 0 || $PRE_SIGN_DATA == true)
                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                SUPPLIES
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2 font-italic">
                                @if ($CHARGES_SUPPLIES > 0)
                                    {{ number_format($CHARGES_SUPPLIES, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line2">
                                @if ($VAT_SUPPLIES > 0)
                                    {{ number_format($VAT_SUPPLIES, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line2">
                                @if ($SP_SUPPLIES > 0)
                                    {{ number_format($SP_SUPPLIES, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                                @if ($GOV_SUPPLIES > 0)
                                    {{ number_format($GOV_SUPPLIES, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">
                            </div>
                        </div>
                    @endif
                    @if ($CHARGES_LAB_N_DIAGNOSTICS > 0 || $PRE_SIGN_DATA == true)
                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                LABORATORY & DIAGNOSTIC
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2 font-italic">
                                @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                                    {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line2">
                                @if ($VAT_LAB_N_DIAGNOSTICS > 0)
                                    {{ number_format($VAT_LAB_N_DIAGNOSTICS, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line2">
                                @if ($SP_LAB_N_DIAGNOSTICS > 0)
                                    {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                                @if ($GOV_LAB_N_DIAGNOSTICS > 0)
                                    {{ number_format($GOV_LAB_N_DIAGNOSTICS, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">

                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">

                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">

                            </div>
                        </div>
                    @endif
                    @if ($CHARGES_OPERATING_ROOM_FEE > 0 || $PRE_SIGN_DATA == true)
                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                OPERATING ROOM FEES
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2 font-italic">
                                {{ number_format($CHARGES_OPERATING_ROOM_FEE, 2) }}
                            </div>
                            <div id="p-vat" class="col-1 text-right left-line2">
                                @if ($VAT_OPERATING_ROOM_FEE > 0)
                                    {{ number_format($VAT_OPERATING_ROOM_FEE, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line2">
                                @if ($SP_OPERATING_ROOM_FEE > 0)
                                    {{ number_format($SP_OPERATING_ROOM_FEE, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                                @if ($GOV_OPERATING_ROOM_FEE > 0)
                                    {{ number_format($GOV_OPERATING_ROOM_FEE, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">

                            </div>
                        </div>
                    @endif
                    @if ($CHARGES_OTHERS > 0 )
                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                ADMINISTRATIVE & OTHER FEES
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2 font-italic">
                                @if ($CHARGES_OTHERS > 0)
                                    {{ number_format($CHARGES_OTHERS, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line2">
                                @if ($VAT_OTHERS > 0)
                                    {{ number_format($VAT_OTHERS, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line2">
                                @if ($SP_OTHERS > 0)
                                    {{ number_format($SP_OTHERS, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-2 text-right  left-line2 text-xs">
                                @if ($GOV_OTHERS > 0)
                                    {{ number_format($GOV_OTHERS, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-1  left-line2 text-center "> </div>
                            <div id="p-first" class="col-1  left-line2 text-center "> </div>
                            <div id="p-second" class="col-1 left-line2 text-center "> </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">
                                @if ($OP_OTHERS > 0)
                                    {{ number_format($OP_OTHERS, 2) }}
                                @endif
                            </div>
                        </div>

                    @endif

                    <div class="row bottom-line2 right-line2 left-line2 text-sm">
                        <div id="p-particular" class="col-3 text-left ">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($CHARGES_SUB_TOTAL > 0)
                                {{ number_format($CHARGES_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($VAT_SUB_TOTAL > 0)
                                {{ number_format($VAT_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line2 font-weight-bold">
                            @if ($SP_SUB_TOTAL > 0)
                                {{ number_format($SP_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-2 text-right  left-line2 font-weight-bold">
                            @if ($GOV_SUB_TOTAL > 0)
                                {{ number_format($GOV_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($AD_SUB_TOTAL > 0)
                                {{ number_format($AD_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line2 text-right  font-weight-bold">
                            @if ($P1_SUB_TOTAL > 0)
                                {{ number_format($P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-right font-weight-bold ">
                            @if ($P2_SUB_TOTAL > 0)
                                {{ number_format($P2_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-right left-line2 font-weight-bold">
                            @if ($OP_SUB_TOTAL > 0)
                                {{ number_format($OP_SUB_TOTAL, 2) }}
                            @else
                                &nbsp;
                            @endif

                        </div>
                    </div>
                    <div class="row bottom-line2 right-line2 left-line2 text-sm">
                        <div id="p-particular"
                            class="col-12 text-center font-weight-bold text-left font-weight-light text-danger">
                            PROFESSIONAL FEES
                        </div>
                    </div>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($feeList as $list)
                        @php
                            $i++;
                        @endphp
                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                {{ $i . '. ' }} <span class="text-sm">{{ $list->NAME }}</span>

                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2 font-italic">
                                @if ($list->AMOUNT > 0)
                                    <i> {{ number_format($list->AMOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right left-line2 font-italic"> </div>
                            <div id="p-sp" class="col-1 text-right left-line2 font-italic">
                                @if ($list->DISCOUNT > 0)
                                    <i>{{ number_format($list->DISCOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-gov" class="col-2 text-right  left-line2 text-xs font-italic"> </div>
                            <div id="p-after-disc" class="col-1 text-right  left-line2 font-italic">
                                @if ($list->DISCOUNT > 0)
                                    <i> {{ number_format($list->AMOUNT - $list->DISCOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-first" class="col-1  left-line2 text-right font-italic">
                                @if ($list->FIRST_CASE > 0)
                                    <i> {{ number_format($list->FIRST_CASE, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-right "> </div>
                            <div id="p-pocket" class="col-1 text-right left-line2">
                                &nbsp;
                            </div>
                        </div>


                        <div class="row bottom-line2 right-line2 left-line2 text-sm">
                            <div id="p-particular" class="col-3 text-left ">
                                ACCREDITATION # <b class="font-weight-bold"> {{ $list->PIN }}</b>
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2 font-weight-bold">

                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line2 font-weight-bold"> </div>
                            <div id="p-sp" class="col-1 text-right   left-line2 font-weight-bold">

                            </div>
                            <div id="p-gov" class="col-2 text-right  left-line2 text-xs font-weight-bold"> </div>
                            <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">

                            </div>
                            <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">

                            </div>
                            <div id="p-second" class="col-1 left-line2 text-right font-weight-bold"> </div>
                            <div id="p-pocket" class="col-1 text-right left-line2 font-weight-bold">
                                &nbsp;
                            </div>
                        </div>
                    @endforeach

                    <div class="row bottom-line2 right-line2 left-line2 text-sm">
                        <div id="p-particular" class="col-3 text-left">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($PROFESSIONAL_FEE_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line2 font-weight-bold"> </div>
                        <div id="p-sp" class="col-1 text-right   left-line2 font-weight-bold">
                            @if ($PROFESSIONAL_DISCOUNT_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_DISCOUNT_SUB_TOTAL, 2) }}
                            @endif
                        </div>

                        <div id="p-after-disc" class="col-2 text-right left-line2 font-weight-bold">
                            {{-- @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL - $PROFESSIONAL_DISCOUNT_SUB_TOTAL, 2) }}
                            @endif --}}
                        </div>
                        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-right font-weight-bold ">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-right left-line2 ">

                        </div>
                        <div id="p-pocket" class="col-1 text-right left-line2 ">

                        </div>
                    </div>



                    <div class="row bottom-line2 right-line2 left-line2 text-sm text-danger">
                        <div id="p-particular" class="col-3 text-left ">
                            <b>TOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($CHARGE_TOTAL > 0)
                                {{ number_format($CHARGE_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($VAT_TOTAL > 0)
                                {{ number_format($VAT_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line2 font-weight-bold">
                            @if ($SP_TOTAL > 0)
                                {{ number_format($SP_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-2 text-right  left-line2 font-weight-bold">
                            @if ($GOV_TOTAL > 0)
                                {{ number_format($GOV_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($AD_TOTAL > 0)
                                {{ number_format($AD_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">
                            @if ($P1_TOTAL > 0)
                                {{ number_format($P1_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-right  font-weight-bold">
                            @if ($P2_TOTAL > 0)
                                {{ number_format($P2_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-center text-xs left-line2 font-weight-bold">
                            ZERO COPAY
                        </div>
                    </div>

                    @livewire('PhilHealth.PrintItemized2', ['num' => $NO_OF_TREATMENT, 'locationid' => $LOCATION_ID, 'date' => $DATE_ADMITTED ?? null, 'breakDownDate' => $breakDownDate, 'patientId' => $CONTACT_ID, 'OUTPUT_SIGN' => $OUTPUT_SIGN])

                </div>

                <div class="col-12 ">
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
                                <div class="col-12 mt-2">
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
                            <div class="row">
                                <div class="col-12" @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
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

                                <div class="col-6 ">
                                    <div @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                        Date Signed:
                                        </span>
                                        {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                    </div>

                                </div>
                                <div class="col-6" @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    CONTACT No. {{ $PATIENT_CONTACT }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}

                </div>
            </div>
    </section>

</div>
