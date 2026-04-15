    <div class="card card-yellow @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title" type="button" wire:loading.attr='disabled' wire:click="onClickWid"><i class="fa fa-money" aria-hidden="true"></i> Sales Collection</h3>
            <div class="card-tools">
                <button type="button" wire:loading.attr='disabled' wire:click="onClickWid" class="btn btn-tool">
                    @if (!$isShow)
                        <i class="fas fa-plus"></i>
                    @else
                        <i class="fas fa-minus"></i>
                    @endif
                </button>
                <div wire:loading.delay>
                    <span class="spinner"></span>
                </div>
            </div>
        </div>
        <div class="card-body p-1 @if (!$isShow) d-none @endif">
            <div class="inner">
                <div class="row">
                    <div class="col-8">
                        <div class="text-xs row">
                            <div class="col-4 text-right"> Month </div>
                            <div class="col-8">
                                <select class="text-xs w-100" wire:model.live='month'>
                                    @foreach ($monthlyList as $list)
                                        <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>



                        </div>

                    </div>
                    <div class='col-4'>
                        <div class="text-xs row">
                            <div class="col-4 text-right">
                                Year
                            </div>
                            <div class="col-8">
                                <select class="text-xs w-100" wire:model.live='year'>
                                    @foreach ($yearList as $list)
                                        <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <table class="table table-bordered table-hover">
                    <thead class="bg-sky">
                        <tr>
                            <th class="">Branch</th>
                            <th class="">Service Charges</th>
                            <th class="">Sales Receipt</th>
                            <th class="">Invoice</th>
                            <th class="">Receive Payment </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locationList as $list)
                            <tr>
                                <td>{{ $list->NAME }}</td>
                                <td class="text-right">{{ number_format($list->SERVICE_CHARGES_TOTAL, 2) }}</td>
                                <td class="text-right">{{ number_format($list->SALES_RECEIPT_TOTAL, 2) }}</td>
                                <td class="text-right">{{ number_format($list->INVOICE_TOTAL, 2) }}</td>
                                <td class="text-right">{{ number_format($list->PAYMENT_TOTAL, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
