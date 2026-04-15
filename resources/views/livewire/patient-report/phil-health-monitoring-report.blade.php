<?php
use App\Services\OtherServices;
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsphilhealth_monitoring') }}">
                            Philhealth Monitoring Report
                        </a>
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
                            <div class="col-12 col-md-2">
                                <div class="row">
                                    <div class="col-12 col-md-5">

                                        <label class="text-xs ">Year:</label>
                                        <select class="form-control form-control-sm" wire:model='YEAR'>
                                            @foreach ($yearList as $list)
                                                <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label class="text-xs ">Month:</label>
                                        <select class="form-control form-control-sm" wire:model='MONTH'>
                                            <option value="0"> All </option>
                                            @foreach ($monthList as $list)
                                                <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <div wire:loading.delay>
                                                <span class="spinner"></span>
                                            </div>
                                            <button class="btn btn-sm btn-primary mt-4" wire:click='generate()'
                                                wire:loading.attr='disabled'>Filter</button>
                                            <button class="btn btn-sm btn-success mt-4" wire:click='export()'
                                                wire:loading.attr='disabled'>Export</button>

                                        </div>
                                        <div class="col-6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-xs ">Location:</label>
                                        <select
                                            @if (Auth::user()->locked_location) style="opacity:
                                                0.5;pointer-events: none;" @endif
                                            name="location" wire:model='LOCATION_ID'
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
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        @php
                            $running = 0;
                            $TOTAL_NOT = 0;
                            $TOTAL_AMOUNT = 0;
                            $TOTAL_WTAX = 0;
                            $TOTAL_PAID = 0;
                            $TOTAL_GROSS = 0;
                            $TOTAL_PF = 0;
                            $TOTAL_NET = 0;
                        @endphp
                        <thead class="text-xs bg-sky sticky-header">
                            <tr>
                                <th>No.</th>
                                <th>Date Trans.</th>
                                <th>Series LHIO</th>
                                <th>Name of Patient</th>
                                <th>Confinement Period</th>
                                <th class="text-center">No. of <br /> Treatment</th>
                                <th>Total <br /> Amount</th>
                                <th class="bg-info">Date Paid</th>
                                <th class="bg-info">OR Number</th>
                                <th class="bg-info">Wtax <br /> Amount.</th>
                                <th class="bg-info">Paid <br /> Amount.</th>
                                <th class="bg-info">Gross <br /> Amount.</th>
                                <th class="bg-success">Doctor PF</th>
                                <th class="bg-success">Net <br /> Amount.</th>
                                <th class="text-center bg-orange">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    @php
                                        $running++;
                                    @endphp
                                    <td>{{ $running }}</td>
                                    <td>
                                        @if ($list->AR_DATE)
                                            {{ date('M/d/Y', strtotime($list->AR_DATE)) }}
                                        @endif
                                    </td>
                                    <td>{{ $list->AR_NO }}</td>
                                    <td>{{ $list->CONTACT_NAME }}</td>
                                    <td>
                                        {{ OtherServices::formatDates($list->CONFINE_PERIOD) }}
                                    </td>
                                    <td class="text-center">{{ $list->HEMO_TOTAL }}</td>
                                    @php
                                        $TOTAL_NOT += $list->HEMO_TOTAL;
                                        $TOTAL_AMOUNT += $list->P1_TOTAL;
                                    @endphp
                                    <td class="text-right">{{ number_format($list->P1_TOTAL, 2) }}</td>
                                    <td>
                                        @if ($list->PAID_DATE)
                                            {{ date('M/d/Y', strtotime($list->PAID_DATE)) }}
                                        @endif
                                    </td>
                                    <td>{{ $list->OR_NUMBER }}</td>
                                    <td class="text-right">
                                        @if ($list->TAX_AMOUNT > 0)
                                            @php
                                                $TOTAL_WTAX += $list->TAX_AMOUNT;
                                            @endphp
                                            {{ number_format($list->TAX_AMOUNT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->PAID_AMOUNT > 0)
                                            @php
                                                $TOTAL_PAID += $list->PAID_AMOUNT;
                                            @endphp
                                            {{ number_format($list->PAID_AMOUNT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->PAID_AMOUNT > 0)
                                            @php
                                                $TOTAL_GROSS += $list->PAID_AMOUNT + $list->TAX_AMOUNT;
                                            @endphp
                                            {{ number_format($list->PAID_AMOUNT + $list->TAX_AMOUNT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->DOCTOR_PF > 0)
                                            @php
                                                $TOTAL_PF += $list->DOCTOR_PF;
                                            @endphp
                                            {{ number_format($list->DOCTOR_PF, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($list->DOCTOR_PF > 0)
                                            @php
                                                $TOTAL_NET += $list->PAID_AMOUNT + $list->TAX_AMOUNT - $list->DOCTOR_PF;
                                            @endphp
                                            {{ number_format($list->PAID_AMOUNT + $list->TAX_AMOUNT - $list->DOCTOR_PF, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($list->DOCTOR_PF > 0)
                                            @if ($list->DOCTOR_PF_BALANCE > 0)
                                                <i class="fa fa-times-circle text-danger" aria-hidden="true"></i> Not
                                                Paid
                                            @else
                                                <i class="fa fa-check-circle text-success" aria-hidden="true"></i> Paid
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="text-danger font-weight-bold">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">{{ $TOTAL_NOT }}</td>
                                <td class="text-right">{{ number_format($TOTAL_AMOUNT, 2) }}</td>
                                <td></td>
                                <td></td>
                                <td class='text-right'>{{ number_format($TOTAL_WTAX, 2) }}</td>
                                <td class='text-right'>{{ number_format($TOTAL_PAID, 2) }}</td>
                                <td class='text-right'>{{ number_format($TOTAL_GROSS, 2) }}</td>
                                <td class='text-right'>{{ number_format($TOTAL_PF, 2) }}</td>
                                <td class='text-right'>{{ number_format($TOTAL_NET, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
