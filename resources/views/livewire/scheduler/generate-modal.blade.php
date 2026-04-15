<div class="col-md-3">

    <button wire:click="openModal" class="btn btn-success btn-sm text-xs">
        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Generate Schedule
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'active') active @endif"
                                            id="custom-tabs-four-active-tab" wire:click="SelectTab('active')"
                                            data-toggle="pill" href="#custom-tabs-four-active" role="tab"
                                            aria-controls="custom-tabs-four-active" aria-selected="true">Active Patients
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == '1st') active @endif"
                                            id="custom-tabs-four-1st-tab" wire:click="SelectTab('1st')"
                                            data-toggle="pill" href="#custom-tabs-four-1st" role="tab"
                                            aria-controls="custom-tabs-four-1st" aria-selected="true">
                                            1st Shift
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == '2nd') active @endif"
                                            id="custom-tabs-four-2nd-tab" wire:click="SelectTab('2nd')"
                                            data-toggle="pill" href="#custom-tabs-four-2nd" role="tab"
                                            aria-controls="custom-tabs-four-2nd" aria-selected="true">
                                            2nd Shift
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == '3rd') active @endif"
                                            id="custom-tabs-four-3rd-tab" wire:click="SelectTab('3rd')"
                                            data-toggle="pill" href="#custom-tabs-four-3rd" role="tab"
                                            aria-controls="custom-tabs-four-3rd" aria-selected="true">
                                            3rd Shift
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade @if ($tab == 'active') show active @endif"
                                        id="custom-tabs-four-active" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-active-tab">
                                        <div class="form-group">
                                            <table class="table table-sm table-bordered">
                                                <thead class="text-xs bg-sky">
                                                    <tr>
                                                        <th class="text-center">
                                                            <input type="checkbox" wire:model.live='SelectAll' />
                                                        </th>
                                                        <th>Name</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th class="text-center">Admitted</th>
                                                        <th class="text-center">50/60 Hrs.</th>
                                                        <th>Schedule Type</th>
                                                        <th>Date Admission</th>
                                                        <th>Action </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs">
                                                    @foreach ($contactList as $list)
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="checkbox"
                                                                    wire:model.live='patientSelected.{{ $list->ID }}' />
                                                            </td>
                                                            <td> {{ $list->NAME }}</td>
                                                            <td>{{ $list->PATIENT_TYPE }}</td>
                                                            <td>{{ $list->PATIENT_STATUS }}</td>
                                                            <td class="text-center">
                                                                @if ($list->ADMITTED)
                                                                    YES
                                                                @else
                                                                    NO
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($list->LONG_HRS_DURATION)
                                                                    YES
                                                                @else
                                                                    NO
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $list->SCHEDULE_TYPE }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($list->DATE_ADMISSION)->format('M/d/Y') }}
                                                            </td>
                                                            <td class="text-center"><a target="_blank"
                                                                    class="text-primary"
                                                                    href='{{ route(' maintenancecontactpatients_edit', ['id' => $list->ID]) }}'>View</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                    <div class="tab-pane fade @if ($tab == '1st') show active @endif"
                                        id="custom-tabs-four-1st" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-1st-tab">
                                        @livewire('Scheduler.WeeklyShift', ['weekdays' => $weekdays, 'LOCATION_ID' => $LOCATION_ID, 'SHIFT_ID' => 1], key('1stShift'))
                                    </div>
                                    <div class="tab-pane fade @if ($tab == '2nd') show active @endif"
                                        id="custom-tabs-four-2nd" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-new-2nd">
                                        @livewire('Scheduler.WeeklyShift', ['weekdays' => $weekdays, 'LOCATION_ID' => $LOCATION_ID, 'SHIFT_ID' => 2], key('2ndShift'))
                                    </div>
                                    <div class="tab-pane fade @if ($tab == '3rd') show active @endif"
                                        id="custom-tabs-four-3rd" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-new-3rd">
                                        @livewire('Scheduler.WeeklyShift', ['weekdays' => $weekdays, 'LOCATION_ID' => $LOCATION_ID, 'SHIFT_ID' => 2], key('3rdShift'))
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-success btn-sm"
                                        wire:click="generate">Generate</button>
                                </div>
                                <div class="col-md-3 text-right">
                                    <label class="text-sm">Selected :</label>
                                </div>
                                <div class="col-md-3">
                                    <select wire:model.live='WEEKLY_ID' class="form-control form-control-sm">
                                        @foreach ($weekLevel as $list)
                                            <option value="{{ $list['ID'] }}">{{ $list['DESCRIPTION'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    @livewire('Scheduler.GeneratePDF', ['weekdays' => $weekdays, 'LOCATION_ID' => $LOCATION_ID, 'shiftList' => $shiftList], key('generate_data'))
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-secondary btn-sm"
                                wire:click="closeModal">Close</button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
