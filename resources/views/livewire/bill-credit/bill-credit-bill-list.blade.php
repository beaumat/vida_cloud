<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-info">
            <tr>

                <th class="col-2">Reference</th>
                <th class="col-2">Date</th>
                <th class="col-2">Org. Amount</th>
                <th class="col-2">Applied</th>
                <th class="col-2">Running Bal.</th>
                <th class="text-center col-1">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>
                        <a target="_blank" href="{{ route('vendorsbills_edit', ['id' => $list->BILL_ID]) }}">
                            {{ $list->CODE }}
                        </a>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>
                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->AMOUNT_APPLIED, 2) }}</td>
                    @php
                        $ORG_AMOUNT = $ORG_AMOUNT - $list->AMOUNT_APPLIED;
                    @endphp
                    <td class="text-right">{{ number_format($ORG_AMOUNT, 2) }}</td>
                    <td>
                        <button class="btn btn-xs btn-danger w-100"
                            wire:click='delete({{ $list->ID }}, {{ $list->BILL_ID }})'
                            wire:confirm="Are you sure you want to delete this?" class="btn-sm text-danger">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    @livewire('BillCredit.BillCreditBillModal', ['VENDOR_ID' => $VENDOR_ID, 'LOCATION_ID' => $LOCATION_ID, 'BILL_CREDIT_ID' => $BILL_CREDIT_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED])
</div>
