       <div style="max-height: 60vh; overflow-y: auto;">
           <table class="table table-sm table-bordered table-hover">
               <thead class="bg-sky text-xs">
                   <tr>
                       <th>(SC)Code</th>
                       <th>(SC)Date</th>
                       <th>Items</th>
                       <th class="text-right">(SC) Amount</th>
                       <th class="text-right bg-info">Paid Amount</th>
                       <th class="text-right bg-danger">
                           Balance <i wire:click='reload()' type="button" class="fa fa-refresh" aria-hidden="true">
                       </th>
                   </tr>
               </thead>
               <tbody class="text-xs">
                   @foreach ($dataList as $list)
                       @php
                           $BALANCE = $BALANCE + $list->BALANCE;
                       @endphp
                       <tr>
                           <td> <a target="_BLANK"
                                   href="{{ route('patientsservice_charges_edit', ['id' => $list->SERVICE_CHARGES_ID]) }}">
                                   {{ $list->CODE }} </a> </td>
                           <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                           <td>{{ $list->ITEM_NAME }}</td>
                           <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                           <td class="text-right">{{ number_format($list->PAID_AMOUNT, 2) }}</td>
                           <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                       </tr>
                   @endforeach
                   <tr class="bg-white">
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td class="text-right font-weight-bold">Total:</td>
                       <td class="text-right font-weight-bold text-danger">{{ number_format($BALANCE, 2) }}</td>
                   </tr>
               </tbody>
           </table>
       </div>
