<div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center" @if ($HEADER) style="opacity: 0.0" @endif>
                    <img class="print-logo2" src="{{ asset('dist/logo/vida_logo.png') }}" />
                    <div class="text-center" style="top:120px;position:relative">
                        <b class="print-address1 text-center">{{ $REPORT_HEADER_1 }} <br /> {{ $REPORT_HEADER_2 }}<br />
                            {{ $REPORT_HEADER_3 }}</b>
                    </div>
                </div>
                <div class="col-12 mb-4" style="top:100px;padding-bottom:100px;">
                    <div class="row">
                        <div class="col-6">
                            <b class="bottom-line"
                                @if ($HEADER) style="opacity: 0.0" @endif>PHILHEALTH ACCREDITED:
                                <strong class="text-primary">{{ $CENTER_ACCREDITED_NO }}</strong></b>
                            <div class="row mt-4">
                                <div class="col-4" @if ($HEADER) style="opacity: 0.0" @endif>
                                    Patient`s Name : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line"> &nbsp; {{ $PATIENT_NAME }}</div>
                                <div class="col-4" @if ($HEADER) style="opacity: 0.0" @endif>
                                    Philhealth No. : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line"> &nbsp; {{ $PIN }}</div>
                                <div class="col-4" @if ($HEADER) style="opacity: 0.0" @endif>
                                    Birth Date : </div>
                                <div class="col-8 bottom-line"
                                    @if ($HEADER) style="opacity: 0.0" @endif>
                                    <div class="row">
                                        <div class="col-6"> &nbsp;
                                            {{ date('m/d/Y', strtotime($DATE_OF_BIRTH)) }}</div>
                                        <div class="col-6 text-center ">Age: {{ $AGE }}</div>
                                    </div>
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    Address : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line text-sm"> &nbsp; {{ $ADDRESS1 }}</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    &nbsp;</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line text-sm">
                                    <div class="width:800px; position:absolute;">
                                        &nbsp; {{ $ADDRESS2 }}
                                    </div>
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-4">
                                    Diagnosis : </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-8 bottom-line text-sm"> &nbsp; {{ $FINAL_DIAGNOSIS }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-5 font-weight-bold"> Soa
                                    Reference No. : </div>
                                <div class="col-7 @if ($OUTPUT_SIGN) bottom-line @endif">
                                    {{ $CODE }}</div>
                            </div>
                            <div class="row mt-4">
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">Date
                                    & Time Admitted :</div>
                                <div class="col-7 @if ($OUTPUT_SIGN) bottom-line @endif">
                                    {{ $DATE_ADMITTED ? \Carbon\Carbon::parse($DATE_ADMITTED)->format('m/d/Y') : '' }}
                                    {{ $TIME_ADMITTED ? \Carbon\Carbon::parse($TIME_ADMITTED)->format('h:i:s A') : '' }}
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">Date
                                    & Time Discharged :</div>
                                <div class="col-7 @if ($OUTPUT_SIGN) bottom-line @endif">
                                    {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : '' }}
                                    {{ $TIME_DISCHARGED ? \Carbon\Carbon::parse($TIME_DISCHARGED)->format('h:i:s A') : '' }}
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">
                                    Session Date :</div>
                                <div class="col-7 @if (!$PRE_SIGN_DATA) bottom-line @endif">
                                    &nbsp;
                                    {{ $allDate }}
                                </div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif class="col-5">
                                    First Case Rate :</div>
                                <div @if ($HEADER) style="opacity: 0.0" @endif
                                    class="col-7 bottom-line"> {{ $FIRST_CASE_RATE }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <b class="text-xl">SUMMARY OF FEESasdasd</b>
                </div>
                <div class="col-12 mt-2" id="details" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <div class="row top-line right-line left-line">
                        <div class="col-4">
                        </div>
                        <div class="col-1 left-line">
                        </div>
                        <div class="col-3 text-center  left-line bottom-line">
                            AMOUNT OF <br /> DISCOUNTS
                        </div>
                        <div class="col-3  text-center left-line bottom-line">
                            PHILHEALTH <br /> BENEFITS
                        </div>
                        <div class="col-1  left-line">
                        </div>
                    </div>

                    <div class="row bottom-line right-line left-line">
                        <div class="col-4 text-center ">
                            PARTICULARS
                        </div>
                        <div class="col-1 text-center left-line">
                            ACTUAL <br /> CHARGES
                        </div>
                        <div class="col-1 text-center left-line">
                            VAT <br /> EXEMPTS
                        </div>
                        <div class="col-1 text-center left-line">
                            SENIOR CITIZEN / <br /> PWD
                        </div>
                        <div class="col-1 text-center  left-line text-xs">
                            <div class="row text-left">
                                <div class="col-12">___PCSO</div>
                                <div class="col-12">___DSWD</div>
                                <div class="col-12">___DOH(MAP)</div>
                                <div class="col-12">___HMO</div>
                                <div class="col-12">___LINGAP</div>
                            </div>
                        </div>

                        <div class="col-2  left-line text-center ">
                            First <br /> Case Rate <br /> amount
                        </div>
                        <div class="col-1 left-line text-center ">
                            Second Case Rate amount
                        </div>
                        <div class="col-1 text-center left-line">
                            Ouf of <br /> Pocket <br /> of Patient
                        </div>
                    </div>

                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-center ">
                            <b> HCI Fees</b>
                        </div>
                        <div id="p-charge" class="col-1 text-center left-line">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                        </div>

                        <div id="p-first" class="col-2  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Room and Board
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line">
                            @if ($CHARGES_ROOM_N_BOARD > 0)
                                {{ number_format($CHARGES_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line">
                            @if ($VAT_ROOM_N_BOARD > 0)
                                {{ number_format($VAT_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line">
                            @if ($SP_ROOM_N_BOARD > 0)
                                {{ number_format($SP_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs">
                            @if ($GOV_ROOM_N_BOARD > 0)
                                {{ number_format($GOV_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-right "> </div>
                        <div id="p-second" class="col-1 left-line text-right "> </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                            @if ($OP_ROOM_N_BOARD > 0)
                                {{ number_format($OP_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Drugs & Medicine
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line">
                            @if ($CHARGES_DRUG_N_MEDICINE > 0)
                                {{ number_format($CHARGES_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line">
                            @if ($VAT_DRUG_N_MEDICINE > 0)
                                {{ number_format($VAT_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line">
                            @if ($SP_DRUG_N_MEDICINE > 0)
                                {{ number_format($SP_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs">
                            @if ($GOV_DRUG_N_MEDICINE > 0)
                                {{ number_format($GOV_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-center ">

                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                            @if ($OP_DRUG_N_MEDICINE > 0)
                                {{ number_format($OP_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Laboratory & Diagnostics
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line">
                            @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line">
                            @if ($VAT_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($VAT_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line">
                            @if ($SP_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs">
                            @if ($GOV_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($GOV_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-center ">

                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                            @if ($OP_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($OP_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Operating Room Fee
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line">

                            @if ($CHARGES_OPERATING_ROOM_FEE > 0)
                                {{ number_format($CHARGES_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line">
                            @if ($VAT_OPERATING_ROOM_FEE > 0)
                                {{ number_format($VAT_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line">
                            @if ($SP_OPERATING_ROOM_FEE > 0)
                                {{ number_format($SP_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs">
                            @if ($GOV_OPERATING_ROOM_FEE > 0)
                                {{ number_format($GOV_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                            @if ($OP_OPERATING_ROOM_FEE > 0)
                                {{ number_format($OP_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Supplies
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line">
                            @if ($CHARGES_SUPPLIES > 0)
                                {{ number_format($CHARGES_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line">
                            @if ($VAT_SUPPLIES > 0)
                                {{ number_format($VAT_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line">
                            @if ($SP_SUPPLIES > 0)
                                {{ number_format($SP_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs">
                            @if ($GOV_SUPPLIES > 0)
                                {{ number_format($GOV_SUPPLIES, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                            @if ($OP_SUPPLIES > 0)
                                {{ number_format($OP_SUPPLIES, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Administrative & Other Fees
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line">
                            @if ($CHARGES_OTHERS > 0)
                                {{ number_format($CHARGES_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line">
                            @if ($VAT_OTHERS > 0)
                                {{ number_format($VAT_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line">
                            @if ($SP_OTHERS > 0)
                                {{ number_format($SP_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs">
                            @if ($GOV_OTHERS > 0)
                                {{ number_format($GOV_OTHERS, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-center "> </div>
                        <div id="p-second" class="col-1 left-line text-center "> </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                            @if ($OP_OTHERS > 0)
                                {{ number_format($OP_OTHERS, 2) }}
                            @endif
                        </div>
                    </div>

                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-right ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                        </div>

                        <div id="p-first" class="col-2  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                        </div>
                    </div>


                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-center text-md">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line font-weight-bold">
                            @if ($CHARGES_SUB_TOTAL > 0)
                                {{ number_format($CHARGES_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line font-weight-bold">
                            @if ($VAT_SUB_TOTAL > 0)
                                {{ number_format($VAT_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line font-weight-bold">
                            @if ($SP_SUB_TOTAL > 0)
                                {{ number_format($SP_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line font-weight-bold">
                            @if ($GOV_SUB_TOTAL > 0)
                                {{ number_format($GOV_SUB_TOTAL, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-right  font-weight-bold">
                            @if ($P1_SUB_TOTAL > 0)
                                {{ number_format($P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-right font-weight-bold ">
                            @if ($P2_SUB_TOTAL > 0)
                                {{ number_format($P2_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-right left-line font-weight-bold">
                            {{ number_format($OP_SUB_TOTAL, 2) }}
                        </div>
                    </div>


                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left text-sm font-weight-italic">
                            Professional Fee/s
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line"> </div>
                        <div id="p-vat" class="col-1 text-center  left-line"> </div>
                        <div id="p-sp" class="col-1 text-center   left-line"> </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs"> </div>

                        <div id="p-first" class="col-2  left-line text-center "> </div>
                        <div id="p-second" class="col-1 left-line text-center "> </div>
                        <div id="p-pocket" class="col-1 text-center left-line"> </div>
                    </div>
                    {{-- Doctor --}}
                    @php
                        $i = 4;
                    @endphp
                    @foreach ($feeList as $list)
                        @php
                            $i++;
                        @endphp
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                <span class="text-md font-weight-bold">{{ $list->NAME }}</span>
                            </div>
                            <div id="p-charge" class="col-1 text-right left-line">
                                @if ($list->AMOUNT > 0)
                                    <i> {{ number_format($list->AMOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right left-line"> </div>
                            <div id="p-sp" class="col-1 text-right left-line">
                                @if ($list->DISCOUNT > 0)
                                    <i>{{ number_format($list->DISCOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs"> </div>
                            <div id="p-first" class="col-2  left-line text-right">
                                @if ($list->FIRST_CASE > 0)
                                    <i> {{ number_format($list->FIRST_CASE, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-second" class="col-1 left-line text-right "> </div>
                            <div id="p-pocket" class="col-1 text-right left-line">
                                @if ($list->FIRST_CASE > 0)
                                    <i> {{ number_format($list->AMOUNT - $list->DISCOUNT - $list->FIRST_CASE, 2) }}</i>
                                @endif
                            </div>
                        </div>
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left  ">
                                <i>Acreditation No. <b>{{ $list->PIN }}</b></i>
                            </div>
                            <div id="p-charge" class="col-1 text-center  left-line">
                                &nbsp;
                            </div>
                            <div id="p-vat" class="col-1 text-center  left-line">
                                &nbsp;</div>
                            <div id="p-sp" class="col-1 text-center   left-line">
                                &nbsp;
                            </div>
                            <div id="p-gov" class="col-1 text-center  left-line text-xs">
                                &nbsp; </div>

                            <div id="p-first" class="col-2  left-line text-center ">
                                &nbsp;
                            </div>
                            <div id="p-second" class="col-1 left-line text-center ">
                                &nbsp;
                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line">
                                &nbsp;
                            </div>
                        </div>
                    @endforeach

                    @for ($n = 1; $n <= 5; $n++)
                        @if ($n > $i)
                            <div class="row bottom-line right-line left-line">
                                <div id="p-particular" class="col-4 text-left ">
                                    {{ $n . '. ' }}
                                </div>
                                <div id="p-charge" class="col-1 text-center  left-line">
                                    &nbsp;
                                </div>
                                <div id="p-vat" class="col-1 text-center  left-line">
                                    &nbsp;</div>
                                <div id="p-sp" class="col-1 text-center   left-line">
                                    &nbsp;
                                </div>
                                <div id="p-gov" class="col-1 text-center  left-line text-xs">
                                    &nbsp; </div>

                                <div id="p-first" class="col-2  left-line text-center ">
                                    &nbsp;
                                </div>
                                <div id="p-second" class="col-1 left-line text-center ">
                                    &nbsp;
                                </div>
                                <div id="p-pocket" class="col-1 text-center left-line">
                                    &nbsp;
                                </div>
                            </div>
                        @endif
                    @endfor
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-center ">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line font-weight-bold">
                            @if ($PROFESSIONAL_FEE_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line font-weight-bold"> </div>
                        <div id="p-sp" class="col-1 text-right   left-line font-weight-bold">
                            @if ($PROFESSIONAL_DISCOUNT_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_DISCOUNT_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs font-weight-bold"> </div>

                        <div id="p-first" class="col-2  left-line text-right font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-right font-weight-bold"> </div>
                        <div id="p-pocket" class="col-1 text-right left-line font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL - $PROFESSIONAL_DISCOUNT_SUB_TOTAL - $PROFESSIONAL_P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-right ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                        </div>

                        <div id="p-first" class="col-2  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                        </div>
                    </div>

                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-center text-md ">
                            <b>TOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line font-weight-bold text-md">
                            @if ($CHARGE_TOTAL > 0)
                                {{ number_format($CHARGE_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line font-weight-bold text-md">
                            @if ($VAT_TOTAL > 0)
                                {{ number_format($VAT_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line font-weight-bold text-md">
                            @if ($SP_TOTAL > 0)
                                {{ number_format($SP_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs font-weight-bold text-md">
                            @if ($GOV_TOTAL > 0)
                                {{ number_format($GOV_TOTAL, 2) }}
                            @endif
                        </div>

                        <div id="p-first" class="col-2  left-line text-right font-weight-bold text-md">
                            @if ($P1_TOTAL > 0)
                                {{ number_format($P1_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-right  font-weight-bold text-md">
                            @if ($P2_TOTAL > 0)
                                {{ number_format($P2_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-right left-line font-weight-bold text-md">
                            {{ number_format($OP_TOTAL, 2) }}
                        </div>
                    </div>
                </div>

                @if ($OUTPUT_SIGN)
                    <div class="col-12 mt-4">
                        <div class="row">
                            <div class="col-4">
                                Prepared by:
                                <div class="form-group row  mt-4">
                                    <div class="col-10 text-center bottom-line">
                                        <strong>
                                            {{ $USER_NAME }}</strong>
                                    </div>
                                    <div class="col-10 text-center">PHIC IN-Charge</div>
                                    <div class="col-10 mt-4 bottom-line text-center">Date Signed:
                                        {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                    </div>
                                    <div class="col-12 ">CONTACT No. {{ $USER_CONTACT }}</div>
                                </div>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                Conforme:
                                <div class="form-group row  mt-4">
                                    <div class="col-12 text-center bottom-line"><b>{{ $PATIENT_NAME }}</b></div>
                                    <div class="col-12 ">Member/Patient/Authorized Representative</div>
                                    <div class="col-12 ">(Signature over printed name)</div>
                                    <div class="col-12 text-xs">Relationship of member of authorized representative
                                    </div>
                                    <div class="col-12 bottom-line">&nbsp;</div>
                                    <div class="col-12">Date Signed:
                                        {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : ' ' }}
                                    </div>
                                    <div class="col-12 ">CONTACT No. {{ $PATIENT_CONTACT }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

</div>
