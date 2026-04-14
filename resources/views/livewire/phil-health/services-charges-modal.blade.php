<div>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Service Charges Items</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="bg-sky text-xs">
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" wire:model.live='SelectAllCharges' />
                                    </th>
                                    <th class="col-1 ">Date</th>
                                    <th class="col-1 ">Reference</th>
                                    <th class="col-1 ">Org.Amount</th>
                                    <th class="col-1 ">Balance</th>
                                    <th class="col-3 bg-info">Item Description</th>
                                    <th class="text-center bg-info">Qty</th>
                                    <th class="col-1 bg-info">Unit</th>
                                    <th class="col-1 bg-info">Amoint</th>
                                    <th class="col-1 bg-info">Paid</th>
                                    <th class="col-1 bg-dark">Initial</th>
                                </tr>
                            </thead>

                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td class="text-center">
                                            <input class="text-lg" type="checkbox"
                                                wire:model.live="selectedCharges.{{ $list->ID }}" />
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>
                                        <td> <a target="_BLANK"
                                                href="{{ route('patientsservice_charges_edit', ['id' => $list->SERVICE_CHARGES_ID]) }}">{{ $list->CODE }}</a>
                                        </td>
                                        <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                                        <th>
                                            {{ $list->ITEM_NAME }}
                                        </th>
                                        <th class="text-center">
                                            {{ number_format($list->QUANTITY, 0) }}
                                        </th>
                                        <th>
                                            {{ $list->SYMBOL }}
                                        </th>
                                        <th>
                                            {{ number_format($list->ITEM_AMOUNT, 2) }}
                                        </th>
                                        <th>
                                            {{ number_format($list->PAID_AMOUNT, 2) }}
                                        </th>

                                        <th>
                                            @php
                                                $temp_max = $list->ITEM_AMOUNT - $list->PAID_AMOUNT;

                                            @endphp

                                            <input type="number" min="0" max='{{ $temp_max }}'
                                                wire:model="paymentAmounts.{{ $list->ID }}"
                                                class="text-xs w-100" />

                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" wire:click="save">Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
