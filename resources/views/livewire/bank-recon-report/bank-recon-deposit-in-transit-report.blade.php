<div>
    <h4>DEPOSIT IN TRANSIT</h4>

    <table class="details-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
                <th> Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataList as $list)
                <tr>
                    <td> {{ date('m/d/Y', strtotime($list->OBJECT_DATE)) }}</td>
                    <td> {{ $List->TYPE }}</td>
                    <td>{{ $list->TX_CODE }}</td>
                    <td>{{ $list->TX_NAME }} - {{ $list->TX_NOTES }}</td>
                    <td class="text-right">{{  number_format($list->AMOUNT, 2) }}</td>
                    <td>{{ $list->LOCATION_NAME }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>