<div>
    <button wire:click="openModal" class="btn btn-warning btn-xs text-xs w-100">
        Payment History
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Payment History </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm ">
                            <thead class="text-xs bg-info">
                                <tr>
                                    <th class="col-2">Type</th>
                                    <th class="col-2">Ref No.</th>
                                    <th class="col-2">Date</th>
                                    <th class="col-2 text-right">Amount Applied</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->TYPE }}</td>
                                        <td>
                                            @if ($list->TYPE == 'Pay Bills')
                                                <a target="_BLANK"
                                                    href="{{ route('vendorsbill_payment_edit', ['id' => $list->MAIN_ID]) }}">{{ $list->CODE }}</a>
                                            @elseif ($list->TYPE == 'Bill Credits')
                                                <a target="_BLANK"
                                                    href="{{ route('vendorsbill_credit_edit', ['id' => $list->MAIN_ID]) }}">{{ $list->CODE }}</a>
                                            @elseif ($list->TYPE == 'Withholding Tax')
                                                <a target="_BLANK"
                                                    href="{{ route('vendorswithholding_tax_edit', ['id' => $list->MAIN_ID]) }}">{{ $list->CODE }}</a>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>
                                        <td class="text-right">{{ number_format($list->AMOUNT_APPLIED, 2) }}</td>
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
