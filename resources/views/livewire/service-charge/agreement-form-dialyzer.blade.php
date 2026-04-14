<div>

    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky sticky-header">
            <tr>
                <th>Date</th>
                <th>Reference No.</th>
                <th>Item Description</th>
                <th>Quantity</th>

            </tr>
        </thead>
        <tbody>
            @php
                $total_qty = 0;
            @endphp
            @foreach ($itemList as $item)
                <tr>
                    <td class="text-xs">{{ date('M/d/Y', strtotime($item->DATE)) }}</td>
                    <td class="text-xs">
                        <a target="_blank"
                            href="{{ route('patientsservice_charges_edit', ['id' => $item->SERVICE_CHARGES_ID]) }}">
                            {{ $item->CODE }}
                        </a>

                    </td>
                    <td class="text-xs">{{ $item->DESCRIPTION }}</td>
                    @php
                        $total_qty += $item->QUANTITY;
                    @endphp
                    <td class="text-xs text-center">{{ number_format($item->QUANTITY, 0) }}</td>
                </tr>
            @endforeach

            <tr class="bg-light">
                <td colspan="3" class="text-right text-xs font-weight-bold text-danger">Total Quantity</td>
                <td class="text-center text-xs font-weight-bold text-danger">{{ number_format($total_qty, 0) }}</td>
            </tr>
    </table>

</div>
