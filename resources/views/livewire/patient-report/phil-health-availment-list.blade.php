<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsphilhealth_availment_list') }}">
                            Philhealth Availment Report
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
                                        <select class="form-control form-control-sm" wire:model.live='YEAR'>
                                            @foreach ($yearList as $list)
                                                <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-5">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">

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
                                            name="location" wire:model.live='LOCATION_ID'
                                            class="form-control form-control-sm text-xs ">
                                            <option value="0"> All Location</option>
                                            @foreach ($locationList as $item)
                                                <option value="{{ $item->ID }}">{{ $item->NAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-1">
                            <button class="btn btn-sm btn-primary w-100" wire:click='printAll()'>Print All</button>
                        </div>
                           <div class="col-1">
                            <button class="btn btn-sm btn-success w-100" wire:click='exportAll()'>Export All</button>
                        </div>
                        <div class="col-10">
                            <input type="text" wire:model.live.debounce.150ms='search' name="search"
                                class="form-control form-control-sm mb-1" placeholder="Search.." />
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky sticky-header">
                            <tr>
                                <th class="text-center">
                                    <input class="text-lg" type="checkbox" wire:model.live="SelectAll" />
                                </th>
                                <th>Name</th>
                                <th class="col-1 text-center">Total Dialyzer</th>
                                <th class="text-center">Jan </th>
                                <th class="text-center">Feb </th>
                                <th class=" text-center">Mar </th>
                                <th class=" text-center">Apr </th>
                                <th class=" text-center">May </th>
                                <th class=" text-center">Jun </th>
                                <th class=" text-center">Jul </th>
                                <th class=" text-center">Aug </th>
                                <th class=" text-center">Sep </th>
                                <th class=" text-center">Oct </th>
                                <th class=" text-center">Nov </th>
                                <th class=" text-center">Dec </th>

                                <th class="col-1 text-center">No. Actual Confinement </th>
                                <th class="col-1 text-center">No. Other Confinement</th>
                                <th class="col-1 text-center">Total Confinement</th>
                                <th class="col-1 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($patientList as $list)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="patientID{{ $list->ID }}"
                                            wire:model.live="selectPatient.{{ $list->ID }}" />
                                    </td>
                                    <td>{{ $list->NAME }}</td>
                                    <td class="text-center">{{ $list->TOTAL_ITEMS + $list->TOTAL_OTHER_ITEM }}</td>
                                    <td class="text-center">{{ $list->TOTAL_JAN }}</td>
                                    <td class="text-center">{{ $list->TOTAL_FEB }}</td>
                                    <td class="text-center">{{ $list->TOTAL_MAR }}</td>
                                    <td class="text-center">{{ $list->TOTAL_APR }}</td>
                                    <td class="text-center">{{ $list->TOTAL_MAY }}</td>
                                    <td class="text-center">{{ $list->TOTAL_JUN }}</td>
                                    <td class="text-center">{{ $list->TOTAL_JUL }}</td>
                                    <td class="text-center">{{ $list->TOTAL_AUG }}</td>
                                    <td class="text-center">{{ $list->TOTAL_SEP }}</td>
                                    <td class="text-center">{{ $list->TOTAL_OCT }}</td>
                                    <td class="text-center">{{ $list->TOTAL_NOV }}</td>
                                    <td class="text-center">{{ $list->TOTAL_DEC }}</td>

                                    <td class="text-center">{{ $list->TOTAL_DAYS }}</td>
                                    <td class="text-center">{{ $list->TOTAL_OTHER }}</td>
                                    <td class="text-center">{{ $list->TOTAL_DAYS + $list->TOTAL_OTHER }}</td>
                                    <td>
                                        <a target="_BLANK"
                                            href="{{ route('maintenancecontactprint_availment', ['id' => $list->ID, 'locationid' => $LOCATION_ID, 'year' => $YEAR]) }}"
                                            class="btn btn-primary btn-xs w-100">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>


@script
    <script>
        $wire.on('OpenNewTab', (eventData) => {
            window.open(eventData.data, '_blank');
        });
    </script>
@endscript
