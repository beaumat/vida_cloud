<table class="text-xs">
    <thead class="text-xs">
        <tr @if ($select_date && $select_date->format('Y-m-d') == $Date->format('Y-m-d')) class="bg-success" @else class="bg-info" @endif>
            <th>S</th>
            <th>W</th>
            <th>P</th>
            <th>A</th>
            <th>C</th>
        </tr>
    </thead>
    <tbody class="text-xs">
        @foreach ($totalPatientsByShift as $list)
            <tr>
                <td class="text-primary font-weight-bold">{{ $list->SHIFT_ID }}</td>
                <td class="text-primary font-weight-bold">{{ $list->W }}</td>
                <td class="text-primary font-weight-bold">{{ $list->P }}</td>
                <td class="text-primary font-weight-bold">{{ $list->A }}</td>
                <td class="text-primary font-weight-bold">{{ $list->C }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
