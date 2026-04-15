     <div class="form-group">
         @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
         <div style="max-height: 73vh; overflow-y: auto;" class="border">
             <div class="form-group">
                 <table class="table table-sm table-bordered table-hover">
                     <thead class="text-xs bg-sky sticky-header">
                         <tr>
                             <th class="col-9">Items Covered by PhilHealth </th>
                             <th class="col-3 text-center"> <button type="button" class="btn btn-xs btn-info w-100"
                                     wire:click='AutoDetect()' wire:confirm='Are you sure?'>Auto Fill-up</button>
                             </th>
                         </tr>
                     </thead>
                     <tbody class="text-xs">
                         @foreach ($dataList as $list)
                             <tr>
                                 <td>{{ $list->DESCRIPTION }}</td>
                                 <td class="text-center">
                                     <input type="checkbox" wire:model="checkedItems.{{ $list->ID }}"
                                         wire:change="update({{ $list->ID }}, $event.target.checked)"
                                         value="{{ $list->IS_CHECK ? true : false }}" />
                                 </td>
                             </tr>
                         @endforeach

                     </tbody>
                 </table>
             </div>

         </div>
     </div>
