<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-1">Reference</th>
                <th class="col-1">Bill Amount</th>
                <th class="col-1">Taxable Amount</th>
                <th class="col-1">Bill Bal.</th>
                <th class="col-1">Amount WithHeld</th>
                @if ($STATUS == 0 || $STATUS == 16)
                    <th class="text-center col-1">Action</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                    <td> <a target="_blank"
                            href="{{ route('vendorsbills_edit', ['id' => $list->BILL_ID]) }}">{{ $list->CODE }}</a>
                    </td>
                    <td class="text-right">{{ number_format($list->ORG_AMOUNT, 2) }} </td>
                    <td class="text-right">{{ number_format($list->INPUT_TAX_AMOUNT, 2) }} </td>
                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                    <td class="text-right">
                        {{ number_format($list->AMOUNT_WITHHELD, 2) }}
                    </td>
                    @if ($STATUS == 0 || $STATUS == 16)
                        <td class="text-center">
                            <button title="Delete" id="deletebtn"
                                wire:click='delete({{ $list->ID }}, {{ $list->BILL_ID }})'
                                wire:confirm="Are you sure you want to delete this?" class="btn btn-danger btn-xs">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @if ($STATUS == 0 || $STATUS == 16)
        @livewire('WithHoldingTax.BillListModal', ['VENDOR_ID' => $VENDOR_ID, 'LOCATION_ID' => $LOCATION_ID, 'WITHHOLDING_TAX_ID' => $WITHHOLDING_TAX_ID, 'EWT_RATE' => $EWT_RATE])
    @endif
</div>
