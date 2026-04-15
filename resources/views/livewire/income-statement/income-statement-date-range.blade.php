<div class="form-group">
    <table class="table table-sm table-bordered table-hover ">
        <thead class="bg-sky h1">
            <tr>
                <th class='col-7'>Account</th>
                <th class=''>Amount</th>
            </tr>
        </thead>
        <tbody class="h1">

            @foreach ($dataList as $list)
                <tr
                    class="@if ($list['ACCOUNT_TYPE'] == 'total') font-weight-bold @elseif ($list['ACCOUNT_TYPE'] == 'grand') font-weight-bold  text-info @else text-primary @endif">
                    <td
                        class="@if ($list['ACCOUNT_TYPE'] == 'total') text-sm @elseif ($list['ACCOUNT_TYPE'] == 'grand') text-sm @endif">
                        &nbsp; {{ $list['ACCOUNT_NAME'] }}</td>
                    <td
                        class="text-right  @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand') font-weight-bold @else font-weight-normal text-danger @endif">

                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['TOTAL'] }}
                        @else
                            <a target="_blank"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer_summary', ['id' => $list['ACCOUNT_ID'], 'datefrom' => $DATE_FROM, 'dateto' => $DATE_TO, 'locationid' => $LOCATION_ID]) }}">
                                {{ $list['TOTAL'] }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
