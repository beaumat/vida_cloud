<?php
use App\Services\ItemSoaItemizedServices;
use App\Services\ItemSoaServices;
?>

<div class="row bottom-line2 right-line2 left-line2 text-sm">
    <div class="col-12 font-weight-bold text-center text-danger">
        ITEMIZED CHARGES
    </div>
    <div class="col-8">
        <table class="w-100" border="1">
            <thead>
                <tr class="text-center text-sm">
                    <th>SERVICE DATE</th>
                    <th>ITEM NAME</th>
                    <th>UNIT OF MEASUREMENT</th>
                    <th>PRICE</th>
                    <th>QTY</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            @php
                $TOTAL = 0;
                $TYPE = '';
                $posted = false;
            @endphp
            @php
                $row = 0;
            @endphp
            <tbody class='text-xs'>
                @php
                    $GRAND_TOTAL = 0;
                    $tempGroup = 0;
                    $oneTimeQty = 0;
                    $oneTimeTotal = 0;
                @endphp

                @foreach ($dataList as $list)
                    @if ($TYPE == '')
                    @elseif ($TYPE != $list->TYPE_NAME)
                        <tr class="font-weight-bold">
                            <td class="text-center  pb-0 pt-0">
                                @if (isset($breakDownDate[$row]))
                                    {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                                @endif

                                @php
                                    $row++;
                                @endphp
                            </td>
                            <td class="pb-0 pt-0">{{ $TYPE }} TOTAL</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right pb-0 pt-0">{{ number_format($TOTAL, 2) }}</td>
                        </tr>
                        @php
                            $TOTAL = 0;
                        @endphp
                    @endif
                    @php
                        $TYPE = $list->TYPE_NAME;
                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold pb-0 pt-0">
                            @if (isset($breakDownDate[$row]))
                                {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                            @endif

                            @php
                                $row++;
                            @endphp
                            @php
                                if ($list->ACTUAL_BASE) {
                                    $defult_Qty = ItemSoaItemizedServices::getQuantityActual(
                                        $dateList,
                                        $LOCATION_ID,
                                        $PATIENT_ID,
                                        $list->ID,
                                    );
                                    $AMOUNT = $defult_Qty * $list->RATE ?? 0;
                                } else {
                                    $defult_Qty = $qty;
                                    $AMOUNT = $qty * $list->RATE ?? 0;
                                }
                                $TOTAL = $TOTAL + $AMOUNT;
                            @endphp
                        </td>
                        <td class="pb-0 pt-0">{{ $list->ITEM_NAME }}</td>
                        <td class="text-center">{{ $list->UNIT_NAME }}</td>
                        <td class="text-right  pb-0 pt-0">
                            {{ $list->FIX_QTY > 0 ? number_format($AMOUNT / $list->FIX_QTY, 2) : number_format($list->RATE, 2) }}
                        </td>
                        @if ($list->GROUP_ID > 0)
                            @if ($oneTimeQty == 0)
                                @php
                                    $oneTimeQty = $defult_Qty;
                                    $oneTimeTotal =
                                        ItemSoaServices::getTotal($list->GROUP_ID, $LOCATION_ID) * $defult_Qty;
                                @endphp
                                <td class="text-center pb-0 pt-0 " style="border-bottom-color: white;">
                                    {{ $oneTimeQty }}</td>
                                <td class="text-right pb-0 pt-0" style="border-bottom-color: white;">
                                    {{ number_format($oneTimeTotal, 2) }}</td>
                            @else
                                <td class="text-center pb-0 pt-0" style="border-top-color:white;"> </td>
                                <td class="text-right pb-0 pt-0" style="border-top-color:white;"></td>
                            @endif
                        @else
                            @php
                                $oneTimeQty = 0;
                                $oneTimeTotal = 0.0;
                            @endphp
                            <td class="text-center pb-0 pt-0 ">
                                {{ $list->FIX_QTY > 0 ? $list->FIX_QTY : $defult_Qty }}
                            </td>
                            <td class="text-right pb-0 pt-0">
                                {{ number_format($AMOUNT, 2) }}
                            </td>
                        @endif

                        @php
                            $GRAND_TOTAL = $GRAND_TOTAL + $AMOUNT ?? 0;
                        @endphp
                    </tr>


                    @php
                        $tempGroup = $list->GROUP_ID ?? 0;
                    @endphp
                @endforeach

                <tr class="font-weight-bold">
                    <td></td>
                    <td class="pb-0 pt-0">{{ $TYPE }} TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right pb-0 pt-0">{{ number_format($TOTAL, 2) }}</td>
                </tr>


                <tr class="font-weight-bold text-danger">
                    <td class="text-right">TOTAL:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right pb-0 pt-0">{{ number_format($GRAND_TOTAL, 2) }}</td>

                </tr>
            </tbody>
        </table>

    </div>
    <div class="col-4">
        <table class="w-100" border="1">
            <thead class="text-sm">
                <tr class="text-center">
                    <th class="">ROUTINE MONTHLY LABORATORIES</th>
                </tr>
            </thead>

            <tbody class='text-sm'>
                <tr>
                    <th class="text-center  pb-0 pt-0">(CLINICAL CHEMISTRY)</th>
                </tr>
                <tr>
                    <td class="text-center pb-0 pt-0">PRE AND POST DIALYSIS BUN</td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> SERUM CREATININE </td>
                </tr>
                <tr>
                    <td class="text-center pb-0 pt-0"> POTASSIUM </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> PHOSPHORUS </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> CALCIUM </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> SERUM SODIUM </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> KT/V </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> URR </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> ALBUMIN </td>
                </tr>

                <tr>
                    <td class="text-center "> URIC ACID </td>
                </tr>

                <tr>
                    <th class="text-center  pb-0 pt-0">(HEMATOLOGY) COMPLETE BLOOD COUNT</th>
                </tr>

                <tr>
                    <td class="text-center  pb-0 pt-0">HEMOGLOBIN</td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> HEMATOCRIT</td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> RED BLOOD CELLS </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> MCV </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> MCH </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> MCHC</td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> WHTE BLOODCELLS </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> NEUTROPHILS </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> LYMPHOCYTES </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> EOSINOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> BASOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center  pb-0 pt-0"> PLATELET COUNT </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12 pb-1">

    </div>
</div>
