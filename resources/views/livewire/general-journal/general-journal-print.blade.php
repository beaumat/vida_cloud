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
                <div class="col-12 text-center">
                    <b class="h4">GENERAL JOURNAL</b>
                </div>
                <div class="col-8  text-left">
                    <div class="row">
                        <div class="col-2"> Contact : </div>
                        <div class="col-8 bottom-line2"> {{ $CONTACT_NAME }}</div>
                    </div>
                    <div class="row">
                        <div class="col-2"> Location : </div>
                        <div class="col-8 bottom-line2"> {{ $LOCATION_NAME }}</div>
                    </div>
                </div>

                <div class="col-4 ">
                    <div class="row ">
                        <div class="col-4 text-right"> Reference No. : </div>
                        <div class="col-6 bottom-line2"> {{ $CODE }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">Date :</div>
                        <div class="col-6 bottom-line2">
                            {{ date('m/d/Y', strtotime($DATE)) }}
                        </div>
                    </div>
                </div>
                @php
                    $varDebit = 0;
                    $varCredit = 0;
                @endphp
                <div class="col-12 text-center mt-4 ">
                    <table class="w-100" border="1">
                        <thead>
                            <tr class="bgBlack text-white">
                                <th class="col-1 text-left">Acct. No.</th>
                                <th class="col-3 text-left">Account Title</th>
                                <th class="col-1 text-right">Debit</th>
                                <th class="col-1 text-right">Credit</th>
                                <th class="col-5 text-left">Particular</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($listDetails as $list)
                                <tr class="border-dark border-bottom">
                                    <td class="text-left p-1">{{ $list->CODE }}</td>
                                    <td class="text-left p-1">{{ $list->ACCOUNT_DESCRIPTION }}</td>
                                    @php
                                        $varDebit += $list->DEBIT;
                                        $varCredit += $list->CREDIT;
                                    @endphp
                                    <td class="text-right p-1">
                                        {{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}
                                    </td>
                                    <td class="text-right p-1">
                                        {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }}
                                    </td>
                                    <td class="text-left">{{ $list->NOTES }}</td>
                                </tr>
                            @endforeach
                            {{-- @foreach ($expensesList as $list)
                         
                                @endforeach --}}
                            <tr class=" border-white border-top border-left border-right ">
                                <td class="text-left p-1"></td>
                                <td class="text-left p-1"></td>
                                <td class="text-right"> {{ number_format($varDebit, 2) }}</td>
                                <td class="text-right"> {{ number_format($varCredit, 2) }}</td>

                                <td><b></b></td>
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
