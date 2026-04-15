<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header text-sm bg-sky">
                Transaction Details
            </div>
            <div class="card-body">
                @if ($dataList->count() == 0)
                    @livewire('BankStatement.CsvImport', ['FILE_TYPE' => $FILE_TYPE, 'BANK_STATEMENT_ID' => $BANK_STATEMENT_ID])
                @else
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-primary">
                            <tr>
                                <th class="col-1">DATE TRANSACTION</th>
                                <th class="col-2">REFERENCE</th>
                                <th class="col-3">DESCRIPTION</th>
                                <th class="col-2">CHECK NUMBER</th>
                                <th class="col-1">DEBIT</th>
                                <th class="col-1">CREDIT</th>
                                <th class="col-1">BALANCE</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>

                                    <td> {{ date('m/d/Y H:i:s', strtotime($list->DATE_TRANSACTION)) }}</td>
                                    <td>{{ $list->REFERENCE }}</td>
                                    <td>{{ $list->DESCRIPTION }}</td>
                                    <td>{{ $list->CHECK_NUMBER }}</td>
                                    <td>
                                        @if ($list->DEBIT > 0)
                                            {{ number_format($list->DEBIT, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($list->CREDIT > 0)
                                            {{ number_format($list->CREDIT, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($list->BALANCE > 0)
                                            {{ number_format($list->BALANCE, 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="card-footer"></div>
        </div>

    </div>

</div>
