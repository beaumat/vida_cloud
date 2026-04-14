<div class="" style="line-height: 0.6;">
    <div class="row ">
        <div class="col-12 mb-4">
            <h1 class="times-new-roman">Statement Of Account Computation</h1>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text">ACTUAL CHARGES:</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 100px;">HCI FEES</p>
        </div>
        {{-- Drug --}}
        <div class="col-12 ">
            <p class="times-new-roman soa-text" style="padding-left: 200px;">Drugs And Medicine:</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 280px;">No of Treatment X
                {{ number_format($DRUG_N_MEDINE_AMOUNT, 2) }}</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 370px;"> <i> Ex. {{ $NO_OF_TREATMENT }} treatment X
                    {{ number_format($DRUG_N_MEDINE_AMOUNT, 2) }} =
                    {{ number_format($DRUG_N_MEDINE_AMOUNT * $NO_OF_TREATMENT, 2) }}
                </i></p>
        </div>
        {{-- Operation --}}
        <div class="col-12 mt-4">
            <p class="times-new-roman soa-text" style="padding-left: 200px;">Operation Room Fees:</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 280px;">No of Treatment X
                {{ number_format($OPERATING_ROOM_FEE_AMOUNT + $ROOM_FEE, 2) }}</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 370px;"> <i> Ex. {{ $NO_OF_TREATMENT }} treatment X
                    {{ number_format($OPERATING_ROOM_FEE_AMOUNT + $ROOM_FEE, 2) }} =
                    {{ number_format(($OPERATING_ROOM_FEE_AMOUNT + $ROOM_FEE) * $NO_OF_TREATMENT, 2) }}
                </i></p>
        </div>
        {{-- Supplies  --}}
        <div class="col-12 mt-4">
            <p class="times-new-roman soa-text" style="padding-left: 200px;">Supplies:</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 280px;">No of Treatment X
                {{ number_format($SUPPLIES, 2) }}</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 370px;"> <i> Ex. {{ $NO_OF_TREATMENT }} treatment X
                    {{ number_format($SUPPLIES, 2) }} = {{ number_format($SUPPLIES * $NO_OF_TREATMENT, 2) }}
                </i></p>
        </div>
        {{-- Professional Fees  --}}
        <div class="col-12 mt-4">
            <p class="times-new-roman soa-text" style="padding-left: 200px;">Professional Fees:</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 280px;">No of Treatment X
                {{ number_format($PROF_FEE_AMOUNT, 2) }}</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 370px;"> <i> Ex. {{ $NO_OF_TREATMENT }} treatment
                    X
                    {{ number_format($PROF_FEE_AMOUNT, 2) }} =
                    {{ number_format($PROF_FEE_AMOUNT * $NO_OF_TREATMENT, 2) }}
                </i></p>
        </div>

        <div class="col-12" style="padding-top:80px;">
            <p class="times-new-roman soa-text">PHILHEALTH BENEFITS:</p>
        </div>
        {{-- Subtotal:  --}}
        <div class="col-12 ">
            <p class="times-new-roman soa-text" style="padding-left: 100px;">Subtotal:</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 200px;">No of Treatment X
                {{ number_format($P1_PHIC_AMOUNT, 2) }}</p>
            </p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 280px;"> <i> Ex. {{ $NO_OF_TREATMENT }} treatment
                    X
                    {{ number_format($P1_PHIC_AMOUNT, 2) }} =
                    {{ number_format($P1_PHIC_AMOUNT * $NO_OF_TREATMENT, 2) }}
                </i></p>
        </div>
        {{-- Professional Fees  --}}
        <div class="col-12 mt-4">
            <p class="times-new-roman soa-text" style="padding-left: 100px;">Professional Fees:</p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 200px;">No of Treatment X
                {{ number_format($PROF_FEE_AMOUNT, 2) }}</p>
            </p>
        </div>
        <div class="col-12">
            <p class="times-new-roman soa-text" style="padding-left: 280px;"> <i> Ex. {{ $NO_OF_TREATMENT }} treatment
                    X
                    {{ number_format($PROF_FEE_AMOUNT, 2) }} =
                    {{ number_format($PROF_FEE_AMOUNT * $NO_OF_TREATMENT, 2) }}</p>
            </i></p>
        </div>
    </div>
</div>
