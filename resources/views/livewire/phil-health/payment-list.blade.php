<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-12">
        {{-- <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs text-left">
                <tr class="bg-sky text-white">
                    <th class="col-1">Payment No.</th>
                    <th class="col-1">Date Created</th>
                    <th class="col-1">O.R No.</th>
                    <th class="col-1">O.R Date</th>
                    <th class="col-1">Gross Income</th>
                    <th class="col-1">WTax</th>
                    <th class="col-1">Less Amount</th>
                    <th class="col-1">Applied</th>
                    <th class="col-5">Notes</th>

                </tr>
            </thead>
            <tbody class="text-dark text-xs text-left">
                @foreach ($paymentList as $list)
                    @php
                        $i++;
                    @endphp
                    <tr>

                        <td>
                            <a target="_BLANK" href="#"
                                class="text-primary">
                                {{ $list->CODE }}
                            </a>
                        </td>
                        <td> {{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                        <td>
                            @if ($editId == $list->ID)
                                <input type="text" name="editReceiptRefNo" wire:model='editReceiptRefNo'
                                    class="w-100 text-xs" />
                            @else
                                {{ $list->RECEIPT_REF_NO }}
                            @endif
                        </td>
                        <td>
                            @if ($editId == $list->ID)
                                <input type="date" name="editReceiptDate" wire:model='editReceiptDate'
                                    class="w-100 text-xs" />
                            @else
                                {{ \Carbon\Carbon::parse($list->RECEIPT_DATE)->format('m/d/Y') }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($editId == $list->ID)
                                <input type="number" name="editAmount" wire:model='editAmount' class="w-100 text-xs" />
                            @else
                                {{ number_format($list->AMOUNT, 2) }}
                            @endif
                        </td>
                        <td class="text-right">
                            {{ number_format($list->WTAX_AMOUNT, 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format($list->LESS_AMOUNT, 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format($list->AMOUNT_APPLIED, 2) }}
                        </td>
                        <td class="text-left">
                            @if ($editId == $list->ID)
                                <input type="text" name="editNotes" wire:model='editNotes' class="w-100 text-xs" />
                            @else
                                {{ $list->NOTES }}
                            @endif
                        </td>

                    </tr>
                @endforeach
                
            </tbody>

        </table> --}}
    </div>
    <div class="col-md-6">
        <a class="btn btn-xs btn-success" target="_BLANK" href="#"></a>
    
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-8 text-right">
                <label>First Case Rate Amount :</label>
            </div>
            <div class="col-md-4 ">
                <label class="text-info">{{ number_format($P1_TOTAL) }}</label>
            </div>
            <div class="col-md-8 text-right">
                <label>Total Collection :</label>
            </div>
            <div class="col-md-4 ">
                <label class="text-success">{{ number_format($PAYMENT_AMOUNT) }}</label>
            </div>
            <div class="col-md-8 text-right">
                <label>Total Balance :</label>
            </div>
            <div class="col-md-4 ">
                <label class="text-danger">{{ number_format($BALANCE) }}</label>
            </div>
        </div>
    </div>
</div>
