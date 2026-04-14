<div id="printableContent">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                    <div class="text-center">
                        <b class="print-address1 text-center">
                            {{ $REPORT_HEADER_1 }} <br />
                            {{ $REPORT_HEADER_2 }} <br />
                            {{ $REPORT_HEADER_3 }} </b>
                    </div>
                </div>
                <div class="col-4 ">
                    <div class="row ">
                        <div class="col-3 text-left"> Location :</div>
                        <div class="col-7 bottom-line"> {{ $LOCATION_NAME }}</div>
                    </div>

                </div>
                <div class="col-4 text-center">
                    <b class="h4">PULL OUT</b>
                </div>
                <div class="col-4 ">
                    <div class="row ">
                        <div class="col-4 text-right"> Reference No. :</div>
                        <div class="col-6 bottom-line"> {{ $CODE }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">Date :</div>
                        <div class="col-6 bottom-line">
                            {{ date('m/d/Y', strtotime($DATE)) }}
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center mt-4 ">
                    <table class="w-100" border="1">
                        <thead>
                            <tr class="bgBlack text-white">
                                <th class="col-2 text-left">CODE</th>
                                <th class="col-7 text-left">ITEM DESCRIPTION</th>
                                <th class="col-2">UOM</th>
                                <th class="col-1">QTY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemList as $list)
                                <tr>
                                    <td class="text-left p-1">{{ $list->CODE }}</td>
                                    <td class="text-left p-1">{{ $list->DESCRIPTION }}</td>
                                    <td>{{ $list->UNIT_NAME }}</td>
                                    <td>{{ number_format($list->QUANTITY, 1) }}</td>
                                </tr>
                            @endforeach

                            {{-- <tr class="blackbox">
                                <td> <u class="h4"></u> </td>
                                <td class="left-line">
                                    <h4></h4>
                                </td>
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
                    <div class="row mt-4">
                        <div class="col-3 text-left">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"> <b> {{ $PREPARED_BY_NAME }}</b> </div>
                                <div class="col-12 text-center"><i>Prepared by</i></div>
                            </div>
                        </div>
                        <div class="col-6">
                        </div>
                        <div class="col-3 text-right">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"><b>&nbsp;</b></div>
                                <div class="col-12 text-center"><i>Received By</i></div>
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
