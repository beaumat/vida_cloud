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
                        <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif
                </div>
                <div class="col-4  text-left">
                    <div class="row">
                        <div class="col-3">
                            @if ($CONTACT_TYPE == 0)
                                Vendor
                            @else
                                Doctor
                            @endif :
                        </div>
                        <div class="col-9 bottom-line"> {{ $CONTACT_NAME }}</div>
                    </div>
                    <div class="row">
                        <div class="col-3"> Location : </div>
                        <div class="col-9 bottom-line"> {{ $LOCATION_NAME }}</div>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <b class="h3">Bill Payments</b>
                </div>
                <div class="col-4 ">
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
                <div class="col-12 text-center mt-4 ">
                    <table class="w-100" border="1">
                        <thead>
                            <tr class="bgBlack text-white">
                                <th class="col-4 text-left">Bill No.</th>
                                <th class="col-4 text-left">Date </th>
                                <th class="col-4 text-right">Payment Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($billList as $list)
                                <tr>
                                    <td class="text-left">
                                        {{ $list->CODE }}
                                    </td>
                                    <td class='text-left'>
                                        {{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($list->AMOUNT_PAID, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-left font-weight-bold">
                                    TOTAL
                                </td>
                                <td class='text-left'>
                                </td>
                                <td class="text-right font-weight-bold">
                                    {{ number_format($AMOUNT, 2) }}
                                </td>
                            </tr>
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
