<div>

    <div class="row top-line2 right-line2 left-line2 text-center mt-1 ">
        <div class="col-12 font-weight-bold text-danger text-sm">SUMMARY OF FEES</div>
    </div>
    <div class="row top-line2 bottom-line2 right-line2 left-line2 text-sm">
        <div class="col-3 text-center ">
            <div class="top" style="top:0"> PARTICULARS </div>
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
        <div class="col-1 text-center left-line2">
            AMOUNT AFTER DISCOUNT
        </div>
        <div class="col-2  left-line2 text-center ">
            PHILHEALTH <br /> PACKAGE
        </div>
        <div class="col-2 text-center  left-line2">
            <div class="row text-left text-xs">
                <div class="col-12">___PCSO</div>
                <div class="col-12">___DSWD</div>
                <div class="col-12">___HMO</div>
                <div class="col-12">___OTHER FUNDING SOURCE/ FACILITY SUBSIDY</div>
            </div>

        </div>
        <div class="col-1 text-center left-line2">
            OUT OF POCKET OF PATIENT
        </div>
    </div>

    <div class="row bottom-line2 right-line2 left-line2 text-sm">

        <div id="p-particular" class="col-3 text-left ">
            <div class="bottom font-weight-bold text-center" style="bottom: 0;">
                HCI FEE/S
            </div>
        </div>
        <div id="p-charge" class="col-1 text-right  left-line2">

        </div>
        <div id="p-vat" class="col-1 text-right  left-line2">
        </div>
        <div id="p-sp" class="col-1 text-right   left-line2">
        </div>
        <div id="p-after-disc" class="col-1 text-right  left-line2"> </div>
        <div id="p-first" class="col-2  left-line2 text-right "> </div>
        <div id="p-gov" class="col-2 text-right  left-line2 text-xs">

        </div>
        <div id="p-pocket" class="col-1 text-center left-line2"> </div>
    </div>
    @if ($CHARGES_ROOM_N_BOARD > 0)
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
            <div id="p-sp" class="col-1 text-right  left-line2">
                @if ($SP_ROOM_N_BOARD > 0)
                    {{ number_format($SP_ROOM_N_BOARD, 2) }}
                @endif
            </div>

            <div id="p-after-disc" class="col-1 text-right  left-line2"> </div>
            <div id="p-first" class="col-1  left-line2 text-right "> </div>
            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                @if ($GOV_ROOM_N_BOARD > 0)
                    {{ number_format($GOV_ROOM_N_BOARD, 2) }}
                @endif
            </div>
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

            <div id="p-after-disc" class="col-1 text-right  left-line2">
                @if ($P1_DRUG_N_MEDICINE > 0)
                    {{ number_format($P1_DRUG_N_MEDICINE, 2) }}
                @endif
            </div>
            <div id="p-first" class="col-2  left-line2 text-right ">
                @if ($P1_DRUG_N_MEDICINE > 0)
                    {{ number_format($P1_DRUG_N_MEDICINE, 2) }}
                @endif
            </div>
            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                @if ($GOV_DRUG_N_MEDICINE > 0)
                    {{ number_format($GOV_DRUG_N_MEDICINE, 2) }}
                @endif
            </div>
            <div id="p-pocket" class="col-1 text-center left-line2"> </div>
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

            <div id="p-after-disc" class="col-1 text-right  left-line2">
                @if ($P1_SUPPLIES > 0)
                    {{ number_format($P1_SUPPLIES, 2) }}
                @endif
            </div>
            <div id="p-first" class="col-2  left-line2 text-right ">
                @if ($P1_SUPPLIES > 0)
                    {{ number_format($P1_SUPPLIES, 2) }}
                @endif
            </div>
            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                @if ($GOV_SUPPLIES > 0)
                    {{ number_format($GOV_SUPPLIES, 2) }}
                @endif
            </div>
            <div id="p-pocket" class="col-1 text-center left-line2">
            </div>
        </div>
    @endif

    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-3 text-left ">
            LABORATORY & DIAGNOSTIC
        </div>
        <div id="p-charge" class="col-1 text-right  left-line2 font-italic">
            @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
            @else
                -
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

        <div id="p-after-disc" class="col-1 text-right  left-line2">
            @if ($P1_LAB_N_DIAGNOSTICS > 0)
                {{ number_format($P1_LAB_N_DIAGNOSTICS, 2) }}
            @endif
        </div>
        <div id="p-first" class="col-2  left-line2 text-right ">
            @if ($P1_LAB_N_DIAGNOSTICS > 0)
                {{ number_format($P1_LAB_N_DIAGNOSTICS, 2) }}
            @endif
        </div>
        <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
            @if ($GOV_LAB_N_DIAGNOSTICS > 0)
                {{ number_format($GOV_LAB_N_DIAGNOSTICS, 2) }}
            @endif
        </div>
        <div id="p-pocket" class="col-1 text-center left-line2">

        </div>
    </div>

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

            <div id="p-after-disc" class="col-1 text-right  left-line2">
                @if ($P1_OPERATING_ROOM_FEE > 0)
                    {{ number_format($P1_OPERATING_ROOM_FEE, 2) }}
                @endif
            </div>
            <div id="p-first" class="col-2  left-line2 text-right ">
                @if ($P1_OPERATING_ROOM_FEE > 0)
                    {{ number_format($P1_OPERATING_ROOM_FEE, 2) }}
                @endif
            </div>
            <div id="p-gov" class="col-2 text-right  left-line2 text-xs">
                @if ($GOV_OPERATING_ROOM_FEE > 0)
                    {{ number_format($GOV_OPERATING_ROOM_FEE, 2) }}
                @endif
            </div>
            <div id="p-pocket" class="col-1 text-center left-line2">

            </div>
        </div>
    @endif
    @if ($CHARGES_OTHERS > 0)
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

            <div id="p-after-disc" class="col-1  left-line2 text-right ">
                @if ($P1_OTHERS > 0)
                    {{ number_format($P1_OTHERS, 2) }}
                @endif
            </div>
            <div id="p-first" class="col-2  left-line2 text-right ">
                @if ($P1_OTHERS > 0)
                    {{ number_format($P1_OTHERS, 2) }}
                @endif
            </div>
            <div id="p-after-disc" class="col-2 text-right  left-line2 text-xs">
                @if ($GOV_OTHERS > 0)
                    {{ number_format($GOV_OTHERS, 2) }}
                @endif
            </div>
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

        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
            @if ($AD_SUB_TOTAL > 0)
                {{ number_format($AD_SUB_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-first" class="col-2  left-line2 text-right  font-weight-bold">
            @if ($P1_SUB_TOTAL > 0)
                {{ number_format($P1_SUB_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-gov" class="col-2 text-right  left-line2 font-weight-bold">
            @if ($GOV_SUB_TOTAL > 0)
                {{ number_format($GOV_SUB_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-pocket" class="col-1 text-right left-line2 font-weight-bold">
            @if ($OP_SUB_TOTAL > 0)
                {{ number_format($OP_SUB_TOTAL, 2) }}
            @else
                &nbsp;-
            @endif

        </div>
    </div>
    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-12 text-center font-weight-bold text-left font-weight-light text-danger">
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

            <div id="p-after-disc" class="col-1 text-right  left-line2 font-italic">
                @if ($list->DISCOUNT > 0)
                    <i> {{ number_format($list->AMOUNT - $list->DISCOUNT, 2) }}</i>
                @endif
            </div>
            <div id="p-first" class="col-2  left-line2 text-right font-italic">
                @if ($list->FIRST_CASE > 0)
                    <i> {{ number_format($list->FIRST_CASE, 2) }}</i>
                @endif
            </div>
            <div id="p-gov" class="col-2 text-right  left-line2 text-xs font-italic"> </div>
            <div id="p-pocket" class="col-1 text-right left-line2">
                &nbsp;-
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

            <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">

            </div>
            <div id="p-first" class="col-2  left-line2 text-right font-weight-bold">

            </div>
            <div id="p-gov" class="col-2 text-right  left-line2 text-xs font-weight-bold"> </div>
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

        <div id="p-first" class="col-1  left-line2 text-right font-weight-bold">
            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-second" class="col-2 left-line2 text-right font-weight-bold ">
            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-pocket" class="col-2 text-right left-line2 ">

        </div>
        <div id="p-pocket" class="col-1 text-right left-line2 ">
            -
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

        <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
            @if ($AD_TOTAL > 0)
                {{ number_format($AD_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-first" class="col-2  left-line2 text-right font-weight-bold">
            @if ($P1_TOTAL > 0)
                {{ number_format($P1_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-second" class="col-2 left-line2 text-right  font-weight-bold">
            @if ($GOV_TOTAL > 0)
                {{ number_format($GOV_TOTAL, 2) }}
            @endif
        </div>
        <div id="p-pocket" class="col-1 text-center text-xs left-line2 font-weight-bold">
            ZERO COPAY
        </div>
    </div>



</div>
