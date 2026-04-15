<?php
use App\Services\ItemSoaItemizedServices;
use App\Services\ItemSoaServices;
?>

<div class="row bottom-line2 right-line2 left-line2 text-sm">
    <div class="col-12 font-weight-bold text-center text-danger">
        ITEMIZED CHARGES
    </div>
    <div class="col-12">
        <table class="w-100" border="1">
            <thead>
                <tr class="text-center text-sm">
                    <th>SERVICE DATE</th>
                    <th>ITEM NAME</th>
                    <th>UNIT OF MEASUREMENT</th>
                    <th class="col-1">PRICE</th>
                    <th class="col-1">QTY</th>
                    <th class="col-2">AMOUNT</th>
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
                                    $oneTimeTotal = ItemSoaServices::getTotal($list->GROUP_ID, $LOCATION_ID);
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

    <div class="col-12 pb-1">

    </div>
</div>
