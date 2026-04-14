<div>

    <button wire:click="openModal" class="btn btn-success btn-sm text-xs ">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lx" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Bill List</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                        <table class="table table-sm table-bordered table-hover">
                            <thead class="bg-sky text-xs">
                                <tr>
                                    <th class="col-1">Select</th>
                                    <th class="col-2">Date</th>
                                    <th class="col-2">Reference</th>
                                    <th class="col-2">Amount</th>
                                    <th class="col-2">Balance</th>

                                </tr>
                            </thead>

                            <tbody class="text-xs">
                                @foreach ($billList as $list)
                                    <tr>
                                        <td class="text-center">
                                            <input class="text-lg" type="checkbox"
                                                wire:model.live="selectedCharges.{{ $list->ID }}" />

                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>
                                        <td>{{ $list->CODE }}</td>
                                        <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>

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
