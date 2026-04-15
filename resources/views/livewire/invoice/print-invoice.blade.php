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
                        <img class="print-logo" style="top:0px; width: 700px;"
                            src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif
                </div>
                <div class="col-12 text-center">
                    @if ($LOCATION_ID == 34)
                        <b class="h3">Delivery Receipt</b>
                    @else
                        <b class="h3">Sales Invoice</b>
                    @endif


                    <br />
                    <br />
                </div>
                <div class="col-8  text-left">
                    <div class="row">
                        <div class="col-2"> Client Name : </div>
                        <div class="col-10 bottom-line2"> &nbsp;{{ $CONTACT_NAME }}</div>
                    </div>
                    <div class="row">
                        <div class="col-2"> Address : </div>
                        <div class="col-10 bottom-line2"> &nbsp; {{ $ADDRESS }} </div>
                    </div>

                    <div class="row">
                        <div class="col-2"> Phone : </div>
                        <div class="col-10 bottom-line2"> &nbsp; {{ $CONTACT_NO }} </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-2"> Location : </div>
                        <div class="col-10 bottom-line"> {{ $LOCATION_NAME }}</div>
                    </div> --}}
                </div>

                <div class="col-4 ">
                    <div class="row ">
                        <div class="col-4 text-right"> DR No. : </div>
                        <div class="col-6 bottom-line2"> {{ $CODE }}</div>
                    </div>
                    <div class="row ">
                        <div class="col-4 text-right"> PO No. : </div>
                        <div class="col-6 bottom-line2"> {{ $PO_NUMBER }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">Date :</div>
                        <div class="col-6 bottom-line2">
                            {{ date('m/d/Y', strtotime($DATE)) }}
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-4 text-right"> Terms. : </div>
                        <div class="col-6 bottom-line2"> {{ $TERMS }}</div>
                    </div>
                </div>
                <div class="col-12 text-center mt-4 ">
                    <table class="w-100" border="1">
                        <thead>
                            <tr class="bgBlack text-white">
                                <th class="text-left">&nbsp;No.</th>
                                <th class="col-10 text-left">Item Description</th>
                                <th class="col-1 text-right">QTY</th>
                                <th class="col-1">UOM</th>
                                {{-- <th class="col-1 text-right">Rate</th>
                                <th class="col-2 text-right">Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemList as $list)
                                <tr class="border-dark border-bottom">
                                    @php
                                        $rows = $rows + 1;
                                    @endphp
                                    <td class="text-left p-1">&nbsp;{{ $rows }}</td>
                                    <td class="text-left p-1">{{ $list->DESCRIPTION }}</td>
                                    <td class="text-right">{{ number_format($list->QUANTITY, 1) }}&nbsp;</td>
                                    <td>{{ $list->UNIT_NAME }}</td>
                                    {{-- <td class="text-right">{{ number_format($list->RATE, 2) }}&nbsp;</td>
                                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}&nbsp;</td> --}}
                                </tr>
                            @endforeach
                            {{-- <tr class=" border-white border-top border-left border-right ">
                                <td class="text-left p-1"></td>
                                <td class="text-left p-1"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><b class="text-lg text-danger">{{ number_format($AMOUNT, 2) }}
                                        &nbsp;</b></td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
                <div class="col-12 ">
                    <div class="row mt-1">
                        <div class="col-12 text-left"><b>Notes :</b> {{ $NOTES }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row mt-2">
                        <div class="col-1">
                        </div>
                        <div class="col-3 text-left">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"> <b>&nbsp; </b> </div>
                                <div class="col-12 text-center"><i>Checked by</i></div>
                            </div>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-3 text-right">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"> <b>&nbsp; </b> </div>
                                <div class="col-12 text-center"><i>Delivered by</i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row mt-2">
                        <div class="col-1">
                        </div>
                        <div class="col-3 text-left">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"> <b>&nbsp; </b> </div>
                                <div class="col-12 text-center"><i>Approved by</i></div>
                            </div>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-3 text-right">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"> <b>&nbsp; </b> </div>
                                <div class="col-12 text-center"><i>Received by</i></div>
                            </div>
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
