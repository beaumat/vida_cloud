<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-1">Reference</th>
                <th class="col-1">Orig. Amount</th>
                <th class="col-1">Balance</th>
                <th class="col-2 bg-info">Item Description</th>
                <th class="bg-info text-right"> Qty</th>
                <th class="bg-info"> UOM</th>
                <th class="col-1 bg-info text-right"> Item Amount</th>
                <th class="col-1 bg-warning text-right">Paid</th>
                <th class="col-2 bg-primary ">Revenue Accounts</th>
                @if ($REF_ID == 0)
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
                            href="{{ route('patientsservice_charges_edit', ['id' => $list->SERVICE_CHARGES_ID]) }}">{{ $list->CODE }}</a>
                    </td>
                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                    <td>{{ $list->ITEM_NAME }}</td>
                    <td class="text-right">{{ number_format($list->QUANTITY, 2) }}</td>
                    <td>{{ $list->SYMBOL }}</td>
                    <td class="text-right">{{ number_format($list->ITEM_AMOUNT, 2) }}</td>
                    <td class="text-right">
                        @if ($editPaymentChargeId === $list->ID)
                            <input wire:model='editAmountApplied' type="number" class="form-control form-control-sm" />
                        @else
                            {{ number_format($list->AMOUNT_APPLIED, 2) }}
                        @endif
                    </td>

                    <td>
                        {{ $list->ACCOUNT_NAME }}
                    </td>
                    @if ($REF_ID == 0)
                        <td class="text-center">
                            @if ($editPaymentChargeId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="update()"
                                    class="btn btn-success btn-xs">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="cancel()"
                                    class="btn btn-warning btn-xs ">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn"
                                    wire:click='edit( {{ $list->ID }}, {{ $list->SERVICE_CHARGES_ITEM_ID }}, {{ $list->AMOUNT_APPLIED }})'
                                    class="btn btn-info  btn-xs">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn" class="btn btn-danger btn-xs"
                                    wire:click='delete({{ $list->ID }},{{ $list->SERVICE_CHARGES_ITEM_ID }})'
                                    wire:confirm="Are you sure you want to delete this?">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    @livewire('PatientPayment.ServiceChargeList', ['PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'PATIENT_PAYMENT_ID' => $PATIENT_PAYMENT_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED, 'PHILHEALTH_ID' => $PHILHEALTH_ID])

</div>
