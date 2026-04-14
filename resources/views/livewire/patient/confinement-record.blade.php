     <div class="row">
         <div class="col-12">
             <div class="card">
                 <div class="card-body">
                     @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                     <div class="row">
                         <div class="col-md-12 mb-2">
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="mt-0">
                                         <label class="text-sm"> <a href="#" wire:click='refreshList()'>Search:</a>
                                         </label>
                                         <input type="text" wire:model.live.debounce.150ms='search'
                                             class="w-100 form-control form-control-sm" placeholder="Search" />
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <table class="table table-sm table-bordered table-hover">
                         <thead class="text-xs bg-sky">
                             <tr>
                                 <th class="col-2">Date Start</th>
                                 <th class="col-2">Date End</th>
                                 <th class="col-6">Notes</th>
                                 <th class="col-2 text-center">Action</th>
                             </tr>
                         </thead>
                         <tbody class="text-xs">
                             @foreach ($dataList as $list)
                                 <tr>
                                     <td>
                                         @if ($E_ID == $list->ID)
                                             <input type="date" class="form-control form-control-sm"
                                                 name="date_start" wire:model='E_DATE_START' />
                                         @else
                                             {{ date('m/d/Y', strtotime($list->DATE_START)) }}
                                         @endif
                                     </td>
                                     <td>
                                         @if ($E_ID == $list->ID)
                                             <input type="date" class="form-control form-control-sm"
                                                 name="date_start" wire:model='E_DATE_END' />
                                         @else
                                             {{ $list->DATE_END ? date('m/d/Y', strtotime($list->DATE_END)) : '' }}
                                         @endif
                                     </td>
                                     <td>
                                         @if ($E_ID == $list->ID)
                                             <input type="text" class="form-control form-control-sm" name="notes"
                                                 wire:model='E_DESCRIPTION' />
                                         @else
                                             {{ $list->DESCRIPTION }}
                                         @endif
                                     </td>
                                     <td>
                                         @if ($E_ID == $list->ID)
                                             <div class="row">
                                                 <div class="col-6">
                                                     <button type="button" class="btn btn-xs btn-primary w-100"
                                                         wire:click='update()'>Update</button>
                                                 </div>
                                                 <div class="col-6">
                                                     <button type="button" class="btn btn-xs btn-warning w-100"
                                                         wire:click='cancel()'>Cancel</button>
                                                 </div>
                                             </div>
                                         @else
                                             <div class="row">
                                                 <div class="col-6">
                                                     <button type="button" class="btn btn-xs btn-info w-100"
                                                         wire:click='edit({{ $list->ID }})'>Edit</button>
                                                 </div>
                                                 <div class="col-6">
                                                     <button type="button" class="btn btn-xs btn-danger w-100"
                                                         wire:click='delete({{ $list->ID }})'>Delete</button>
                                                 </div>
                                             </div>
                                         @endif
                                     </td>
                                 </tr>
                             @endforeach
                             <tr>
                                 <td><input type="date" class="form-control form-control-sm" name="date_start"
                                         wire:model='DATE_START' /></td>
                                 <td><input type="date" class="form-control form-control-sm" name="date_end"
                                         wire:model='DATE_END' /></td>
                                 <td><input type="text" class="form-control form-control-sm" name="notes"
                                         wire:model='DESCRIPTION' /></td>

                                 <td>
                                     <button type="button" class="btn btn-xs btn-success w-100" wire:click='save'>
                                         <i class="fa fa-plus" aria-hidden="true"></i>

                                     </button>
                                 </td>
                             </tr>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
         <div class="col-6 col-md-6">
             {{ $dataList->links() }}
         </div>
     </div>
