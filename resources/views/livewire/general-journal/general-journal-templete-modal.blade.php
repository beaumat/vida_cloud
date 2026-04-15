<div>
    <button wire:click="openModal" class="btn btn-warning btn-sm text-xs ">
        Used Templeted
    </button>

    @if ($showModal)
        <div class="modal show" id="modal-sm" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-sm" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Use PaySlip Templeted</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <div class="modal-body">

                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th>Account</th>
                                    <th class="col-2">Debit</th>
                                    <th class="col-2">Credit</th>
                                    <th class="col-3">Notes</th>
                                    <th class="col-1">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->ACCOUNT_NAME }}</td>
                                        <td>{{ $list->DEBIT }}</td>
                                            <td>{{ $list->CREDIT }}</td>
                                        <td>{{ $list->NOTES }}</td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
