<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsap_aging') }}"> AP Aging Report </a>
                    </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE" titleName="As of Date "
                                            wire:model.live='DATE' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        {{-- <livewire:date-input name="DATE_TO" titleName="Date To"
                                            wire:model.live='DATE_TO' :isDisabled="false" /> --}}
                                    </div>
                                    <div class='col-md-12 mt-1'>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-xs w-25"
                                                wire:click='summary()'>Summary</button>
                                            <button class="btn btn-info btn-xs w-25"
                                                wire:click='details()'>Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">
                                            <label class="text-xs ">Location:</label>
                                            <select
                                                @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                name="location" wire:model.live='LOCATION_ID'
                                                class="form-control form-control-sm text-xs ">
                                                <option value="0"> All Location</option>
                                                @foreach ($locationList as $item)
                                                    <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" col-12 col-sm-12 col-md-12  col-lg-12" style="max-height: 80vh; overflow-y: auto;">

                    @if ($isSummary)
                        @php
                            $DUE_CURRENT = 0;
                            $DUE_1_30 = 0;
                            $DUE_31_60 = 0;
                            $DUE_61_90 = 0;
                            $DUE_90_OVER = 0;
                            $BALANCE = 0;
                        @endphp

                        <table class="table table-sm  table-bordered table-hover ">
                            <thead class="bg-sky h1">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th class="text-left">Current</th>
                                    <th class="text-left">1-30</th>
                                    <th class="text-left">31-60</th>
                                    <th class="text-left">61-90</th>
                                    <th class="text-left">Over 90</th>
                                    <th class="text-left">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="h1">
                                @foreach ($summaryList as $list)
                                    <tr>
                                        <td>{{ $list->CONTACT_NAME }}</td>
                                        <td>{{ $list->TYPE }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_CURRENT, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_1_30, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_31_60, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_61_90, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->DUE_90_OVER, 2) }}</td>
                                        <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>

                                        @php
                                            $DUE_CURRENT = $DUE_CURRENT + $list->DUE_CURRENT;
                                            $DUE_1_30 = $DUE_1_30 + $list->DUE_1_30;
                                            $DUE_31_60 = $DUE_31_60 + $list->DUE_31_60;
                                            $DUE_61_90 = $DUE_61_90 + $list->DUE_61_90;
                                            $DUE_90_OVER = $DUE_90_OVER + $list->DUE_90_OVER;
                                            $BALANCE = $BALANCE + $list->BALANCE;
                                        @endphp
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-primary">TOTAL</td>
                                    <td class="text-right text-primary">{{ number_format($DUE_CURRENT, 2) }}</td>
                                    <td class="text-right text-primary">{{ number_format($DUE_1_30, 2) }}</td>
                                    <td class="text-right text-primary">{{ number_format($DUE_31_60, 2) }}</td>
                                    <td class="text-right text-primary">{{ number_format($DUE_61_90, 2) }}</td>
                                    <td class="text-right text-primary">{{ number_format($DUE_90_OVER, 2) }}</td>
                                    <td class="text-right text-primary">{{ number_format($BALANCE, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table table-sm  table-bordered table-hover ">
                            <thead class="bg-info h1">
                                <tr>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Reference #</th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Type</th>
                                    <th class="text-left">Terms</th>
                                    <th class="text-left">Due Date</th>
                                    <th class="text-left">Aging</th>
                                    <th class="text-left">Open Balance</th>
                                    <th class="text-left">Location</th>
                                </tr>
                            </thead>
                            <tbody class="h1">
                                @php
                                    $TMP_AGING = '';
                                    $COMPARE = '';
                                    $RUN_BALANCE = 0;
                                    $RUN_TOTAL = 0;
                                @endphp
                                @foreach ($detailList as $list)
                                    @if ($list->AGING <= 0)
                                        @if ($D_CURRENT == false)
                                            @if ($COMPARE != $TMP_AGING && $RUN_BALANCE > 0)
                                                <tr>
                                                
                                                    <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right text-primary">
                                                        {{ number_format($RUN_BALANCE, 2) }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td class="h4 text-primary">CURRENT</td>
                                            </tr>
                                            @php
                                                $D_CURRENT = true;
                                                $TMP_AGING = 'CURRENT';
                                                $RUN_BALANCE = 0;
                                            @endphp
                                        @endif
                                    @elseif ($list->AGING <= 30)
                                        @if ($D_1_30 == false)
                                            @if ($RUN_BALANCE > 0)
                                                <tr>
                                                    <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right text-primary">
                                                        {{ number_format($RUN_BALANCE, 2) }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="h4 text-primary">&nbsp;</td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td class="h4 text-primary">1-30</td>
                                            </tr>
                                            @php
                                                $D_1_30 = true;
                                                $TMP_AGING = '1-30';
                                                $RUN_BALANCE = 0;
                                            @endphp
                                        @endif
                                    @elseif ($list->AGING <= 60)
                                        @if ($D_31_60 == false)
                                            @if ($RUN_BALANCE > 0)
                                                <tr>
                                                    <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right text-primary">
                                                        {{ number_format($RUN_BALANCE, 2) }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="h4 text-primary">&nbsp;</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="h4 text-primary">31-60</td>
                                            </tr>
                                            @php
                                                $D_31_60 = true;
                                                $TMP_AGING = '31-60';
                                                $RUN_BALANCE = 0;
                                            @endphp
                                        @endif
                                    @elseif ($list->AGING <= 90)
                                        @if ($D_61_90 == false)
                                            @if ($RUN_BALANCE > 0)
                                                <tr>
                                                    <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right text-primary">
                                                        {{ number_format($RUN_BALANCE, 2) }} </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="h4 text-primary">&nbsp;</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="h4 text-primary">61-90</td>
                                            </tr>
                                            @php
                                                $D_61_90 = true;
                                                $TMP_AGING = '61-90';
                                                $RUN_BALANCE = 0;
                                            @endphp
                                        @endif
                                    @else
                                        @if ($D_91_OVER == false)
                                            @if ($RUN_BALANCE > 0)
                                                <tr>
                                                    <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right text-primary">
                                                        {{ number_format($RUN_BALANCE, 2) }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="h4 text-primary">&nbsp;</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="h4 text-primary">91 OVER</td>
                                            </tr>
                                            @php
                                                $D_91_OVER = true;
                                                $TMP_AGING = '91 OVER';
                                                $RUN_BALANCE = 0;
                                            @endphp
                                        @endif
                                    @endif
                                    @php
                                        $RUN_BALANCE = $RUN_BALANCE + $list->BALANCE_DUE;
                                    @endphp
                                    <tr>
                                        <td>{{ date('M/d/Y', strtotime($list->DATE)) }}</td>
                                        <td>{{ $list->CODE }}</td>
                                        <td>{{ $list->CONTACT_NAME }}</td>
                                        <td>{{ $list->TYPE }}</td>
                                        <td>{{ $list->PAYMENT_TERMS }}</td>
                                        <td>{{ date('M/d/Y', strtotime($list->DUE_DATE)) }}</td>
                                        <td>{{ $list->AGING < 1 ? '' : $list->AGING }}</td>
                                        <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                                        <td>{{ $list->LOCATION_NAME }}</td>
                                    </tr>
                                    @php
                                        $COMPARE = $TMP_AGING;
                                        $RUN_TOTAL = $RUN_TOTAL + $list->BALANCE_DUE ?? 0;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right text-primary">
                                        {{ number_format($RUN_BALANCE, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right text-danger">{{ number_format($RUN_TOTAL, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
