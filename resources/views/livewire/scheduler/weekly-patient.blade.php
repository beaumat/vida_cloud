<div>
    <table class="table table-sm table-white table-bordered">
        <tbody class="text-xs">
            @foreach ($patientList as $list)
                <tr>
                    <td class="col-1">{{ $list['ID'] }}</td>
                    <td class="col-11">{{ $list['PATIENT_NAME'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
