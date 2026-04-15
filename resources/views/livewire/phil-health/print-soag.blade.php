<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center" @if ($HEADER) style="opacity: 0.0" @endif>
                    @if (empty($LOGO_FILE))
                        <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="text-center">
                            <b class="print-address1 text-center">
                                {{ $REPORT_HEADER_1 }} <br />
                                {{ $REPORT_HEADER_2 }}<br />
                                {{ $REPORT_HEADER_3 }}</b>
                        </div>
                    @else
                        {{-- nothing customize --}}
                        <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <b class="bottom-line2"
                                @if ($HEADER) style="opacity: 0.0" @endif>PHILHEALTH ACCREDITED:</b>
                            <div class="row mt-1">
                                <div class="col-4" @if ($HEADER) style="opacity: 0.0" @endif>
                                    PATIENT`S NAME : </div>
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
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">FINAL
                                    DIAGNOSIS : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-xs"> &nbsp; {{ $FINAL_DIAGNOSIS }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    OTHER DIAGNOSIS : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line2 text-xs"> &nbsp; {{ $OTHER_DIAGNOSIS }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    TREATMENT DATES : </div>
                                <div class="col-8 @if (!$PRE_SIGN_DATA) bottom-line2 @endif text-xs">
                                    &nbsp;
                                    {{ $allDate }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">
                                    Soa Reference No. :
                                </div>
                                <div class="col-7 @if ($OUTPUT_SIGN) bottom-line2 @endif">
                                    {{ $CODE }}</div>
                            </div>
                            <div class="row mt-1">
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
                                <div class="col-12"> <br /></div>
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
                <div class="col-12 text-center mt-1" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <b class="text-lg">SUMMARY OF FEES sad</b>
                </div>
                <div class="col-12 mt-1 text-sm" id="details"
                    @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <div class="row top-line2 right-line2 left-line2">
                        <div class="col-4">
                        </div>
                        <div class="col-1 left-line2">
                        </div>
                        <div class="col-4 text-center  left-line2 bottom-line2">
                            AMOUNT OF DISCOUNTS
                        </div>
                        <div class="col-2  text-center left-line2 bottom-line2">
                            PHILHEALTH BENEFITS
                        </div>
                        <div class="col-1  left-line2">
                        </div>
                    </div>

                    <div class="row bottom-line2 right-line2 left-line2">
                        <div class="col-4 text-center ">
                            <div class="top" style="top:0"> PARTICULARS </div>
                            <div class="bottom font-weight-bold" style="bottom: 0;">
                                <br />
                                <br />
                                HCI FEE/S
                            </div>
                        </div>
                        <div class="col-1 text-center left-line2">
                            ACTUAL <br /> CHARGES
                        </div>
                        <div class="col-1 text-center left-line2">
                            VAT EXEMPT
                        </div>
                        <div class="col-1 text-center left-line2">
                            SENIOR CITIZEN / PWD
                        </div>
                        <div class="col-1 text-center  left-line2 text-xs">
                            <div class="row text-left">
                                <div class="col-12">___PCSO</div>
                                <div class="col-12">___DSWD</div>
                                <div class="col-12">___DOH(MAP)</div>
                                <div class="col-12">___HMO</div>
                                <div class="col-12">___LINGAP</div>
                            </div>
                        </div>
                        <div class="col-1 text-center left-line2">
                            AMOUNT AFTER DISCOUNT
                        </div>
                        <div class="col-1  left-line2 text-center ">
                            First <br /> Case Rate amount
                        </div>
                        <div class="col-1 left-line2 text-center ">
                            Second Case Rate amount
                        </div>
                        <div class="col-1 text-center left-line2">
                            Ouf of Pocket of Patient
                        </div>
                    </div>
                    {{-- Meds Item --}}
                    @foreach ($medList as $list)
                        <div class="row bottom-line2 right-line2 left-line2">
                            <div id="p-particular" class="col-4 text-left ">
                                &nbsp;{{ $list->ITEM_NAME }}
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2">
                                @if ($list->RATE > 0)
                                    <i> {{ number_format($list->RATE * $NO_OF_TREATMENT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-center  left-line2">
                            </div>
                            <div id="p-sp" class="col-1 text-center   left-line2">
                            </div>
                            <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">
                            </div>
                        </div>
                    @endforeach
                    <div class="row bottom-line2 right-line2 left-line2 font-weight-bold">
                        <div id="p-particular" class="col-4 text-left ">
                            Drugs & Medicine
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line2">
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
                        <div id="p-gov" class="col-1 text-right  left-line2 text-xs">
                            @if ($GOV_DRUG_N_MEDICINE > 0)
                                {{ number_format($GOV_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($P1_DRUG_N_MEDICINE > 0)
                                {{ number_format($P1_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold ">
                            @if ($P1_DRUG_N_MEDICINE > 0)
                                {{ number_format($P1_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">
                        </div>
                    </div>

                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-right ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line2">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                        <div id="p-first" class="col-1  left-line2 text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">
                        </div>
                    </div>

                    @foreach ($laboratoryList as $list)
                        <div class="row bottom-line2 right-line2 left-line2">
                            <div id="p-particular" class="col-4 text-left ">
                                &nbsp;{{ $list->ITEM_NAME }}
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2">
                                @if ($list->RATE > 0)
                                    <i> {{ number_format($list->RATE * $NO_OF_TREATMENT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-center  left-line2">
                            </div>
                            <div id="p-sp" class="col-1 text-center   left-line2">
                            </div>
                            <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">
                            </div>
                        </div>
                    @endforeach
                    <div class="row bottom-line2 right-line2 left-line2 ">
                        <div id="p-particular" class="col-4 text-left font-weight-bold">
                            Laboratory & Diagnostics
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line2">
                            @if ($VAT_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($VAT_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line2 font-weight-bold">
                            @if ($SP_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line2 text-xs font-weight-bold">
                            @if ($GOV_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($GOV_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($P1_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($P1_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">
                            @if ($P1_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($P1_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">
                        </div>
                    </div>
                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-right ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line2">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                        <div id="p-first" class="col-1  left-line2 text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">
                        </div>
                    </div>
                    {{-- Lab --}}


                    {{-- Supplies --}}

                    @foreach ($suppliesList as $list)
                        <div class="row bottom-line2 right-line2 left-line2">
                            <div id="p-particular" class="col-4 text-left ">
                                &nbsp;{{ $list->ITEM_NAME }}
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2">
                                @if ($list->RATE > 0)
                                    <i> {{ number_format($list->RATE * $NO_OF_TREATMENT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-center  left-line2">
                            </div>
                            <div id="p-sp" class="col-1 text-center   left-line2">
                            </div>
                            <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">
                            </div>
                        </div>
                    @endforeach
                    <div class="row bottom-line2 right-line2 left-line2 font-weight-bold">
                        <div id="p-particular" class="col-4 text-left ">
                            Supplies
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line2">
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
                        <div id="p-gov" class="col-1 text-right  left-line2 text-xs">
                            @if ($GOV_SUPPLIES > 0)
                                {{ number_format($GOV_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">

                            @if ($P1_SUPPLIES > 0)
                                {{ number_format($P1_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">

                            @if ($P1_SUPPLIES > 0)
                                {{ number_format($P1_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">

                        </div>
                    </div>
                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-right ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line2">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                        <div id="p-first" class="col-1  left-line2 text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">
                        </div>
                    </div>


                    @foreach ($adminFeeList as $list)
                        <div class="row bottom-line2 right-line2 left-line2">
                            <div id="p-particular" class="col-4 text-left ">
                                &nbsp;{{ $list->ITEM_NAME }}
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2">
                                @if ($list->RATE > 0)
                                    <i> {{ number_format($list->RATE * $NO_OF_TREATMENT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-center  left-line2">
                            </div>
                            <div id="p-sp" class="col-1 text-center   left-line2">
                            </div>
                            <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                            </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">
                            </div>
                        </div>
                    @endforeach
                    <div class="row bottom-line2 right-line2 left-line2 font-weight-bold">
                        <div id="p-particular" class="col-4 text-left ">
                            Administrative & Other Fees
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line2">
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
                        <div id="p-gov" class="col-1 text-right  left-line2 text-xs">
                            @if ($GOV_OTHERS > 0)
                                {{ number_format($GOV_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">

                            @if ($P1_OTHERS > 0)
                                {{ number_format($P1_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">
                            @if ($P1_OTHERS > 0)
                                {{ number_format($P1_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center "> </div>
                        <div id="p-pocket" class="col-1 text-center left-line2"> </div>
                    </div>

                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-right ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line2">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                        <div id="p-first" class="col-1  left-line2 text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">
                        </div>
                    </div>

                    {{-- Other fees --}}


                    <div class="row bottom-line2 right-line2 left-line2 font-weight-bold">
                        <div id="p-particular" class="col-4 text-left ">
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
                        <div id="p-gov" class="col-1 text-right  left-line2 font-weight-bold">
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

                            {{ number_format($OP_SUB_TOTAL, 2) }}

                        </div>
                    </div>
                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-left font-weight-bold">
                            PROFESSIONAL FEE/S
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line2"> </div>
                        <div id="p-vat" class="col-1 text-center  left-line2"> </div>
                        <div id="p-sp" class="col-1 text-center   left-line2"> </div>
                        <div id="p-gov" class="col-1 text-center  left-line2 text-xs"> </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                        <div id="p-first" class="col-1  left-line2 text-center "> </div>
                        <div id="p-second" class="col-1 left-line2 text-center "> </div>
                        <div id="p-pocket" class="col-1 text-center left-line2"> </div>
                    </div>
                    {{-- Doctor --}}
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($feeList as $list)
                        @php
                            $i++;
                        @endphp
                        <div class="row bottom-line2 right-line2 left-line2">
                            <div id="p-particular" class="col-4 text-left ">
                                {{ $i . '. ' }} <span class="text-sm">{{ $list->NAME }}</span>
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line2">
                                @if ($list->AMOUNT > 0)
                                    <i> {{ number_format($list->AMOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right left-line2"> </div>
                            <div id="p-sp" class="col-1 text-right left-line2">
                                @if ($list->DISCOUNT > 0)
                                    <i>{{ number_format($list->DISCOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line2 text-xs"> </div>
                            <div id="p-after-disc" class="col-1 text-right  left-line2">
                                @if ($list->DISCOUNT > 0)
                                    {{-- <i> {{ number_format($list->AMOUNT - $list->DISCOUNT, 2) }}</i> --}}
                                    <i> {{ number_format($list->FIRST_CASE, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-first" class="col-1  left-line2 text-right">
                                @if ($list->FIRST_CASE > 0)
                                    <i> {{ number_format($list->FIRST_CASE, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-right "> </div>
                            <div id="p-pocket" class="col-1 text-right left-line2">
                                @if ($list->FIRST_CASE > 0)
                                    <i>
                                        {{-- {{ number_format($list->AMOUNT - $list->DISCOUNT - $list->FIRST_CASE, 2) }} --}}
                                        0.00
                                    </i>
                                @endif
                            </div>
                        </div>
                        <div class="row bottom-line2 right-line2 left-line2">
                            <div id="p-particular" class="col-4 text-left  ">
                                <i>Acreditation No. <b>{{ $list->PIN }}</b></i>
                            </div>
                            <div id="p-charge" class="col-1 text-center  left-line2">
                                &nbsp;
                            </div>
                            <div id="p-vat" class="col-1 text-center  left-line2">
                                &nbsp;</div>
                            <div id="p-sp" class="col-1 text-center   left-line2">
                                &nbsp;
                            </div>
                            <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                                &nbsp; </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line2">
                                &nbsp;
                            </div>
                            <div id="p-first" class="col-1  left-line2 text-center ">
                                &nbsp;
                            </div>
                            <div id="p-second" class="col-1 left-line2 text-center ">
                                &nbsp;
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line2">
                                &nbsp;
                            </div>
                        </div>
                    @endforeach

                    {{-- @for ($n = 1; $n <= 5; $n++)
                        @if ($n > $i)
                            <div class="row bottom-line2 right-line2 left-line2">
                                <div id="p-particular" class="col-4 text-left ">
                                    {{ $n . '. ' }}
                                </div>
                                <div id="p-charge" class="col-1 text-center  left-line2">
                                    &nbsp;
                                </div>
                                <div id="p-vat" class="col-1 text-center  left-line2">
                                    &nbsp;</div>
                                <div id="p-sp" class="col-1 text-center   left-line2">
                                    &nbsp;
                                </div>
                                <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                                    &nbsp; </div>
                                <div id="p-after-disc" class="col-1 text-center  left-line2">
                                    &nbsp;
                                </div>
                                <div id="p-first" class="col-1  left-line2 text-center ">
                                    &nbsp;
                                </div>
                                <div id="p-second" class="col-1 left-line2 text-center ">
                                    &nbsp;
                                </div>
                                <div id="p-pocket" class="col-1 text-center left-line2">
                                    &nbsp;
                                </div>
                            </div>
                        @endif
                    @endfor --}}
                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-left ">
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
                        <div id="p-gov" class="col-1 text-right  left-line2 text-xs font-weight-bold"> </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-right font-weight-bold"> </div>
                        <div id="p-pocket" class="col-1 text-right left-line2 font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{-- {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL - $PROFESSIONAL_DISCOUNT_SUB_TOTAL - $PROFESSIONAL_P1_SUB_TOTAL, 2) }} --}}
                                0.00
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-right ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line2">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line2">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line2 text-xs">
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line2"> </div>
                        <div id="p-first" class="col-1  left-line2 text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line2 text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line2">
                        </div>
                    </div>

                    <div class="row bottom-line2 right-line2 left-line2">
                        <div id="p-particular" class="col-4 text-left ">
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
                        <div id="p-gov" class="col-1 text-right  left-line2 text-xs font-weight-bold">
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
                        <div id="p-pocket" class="col-1 text-right left-line2 font-weight-bold text-xs">
                            {{-- {{ number_format($OP_TOTAL, 2) }} --}} ZERO COPAY
                        </div>
                    </div>
                </div>

                {{-- @if ($OUTPUT_SIGN) --}}
                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col-4">
                            <div @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>Prepared by:</div>
                            <div class="form-group row mt-4">
                                <div class="col-7 text-center">
                                    <strong class="bottom-line2"
                                        @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                        {{ $USER_NAME }}</strong>
                                </div>
                                <div class="col-7 text-center"
                                    @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>PHIC IN-Charge</div>
                                <div class="col-12">
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
                        <div class="col-4"></div>
                        <div class="col-4">
                            <div @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>Conforme:</div>
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line2"
                                    @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    <b>{{ $PATIENT_NAME }}</b>
                                </div>
                                <div class="col-12 " @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    Member/Patient/Authorized Representative</div>
                                <div class="col-12 " @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    (Signature over printed name)</div>
                                <div class="col-12 text-xs"
                                    @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>Relationship of
                                    member of authorized representative
                                </div>
                                <div class="col-12 bottom-line2"
                                    @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>&nbsp;</div>
                                <div class="col-12 ">
                                    <span @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                        Date Signed:
                                    </span>
                                    <span>
                                        {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                    </span>

                                </div>
                                <div class="col-12 " @if (!$OUTPUT_SIGN) style="opacity: 0.0" @endif>
                                    CONTACT No. {{ $PATIENT_CONTACT }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}

            </div>
        </div>
    </section>

</div>
