<div id="printableContent">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <h2>Statement of Account</h2>
                </div>
                <div class="col-12">
                    <div class="form-group text-md mt-2">
                        <div class="row">
                            <div class="col-8 row">
                                <div class="col-2 text-right">NAME :</div>
                                <div class="col-10 font-weight-bold h5">{{ $NAME }}</div>
                                <div class="col-2 text-right">TYPE :</div>
                                <div class="col-10 font-weight-bold h5">{{ $CONTACT_TYPE }}</div>
                                @if ($dateTo == '')
                                    <div class=" col-2 text-right"> As of Date : </div>
                                    <div class="col-10 font-weight-bold h5"> {{ date('M d, Y', strtotime($dateFrom)) }}
                                    </div>
                                @else
                                @endif
                            </div>

                            <div class="col-4 text-left ">
                                <div class="row">
                                    <div class="col-4 text-right">Previous Bal. :</div>
                                    <div class="col-8 font-weight-bold h5">{{ number_format($PREV_BALANCE, 2) }}</div>
                                    <div class="col-4 text-right">Total Debit :</div>
                                    <div class="col-8 font-weight-bold h5">{{ number_format($TOTAL_DEBIT, 2) }}</div>
                                    <div class="col-4 text-right">Total Credit :</div>
                                    <div class="col-8 font-weight-bold h5">{{ number_format($TOTAL_CREDIT, 2) }}</div>
                                    <div class="col-4 text-right">Balance Due :</div>
                                    <div class="col-8 font-weight-bold h5">{{ number_format($BALANCE_DUE, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center mt-4 ">
                    <table class="w-100" border="1">
                        <thead>
                            <tr class="bgBlack text-white">
                                <th class="col-1 text-left">Date</th>
                                <th class="col-1 text-left">Type</th>
                                <th class="col-1 text-left">Ref#</th>
                                <th class="col-1 text-left">Location</th>
                                <th>Description</th>
                                <th class="col-1">Debit</th>
                                <th class="col-1">Credit</th>
                                <th class="col-1">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $BALANCE = $PREV_BALANCE;
                            @endphp
                            @if ($BALANCE > 0)
                                <tr>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Previous Balance</td>
                                    <td class="text-right">

                                    </td>
                                    <td class="text-right">

                                    </td>
                                    <td class="text-right">{{ number_format($BALANCE, 2) }}</td>
                                </tr>
                            @endif


                            @foreach ($dataList as $list)
                                @php
                                    $BALANCE = $BALANCE + $list->AMT;
                                @endphp
                                <tr>
                                    <td> {{ date('M d, Y', strtotime($list->DATE)) }}</td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td>{{ $list->CODE }}</td>
                                    <td>{{ $list->LOCATION }}</td>
                                    <td>{{ $list->DESCRIPTION }}</td>
                                    <td class="text-right">
                                        @if ($list->ENTRY_TYPE == 0)
                                            {{ number_format($list->AMOUNT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->ENTRY_TYPE != 0)
                                            {{ number_format($list->AMOUNT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($BALANCE, 2) }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
