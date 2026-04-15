    <div>
        @if ($showModal)
            <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
                style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
                <div class="modal-dialog modal-xl" role="document"
                    style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title"> Collection & Deposit</h6>
                            <button type="button" class="close" wire:click="closeModal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="mt-0">
                                                    <label class="text-sm">Search:</label>
                                                    <input type="text" wire:model.live.debounce.150ms='search'
                                                        class="w-100 form-control form-control-sm"
                                                        placeholder="Search" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mt-0">
                                                    <label class="text-sm">Location:</label>
                                                    <select
                                                        @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                        name="location" wire:model.live='LOCATION_ID'
                                                        class="form-control form-control-sm">
                                                        <option value="0"> All Location</option>
                                                        @foreach ($locationList as $item)
                                                            <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div style="max-height: 60vh; overflow-y: auto;">
                                            <table class="table table-sm table-bordered table-hover">
                                                <thead class="text-xs bg-sky sticky-header">
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th class='col-1'>Type</th>
                                                        <th class='col-1'>Date</th>
                                                        <th class='col-1'>Code</th>
                                                        <th class="col-1">P.O</th>
                                                        <th class='col-2'>Name</th>
                                                        <th class='col-4'>Notes</th>
                                                        <th class='col-1 text-right'>Amount</th>
                                                        <th class='col-1 '>Location</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs">
                                                    @foreach ($dataList as $list)
                                                        <tr>
                                                            <td>
                                                                <button class="btn btn-success btn-xs"
                                                                    wire:click='AddItem({{ $list->OBJECT_ID }},{{ $list->OBJECT_TYPE }},`{{ $list->OBJECT_DATE }}`, {{ $list->ENTRY_TYPE }},{{ $list->AMOUNT }})'>
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                </button>
                                                            </td>
                                                            <td>{{ $list->TYPE }}</td>
                                                            <td>{{ date('M/d/Y', strtotime($list->DATE)) }}</td>
                                                            <td>{{ $list->TX_CODE }}</td>
                                                            <td>{{ $list->TX_PO }}</td>
                                                            <td>{{ $list->TX_NAME }}</td>
                                                            <td>{{ $list->TX_NOTES }}</td>
                                                            <td class='text-right'>
                                                                {{ number_format($list->AMOUNT, 2) }}
                                                            </td>
                                                            <td>
                                                                {{ $list->LOCATION_NAME }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        @livewire('BankRecon.BankReconDetails', ['ACCOUNT_RECONCILIATION_ID' => $ACCOUNT_RECONCILIATION_ID])
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                wire:click="closeModal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
