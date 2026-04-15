    <div class="card card-info @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title" type="button" wire:loading.attr='disabled' wire:click="onClickWid"><i class="fa fa-heartbeat" aria-hidden="true"></i> Treatment Summary</h3>
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
            <div class="row">
                <div class="col-8">
                    <div class="text-xs">Select: Month <span class="badge bg-info">Previous</span> <span
                            class="badge bg-primary">Selected</span></div>
                    <select class="text-xs w-100" wire:model.live='month'>
                        @foreach ($monthlyList as $list)
                            <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class='col-4'>
                    <div class="text-xs">Select: Year</div>
                    <select class="text-xs w-100" wire:model.live='year'>
                        @foreach ($yearList as $list)
                            <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-sky">
                            <tr>
                                <th>Branch</th>
                                <th class="text-center col-3">Philhealth</th>
                                <th class="text-center">Priming</th>
                                <th class="text-center">Regular</th>
                                <th class="text-center col-3">No of Treament</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locationList as $list)
                                <tr>
                                    <td class="text-xs">{{ $list->NAME }}</td>
                                    <td class="text-center ">
                                        <div class="row">
                                            <div class="text-info col-6">{{ $list->PREV_TOTAL_PHILHEALTH }}</div>
                                            <div class="text-primary col-6">{{ $list->TOTAL_PHILHEALTH }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="row">
                                            <div class="text-info col-6">{{ $list->PREV_TOTAL_PRIMING }}</div>
                                            <div class="text-primary col-6">{{ $list->TOTAL_PRIMING }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="row">
                                            <div class="text-info col-6">{{ $list->PREV_TOTAL_REGULAR }}</div>
                                            <div class="text-primary col-6">{{ $list->TOTAL_REGULAR }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="row">
                                            <div class="text-info col-6">
                                                {{ $list->PREV_TOTAL_PHILHEALTH + $list->PREV_TOTAL_PRIMING + $list->PREV_TOTAL_REGULAR }}
                                            </div>
                                            <div class="text-primary col-6">
                                                {{ $list->TOTAL_PHILHEALTH + $list->TOTAL_PRIMING + $list->TOTAL_REGULAR }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
