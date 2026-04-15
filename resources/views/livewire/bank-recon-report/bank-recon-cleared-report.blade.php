<div>
    <h4>CLEARED TRANSACTIONS</h4>

    <table class="details-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Description</th>
                <th>Check #</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bankStatementList as $list)
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
</div>