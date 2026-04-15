<div class="px-2">
    <h6 class="text-primary">Shift: {{ $shiftData->NAME }}</h6>


    <div class="form-group row text-xs">

        @foreach ($weekly as $dateList)
            <div class="col blackbox2" @if (\Carbon\Carbon::parse($dateList)->format('l') == 'Sunday') @else @endif>
                <div class="row">
                    <div class="font-weight-bold bottom-line2 col-12 bgBlack text-white">
                        <b> {{ \Carbon\Carbon::parse($dateList)->format('D') }}</b> :
                        {{ \Carbon\Carbon::parse($dateList)->format('m/d/Y') }}
                    </div>
                </div>
              

                <div class="font-weight-bold">
                    @livewire('Scheduler.PrintWeeklyNames', ['date' => \Carbon\Carbon::parse($dateList)->format('Y-m-d'), 'location' => $LOCATION_ID, 'shift' => $SHIFT_ID])
                </div>
            </div>
        @endforeach

    </div>


</div>
