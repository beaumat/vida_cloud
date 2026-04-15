<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-1">Reference</th>
                <th class="col-1">Orig. Amount</th>
                <th class="col-1">Balance</th>
                <th class="col-1">Payment</th>

                @if ($PF_PERIOD_ID > 0)
                    <th class="col-1">WTax</th>
                @endif
                @if ($STATUS == 0 || $STATUS == 16)
                    <th class="text-center col-1">Action</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                    <td>
                        <a target="_blank"
                            href="{{ route('vendorsbills_edit', ['id' => $list->BILL_ID]) }}">{{ $list->CODE }}</a>
                    </td>
                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                    <td class="text-right">
                        @if ($editPaymentId === $list->ID)
                            <input wire:model='editAmountApplied' type="number" class="form-control form-control-sm" />
                        @else
                            {{ number_format($list->AMOUNT_PAID, 2) }}
                        @endif
                    </td>

                    @if ($PF_PERIOD_ID > 0)
                        @if ($list->TAX_AMOUNT > 0)
                            <td class="text-right">
                                {{ number_format($list->TAX_AMOUNT, 2) }}
                            </td>
                        @else
                            <td>
                                <button type="button" class="btn btn-xs btn-success w-100"
                                    wire:click='addingTax({{ $list->ID }},{{ $list->BILL_ID }},{{ $list->AMOUNT }})'
                                    wire:confirm='Are you sure tax. the payment will adjusted?'>
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add Tax
                                </button>
                            </td>
                        @endif
                    @endif
                    @if ($STATUS == 0 || $STATUS == 16)
                        <td class="text-center">
                            @if ($editPaymentId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="update()"
                                    class="btn btn-success btn-xs">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" wire:click="cancel()"
                                    class="btn btn-warning btn-xs ">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn"
                                    wire:click='edit( {{ $list->ID }}, {{ $list->BILL_ID }}, {{ $list->AMOUNT_PAID }})'
                                    class="btn btn-info  btn-xs">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn"
                                    wire:click='delete({{ $list->ID }}, {{ $list->BILL_ID }})'
                                    wire:confirm="Are you sure you want to delete this?" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($STATUS == 0 || $STATUS == 16)

        @if ($PF_PERIOD_ID > 0)
            @livewire('BillPayments.DoctorPaid', ['VENDOR_ID' => $VENDOR_ID, 'LOCATION_ID' => $LOCATION_ID, 'CHECK_ID' => $CHECK_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED, 'STATUS' => $STATUS, 'SAME_AMOUNT' => $SAME_AMOUNT, 'PF_PERIOD_ID' => $PF_PERIOD_ID, 'DATE' => $DATE])
        @else
            @livewire('BillPayments.BillModal', ['VENDOR_ID' => $VENDOR_ID, 'LOCATION_ID' => $LOCATION_ID, 'CHECK_ID' => $CHECK_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED, 'STATUS' => $STATUS, 'SAME_AMOUNT' => $SAME_AMOUNT, 'PF_PERIOD_ID' => 0])
        @endif
    @endif
</div>
