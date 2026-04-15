<div>
    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Payment List</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div class='form-group form-group-sm'>
                            <div class="row">
                                <div class="col-4">
                                    <label class="text-xs form-label">Payment Method :</label>
                                    <select class="form-select text-xs p-1" wire:model.live ='PAYMENT_METHOD_ID'>
                                        <option value="0"> All Payment Method</option>
                                        @foreach ($paymentMethodList as $list)
                                            <option value="{{ $list->ID }}">{{ $list->DESCRIPTION }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-4">
                                    <label class="text-xs form-label">Search :</label>
                                    <input type="text" class="w-50 text-xs p-1" wire:model.live='search' />
                                </div>
                            </div>
                        </div>
                        <div style="max-height: 73vh; overflow-y: auto;" class="border">
                            <table class="table table-sm table-bordered table-hover mt-1">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="text-center"></th>
                                        <th class="col-1">Date</th>
                                        <th class="col-1">Type</th>
                                        <th class="col-1">Ref No.</th>
                                        <th class="col-1">Payment Method</th>
                                        <th class="col-7">Received From</th>
                                        <th class="col-1 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <button class="btn btn-success btn-xs"
                                                    wire:click='AddFund({{ $list->ID }},{{ $list->OBJECT_TYPE }})'>
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                            <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td>{{ $list->TYPE }}</td>
                                            <td>{{ $list->CODE }}</td>
                                            <td>{{ $list->PAYMENT_METHOD }}</td>
                                            <td>{{ $list->RECEIVED_FROM_NAME }}</td>
                                            <td class='text-right'>{{ number_format($list->AMOUNT, 2) }}</td>
                                        </tr>
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
