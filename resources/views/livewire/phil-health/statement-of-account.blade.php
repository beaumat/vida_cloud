<div class="row">
    <div class="col-md-10">
        <table class="table table-sm text-xs table-bordered ">
            <thead>
                <tr class="text-center">
                    <th style="width: 25%;" class="bg-success">PARTICULARS</th>
                    <th style="width: 10%;" class="bg-primary">ACTUAL CHARGES</th>
                    <th style="width: 10%;" class="bg-info">VAT EXEMPT</th>
                    <th style="width: 10%;" class="bg-info">SENIOR CITIZEN/ PWD DISC.</th>
                    <th style="width: 5%;" class="bg-info">AMOUNT AFTER <br /> DISCOUNT</th>
                    <th style="width: 10%;" class="bg-warning">PHILHEALTH <br /> PACKAGE</th>
                    <th style="width: 10%;" class="bg-info">
                        <ul style="list-style: none; padding: 0; margin: 0;" class="text-center">
                            <li>PCSO</li>
                            <li>DSWD</li>
                            <li>DOH</li>
                            <li>HMO</li>
                            <li>LINGAP</li>
                        </ul>
                    </th>
                    <th style="width: 10%;" class="bg-secondary">Out of Pocket of Patients</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td class="text-center font-weight-bold"><label class="text-sm">HCI Fee</label></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Room and Board</td>
                    <td class="text-right">
                        @if ($CHARGES_ROOM_N_BOARD > 0)
                            {{ number_format($CHARGES_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                    <td class="text-right">

                        @if ($VAT_ROOM_N_BOARD > 0)
                            {{ number_format($VAT_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($SP_ROOM_N_BOARD > 0)
                            {{ number_format($SP_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                    <td>
                   
                    </td>
                    <td>
               
                    </td>
                    <td class="text-right">
                        @if ($GOV_ROOM_N_BOARD > 0)
                            {{ number_format($GOV_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($OP_ROOM_N_BOARD > 0)
                            {{ number_format($OP_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                </tr> --}}
                <tr>
                    <td class="text-left">Drug & Medicines</td>
                    <td class="text-right">
                        @if ($CHARGES_DRUG_N_MEDICINE > 0)
                            {{ number_format($CHARGES_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($VAT_DRUG_N_MEDICINE > 0)
                            {{ number_format($VAT_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($SP_DRUG_N_MEDICINE > 0)
                            {{ number_format($SP_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- DISCOUNT --}}
                        @if ($P1_DRUG_N_MEDICINE > 0)
                            {{ number_format($P1_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- PACKAGE --}}
                        @if ($P1_DRUG_N_MEDICINE > 0)
                            {{ number_format($P1_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($GOV_DRUG_N_MEDICINE > 0)
                            {{ number_format($GOV_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($OP_DRUG_N_MEDICINE > 0)
                            {{ number_format($OP_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-left">Laboratory & Diagnostics</td>
                    <td class="text-right">
                        @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">

                        @if ($VAT_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($VAT_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">

                        @if ($SP_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($P1_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($P1_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($P1_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($P1_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($GOV_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($GOV_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($OP_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($OP_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-left">Operating Room Fees</td>
                    <td class="text-right">
                        {{ number_format($CHARGES_OPERATING_ROOM_FEE, 2) }}
                    </td>
                    <td class="text-right">
                        @if ($VAT_OPERATING_ROOM_FEE > 0)
                            {{ number_format($VAT_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($SP_OPERATING_ROOM_FEE > 0)
                            {{ number_format($SP_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($P1_OPERATING_ROOM_FEE > 0)
                            {{ number_format($P1_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($P1_OPERATING_ROOM_FEE > 0)
                            {{ number_format($P1_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($GOV_OPERATING_ROOM_FEE > 0)
                            {{ number_format($GOV_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td>
                        @if ($OP_OPERATING_ROOM_FEE > 0)
                            {{ number_format($OP_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-left">Supplies</td>
                    <td class="text-right">
                        @if ($CHARGES_SUPPLIES > 0)
                            {{ number_format($CHARGES_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($VAT_SUPPLIES > 0)
                            {{ number_format($VAT_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($SP_SUPPLIES > 0)
                            {{ number_format($SP_SUPPLIES, 2) }}
                        @endif
                    </td>

                    <td class="text-right">
                        @if ($P1_SUPPLIES > 0)
                            {{ number_format($P1_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($P1_SUPPLIES > 0)
                            {{ number_format($P1_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($GOV_SUPPLIES > 0)
                            {{ number_format($GOV_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($OP_SUPPLIES > 0)
                            {{ number_format($OP_SUPPLIES, 2) }}
                        @endif
                    </td>

                </tr>

                <tr>
                    <td class="text-left">Administrative & Others Fee</td>
                    <td class="text-right">

                        @if ($CHARGES_OTHERS > 0)
                            {{ number_format($CHARGES_OTHERS, 2) }}
                        @endif
                    </td>
                    <td>

                        @if ($VAT_OTHERS > 0)
                            {{ number_format($VAT_OTHERS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($SP_OTHERS > 0)
                            {{ number_format($SP_OTHERS, 2) }}
                        @endif
                    </td>

                    <td class="text-right">
                        @if ($P1_OTHERS > 0)
                            {{ number_format($P1_OTHERS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($P1_OTHERS > 0)
                            {{ number_format($P1_OTHERS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($GOV_OTHERS > 0)
                            {{ number_format($GOV_OTHERS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($OP_OTHERS > 0)
                            {{ number_format($OP_OTHERS, 2) }}
                        @endif
                    </td>
                </tr>
                <tr class="text-xs">
                    <td class="text-left"><label class="text-xs">SUBTOTAL</label></td>
                    <td class="text-right font-weight-bold ">
                        @if ($CHARGES_SUB_TOTAL > 0)
                            {{ number_format($CHARGES_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($VAT_SUB_TOTAL > 0)
                            {{ number_format($VAT_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($SP_SUB_TOTAL > 0)
                            {{ number_format($SP_SUB_TOTAL, 2) }}
                        @endif
                    </td>

                    <td class="text-right font-weight-bold">

                        @if ($AD_SUB_TOTAL > 0)
                            {{ number_format($AD_SUB_TOTAL, 2) }}
                        @endif
                        {{-- DISCOUNT --}}
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($P1_SUB_TOTAL > 0)
                            {{ number_format($P1_SUB_TOTAL, 2) }}
                        @endif
                        {{-- PACKAGE --}}
                    </td>
                    {{-- <td class="text-right font-weight-bold">
                        @if ($P2_SUB_TOTAL > 0)
                            {{ number_format($P2_SUB_TOTAL, 2) }}
                        @endif
                    </td> --}}

                    <td class="text-right font-weight-bold">
                        @if ($GOV_SUB_TOTAL > 0)
                            {{ number_format($GOV_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold ">
                        @if ($OP_SUB_TOTAL > 0)
                            {{ number_format($OP_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                </tr>
                @if ($feeList)
                    <tr>
                        <td class="text-xs text-info">Professional Fee/s</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    @foreach ($feeList as $list)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td> {{ $i . '.)' }} {{ $list->NAME }} </td>
                            <td class="text-right font-weight-bold text-xs ">
                                @if ($list->AMOUNT > 0)
                                    {{ number_format($list->AMOUNT, 2) }}
                                @endif
                            </td>
                            <td></td>
                            <td class="text-right font-weight-bold text-xs ">
                                @if ($list->DISCOUNT > 0)
                                    {{ number_format($list->DISCOUNT, 2) }}
                                @endif
                            </td>
                            <td class="discount text-right font-weight-bold">
                                @if ($list->DISCOUNT > 0)
                                    {{ number_format($list->FIRST_CASE, 2) }}
                                @endif
                                {{-- DISCOUNT --}}
                            </td>
                            <td class="text-right font-weight-bold text-xs ">
                                @if ($list->FIRST_CASE > 0)
                                    {{ number_format($list->FIRST_CASE, 2) }}
                                @endif
                                {{-- PACKAGE --}}
                            </td>
                            <td></td>
                            <td class="text-right">

                            </td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td class="text-left">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold ">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>

                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                </tr>
                <tr class="text-primary text-xs">
                    <td class="text-left"><label>TOTAL</label></td>
                    <td class="text-right font-weight-bold ">
                        @if ($CHARGE_TOTAL > 0)
                            {{ number_format($CHARGE_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($VAT_TOTAL > 0)
                            {{ number_format($VAT_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($SP_TOTAL > 0)
                            {{ number_format($SP_TOTAL, 2) }}
                        @endif
                    </td>

                    <td class="text-right font-weight-bold">
                        @if ($AD_TOTAL > 0)
                            {{ number_format($AD_TOTAL, 2) }}
                        @endif
                        {{-- DISCOUNT --}}
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($P1_TOTAL > 0)
                            {{ number_format($P1_TOTAL, 2) }}
                        @endif
                        {{-- PACKAGE --}}
                    </td>
                    {{-- <td class="text-right font-weight-bold">
                        @if ($P2_TOTAL > 0)
                            {{ number_format($P2_TOTAL, 2) }}
                        @endif
                    </td> --}}

                    <td class="text-right font-weight-bold ">
                        @if ($GOV_TOTAL > 0)
                            {{ number_format($GOV_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($OP_TOTAL > 0)
                            {{ number_format($OP_TOTAL, 2) }}
                        @endif
                    </td>

                </tr>
            </tbody>
        </table>

    </div>

</div>
