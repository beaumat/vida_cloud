<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Available Payment</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div style="max-height: 73vh; overflow-y: auto;" class="border">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="bg-sky text-xs">
                                    <tr>

                                        <th class="col-1">Type</th>
                                        <th class="col-1">Date</th>
                                        <th class="col-2">Reference</th>
                                        <th class="col-2">Deposit</th>
                                        <th class="col-2">Balance</th>
                                        <th class="col-2">Initial</th>
                                        <th class="col-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">

                                    @foreach ($dataList as $list)
                                        @if ($list->IS_COUNT == 0)
                                            <tr>
                                                <td class="col-1">{{ $list->PAYMENT_METHOD }}</td>
                                                <td class="col-2">{{ $list->DATE }}</td>
                                                <td class="col-2">{{ $list->CODE }}</td>
                                                <td class="col-1 text-right">
                                                    {{ number_format($list->AMOUNT, 2) }}
                                                </td>
                                                <td class="col-1 text-right">
                                                    {{ number_format($list->AMOUNT - $list->AMOUNT_APPLIED, 2) }}</td>
                                                <td class="col-1"><input type="number" class="w-100"
                                                        wire:model='paymentAmounts.{{ $list->ID }}' /></td>
                                                <td class="col-1">
                                                    <button type="button" class="text-white btn bg-sky btn-xs w-100"
                                                        wire:click='AddPayment({{ $list->ID }})'>
                                                        <i class="fas fa-plus"></i></button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
