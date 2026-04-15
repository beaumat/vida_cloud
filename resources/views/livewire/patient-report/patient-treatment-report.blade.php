<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportspatient_treatment_report') }}"> Patient Treatment Report
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
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-3 text-right">
                                        <label>Year:</label>
                                    </div>
                                    <div class="col-3">
                                        <input type="number" class="form-control form-control-sm" wire:model='YEAR' />
                                    </div>
                                    <div class="col-3">
                                        <button wire:click='reload()'
                                            class="btn btn-xs btn-warning w-100 ">Reload</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3 text-right">
                                        <label>Month:</label>
                                    </div>
                                    <div class="col-3">
                                        <select class="form-control form-control-sm" name="MONTH"
                                            wire:model.live='MONTH'>
                                            @foreach ($monthList as $item)
                                                <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <button wire:click='generate()'
                                            class="btn btn-xs btn-primary w-100">Generate</button>
                                    </div>
                                    <div class='col-3'>
                                        <button wire:click='ExportGenerate()'
                                            class="btn btn-xs btn-success w-100">Export</button>
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
            </div>
            <div class="row">
                {{-- header --}}
                <div class="col-md-12">
                    <div style="max-height: 75vh; overflow-y: auto;">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-sky sticky-header">
                                <tr>
                                    <th class="text-center ">No.</th>
                                    <th class="col-3">Patient Name</th>
                                    @foreach ($dailyList as $day)
                                        <th class="text-center">
                                            {{ date('d', strtotime($day)) }}<br />{{ date('D', strtotime($day)) }}</th>
                                    @endforeach
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">

                                @php
                                    $patient = 0;
                                    $total = 0;
                                @endphp

                                @foreach ($dataList as $list)
                                    @php
                                        $count = 0;
                                        $index = 0;
                                        $patient = $patient + 1;
                                    @endphp
                                    <tr>
                                        <td class="text-center text-primary">{{ $patient }}</td>
                                        <td>{{ $list->PATIENT_NAME }} </td>
                                        @foreach ($dailyList as $day)
                                            @php
                                                if ($list[date('d', strtotime($day))] == 1) {
                                                    $phicTotal[$index] = $phicTotal[$index] + 1;
                                                }

                                                if ($list[date('d', strtotime($day))] == 2) {
                                                    $premTotal[$index] = $premTotal[$index] + 1;
                                                }
                                                if ($list[date('d', strtotime($day))] == 3) {
                                                    $regularTotal[$index] = $regularTotal[$index] + 1;
                                                }
                                            @endphp
                                            <td
                                                class="text-center   @if ($list[date('d', strtotime($day))] == 1) bg-success  @elseif ($list[date('d', strtotime($day))] == 2) bg-orange @elseif ($list[date('d', strtotime($day))] == 3) bg-info @endif">
                                                @if ($list[date('d', strtotime($day))])
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                    @php
                                                        $storeTotal[$index] = $storeTotal[$index] + 1;
                                                        $count++;
                                                    @endphp
                                                @endif
                                            </td>
                                            @php
                                                $index++;
                                            @endphp
                                        @endforeach
                                        <td class="text-center font-weight-bold text-danger h6">{{ $count }}</td>
                                        @php
                                            $total = $total + $count;
                                        @endphp
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><br /></td>
                                </tr>

                                <tr class="bg-green text-white">
                                    @php
                                        $index = 0;
                                        $sum = 0;
                                    @endphp
                                    <td>

                                    </td>
                                    <td class="font-weight-bold text-center">
                                        <span>No. of Treatment W/ PHIC</span>
                                    </td>
                                    @foreach ($dailyList as $day)
                                        <td class="text-center font-weight-bold ">
                                            {{ $phicTotal[$index] }}
                                            @php
                                                $sum = $sum + $phicTotal[$index];
                                            @endphp
                                        </td>
                                        @php
                                            $index++;
                                        @endphp
                                    @endforeach
                                    <td class="text-center font-weight-bold text-sm ">{{ $sum }}</td>
                                </tr>

                                <tr class="bg-orange text-white">
                                    @php
                                        $index = 0;
                                        $sum = 0;
                                    @endphp
                                    <td>
                                    </td>
                                    <td class="font-weight-bold text-center">
                                        <span>No. of Treatment Priming</span>
                                    </td>
                                    @foreach ($dailyList as $day)
                                        <td class="text-center font-weight-bold ">
                                            {{ $premTotal[$index] }}
                                            @php
                                                $sum = $sum + $premTotal[$index];
                                            @endphp
                                        </td>
                                        @php
                                            $index++;
                                        @endphp
                                    @endforeach
                                    <td class="text-center font-weight-bold text-sm ">{{ $sum }}</td>
                                </tr>
                                <tr class="bg-info text-white">
                                    @php
                                        $index = 0;
                                        $sum = 0;
                                    @endphp
                                    <td>
                                    </td>
                                    <td class="font-weight-bold text-center">
                                        <span>No. of Treatment Regular Rate</span>
                                    </td>
                                    @foreach ($dailyList as $day)
                                        <td class="text-center font-weight-bold ">
                                            {{ $regularTotal[$index] }}
                                            @php
                                                $sum = $sum + $regularTotal[$index];
                                            @endphp
                                        </td>
                                        @php
                                            $index++;
                                        @endphp
                                    @endforeach
                                    <td class="text-center font-weight-bold text-sm ">{{ $sum }}</td>
                                </tr>

                                <tr class="bg-dark text-white">
                                    @php
                                        $index = 0;
                                    @endphp
                                    <td>
                                    </td>
                                    <td class="font-weight-bold text-center">
                                        <span>Total of Treatment</span>
                                    </td>
                                    @foreach ($dailyList as $day)
                                        <td class="text-center font-weight-bold ">
                                            {{ $storeTotal[$index] }}
                                        </td>
                                        @php
                                            $index++;
                                        @endphp
                                    @endforeach
                                    <td class="text-center font-weight-bold text-sm ">{{ $total }}</td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
        </div>
    </section>
</div>
