<div>

    <div class="row text-center mt-1 ">
        <div class="col-12 font-weight-bold text-md">SUMMARY OF FEES asdasd</div>
    </div>
    <div class="row top-line2 bottom-line2 right-line2 left-line2 text-sm ">
        <div class="col-3 text-center ">
            <div class="top" style="top:0"> <strong>Fee Particular asdasd</strong> </div>
        </div>
        <div class="col-1 text-center left-line2 ">
            <strong>Amount</strong>
        </div>

        <div class="col-2 text-center left-line2">
            <strong>Mandatory Discount asdasd</strong>
        </div>

        <div class="col-2  left-line2 text-center ">
            <strong>Philhealth asdasdasd</strong>
        </div>
        <div class="col-2 text-center  left-line2">
            <strong>Other Funding Sources</strong>
        </div>
        <div class="col-2 text-center left-line2">
            <strong>Balance</strong>
        </div>
    </div>


    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-3 text-left ">
            Room and Board
        </div>
        <div id="p-charge" class="col-1 text-center  left-line2">
            -
        </div>
        <div id="p-sp" class="col-2 text-center  left-line2">
            -
        </div>
        <div id="p-first" class="col-2  left-line2 text-center "> -</div>
        <div id="p-gov" class="col-2 text-center  left-line2 text-xs">
            -
        </div>
        <div id="p-pocket" class="col-2 text-center left-line2">- </div>
    </div>

    @if ($CHARGES_DRUG_N_MEDICINE > 0 || $PRE_SIGN_DATA == true)
        <div class="row bottom-line2 right-line2 left-line2 text-sm">
            <div id="p-particular" class="col-3 text-left ">
                Drug and Medicine
            </div>
            <div id="p-charge" class="col-1 text-center  left-line2">
                @if ($CHARGES_DRUG_N_MEDICINE > 0)
                    {{ number_format($CHARGES_DRUG_N_MEDICINE, 2) }}
                @endif
            </div>

            <div id="p-sp" class="col-2 text-center   left-line2">
                @if ($SP_DRUG_N_MEDICINE > 0)
                    {{ number_format($SP_DRUG_N_MEDICINE, 2) }}
                @endif
            </div>


            <div id="p-first" class="col-2  left-line2 text-center ">
                @if ($P1_DRUG_N_MEDICINE > 0)
                    {{ number_format($P1_DRUG_N_MEDICINE, 2) }}
                @endif
                
            </div>
            <div id="p-gov" class="col-2 text-center  left-line2 text-xs">
                -
            </div>
            <div id="p-pocket" class="col-2 text-center left-line2"> - </div>
        </div>
    @endif


    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-3 text-left ">
            Laboratory & Diagnostic
        </div>
        <div id="p-charge" class="col-1 text-center  left-line2 ">
            @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
            @else
                -
            @endif
        </div>
        <div id="p-sp" class="col-2 text-center   left-line2">
            @if ($SP_LAB_N_DIAGNOSTICS > 0)
                {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
            @endif
        </div>
        <div id="p-first" class="col-2  left-line2 text-center ">
            @if ($P1_LAB_N_DIAGNOSTICS > 0)
                {{ number_format($P1_LAB_N_DIAGNOSTICS, 2) }}
            @endif
            
        </div>
        <div id="p-gov" class="col-2 text-center  left-line2 text-xs">
            -
        </div>
        <div id="p-pocket" class="col-2 text-center left-line2">
            -
        </div>
    </div>


    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-3 text-left ">
            Operating Room Fees
        </div>
        <div id="p-charge" class="col-1 text-center  left-line2">
            {{-- @if ($CHARGES_OPERATING_ROOM_FEE > 0)
                {{ number_format($CHARGES_OPERATING_ROOM_FEE, 2) }}
            @else
                -
            @endif --}}
            -
        </div>

        <div id="p-sp" class="col-2 text-center   left-line2">
            @if ($SP_OPERATING_ROOM_FEE > 0)
                {{ number_format($SP_OPERATING_ROOM_FEE, 2) }}
            @else
                -
            @endif
        </div>


        <div id="p-first" class="col-2  left-line2 text-center ">
            @if ($P1_OPERATING_ROOM_FEE > 0)
                {{ number_format($P1_OPERATING_ROOM_FEE, 2) }}
            @else
                -
            @endif
        </div>
        <div id="p-gov" class="col-2 text-center  left-line2 text-xs">
            -
        </div>
        <div id="p-pocket" class="col-2 text-center left-line2">
            -
        </div>
    </div>
    @if ($CHARGES_SUPPLIES > 0 || $PRE_SIGN_DATA == true)
        <div class="row bottom-line2 right-line2 left-line2 text-sm">
            <div id="p-particular" class="col-3 text-left ">
                Medical Supplies
            </div>
            <div id="p-charge" class="col-1 text-center  left-line2 ">
                @if ($CHARGES_SUPPLIES > 0)
                    {{ number_format($CHARGES_SUPPLIES, 2) }}
                @endif
            </div>

            <div id="p-sp" class="col-2 text-center   left-line2">
                @if ($SP_SUPPLIES > 0)
                    {{ number_format($SP_SUPPLIES, 2) }}
                @endif
            </div>


            <div id="p-first" class="col-2  left-line2 text-center ">
                @if ($P1_SUPPLIES > 0)
                    {{ number_format($P1_SUPPLIES, 2) }}
                @endif
                
            </div>
            <div id="p-gov" class="col-2 text-center  left-line2 text-xs">
                
            </div>
            <div id="p-pocket" class="col-2 text-center left-line2"> -
            </div>
        </div>
    @endif

    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-3 text-left ">
            Others
        </div>
        <div id="p-charge" class="col-1 text-center  left-line2 ">
            @if ($CHARGES_OTHERS > 0)
                {{ number_format($CHARGES_OTHERS, 2) }}
            @else
                -
            @endif
        </div>

        <div id="p-sp" class="col-2 text-center   left-line2 ">
            @if ($SP_OTHERS > 0)
                {{ number_format($SP_OTHERS, 2) }}
            @else
                
            @endif
        </div>


        <div id="p-first" class="col-2  left-line2 text-center font-weight-bold">
            @if ($P1_OTHERS > 0)
                {{ number_format($P1_OTHERS, 2) }}
            @else
                -
            @endif
            
        </div>
        <div id="p-after-disc" class="col-2 text-center  left-line2  font-weight-bold">
            -
        </div>
        <div id="p-pocket" class="col-2 text-center left-line2 font-weight-bold">
            -
        </div>
    </div>



    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-3 text-left ">
            <b>Total</b>
        </div>
        <div id="p-charge" class="col-1 text-center  left-line2 font-weight-bold">
            @if ($CHARGES_SUB_TOTAL > 0)
                {{ number_format($CHARGES_SUB_TOTAL, 2) }}
            @else
                -
            @endif
        </div>
        {{-- <div id="p-vat" class="col-1 text-right  left-line2 font-weight-bold">
            @if ($VAT_SUB_TOTAL > 0)
                {{ number_format($VAT_SUB_TOTAL, 2) }}
            @endif
        </div> --}}
        <div id="p-sp" class="col-2 text-center   left-line2 font-weight-bold">
            @if ($SP_SUB_TOTAL > 0)
                ({{ number_format($SP_SUB_TOTAL, 2) }})
            @else
                -
            @endif
        </div>
        {{-- <div id="p-after-disc" class="col-1 text-right  left-line2 font-weight-bold">
            @if ($AD_SUB_TOTAL > 0)
                {{ number_format($AD_SUB_TOTAL, 2) }}
            @endif
        </div> --}}
        <div id="p-first" class="col-2  left-line2 text-center  font-weight-bold">
            @if ($P1_SUB_TOTAL > 0)
                ({{ number_format($P1_SUB_TOTAL, 2) }})
            @endif
        </div>
        <div id="p-gov" class="col-2 text-center  left-line2 font-weight-bold">
            @if ($GOV_SUB_TOTAL > 0)
                {{ number_format($GOV_SUB_TOTAL, 2) }}
            @else
                (0.00)
            @endif
        </div>
        <div id="p-pocket" class="col-2 text-center left-line2 font-weight-bold">
            @if ($OP_SUB_TOTAL > 0)
                {{ number_format($OP_SUB_TOTAL, 2) }}
            @else
                0.00
            @endif
        </div>
    </div>
    <div class="row  mt-4">
        <div id="p-particular" class="col-12 text-center font-weight-bold text-left font-weight-light text-md">
            PROFESSIONAL FEES
        </div>
    </div>
    <div class="row bottom-line2 right-line2 left-line2 top-line2 text-sm">
        <div id="p-particular" class="col-3 text-center font-weight-bold">
            Physician Accreditation Number
        </div>
        <div id="p-charge" class="col-3 text-center  left-line2 font-weight-bold">
            Physician Name
        </div>
        <div id="p-vat" class="col-1 text-center left-line2 font-weight-bold"> Amount </div>
        <div id="p-sp" class="col-1 text-center left-line2 font-weight-bold">
            Discount
        </div>
        <div id="p-after-disc" class="col-1 text-center  left-line2 font-weight-bold">
            Philhealth
        </div>
        <div id="p-first" class="col-2  left-line2 text-center font-weight-bold">
            Others Funding Source
        </div>
        <div id="p-pocket" class="col-1 text-center  font-weight-bold left-line2">
            Balance
        </div>
    </div>
    @php
        $i = 0;
        $AMOUNT = 0;
        $DISCOUNT = 0;
        $TOTAL = 0;
    @endphp
    @foreach ($feeList as $list)
        @php
            $AMOUNT = $list->AMOUNT;
            $DISCOUNT = $list->DISCOUNT;
            $TOTAL = $list->FIRST_CASE;
        @endphp
        <div class="row bottom-line2 right-line2 left-line2 top-line2 text-sm">
            <div id="p-particular" class="col-3 text-center ">
                {{ $list->PIN }}
            </div>
            <div id="p-charge" class="col-3 text-center  left-line2">
                <span class="text-sm">{{ $list->NAME }}</span>
            </div>
            <div id="p-vat" class="col-1 text-center left-line2 ">
                @if ($list->AMOUNT > 0)
                    <i> {{ number_format($list->AMOUNT, 2) }}</i>
                @endif
            </div>
            <div id="p-sp" class="col-1 text-center left-line2 ">
                @if ($list->DISCOUNT > 0)
                    <i>{{ number_format($list->DISCOUNT, 2) }}</i>
                @endif
            </div>
            <div id="p-after-disc" class="col-1 text-center  left-line2">
                -
            </div>
            <div id="p-first" class="col-2  left-line2 text-center ">
                -
            </div>
            <div id="p-pocket" class="col-1 text-center  left-line2">
                -
            </div>
        </div>
    @endforeach


    <div class="row bottom-line2 right-line2 left-line2 text-sm">
        <div id="p-particular" class="col-6 text-left font-weight-bold">
            <b>TOTAL</b>
        </div>
        <div id="p-sp" class="col-1 text-center   left-line2 font-weight-bold">
            <i> {{ number_format($AMOUNT, 2) }}</i>
        </div>
        <div id="p-d" class="col-1  left-line2 text-center font-weight-bold">
            (<i>{{ number_format($DISCOUNT, 2) }}</i>)
        </div>
        <div id="p-first" class="col-1 left-line2 text-center font-weight-bold">
            (<i>{{ number_format($TOTAL, 2) }}</i>)
        </div>
        <div id="p-second" class="col-2 left-line2 text-center  font-weight-bold">
            (0.00)
        </div>
        <div id="p-pocket" class="col-1 text-center left-line2 font-weight-bold">
            0.00
        </div>
    </div>
</div>
