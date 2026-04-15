 <div class="row">
     @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

     <div class="col-12">

         <div class="card">
             <div class="card-body">
                 <div class="row">

                     <div class="col-md-6 mb-2">
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="mt-0">
                                     <label class="text-sm">Search:</label>
                                     <input type="text" wire:model.live.debounce.150ms='search'
                                         class="w-100 form-control form-control-sm" placeholder="Search" />
                                 </div>

                             </div>

                         </div>
                     </div>
                     <div class="col-md-6 mb-2 text-right">

                     </div>
                 </div>
                 <table class="table table-sm table-bordered table-hover">
                     <thead class="text-xs bg-sky">
                         <tr>
                             <th>No.</th>
                             <th>Date</th>
                             <th>Admitted</th>
                             <th>Discharges</th>
                             <th>No. of Treatment </th>
                             <th>Total Charge</th>
                             <th>First Case </th>
                             <th>Collection</th>
                             <th>Location</th>
                             <th>Status</th>
                             <th class="text-center">Temp.</th>
                             <th class="text-center col-2 bg-success">
                                 Action
                             </th>
                         </tr>
                     </thead>
                     <tbody class="text-xs">
                         @foreach ($dataList as $list)
                             <tr>
                                 <td>
                                     <a target="_BLANK"
                                         @can('patient.philhealth.view') href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}" @else href="#" @endcan
                                         class="text-primary">
                                         {{ $list->CODE }}
                                     </a>
                                 </td>
                                 <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                 <td> {{ $list->DATE_ADMITTED ? date('m/d/Y', strtotime($list->DATE_ADMITTED)) : null }}
                                 </td>
                                 <td> {{ $list->DATE_DISCHARGED ? date('m/d/Y', strtotime($list->DATE_DISCHARGED)) : null }}
                                 </td>
                                 <td class="text-center"> {{ $list->HEMO_TOTAL }}</td>
                                 <td class="text-right">
                                     {{ $list->CHARGE_TOTAL > 0 ? number_format($list->CHARGE_TOTAL, 2) : 0 }}</td>
                                 <td class="text-right">
                                     {{ $list->P1_TOTAL > 0 ? number_format($list->P1_TOTAL, 2) : 0 }}</td>
                                 <td class="text-right">
                                     {{ $list->PAYMENT_AMOUNT > 0 ? number_format($list->PAYMENT_AMOUNT, 2) : 0 }}</td>
                                 <td> {{ $list->LOCATION_NAME }}</td>
                                 <td class=" @if ($list->STATUS == 'Paid') text-success @else text-danger @endif ">
                                     {{ $list->STATUS }}</td>
                                 <td class="text-center">
                                     @if ($list->IS_TEMP)
                                         <i class="fas fa-check" aria-hidden="true"></i>
                                     @else
                                         <i class="fa fa-ban" aria-hidden="true"></i>
                                     @endif
                                 </td>
                                 <td class="text-center">
                                     {{-- @can('patient.philhealth.print')
                                         <a target="_BLANK" title="Soa"
                                             href="{{ route('patientsphic_print', ['id' => $list->ID]) }}"
                                             class="btn-sm text-primary"> <i class="fa fa-file-pdf-o"
                                                 aria-hidden="true"></i></a>
                                         <a target="_BLANK" title="Philheath Form"
                                             href="{{ route('patientsphic_print_form', ['id' => $list->ID]) }}"
                                             class="btn-sm text-danger"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                         </a>
                                     @endcan --}}
                                     @can('patient.philhealth.view')
                                         <a href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                             class="btn-sm text-info">
                                             <i class="fas fa-edit" aria-hidden="true"></i>
                                         </a>
                                     @endcan
                                     @can('patient.philhealth.delete')
                                         <a href="#" wire:click='delete({{ $list->ID }})'
                                             wire:confirm="Are you sure you want to delete this?"
                                             class="btn-sm text-danger">
                                             <i class="fas fa-times" aria-hidden="true"></i>
                                         </a>
                                     @endcan
                                 </td>
                             </tr>
                         @endforeach
                     </tbody>
                 </table>
                 <div class="form-group mt-2">
                     {{-- <button type="button" class="btn btn-xs btn-warning" wire:click='AddTemp'
                         wire:confirm="Are you sure you want to add temporary files?">
                         <i class="fas fa-plus"></i> Add
                     </button> --}}

                     @can('patient.philhealth.print')
                         <a type="button" target="_BLANK" title="Print Soa"
                             href="{{ route('patientsprintout_soa_temp', ['id' => $CONTACT_ID]) }}"
                             class=" btn  btn-sm btn-primary">
                             <i class="fa fa-print" aria-hidden="true"></i> SOA (Pre-sign)
                         </a>
                         <a type="button" target="_BLANK" title="Print Soa"
                             href="{{ route('patientsprintout_summary_temp', ['id' => $CONTACT_ID]) }}"
                             class=" btn  btn-sm btn-primary">
                             <i class="fa fa-print" aria-hidden="true"></i> Summary (Pre-sign)
                         </a>
                         <a type="button" target="_BLANK" title="Print Philheath CSF (pre-sign)"
                             href="{{ route('patientsprintout_csf_temp', ['id' => $CONTACT_ID]) }}"
                             class="btn btn-info btn-sm"> <i class="fa fa-print" aria-hidden="true"></i>
                             CSF (Pre-sign)
                         </a>
                         <a type="button" target="_BLANK" title="Print Philheath CF4 (pre-sign)"
                             href="{{ route('patientsprintout_cf4_temp_out', ['id' => $CONTACT_ID]) }}"
                             class="btn btn-dark btn-sm"> <i class="fa fa-print" aria-hidden="true"></i>
                             CF4 (Pre-sign)
                         </a>
                     @endcan
                 </div>
             </div>
         </div>
     </div>
     <div class="col-6 col-md-6">
         {{ $dataList->links() }}
     </div>
 </div>
