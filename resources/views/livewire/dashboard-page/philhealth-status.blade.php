    <div class="card card-success @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title" type="button" wire:loading.attr='disabled' wire:click="onClickWid"><i class="fa fa-medkit" aria-hidden="true"></i> Philhealth Monitoring</h3>
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
                    <div class="col-12">
                        &nbsp; <span class="text-xs text-dark">Remarks:due date for more than </span> <span
                            class="badge bg-orange">10 days</span>
                        <span class="badge bg-pink">20 Days</span>
                        <span class="badge bg-danger">30 Days</span>
                    </div>

                </div>
                <table class="table table-bordered table-hover">
              <thead class="bg-sky">
                        <tr>
                            <th class="col-3">Branch</th>
                            <th class="text-center col-2">Latest SOA Created</th>
                            <th class="text-center col-2"># of w/o Transmittal</th>
                            <th class="text-center col-2">Last Due w/o Trans.</th>
                            <th class="text-center col-2"># of Not Paid</th>
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
                                <td class="text-center">{{ $list->NO_TRANSMIT }}</td>
                                <td class="text-left">


                                    @if ($list->DUE <= 9)
                                        {{ $list->DUE }} Days
                                    @elseif ($list->DUE <= 19)
                                        <span class="text-orange font-weight-bold">{{ $list->DUE }} Days</span>
                                    @elseif ($list->DUE <= 29)
                                        <span class="text-pink font-weight-bold">{{ $list->DUE }} Days</span>
                                    @else
                                        <span class="text-red font-weight-bold">{{ $list->DUE }} Days</span>
                                    @endif
                                </td>

                                <td class="text-center">{{ $list->NOT_PAID }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
