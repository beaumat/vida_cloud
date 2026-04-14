     <div class="form-group">
         @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
         <div style="max-height: 73vh; overflow-y: auto;" class="border">
             <div>
                 <table class="table table-sm table-bordered table-hover">
                     <thead class="text-xs bg-sky sticky-header">
                         <tr>
                             <th class="col-7">Item Description </th>
                             <th class="col-1">Quantity </th>
                             <th class="col-2">Price</th>
                             <th class="col-2 text-center">Action</th>
                         </tr>
                     </thead>
                     <tbody class="text-xs">
                         @php
                             $total = 0;
                         @endphp
                         @foreach ($dataList as $list)
                             @php
                                 $total = $total + ($list->QUANTITY * $list->RATE ?? 0);
                             @endphp
                             <tr>
                                 <td>

                                     @if ($E_ID == $list->ID)
                                         <input type="text" name="descripton" wire:model='E_DESCRIPTION'
                                             class="w-100" />
                                     @else
                                         {{ $list->DESCRIPTION }}
                                     @endif


                                 </td>
                                 <td class="text-right">
                                     @if ($E_ID == $list->ID)
                                         <input type="number" name="quantity" wire:model='E_QUANTITY' class="w-100" />
                                     @else
                                         {{ $list->QUANTITY }}
                                     @endif


                                 </td>
                                 <td class="text-right">
                                     @if ($E_ID == $list->ID)
                                         <input type="number" name="rate" wire:model='E_RATE' class="w-100" />
                                     @else
                                         {{ number_format($list->RATE, 2) }}
                                     @endif

                                 </td>
                                 <td>
                                     <div class="row">
                                         @if ($E_ID == $list->ID)
                                             <div class="col-6">
                                                 <button name="update" class="btn btn-xs btn-primary w-100"
                                                     wire:click='update()'>
                                                     <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                 </button>
                                             </div>
                                             <div class="col-6 w-100">
                                                 <button name="cancel" class="btn btn-xs btn-warning w-100"
                                                     wire:confirm='Are you sure to cancel?' wire:click='canceled()'>
                                                     <i class="fa fa-ban" aria-hidden="true"></i>
                                                 </button>
                                             </div>
                                         @else
                                             <div class="col-6">
                                                 <button name="edit" class="btn btn-xs btn-info w-100"
                                                     wire:click='edit({{ $list->ID }})'>
                                                     <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                 </button>
                                             </div>
                                             <div class="col-6 w-100">
                                                 <button name="delete" class="btn btn-xs btn-danger w-100"
                                                     wire:confirm='Are you sure to delete?'
                                                     wire:click='delete({{ $list->ID }})'>
                                                     <i class="fa fa-trash" aria-hidden="true"></i>
                                                 </button>
                                             </div>
                                         @endif
                                     </div>
                                 </td>
                             </tr>
                         @endforeach
                         <tr>
                             <td><input type="text" name="descripton" wire:model='DESCRIPTION' class="w-100" />
                             </td>
                             <td><input type="number" name="quantity" wire:model='QUANTITY' class="w-100" /></td>
                             <td><input type="number" name="rate" wire:model='RATE' class="w-100" /></td>
                             <td>

                                 <button type="button" wire:click='store()' class="btn btn-xs btn-success w-100"
                                     wire:loading.attr='hidden'>
                                     <i class="fa fa-plus" aria-hidden="true"></i>
                                 </button>

                                 <div wire:loading.delay>
                                     <span class="spinner"></span>
                                 </div>
                             </td>
                         </tr>
                         <tr>
                             <td></td>
                             <td></td>
                             <td class="text-right text-danger font-weight-bold">{{ number_format($total, 2) }}</td>
                         </tr>

                     </tbody>
                 </table>
             </div>
         </div>
     </div>
