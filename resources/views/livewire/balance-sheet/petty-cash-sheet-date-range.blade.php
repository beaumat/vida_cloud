<div class="form-group">
    <table class="table table-sm table-bordered table-hover">
        <thead class="bg-sky h1">
            <tr>
                <th class="col-7">Account</th>
                <th>Amount</th>
            </tr>
        </thead>

        <tbody class="h1">
            @forelse (($dataList ?? []) as $list)

                @php
                    $CODE = $list['CODE'] ?? '';
                    $type = $list['ACCOUNT_TYPE'] ?? ''; // still used for grouping logic
                @endphp

                <tr class="@if ($type == 'total') font-weight-bold 
                           @elseif ($type == 'grand') font-weight-bold text-info 
                           @else text-primary 
                           @endif">

                    <td class="@if ($type == 'total' ) text-sm @endif">
                        &nbsp; {{ $CODE }}
                    </td>

                    <td class="text-right
    @if ($type == 'total')
        font-weight-bold
    @else
        font-weight-normal
    @endif">

    @if ($type == 'total' )
        {{ $list['TOTAL'] ?? '-' }}
    @elseif (($list['ACCOUNT_ID'] ?? 0) > 0)
        {{-- <a target="_blank"
            href="{{ route('reportsfinancialincome_statement_report_account_viewer_summary', [
                'id' => $list['ACCOUNT_ID'],
                'datefrom' => $DATE_FROM,
                'dateto' => $DATE_TO,
                'locationid' => $LOCATION_ID
            ]) }}">
            {{ $list['TOTAL'] ?? '-' }}
        </a> --}}

        <a target="_blank"
          href="{{ route('reportsfinancialpetty_cash_statement_account_summary', ['id' => $list['ACCOUNT_ID']]) }}">
            {{ $list['TOTAL'] ?? '-' }}
        </a>
    @else
        {{-- Empty for rows without account ID --}}
    @endif

</td>
                </tr>

            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">
                        No data available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>