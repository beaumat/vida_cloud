<div class="form-group">
    <table class="table table-sm table-bordered table-hover ">
        <thead class="bg-sky h1">
            <tr>
                <th class='col-4'>Account</th>
                <th class=''>Jan</th>
                <th class=''>Feb</th>
                <th class=''>Mar</th>
                <th class=''>Apr</th>
                <th class=''>May</th>
                <th class=''>Jun</th>
                <th class=''>Jul</th>
                <th class=''>Aug</th>
                <th class=''>Sep</th>
                <th class=''>Oct</th>
                <th class=''>Nov</th>
                <th class=''>Dec</th>
                <th class=''>Total</th>
            </tr>
        </thead>
        <tbody class="h1">

            @foreach ($dataList as $list)
                <tr
                    class="@if ($list['ACCOUNT_TYPE'] == 'total') font-weight-bold @elseif ($list['ACCOUNT_TYPE'] == 'grand') font-weight-bold  text-info @else text-primary @endif">
                    <td
                        class="@if ($list['ACCOUNT_TYPE'] == 'total') text-sm @elseif ($list['ACCOUNT_TYPE'] == 'grand') text-sm @endif">
                        &nbsp; {{ $list['ACCOUNT_NAME'] }}</td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['JAN'] }}
                        @else
                            <a class="font-weight-normal"href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 1, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank"> {{ $list['JAN'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['FEB'] }}
                        @else
                            <a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 2, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['FEB'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['MAR'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 3, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['MAR'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['APR'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 4, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['APR'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['MAY'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 5, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['MAY'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['JUN'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 6, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['JUN'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['JUL'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 7, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['JUL'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['AUG'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 8, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['AUG'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['SEP'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 9, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['SEP'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['OCT'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 10, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['OCT'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['NOV'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 11, 'locationid' => $LOCATION_ID]) }}"target="_blank">{{ $list['NOV'] }}</a>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand' || $list['ACCOUNT_ID'] == '0')
                            {{ $list['DEC'] }}@else<a class="font-weight-normal"
                                href="{{ route('reportsfinancialincome_statement_report_account_viewer', ['id' => $list['ACCOUNT_ID'], 'year' => $YEAR, 'month' => 12, 'locationid' => $LOCATION_ID]) }}"
                                target="_blank">{{ $list['DEC'] }}</a>
                        @endif
                    </td>
                    <td
                        class="text-right  @if ($list['ACCOUNT_TYPE'] == 'total' || $list['ACCOUNT_TYPE'] == 'grand') font-weight-bold @else font-weight-normal text-danger @endif">
                        {{ $list['TOTAL'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
