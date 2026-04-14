<table class="table  table-sm table-bordered">
    <thead class="bg-sky text-xs">
        <tr>
            <th class="col-1 bg-danger">SUNDAY</th>
            <th class="col-1">MONDAY</th>
            <th class="col-1">TUESDAY</th>
            <th class="col-1">WENSDAY</th>
            <th class="col-1">THURSDAY</th>
            <th class="col-1">FRIDAY</th>
            <th class="col-1">SATURDAY</th>
        </tr>
    </thead>
    <tbody class="text-xs">
        @for ($i = 0; $i < 6; $i++)
            @for ($j = 0; $j < 7; $j++)
                @php
                    $currentDay = $i * 7 + $j - $startDayOfWeek + 1;
                    $isCurrentMonth = $currentDay >= 1 && $currentDay <= $daysInMonth;
                    $isExtraDay = !$isCurrentMonth && (($i == 0 && $j < $startDayOfWeek) || $currentDay > $daysInMonth);
                @endphp
                
                @if ($isCurrentMonth)
                    <td>
                        <b class="@if ($today == $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($currentDay, 2, '0', STR_PAD_LEFT)) text-primary @endif text-sm">
                            {{ $currentDay }}
                        </b>
                        @livewire('Scheduler.shift', ['id' => $year . '-' . $month . '-' . $currentDay, 'contact_id' => $CONTACT_ID, 'shiftList' => $shiftList, 'location_id' => $LOCATION_ID, 'hemo_machine_id' => $HEMO_MACHINE_ID, 'hemoMachineList' => $hemoMachineList])
                    </td>
                @elseif ($currentDay <= 0)
                    <td style="background-color: whitesmoke;">
                        @if ($month == 1)
                            <b class="text-sm">{{ $daysInPreviousMonth + $currentDay }}</b>
                            @livewire('Scheduler.shift', ['id' => $year - 1 . '-' . $month + 11 . '-' . $daysInPreviousMonth + $currentDay, 'contact_id' => $CONTACT_ID, 'shiftList' => $shiftList, 'location_id' => $LOCATION_ID, 'hemo_machine_id' => $HEMO_MACHINE_ID, 'hemoMachineList' => $hemoMachineList])
                        @else
                            <b class="text-sm">{{ $daysInPreviousMonth + $currentDay }}</b>
                            @livewire('Scheduler.shift', ['id' => $year . '-' . $month - 1 . '-' . $daysInPreviousMonth + $currentDay, 'contact_id' => $CONTACT_ID, 'shiftList' => $shiftList, 'location_id' => $LOCATION_ID, 'hemo_machine_id' => $HEMO_MACHINE_ID, 'hemoMachineList' => $hemoMachineList])
                        @endif
                    </td>
                @elseif ($isExtraDay)
                    <td style="background-color: whitesmoke;">
                        <b class="text-sm"> {{ $dayCounter++ }}</b>
                        @if ($month == 12)
                            @livewire('Scheduler.shift', ['id' => $year + 1 . '-' . $month - 11 . '-' . $dayCounter - 1, 'contact_id' => $CONTACT_ID, 'shiftList' => $shiftList, 'location_id' => $LOCATION_ID, 'hemo_machine_id' => $HEMO_MACHINE_ID, 'hemoMachineList' => $hemoMachineList])
                        @else
                            @livewire('Scheduler.shift', ['id' => $year . '-' . $month + 1 . '-' . $dayCounter - 1, 'contact_id' => $CONTACT_ID, 'shiftList' => $shiftList, 'location_id' => $LOCATION_ID, 'hemo_machine_id' => $HEMO_MACHINE_ID, 'hemoMachineList' => $hemoMachineList])
                        @endif
                    </td>
                @else
                    <td></td>
                @endif
            @endfor
            </tr>
        @endfor
    </tbody>

</table>
