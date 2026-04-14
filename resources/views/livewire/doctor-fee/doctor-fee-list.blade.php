<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportspatient_sales_report') }}">
                            Doctor Professional Fee
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
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-danger" wire:click='Generate()'>Generate</button>
                                <button class="btn btn-sm btn-success" wire:click='Export()'>Export</button>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-2  text-right">
                                        <label class="text-sm">From</label>
                                    </div>
                                    <div class="col-3">
                                        <div>
                                            <input type="date" class="form-control form-control-sm" name="DATE_FROM"
                                                wire:model='DATE_FROM' />
                                        </div>
                                    </div>
                                    <div class="col-2 text-right">
                                        <label class="text-sm">To</label>
                                    </div>
                                    <div class="col-3">
                                        <div>
                                            <input type="date" class="form-control form-control-sm" name="DATE_TO"
                                                wire:model='DATE_TO' />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class='col-4  text-md-right'>
                                        <label class="text-xs pt-2">Location:</label>
                                    </div>
                                    <div class="col-8">
                                        <select
                                            @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                            name="location" wire:model.live='LOCATION_ID'
                                            class="form-control form-control-sm text-xs mt-1">
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
                <div class="col-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead>
                            @if ($headerList)
                                <tr class="">
                                    <th class="bg-primary">Doctor List </th>
                                    @foreach ($headerList as $list)
                                        <th class="bg-warning text-center col-1">
                                            {{ date('m/d/Y', strtotime($list['DATE_FROM'])) }}-{{ date('m/d/Y', strtotime($list['DATE_TO'])) }}
                                        </th>
                                    @endforeach

                                    <th name="total" class="bg-orange col-1"></th>
                                    <th name="prev-balance" class="bg-danger col-1"></th>
                                    <th name="grand-total" class="bg-success col-1"></th>
                                </tr>

                            @endif
                        </thead>
                        @php
                            $grandtotal = 0;
                            $balancetotal = 0;
                        @endphp
                        <tbody class="text-xs">
                            @if ($headerList)
                                <tr class="">
                                    <td class="col-3"></td>
                                    @foreach ($headerList as $list)
                                        <td class=" text-center">
                                            {{ date('m/d/Y', strtotime($list['DATE'])) }}
                                        </td>
                                    @endforeach

                                    <td name="total" class=" text-right">Sub</td>
                                    <td name="prev-balance" class="text-danger text-right">Remaining</td>
                                    <td name="grand-total" class="text-success text-right">Grand</td>
                                </tr>
                                <tr class="">
                                    <td>&nbsp;</td>
                                    @foreach ($headerList as $list)
                                        <td class="font-weight-bold text-center">
                                            {{ $list['RECEIPT_NO'] }}
                                        </td>
                                    @endforeach

                                    <td class="text-right"> Total</td>
                                    <td name="balance" class="text-right text-danger">Balance</td>
                                    <td name="grand-total" class="text-right text-success">Total</td>
                                </tr>
                            @endif

                            @foreach ($doctorList as $list)
                                <tr>
                                    <td>{{ $list['DOCTOR_NAME'] }}</td>
                                    @php
                                        $total = 0;
                                    @endphp

                                    @for ($n = 1; $n <= $row; $n++)
                                        @php
                                            $total = $total + $list[$n] ?? 0;
                                        @endphp
                                        <td class='text-right'>{{ number_format($list[$n], 2) }}</td>
                                    @endfor

                                    <td class="text-right">{{ number_format($total, 2) }}</td>
                                    <td name="prev-bal" class="text-right">
                                        @php
                                            $balancetotal = $balancetotal + $list['BALANCE_TOTAL'] ?? 0.0;
                                        @endphp
                                        {{ number_format($list['BALANCE_TOTAL'], 2) }}</td>
                                    <td name="grand-total" class="text-right">
                                        {{ number_format($total + $list['BALANCE_TOTAL'], 2) }}</td>
                                    @php
                                        $grandtotal = $grandtotal + ($total + $list['BALANCE_TOTAL']) ?? 0;
                                    @endphp
                                </tr>
                            @endforeach
                            @if ($grandtotal > 0 || $balancetotal > 0)
                                <tr>
                                    <td class="font-weight-bold text-primary">TOTAL</td>
                                    @for ($n = 1; $n <= $row; $n++)
                                        <td class='text-right font-weight-bold text-primary'>
                                            @if (isset($totalList[$n]))
                                                {{ number_format($totalList[$n], 2) }}
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="text-right font-weight-bold text-primary">
                                        {{ number_format($grandtotal, 2) }}
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">
                                        {{ number_format($balancetotal, 2) }}
                                    </td>
                                    <td class="text-right font-weight-bold text-success">
                                        {{ number_format($grandtotal + $balancetotal, 2) }}
                                    </td>
                                </tr>

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
