    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0 text-xs" wire:loading.class='loading-form'>
            <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if ($tab == 's1st') active @endif" id="custom-tabs-four-s1st-tab"
                        wire:click="SelectTab('s1st')" data-toggle="pill" href="#custom-tabs-four-s1st" role="tab"
                        aria-controls="custom-tabs-four-s1st" aria-selected="true">1st</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($tab == 's2nd') active @endif" id="custom-tabs-four-s2nd-tab"
                        wire:click="SelectTab('s2nd')" data-toggle="pill" href="#custom-tabs-four-s2nd" role="tab"
                        aria-controls="custom-tabs-four-s2nd" aria-selected="true">2nd</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($tab == 's3rd') active @endif" id="custom-tabs-four-s3rd-tab"
                        wire:click="SelectTab('s3rd')" data-toggle="pill" href="#custom-tabs-four-s3rd" role="tab"
                        aria-controls="custom-tabs-four-s3rd" aria-selected="true">3rd</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($tab == 's4th') active @endif" id="custom-tabs-four-s4th-tab"
                        wire:click="SelectTab('s4th')" data-toggle="pill" href="#custom-tabs-four-s4th" role="tab"
                        aria-controls="custom-tabs-four-s4th" aria-selected="true">4th</a>
                </li>
                
            </ul>
        </div>
        <div class="card-body p-1">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade @if ($tab == 's1st') show active @endif"
                    id="custom-tabs-four-s1st" role="tabpanel" aria-labelledby="custom-tabs-four-s1st-tab">
                    @if ($tab == 's1st')
                        @livewire('Scheduler.SchedulerListShiftDetails', ['SHIFT_ID' => 1, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                    @endif
                </div>
                <div class="tab-pane fade @if ($tab == 's2nd') show active @endif"
                    id="custom-tabs-four-s2nd" role="tabpanel" aria-labelledby="custom-tabs-four-s2nd-tab">
                    @if ($tab == 's2nd')
                        @livewire('Scheduler.SchedulerListShiftDetails', ['SHIFT_ID' => 2, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                    @endif
                </div>
                <div class="tab-pane fade @if ($tab == 's3rd') show active @endif"
                    id="custom-tabs-four-s3rd" role="tabpanel" aria-labelledby="custom-tabs-four-s3rd-tab">
                    @if ($tab == 's3rd')
                        @livewire('Scheduler.SchedulerListShiftDetails', ['SHIFT_ID' => 3, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                    @endif
                </div>
                <div class="tab-pane fade @if ($tab == 's4th') show active @endif"
                    id="custom-tabs-four-s4th" role="tabpanel" aria-labelledby="custom-tabs-four-s4th-tab">
                    @if ($tab == 's4th')
                        @livewire('Scheduler.SchedulerListShiftDetails', ['SHIFT_ID' => 4, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                    @endif
                </div>
            </div>
        </div>
    </div>
