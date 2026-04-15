    <div class="card card-red @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title" type="button" wire:loading.attr='disabled' wire:click="onClickWid"><i class="fa fa-user-md" aria-hidden="true"></i> Doctor PF Monitoring</h3>
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
                        &nbsp;
                    </div>
                    <div class='col-4'>
                        &nbsp;
                    </div>
                </div>
                <table class="table table-bordered table-hover">
                    <thead class="bg-sky">
                        <tr>
                            <th class="col-3">Branch</th>
                            <th class="text-center col-2">Latest Billing Created</th>
                            <th class="text-center col-2">Balance Amount</th>
                            <th class="text-center col-2">#bills Not Paid </th>
                            <th class="text-center col-2">#PF not posted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locationList as $list)
                            <tr>
                                <td>{{ $list->NAME }}</td>
                                <td>
                                    @if ($list->LAST_RECORDED)
                                        {{ date('M/d/Y', strtotime($list->LAST_RECORDED)) }}
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($list->TOTAL_BALANCE, 2) }}</td>
                                <td class="text-center">{{ $list->NO_BILL_NOT_PAID }}</td>
                                <td class="text-center">{{ $list->NOT_PAID }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
