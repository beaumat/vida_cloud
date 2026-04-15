<div id="printableContent">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-1">
                    @if (empty($LOGO_FILE))
                        <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="text-center">
                            <b class="print-address1 text-center text-xs p-0 m-0">
                                {{ $REPORT_HEADER_1 }} <br />
                                {{ $REPORT_HEADER_2 }} <br />
                                {{ $REPORT_HEADER_3 }}</b>
                        </div>
                    @else
                        {{-- nothing customize --}}
                        <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif
                </div>
                <div class="col-12 text-center mt-4">
                    <b class="h4 font-weight-bold">PURCHASE ORDER</b>
                </div>
                <div class="col-5  text-left">
                    <div class="row">
                        <div class="col-3"> Vendor : </div>
                        <div class="col-9 bottom-line"> {{ $CONTACT_NAME }}</div>
                    </div>
                    <div class="row">
                        <div class="col-3"> Location : </div>
                        <div class="col-9 bottom-line"> {{ $LOCATION_NAME }}</div>
                    </div>
                </div>
                <div class="col-3 text-center">

                </div>
                <div class="col-4">
                    <div class="row ">
                        <div class="col-4 text-right"> Reference No. : </div>
                        <div class="col-6 bottom-line"> {{ $CODE }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">Date :</div>
                        <div class="col-6 bottom-line">
                            {{ date('m/d/Y', strtotime($DATE)) }}
                        </div>
                    </div>
                </div>
                @if (count($itemList) > 0)
                    <div class="col-12 text-center mt-4 ">
                        <table class="w-100" border="1">
                            <thead>
                                <tr class="bgBlack text-white">
                                    <th class="col-1 text-left">No.</th>
                                    <th class="col-6 text-left">Item Description</th>
                                    <th class="col-1 text-right">Qty</th>
                                    <th class="col-1">Uom</th>
                                    <th class="col-1 text-right">Cost</th>
                                    <th class="col-2 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemList as $list)
                                    @php
                                        $rows = $rows + 1;
                                    @endphp
                                    <tr class="border-dark border-bottom">
                                        <td class="text-left p-1">{{ $rows }}</td>
                                        <td class="text-left p-1">{{ $list->PURCHASE_DESCRIPTION }}</td>
                                        <td class="text-right">{{ number_format($list->QUANTITY, 1) }}&nbsp;</td>
                                        <td>{{ $list->UNIT_NAME }}</td>
                                        <td class="text-right">{{ number_format($list->RATE, 2) }}&nbsp;</td>
                                        @php
                                            $poItemAmount = $poItemAmount + $list->AMOUNT ?? 0;
                                        @endphp
                                        <td class="text-right">{{ number_format($list->AMOUNT, 2) }}&nbsp;</td>
                                    </tr>
                                @endforeach
                                <tr class=" border-white border-top border-left border-right ">
                                    <td class="text-left p-1"></td>
                                    <td class="text-left p-1"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>{{ number_format($poItemAmount, 2) }} &nbsp;</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="col-12 ">
                    <div class="row mt-1">
                        <div class="col-12 text-left"><b>Notes :</b> {{ $NOTES }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row mt-4">
                        <div class="col-3 text-left">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"> <b>&nbsp; </b> </div>
                                <div class="col-12 text-center"><i>Encoded by</i></div>
                            </div>
                        </div>
                        <div class="col-6">
                        </div>
                        <div class="col-3 text-right">
                            {{-- <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"><b>&nbsp;</b></div>
                                <div class="col-12 text-center"><i>Received By</i></div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@script
    <script>
        $wire.on('print', () => {
            var printContents = document.getElementById('printableContent').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });

        function printPageAndClose() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 100);
        }

        window.addEventListener('beforeprint', function() {
            printPageAndClose();
        });
    </script>
@endscript
