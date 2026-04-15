<div>
    @if ($showModal)
        <div class="modal show" id="modal-sm" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-sm" role="document"
                style="width: 50%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Stock Transfer | Received Details</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class='row'>
                            <div class="col-12 p-2">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="text-xs">Ref No.:</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $stockTransfer->CODE }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-xs">Date Transfer:</label>
                                        <input type="date" class="form-control form-control-sm"
                                            value="{{ $stockTransfer->DATE }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div style="max-height: 73vh; overflow-y: auto;" class="border">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="text-xs bg-sky">
                                            <tr>
                                                <th class="col-1">Code</th>
                                                <th class="col-4">Description</th>
                                                <th class="col-1">Qty</th>
                                                <th class="col-1">U/M</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs">
                                            @foreach ($itemList as $list)
                                                <tr>
                                                    <td>{{ $list->CODE }}</td>
                                                    <td>{{ $list->DESCRIPTION }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($list->QUANTITY, 0) }}
                                                    </td>
                                                    <td class="text-sm">
                                                        {{ $list->SYMBOL }}
                                                    </td>

                                                </tr>
                                            @endforeach


                                        </tbody>

                                    </table>
                                </div>
                            </div>
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
