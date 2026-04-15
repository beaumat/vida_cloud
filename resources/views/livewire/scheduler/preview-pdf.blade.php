<!DOCTYPE html>
<html>

<head>
    <title>Styled Table PDF</title>

    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .small {
            font-size: 10px;
        }

        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    @foreach ($shiftList as $shift)
        {{-- <table class="table small">
            <thead>
                <tr>
                    <th style="background-color:black; color:white">
                        <center>{{ $shift->NAME }} SHIFT</center>
                    </th>
                </tr>
            </thead>
        </table> --}}
        <table class="table small">
            <thead @if ($shift->ID == '1') style="background-color:blue;color:white;" @elseif ($shift->ID == '2') style="background-color:darkgreen;color:white;"  @elseif ($shift->ID == '3')  style="background-color:darkorange;color:white;" @endif>
                <tr>
                    @foreach ($weekdays as $dateList)
                        <th @if (\Carbon\Carbon::parse($dateList)->format('l') == 'Sunday') style="background-color:darkred;color:yellow" @endif>
                            <b> {{ \Carbon\Carbon::parse($dateList)->format('l') }}</b> :
                            {{ \Carbon\Carbon::parse($dateList)->format('m/d/Y') }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if ($weekdays)
                    <tr class="text-sm">
                        @foreach ($weekdays as $dateList)
                            <td>
                                @livewire('Scheduler.WeeklyPatient', ['DATE' => \Carbon\Carbon::parse($dateList)->format('Y-m-d'), 'LOCATION_ID' => $LOCATION_ID, 'SHIFT_ID' => $shift->ID], key(\Carbon\Carbon::parse($dateList)->format('Y-m-d') . $shift->ID))
                            </td>
                        @endforeach
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach

</body>

</html>
