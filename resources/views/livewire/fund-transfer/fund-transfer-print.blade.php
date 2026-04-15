<div id="printableContent">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">
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
                <div class="col-12 text-center mt-4 mb-4">
                    <b class="h3">Fund Transfer </b>
                </div>
                <div class="col-2"></div>
                <div class="col-8 mt-4">

                    <table class="w-100">
                        <tbody>
                            <tr>

                                <td>
                                    <b class="h5 text-primary">Transfer Details</b>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>{{ date('M/d/Y', strtotime($DATE)) }}</td>
                            </tr>
                            <tr>
                                <td>Reference No.</td>
                                <td>{{ $CODE }}</td>
                            </tr>
                            <tr>
                                <td>Inter-Location Account</td>
                                <td>{{ $INTER_ACCOUNT }}</td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td>{{ $NOTES }}</td>
                            </tr>
                            <tr>
                                <td>Transfer Amount</td>
                                <td class="font-weight-bold">{{ number_format($AMOUNT, 2) }}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td> <b class="h5 text-primary">FROM (Spend)</b> </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Account</td>
                                <td>{{ $FROM_ACCOUNT }}</td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td>{{ $FROM_LOCATION }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $FROM_NAME }}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td> <b class="h5 text-primary">TO (Received)</b> </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Account</td>
                                <td>{{ $TO_ACCOUNT }}</td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td>{{ $TO_LOCATION }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $TO_NAME }}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                          

                        </tbody>
                    </table>

                </div>
                <div class="col-2"></div>
                <div class="col-2"></div>
                <div class="col-10">
                    <div class="row mt-4">
                        <div class="col-6 text-left">
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"> <b>&nbsp; </b> </div>
                                <div class="col-12 text-center"><i>Encoded by</i></div>
                            </div>
                        </div>
                        <div class="col-3">
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
