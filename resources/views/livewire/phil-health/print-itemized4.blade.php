<div>
    <div class="row text-sm">
        <div class="col-12 font-weight-bold text-center mt-4 text-md">
            ITEMIZED CHARGES
        </div>
        @php
            $TEMP_NAME = '';
        @endphp
        <div class="col-12 text-center text-sm bottom-line2 right-line2 left-line2  top-line2">
            <div class="row ">
                <div class="col-2 bottom-line2 right-line2 font-weight-bold">Service Date</div>
                <div class="col-5 bottom-line2 right-line2 font-weight-bold">Item Name</div>
                <div class="col-2 bottom-line2 right-line2 font-weight-bold">Unit of Measurement</div>
                <div class="col-1 bottom-line2 right-line2 font-weight-bold">Price</div>
                <div class="col-1 bottom-line2 right-line2 font-weight-bold">Quantity</div>
                <div class="col-1 bottom-line2  font-weight-bold">Amount</div>
            </div>
        </div>
        @php
            $TOTAL = 0;
            $TYPE = '';
            $posted = false;
            $row = 0;

            $GRAND_TOTAL = 0;
            $tempGroup = 0;
            $oneTimeQty = 0;
            $oneTimeTotal = 0;
        @endphp

        @foreach ($dataList as $list)
            @if ((int) $list['QTY'] > 0)
                @php
                    $TYPE = $list['TYPE_NAME'];
                @endphp
                <div class="col-12 text-center text-sm bottom-line2 right-line2 left-line2 ">
                    <div class="row ">
                        <div class="col-2 bottom-line2 right-line2 ">
                            {{ date('m-d-Y', strtotime($list['DATE'])) }}
                        </div>
                        <div class="col-5 bottom-line2 right-line2 ">{{ $list['ITEM_NAME'] }}</div>
                        @php
                            $TEMP_NAME = $list['ITEM_NAME'];
                            $AMOUNT = $list['RATE'] * $defult_Qty;
                        @endphp
                        <div class="col-2 bottom-line2 right-line2 ">{{ $list['UNIT_NAME'] }}</div>
                        <div class="col-1 bottom-line2 right-line2 ">{{ number_format($list['RATE'], 2) }}</div>
                        <div class="col-1 bottom-line2 right-line2 "> {{ $list['QTY'] }}</div>
                        <div class="col-1 bottom-line2 "> {{ number_format($AMOUNT, 2) }}</div>
                    </div>
                </div>
                @php
                    $GRAND_TOTAL = $GRAND_TOTAL + $AMOUNT ?? 0;
                @endphp
            @endif
        @endforeach


        <div class="col-12 text-center text-sm bottom-line2 right-line2 left-line2 ">
            <div class="row  font-weight-bold">
                <div class="col-11 bottom-line2 right-line2  text-left">
                    TOTAL
                </div>

                <div class="col-1 bottom-line2 ">{{ number_format($GRAND_TOTAL, 2) }}</div>
            </div>
        </div>



    </div>
