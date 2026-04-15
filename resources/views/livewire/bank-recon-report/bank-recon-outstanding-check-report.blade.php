<div>
    <!-- OUTSTANDING CHECK -->
    <h4>OUTSTANDING CHECK</h4>

    <table class="details-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Check No</th>
                <th>Payee</th>
                <th class="text-right">Amount</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <!-- Example Row -->

            @foreach ($dataList as $list)
                <tr>
                    <td>{{ date('m/d/Y', strtotime($list->OBJECT_DATE)) }}</td>
                    <th>{{ $list->TYPE }}</th>
                    <td>{{ $list->TX_CODE }}</td>
                    <td>{{ " " }}</td>
                    <td>{{ $list->TX_NAME }} - {{ $list->TX_NOTES }}</td>
                    <td class="text-right">{{  number_format($list->AMOUNT, 2) }}</td>
                    <td>{{ $list->LOCATION_NAME }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>