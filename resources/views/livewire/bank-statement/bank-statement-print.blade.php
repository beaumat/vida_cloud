<div id="printableContent">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">

                    <div class="text-center pt-4">
                        <b class="print-address1 text-dark text-center h2">
                            BANK STATEMENT
                        </b>
                    </div>

                </div>
                <div class="col-md-12 text-center">
                    {{-- <div class="text-lg font-weight-bold pb-4">Date Period : {{ $PERIOD }}</div> --}}


                </div>
                <div class="col-md-12">
                    <table class="w-100 mt-4 table-bordered">
                        <thead class="bgBlack text-white">
                            <tr>
                                <th class="col-2">Date </th>
                                <th class="col-1">Reference</th>
                                <th class="col-2 text-center ">Description</th>
                                <th class="col-1 text-center ">Check Number</th>
                                <th class="col-1 text-center">Debit</th>
                                <th class="col-1 text-center ">Credit</th>
                                <th class="col-1 text-center">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataList as $list)
                                <tr>
                                    <td> {{ date('m/d/Y H:i:s', strtotime($list->DATE_TRANSACTION)) }}</td>
                                    <td>{{ $list->REFERENCE }}</td>
                                    <td>{{ $list->DESCRIPTION }}</td>
                                    <td>{{ $list->CHECK_NUMBER }}</td>
                                    <td class="text-right">
                                        @if ($list->DEBIT > 0)
                                            {{ number_format($list->DEBIT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->CREDIT > 0)
                                            {{ number_format($list->CREDIT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->BALANCE > 0)
                                            {{ number_format($list->BALANCE, 2) }}
                                        @endif
                                    </td>
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
