    <div class="card card-teal @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title text-dark" type="button" wire:loading.attr='disabled' wire:click="onClickWid"><i class="fa fa-credit-card" aria-hidden="true"></i> Payables Aging Status
            </h3>
            <div class="card-tools ">
                <button type="button" wire:loading.attr='disabled' wire:click="onClickWid" class="btn btn-tool text-dark">
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
            <div class="row">
                <div class="col-8">
                    &nbsp;
                </div>
                <div class='col-4'>
                    &nbsp;
                </div>
                <div class="col-12">
                    <div style="max-height: 83vh; overflow-y: auto;">
                        <table class="table table-bordered table-hover">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th class="col-6">Branch</th>
                                    <th class="col-1">Current</th>
                                    <th class="col-1">1-30</th>
                                    <th class="col-1">31-60</th>
                                    <th class="col-1">61-90</th>
                                    <th class="col-1">Over 90</th>
                                    <th class="col-1">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($locationList as $list)
                                    <tr>
                                        <td>{{ $list->NAME }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_CURRENT, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_1_30, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_31_60, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_61_90, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_90_OVER, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
