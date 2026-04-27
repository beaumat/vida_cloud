<div class="form-group">
    <table class="table table-sm table-bordered table-hover w-100">
        <thead class="bg-sky h1">
            <tr>
                 <th style="width: 20%;">Date</th>
                <th style="width: 50%;">Code</th>
                <th style="width: 20%;" class="text-right">Amount</th>
               
                <th style="width: 10%;" class="text-center no-print">Action</th>
            </tr>
        </thead>

        <tbody class="h1">
            @foreach ($dataList as $list)
                <tr class="text-primary">
                     <td>
                        {{ $list['DATE'] }}
                    </td>
                    <td class="text-truncate">
                        {{ $list['CODE'] }}
                    </td>

                    <td class="text-right">
                        {{ $list['AMOUNT'] }}
                    </td>

                   

                    <td class="text-center no-print">
                       <a title="View Details"
                                                    {{-- href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}" --}}
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>