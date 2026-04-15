         <table class="table table-sm table-bordered table-hover p-0">
             <thead class='bg-sky text-xs'>
                 <tr>
                     <th class="col-1">No.</th>
                     <th class="col-10">Patient Name</th>
                     <th class="col-1">Status</th>
                 </tr>
             </thead>
             <tbody class="text-xs">
                 @foreach ($dataList as $list)
                     <tr >
                         <td class="{{ $list['EXTRA_CLASS'] }}" >{{ $list['ID'] }}</td>
                         <td>{{ $list['NAME'] }}</td>
                         <td
                             class="@if ($list['STATUS'] == 'Present') text-success font-weight-bold @elseif ($list['STATUS'] == 'Absent')  text-danger font-weight-bold @elseif($list['STATUS'] == 'Cancelled') text-secondary font-weight-bold @endif">
                             {{ $list['STATUS'] }}</td>
                     </tr>
                 @endforeach
             </tbody>
         </table>
