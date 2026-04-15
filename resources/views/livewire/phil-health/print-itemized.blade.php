<?php
use App\Services\ItemSoaItemizedServices;
?>

<div class="mt-1 row">
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
            <tbody class=''>
                @foreach ($dataList as $list)
                    @if ($TYPE == '')
                    @elseif ($TYPE != $list->TYPE_NAME)
                        <tr class="font-weight-bold">
                            <td class="text-center text-sm pb-0 pt-0">
                                @if (isset($breakDownDate[$row]))
                                    {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                                @endif

                                @php
                                    $row++;
                                @endphp
                            </td>
                            <td class="text-sm pb-0 pt-0">{{ $TYPE }} TOTAL</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right text-sm pb-0 pt-0">{{ number_format($TOTAL, 2) }}</td>
                        </tr>
                        @php
                            $TOTAL = 0;
                        @endphp
                    @endif
                    @php
                        if (in_array((int) $LOCATION_ID, [36, 38, 39, 40])) {
                            // For Luzon Branches
                            // If TYPE_NAME is 'ADMINISTRATIVE & OTHER FEES', change it to '
    if ($list->TYPE_NAME == 'ADMINISTRATIVE & OTHER FEES') {
        $TYPE = 'OTHERS';
                            } else {
                                $TYPE = $list->TYPE_NAME;
                            }
                        } else {
                            $TYPE = $list->TYPE_NAME;
                        }

                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold text-sm pb-0 pt-0">
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
                        <td class="text-sm pb-0 pt-0">{{ $list->ITEM_NAME }}</td>
                        <td class="text-sm text-center">{{ $list->UNIT_NAME }}</td>
                        <td class=" text-right text-sm pb-0 pt-0">
                            @if ($defult_Qty >= $list->FIX_QTY)
                                {{ $list->FIX_QTY > 0 ? number_format($AMOUNT / $list->FIX_QTY, 2) : number_format($list->RATE, 2) }}
                            @else
                                {{ number_format($list->RATE, 2) }}
                            @endif
                        </td>
                        <td class="text-center text-sm pb-0 pt-0">
                            @if ($defult_Qty >= $list->FIX_QTY)
                                {{ $list->FIX_QTY > 0 ? $list->FIX_QTY : $defult_Qty }}
                            @else
                                {{ $defult_Qty }}
                            @endif

                        </td>
                        <td class="text-right text-sm pb-0 pt-0">{{ number_format($AMOUNT, 2) }}</td>
                    </tr>
                @endforeach
                @if ($TYPE != 'OTHERS')
                    <tr class="font-weight-bold">
                        <td></td>
                        <td class="text-sm pb-0 pt-0">{{ $TYPE }} TOTAL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right text-sm pb-0 pt-0">{{ number_format($TOTAL, 2) }}</td>
                    </tr>
                @endif
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
                    <th class="text-center text-sm pb-0 pt-0">(CLINICAL CHEMISTRY)</th>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0">PRE AND POST DIALYSIS BUN</td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> SERUM CREATININE </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> POTASSIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> PHOSPHORUS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> CALCIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> SERUM SODIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> KT/V </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> URR </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> URIC ACID </td>
                </tr>

                <tr>
                    <th class="text-center text-sm pb-0 pt-0">(HEMATOLOGY) COMPLETE BLOOD COUNT</th>
                </tr>

                <tr>
                    <td class="text-center text-sm pb-0 pt-0">HEMOGLOBIN</td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> HEMATOCRIT</td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> RED BLOOD CELLS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> MCV </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> MCH </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> MCHC</td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> WHTE BLOODCELLS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> NEUTROPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> LYMPHOCYTES </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> EOSINOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> BASOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm pb-0 pt-0"> PLATELET COUNT </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
