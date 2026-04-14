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
            <tr>
                @for ($j = 0; $j < 7; $j++)
                    @php
                        $currentDay = $i * 7 + $j - $startDayOfWeek + 1;
                        $isCurrentMonth = $currentDay >= 1 && $currentDay <= $daysInMonth;
                        $isExtraDay =
                        !$isCurrentMonth && (($i == 0 && $j < $startDayOfWeek) || $currentDay > $daysInMonth);
                    @endphp
                    @if ($isCurrentMonth)
                        <td class="mouse" wire:click="getsched('{{ $year . '-' . $month . '-' . $currentDay }}')">
                            <b class="@if ($today == $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($currentDay, 2, '0', STR_PAD_LEFT)) text-primary @endif text-sm">
                                {{ $currentDay }}
                            </b>
                            @livewire('Scheduler.shift-lists', ['date' => $year . '-' . $month . '-' . $currentDay, 'location_id' => $LOCATION_ID, 'select_date' => $date,'dataList' => $dataList])
                        </td>
                    @elseif ($currentDay <= 0)
                        @if ($month == 1)
                            <td class="mouse"
                                wire:click="getsched('{{ $year - 1 . '-' . $month + 11 . '-' . $daysInPreviousMonth + $currentDay }}')"
                                style="background-color: whitesmoke;">
                                <b class="text-sm">{{ $daysInPreviousMonth + $currentDay }}</b>
                                @livewire('Scheduler.shift-lists', ['date' => $year - 1 . '-' . $month + 11 . '-' . $daysInPreviousMonth + $currentDay, 'location_id' => $LOCATION_ID, 'select_date' => $date, 'dataList' => $dataList])

                            </td>
                        @else
                            <td class="mouse"
                                wire:click="getsched('{{ $year . '-' . $month - 1 . '-' . $daysInPreviousMonth + $currentDay }}')"
                                style="background-color: whitesmoke;">
                                <b class="text-sm">{{ $daysInPreviousMonth + $currentDay }}</b>
                                @livewire('Scheduler.shift-lists', ['date' => $year . '-' . $month - 1 . '-' . $daysInPreviousMonth + $currentDay, 'location_id' => $LOCATION_ID, 'select_date' => $date, 'dataList' => $dataList])
                            </td>
                        @endif
                    @elseif ($isExtraDay)
                        @if ($month == 12)
                            <td class="mouse"
                                wire:click="getsched('{{ $year + 1 . '-' . $month - 11 . '-' . $dayCounter }}')"
                                style="background-color: whitesmoke;">
                                <b class="text-sm"> {{ $dayCounter++ }}</b>
                                @livewire('Scheduler.shift-lists', ['date' => $year + 1 . '-' . $month - 11 . '-' . $dayCounter - 1, 'location_id' => $LOCATION_ID, 'select_date' => $date, 'dataList' => $dataList])
                            </td>
                        @else
                            <td class="mouse"
                                wire:click="getsched('{{ $year . '-' . $month + 1 . '-' . $dayCounter }}')"
                                style="background-color: whitesmoke;">
                                <b class="text-sm"> {{ $dayCounter++ }}</b>
                                @livewire('Scheduler.shift-lists', ['date' => $year . '-' . $month + 1 . '-' . $dayCounter - 1, 'location_id' => $LOCATION_ID, 'select_date' => $date, 'dataList' => $dataList])
                            </td>
                        @endif
                    @else
                        <td></td>
                    @endif
                @endfor
            </tr>
        @endfor
    </tbody>

</table>
