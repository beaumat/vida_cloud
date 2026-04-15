<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-1">Code</th>
                <th class="col-1">Payment Method</th>
                <th class="col-1 text-right">Amount</th>
                <th class="col-1">Ref No.</th>
                <th class="col-2">Deposit Account</th>
                <th class="text-center col-4">Notes</th>
                <th class="text-center col-1">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ date('M/d/Y', strtotime($list->DATE)) }}</td>
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->PAYMENT_METHOD }}</td>
                    <td class="text-right">{{ number_format($list->AMOUNT_APPLIED, 2) }}</td>

                    <td>{{ $list->RECEIPT_REF_NO }}</td>
                    <td>{{ $list->BANK_ACCOUNT }}</td>
                    <td>{{ $list->NOTES }}</td>
                    <td>
                        <a title="View Details" target="_BLANK"
                            href="{{ route('customerspayment_edit', ['id' => $list->PAYMENT_ID]) }}"
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
                            <select class="form-control form-control-sm" wire:model='PAYMENT_METHOD_ID'>
                                <option value="0"></option>
                                @foreach ($paymentMethodList as $list)
                                    <option value="{{ $list->ID }}">{{ $list->DESCRIPTION }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td> <input type="number" class="form-control form-control-sm text-right" wire:model='AMOUNT'
                                step="0.01" />
                        </td>

                        <td> <input type="text" class="form-control form-control-sm" wire:model='RECEIPT_REF_NO' />
                        </td>
                        <td>
                            <select class="form-control form-control-sm" wire:model='UNDEPOSITED_FUNDS_ACCOUNT_ID'>
                                <option value="0"></option>
                                @foreach ($accountList as $list)
                                    <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td> <input type="text" class="form-control form-control-sm" wire:model='NOTES' /> </td>
                        <td>
                            <div class="">
                                <button title="Add" type="button" wire:loading.attr='hidden'
                                    wire:click='AddPayment()' class="text-white btn bg-sky btn-sm w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </form>
                @endif
            @endif

        </tbody>

    </table>
</div>
