<div id="tableContainer" style="max-height:50vh; overflow-y: auto;">
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky sticky-header">
            <tr>
                @foreach ($headers as $header)
                    <th class="{{ $header['class'] ?? '' }}">{{ $header['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($rows as $row)
                <tr>
                    @foreach ($headers as $header)
                        <td class="{{ $header['td_class'] ?? '' }}">
                            {!! data_get($row, $header['key']) !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
