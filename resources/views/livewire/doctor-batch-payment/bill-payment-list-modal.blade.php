<div>
    
    <button wire:click="openModal" class="btn btn-success btn-xs text-xs ">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Pay Bills
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Paid List </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-hover table-bordered">
                            <thead class="text-xs bg-info">
                                <tr>
                                    <th class="col-1">Pay Bill Date</th>
                                    <th class="col-1 text-right">Pay Bill Amt.</th>
                                    <th class="col-1">Pay Bill No.</th>
                                    <th class="col-1 text-center">No. of Patient</th>
                                    <th class="col-1 text-center bg-warning">Status</th>
                                    <th class="col-1 bg-secondary">OR No.</th>
                                    <th class="col-1 bg-secondary">OR Date</th>
                                    <th class="col-1 bg-secondary">From Date</th>
                                    <th class="col-1 bg-secondary">To Date</th>
                                    <th class="col-1 bg-info text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td> <a target="_BLANK"
                                                href="{{ route('vendorsbill_payment_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                                        </td>
                                        <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>
                                        <td class="text-center"> {{ $list->TOTAL_COUNT }} </td>
                                        <td class="text-center"> {{ $list->STATUS }} </td>
                                        <td> {{ $list->OR_NO }}</td>
                                        <td>{{ \Carbon\Carbon::parse($list->OR_DATE)->format('m/d/Y') }} </td>
                                        <td>{{ \Carbon\Carbon::parse($list->FROM_DATE)->format('m/d/Y') }} </td>
                                        <td>{{ \Carbon\Carbon::parse($list->TO_DATE)->format('m/d/Y') }} </td>
                                        <td>
                                            <button wire type="button" class="btn btn-success btn-xs text-xs w-100"
                                                wire:click='addItem({{ $list->ID }},{{ $list->PF_PERIOD_ID }})'
                                                wire:loading.attr='hidden'
                                                wire:target="addItem({{ $list->ID }},{{ $list->PF_PERIOD_ID }})">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                            <div wire:loading
                                                wire:target="addItem({{ $list->ID }},{{ $list->PF_PERIOD_ID }})">
                                                <span class='spinner'></span>
                                            </div>
                                        </td>
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
