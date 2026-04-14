     <div class="row">
         <div class="col-12">
             <div class="card">
                 <div class="card-body">
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
                         @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                         <thead class="text-xs bg-sky">
                             <tr>
                                 <th class="col-1">No.</th>
                                 <th>Date</th>
                                 <th class="text-center ">W</th>
                                 <th class="text-center ">B.P</th>
                                 <th class="text-center ">H.R</th>
                                 <th class="text-center ">O2(S)</th>
                                 <th class="text-center ">TMP</th>
                                 <th class="text-center ">Start</th>
                                 <th class="text-center ">End</th>
                                 <th class="col-1">Location</th>
                                 <th>Status</th>
                                 <th class="text-center col-1">Sevice Charge</th>
                                 <th class="text-center col-1">Action</th>
                             </tr>
                         </thead>
                         <tbody class="text-xs">
                             @foreach ($dataList as $list)
                                 <tr>
                                     <td>
                                         <a target="_BLANK" href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}"
                                             class="text-primary">
                                             {{ $list->CODE }}
                                         </a>
                                     </td>
                                     <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                     <td class="text-center">
                                         {{ $list->PRE_WEIGHT }} | {{ $list->POST_WEIGHT }}
                                     </td>
                                     <td class="text-center">
                                         {{ $list->PRE_BLOOD_PRESSURE }}/{{ $list->PRE_BLOOD_PRESSURE2 }} |
                                         {{ $list->POST_BLOOD_PRESSURE }}/{{ $list->POST_BLOOD_PRESSURE2 }}
                                     </td>
                                     <td class="text-center"> {{ $list->PRE_HEART_RATE }} |
                                         {{ $list->POST_HEART_RATE }}</td>
                                     <td class="text-center"> {{ $list->PRE_O2_SATURATION }} |
                                         {{ $list->POST_O2_SATURATION }}</td>
                                     <td class="text-center"> {{ $list->PRE_TEMPERATURE }} |
                                         {{ $list->POST_TEMPERATURE }}</td>
                                     <td class="text-center">
                                         @if ($list->TIME_START)
                                             {{ \Carbon\Carbon::parse($list->TIME_START)->format('h:i A') }}
                                         @endif
                                     </td>
                                     <td class="text-center">
                                         @if ($list->TIME_END)
                                             {{ \Carbon\Carbon::parse($list->TIME_END)->format('h:i A') }}
                                         @endif
                                     </td>
                                     <td> {{ $list->LOCATION_NAME }} </td>
                                     <td> {{ $list->STATUS }} </td>
                                     <td><button type="button" class="btn btn-xs btn-success w-100"
                                             wire:click="CreateServiceCharge('{{ $list->DATE }}')"
                                             wire:confirm='Are you sure?'><i class="fa fa-plus"
                                                 aria-hidden="true"></i></button></td>
                                     <td>
                                         <button type="button" class="btn btn-xs btn-warning w-100"
                                             wire:click='TransferRecordTo({{ $list->ID }})'><i
                                                 class="fa fa-exchange" aria-hidden="true"></i></button>
                                     </td>
                                 </tr>
                             @endforeach
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
         <div class="col-6 col-md-6">
             {{ $dataList->links() }}
         </div>
     </div>
