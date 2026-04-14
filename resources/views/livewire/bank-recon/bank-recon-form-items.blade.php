<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                @if ($STATUS == 0 || $STATUS == 16)
                    <th>&nbsp;</th>
                @endif
                <th class='col-1'>Type</th>
                <th class='col-1'>Date</th>
                <th class='col-1'>Code</th>
                <th class="col-1">P.O</th>
                <th class='col-3'>Name</th>
                <th class='col-3'>Notes</th>
                <th class="col-1">Location</th>
                <th class='col-1 text-right'>Deposit</th>
                <th class='col-1 text-right'>Withdraw</th>

            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    @if ($STATUS == 0 || $STATUS == 16)
                        <td>
                            <button class="btn btn-danger btn-xs w-100" wire:click='delete({{ $list->ID }})'>
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                        </td>
                    @endif
                    <td>{{ $list->TYPE }}</td>
                    <td>{{ date('M/d/Y', strtotime($list->DATE)) }}</td>
                    <td>{{ $list->TX_CODE }}</td>
                    <td>{{ $list->TX_PO }}</td>
                    <td>{{ $list->TX_NAME }}</td>
                    <td>{{ $list->TX_NOTES }}</td>
                    <td>{{ $list->LOCATION_NAME }}</td>
                    <td class='text-right'>
                        @if ($list->ENTRY_TYPE == 0)
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>
                    <td class='text-right'>
                        @if ($list->ENTRY_TYPE == 1)
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>

    </table>

</div>
