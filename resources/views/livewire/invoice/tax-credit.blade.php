<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-1">Code</th>
                <th class="col-1">Type</th>
                <th class="col-1 text-right">WTax</th>
                <th class="col-3">Tax Account</th>
                <th class="text-center col-4">Notes</th>
                <th class="text-center col-1">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ date('M/d/Y', strtotime($list->DATE)) }}</td>
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->TAX_TYPE }}</td>
                    <td class="text-right">{{ number_format($list->AMOUNT_WITHHELD, 2) }}</td>
                    <td>{{ $list->TAX_ACCOUNT }}</td>
                    <td>{{ $list->NOTES }}</td>
                    <td>
                        <a title="View Details" target="_BLANK"
                            href="{{ route('customerstax_credit_edit', ['id' => $list->ID]) }}"
                            class="btn btn-sm btn-info w-100"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    </td>
                </tr>
            @endforeach


            @if ($dataList->count() == 0)
                @if ($INVOICE_STATUS_ID != $openStatus)
                    <tr wire:loading.attr='disabled'>
                        <td> </td>
                        <td> </td>
                        <td>
                            <select class="form-control form-control-sm" wire:model.live='EWT_ID'>
                                <option value="0"></option>
                                @foreach ($taxList as $list)
                                    <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class='text-center'>
                            <label>{{ number_format($AMOUNT_WITHHELD, 2) }}</label>
                        </td>
                        <td>
                            {{ $TAX_DESCRIPTION }}
                        </td>
                        <td> <input type="text" class="form-control form-control-sm" wire:model='NOTES' /> </td>
                        <td>
                            <div class="">
                                <button title="Add" type="button" wire:click='AddPayment()'
                                    wire:loading.attr='hidden' class="text-white btn bg-sky btn-sm w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>
                    </tr>

                @endif
            @endif
        </tbody>

    </table>
</div>
