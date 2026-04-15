<div>

    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-info">
            <tr>
                <th class="col-1">Pay Bill No.</th>
                <th class="col-1">Pay Bill Date</th>
                <th class="col-1 text-right">Pay Paid.</th>
                <th class="col-1 text-right">WTax.</th>
                <th class="col-1 text-right">Total.</th>
                <th class="col-1 text-center">No. of Patient</th>
                <th class="col-1 text-center bg-warning">Status</th>
                <th class="col-1 bg-secondary">OR No.</th>
                <th class="col-1 bg-secondary">OR Date</th>
                <th class="col-1 bg-secondary">From Date</th>
                <th class="col-1 bg-secondary">To Date</th>

                @can('patient.doctor.batch.delete')
                    <th class="col-1 bg-info text-center">Action</th>
                @endcan
            </tr>
        </thead>
        <tbody class="text-xs" wire:loading.class='loading-form'>

            @foreach ($dataList as $list)
                <tr>
                    <td>
                        <a target="_BLANK"
                            href="{{ route('vendorsbill_payment_edit', ['id' => $list->CHECK_ID]) }}">{{ $list->CODE }}</a>
                    </td>

                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>
                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->TAX_AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->AMOUNT + $list->TAX_AMOUNT, 2) }}</td>
                    <td class="text-center"> {{ $list->TOTAL_COUNT }} </td>
                    <td class="text-center">
                        <span class="font-weight-bold">{{ $list->STATUS }}</span>
                    </td>
                    <td> {{ $list->OR_NO }}</td>
                    <td>{{ \Carbon\Carbon::parse($list->OR_DATE)->format('m/d/Y') }} </td>
                    <td>{{ \Carbon\Carbon::parse($list->FROM_DATE)->format('m/d/Y') }} </td>
                    <td>{{ \Carbon\Carbon::parse($list->TO_DATE)->format('m/d/Y') }} </td>
                    @can('patient.doctor.batch.delete')
                        <td>
                            @if ($list->STATUS_ID == 0)
                                <div class="row">
                                    <div class="col-6">

                                        <button class="btn btn-success btn-xs w-100"
                                            wire:click="postedPayBill({{ $list->CHECK_ID }})">Posted</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-danger btn-xs text-xs w-100"
                                            wire:click='deleteItem({{ $list->ID }})'
                                            wire:confirm='Are you sure to remove from list?'>
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-danger btn-xs text-xs w-100"
                                            wire:click='deleteItem({{ $list->ID }})'
                                            wire:confirm='Are you sure to remove from list?'>
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif


                        </td>
                    @endcan
                </tr>
            @endforeach
            <tr wire:loading.delay>
                <td colspan="13" class="text-center">
                    <span class="spinner"></span>
                </td>
            </tr>
        </tbody>

    </table>

</div>
