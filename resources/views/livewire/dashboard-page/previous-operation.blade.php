    <div class="card  card-primary @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title" type="button" wire:loading.attr='disabled' wire:click="onClickWid"><i class="fa fa-sun-o"
                    aria-hidden="true"></i> Previous Operation</h3>
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
            <div class="text-xs row">
                <div class="col-6">
                    Previous Date : <strong>{{ date('M/d/Y', strtotime($DATE)) }}</strong>
                </div>
                <div class="col-6">
                    Branch: <strong>{{ $LOCATION_NAME }}</strong>

                </div>
            </div>
            <table class="table table-sm table-bordered table-striped">
                <thead class="bg-info">
                    <tr>
                        <th class="text-center col-10">Operation</th>
                        <th class="text-center">Count</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Service Charge</td>
                        <td class="text-center text-primary">{{ $NO_OF_CHARGE }}</td>
                    </tr>
                    <tr>
                        <td>Treatment Posted</td>
                        <td class="text-center text-success">{{ $NO_OF_POSTED }}</td>
                    </tr>
                    <tr>
                        <td>Treatment Unposted</td>
                        <td class="text-center text-secondary">{{ $NO_OF_UNPOSTED }}</td>
                    </tr>
                    <tr>
                        <td>Treatment Void</td>
                        <td class="text-center text-danger">{{ $NO_OF_VOID }}</td>
                    </tr>
                    <tr>
                        <td>Difference (SERVICE CHARGE vs POSTED)</td>
                        <td class="text-center text-info font-weight-bold ">{{ $NO_OF_CHARGE - $NO_OF_POSTED }}</td>
                    </tr>

                </tbody>

            </table>
            <table class="table table-sm table-bordered table-striped">
                <thead class="bg-dark">
                    <tr>
                        <th class="text-center col-10">Item Release</th>
                        <th class="text-center">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemList as $list)
                        <tr>
                            <td>{{ $list->DESCRIPTION }}</td>
                            <td class="text-center">{{ number_format($list->TOTAL_QUANTITY, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
