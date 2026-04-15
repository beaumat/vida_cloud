<div>
    <button wire:click="openModal" class="btn btn-danger btn-xs text-xs w-100">
        PO History
    </button>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Purchase Order History</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm  table-bordered">
                            <thead class="text-xs bg-info">
                                <tr>
                                    <th class="col-2">Date</th>
                                    <th class="col-2">Ref No.</th>
                                    <th class="col-2">Date Needed</th>
                                    <th class="col-2 text-right">Amount</th>
                                    @if ($STATUS == 0)
                                        <th class="col-1 text-center">&nbsp;</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>
                                        <td>
                                            <a target="_BLANK"
                                                href="{{ route('vendorspurchase_order_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($list->DATE_EXPECTED)->format('m/d/Y') }} </td>
                                        <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                        @if ($STATUS == 0)
                                            <td class="text-center">
                                                <button class="btn btn-success btn-sm text-xs rounded-circle"
                                                    wire:click='createToBill({{ $list->ID }})'>
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        @endif
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
