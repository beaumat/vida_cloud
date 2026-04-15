<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientsschedules') }}"> Schedules</a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @livewire('Scheduler.PrintSchedules', ['MONTH' => $month, 'YEAR' => $year, 'LOCATION_ID' => $LOCATION_ID])
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-2">
                    <div class="sticky-top p-1">
                        <div class="row">
                            <div class="col-md-12">
                                @can('patient.schedule.modify')
                                    <a href="{{ route('patientsschedules_setup') }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-cog" aria-hidden="true"></i> Setup</a>
                                @endcan
                                @can('patient.schedule.print')
                                    <button wire:click="openModalPrint" class="btn btn-primary btn-sm">
                                        <i class="fa fa-print" aria-hidden="true"></i> Preview
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h5 class="text-primary card-title">Date on <b class="text-success">
                                        @if ($schedContact && count($schedContact) > 0)
                                            {{ $DATE->format('m/d/Y') }}
                                        @endif
                                    </b>
                                </h5>
                            </div>
                            <div class="col-md-6 text-right">
                            </div>
                            <div class="col-12">
                                @if ($DATE)
                                    @livewire('Scheduler.SchedulerListShift', ['LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE->format('Y-m-d')])
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-12 col-md-10">
                    <div class="card card-primary">
                        <div class="card-body bg-white">
                            <div id="calendar">
                                <div>
                                    <div class="row mb-2">
                                        <div class="col-md-4"
                                            @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <livewire:select-option name="LOCATION_ID" :options="$locationList"
                                                :zero="false" titleName="Location" :vertical="true"
                                                :isDisabled=false wire:model.live='LOCATION_ID' />
                                        </div>
                                        <div class="col-md-5 text-center mt-2">
                                            <h5>
                                                <select wire:model.live='month'>
                                                    @foreach ($monthList as $list)
                                                        <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                : <input type="number" wire:model.live.debounce='year'
                                                    style="width: 100px;" />
                                            </h5>
                                        </div>
                                        <div class="col-md-3 text-right mt-2">
                                            <button class="btn btn-primary btn-sm" wire:click.live="previousMonth">
                                                <i class="fa fa-chevron-circle-left" aria-hidden="true"></i> </button>
                                            <button class="btn btn-primary btn-sm" wire:click.live="todayMonth">
                                                Today
                                            </button>
                                            <button class="btn btn-primary btn-sm" wire:click.live="nextMonth">
                                                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div wire:loading.delay class="text-center text-gray-500 my-4">
                                        <span>Please wait</span>
                                        <span class="spinner animate-spin ml-1">⏳</span>
                                    </div>
                                   <div wire:loading.remove>
                                        <livewire:scheduler.calendar-list :year="$year" :month="$month"
                                            :locationid="$LOCATION_ID" :key="$refreshComponent" :date="$DATE" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
