<div>
    @if ($showModal)
        <div class="modal show" id="modal-sm" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-sm" role="document"
                style="width: 50%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark">History Depreciation</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <div class="modal-body">
                        <div style="max-height: 73vh; overflow-y: auto;">
                            <table class="table table-bordered table-hover mt-1">
                                <thead class='bg-sky'>
                                    <th>NO. </th>
                                    <th>CODE</th>
                                    <th>DATE DEPRECIATION</th>
                                    <th>MONTHLY COST</th>
                                    <th>STATUS</th>
                                </thead>
                                @php
                                    $countdown = 0;
                                @endphp
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        @php
                                            $countdown++;
                                        @endphp
                                        <tr>
                                            <td>{{ $countdown }}</td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('companydepreciation_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                                            </td>
                                            <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td>{{ number_format($list->AMOUNT, 2) }}</td>
                                            <td>{{ $list->STATUS }}</td>
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
