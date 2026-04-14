<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Code</th>
                <th class="col-4">Description</th>
                <th class="col-1">Unit Base</th>
                <th class="col-1 text-right">Quantity Required</th>
                <th class="col-1 text-right">Cost Amt.</th>
                @if (!$IS_POSTED)
                    <th class ="col-1 text-right">On-Hand</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->DESCRIPTION }}</td>
                    <td>{{ $list->UNIT_BASE }}</td>
                    <td class="text-right">
                        {{ number_format($list->QUANTITY, 1) }}
                    </td>
                    <td class="text-right">
                        {{ number_format($list->AMOUNT, 2) }}
                    </td>
                    @if (!$IS_POSTED)
                        <td class="text-right font-weight-bold @if ($list->OTY_OHAND <= 0) text-danger @else text-success @endif">
                            {{ number_format($list->OTY_OHAND, 2) }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
