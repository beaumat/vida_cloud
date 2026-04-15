<div >
    <table class="table table-sm table-bordered">
        <thead class="text-xs bg-sky">
            @if ($weekdays)
                <tr>
                    @foreach ($weekdays as $dateList)
                        <td class="@if (\Carbon\Carbon::parse($dateList)->format('l') == 'Sunday') bg-danger @endif">
                            <b> {{ \Carbon\Carbon::parse($dateList)->format('l') }}</b> :
                            {{ \Carbon\Carbon::parse($dateList)->format('m/d/Y') }}
                        </td>
                    @endforeach
                </tr>
            @else
                <tr>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                    <th class="bg-danger">Sunday</th>

                </tr>
            @endif
        </thead>
        <tbody class="text-xs">
            @if ($weekdays)
                <tr class="text-sm">
                    @foreach ($weekdays as $dateList)
                        <td>
                            @livewire('Scheduler.WeeklyPatient', ['DATE' => \Carbon\Carbon::parse($dateList)->format('Y-m-d'), 'LOCATION_ID' => $LOCATION_ID, 'SHIFT_ID' => $SHIFT_ID], key(\Carbon\Carbon::parse($dateList)->format('Y-m-d') . $SHIFT_ID))
                        </td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

</div>
