<div>

    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Account Journal No. {{ $JOURNAL_NO }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="max-height: 83vh; overflow-y: auto;">
                            <table class="table table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <td>Jrnl#</td>
                                        <td>Date</td>
                                        <td class="col-1">Type</td>
                                        <td class="col-1">Ref #</td>
                                        <td class="col-2">Name</td>
                                        <td class="col-1">Location</td>
                                        <td class="col-2">Account Title</td>
                                        <td class="col-3">Particular</td>
                                        <th class="col-1 text-right">Debit</th>
                                        <th class="col-1 text-right">Credit</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->JOURNAL_NO }}</td>
                                            <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td>{{ $list->TYPE }}</td>
                                            <td>{{ $list->TX_CODE }}</td>
                                            <td>{{ $list->TX_NAME }}</td>
                                            <td>{{ $list->LOCATION }}</td>
                                            <td>{{ $list->ACCOUNT_TITLE }}</td>
                                            <td>{{ $list->TX_NOTES }}</td>
                                            <td class="text-right">
                                                {{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}
                                                @php
                                                    if ($list->DEBIT > 0) {
                                                        $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT ?? 0;
                                                    }

                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }}
                                                @php
                                                    if ($list->CREDIT > 0) {
                                                        $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT ?? 0;
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right font-weight-bold">
                                            @if ($TOTAL_DEBIT)
                                                {{ number_format($TOTAL_DEBIT, 2) }}
                                            @else
                                                0.00
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold">
                                            @if ($TOTAL_CREDIT)
                                                {{ number_format($TOTAL_CREDIT, 2) }}
                                            @else
                                                0.00
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
