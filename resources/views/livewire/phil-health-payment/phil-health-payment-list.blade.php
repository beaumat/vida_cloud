<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('patientsphic_pay') }}"> Patient: Philhealth Payments </a>
                    </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='locationid'
                                                    class="form-control form-control-sm">
                                                    <option value="0"> All Location</option>
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="mt-0">

                                                <label class="text-sm"><br /></label>
                                                <button class="btn btn-sm btn-secondary w-100"
                                                    wire:click='reloadList()'>
                                                    <i class="fa fa-refresh" aria-hidden="true"></i> Reload
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>
                                            <span type='button' wire:click="sorting('patient_payment.CODE')">No.</span>
                                            @if ($sortby == 'patient_payment.CODE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type='button'
                                                wire:click="sorting('patient_payment.DATE')">Date</span>
                                            @if ($sortby == 'patient_payment.DATE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('c.LAST_NAME')">Lastname</span>
                                            @if ($sortby == 'c.LAST_NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('c.FIRST_NAME')">Firstname</span>
                                            @if ($sortby == 'c.FIRST_NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th>
                                            <span type='button' wire:click="sorting('patient_payment.AMOUNT')">Gross
                                                Income</span>
                                            @if ($sortby == 'patient_payment.AMOUNT')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>WTax </th>
                                        <th>Less Amount </th>
                                        <th>
                                            <span type='button'
                                                wire:click="sorting('patient_payment.AMOUNT_APPLIED')">Applied</span>

                                            @if ($sortby == 'patient_payment.AMOUNT_APPLIED')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('BALANCE')">Balance</span>
                                            @if ($sortby == 'BALANCE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th>
                                            <span type='button' wire:click="sorting('pm.DESCRIPTION')"> Method
                                            </span>
                                            @if ($sortby == 'pm.DESCRIPTION')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('patient_payment.RECEIPT_REF_NO')">
                                                O.R No. </span>
                                            @if ($sortby == 'patient_payment.RECEIPT_REF_NO')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('patient_payment.RECEIPT_DATE')">
                                                O.R Date
                                            </span>
                                            @if ($sortby == 'patient_payment.RECEIPT_DATE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('l.NAME')">
                                                Location
                                            </span>
                                            @if ($sortby == 'l.NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif

                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('s.DESCRIPTION')">
                                                Status
                                            </span>
                                            @if ($sortby == 's.DESCRIPTION')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>


                                        @can('patient.payment.create')
                                            <th class="text-center bg-success">
                                                <a href="{{ route('patientsphic_pay_create') }}"
                                                    class="text-white btn btn-xs w-100">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> <a href="{{ route('patientsphic_pay_edit', ['id' => $list->ID]) }}"
                                                    class="text-primary"> {{ $list->CODE }} </a> </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ $list->LAST_NAME }} </td>
                                            <td> {{ $list->FIRST_NAME }} </td>
                                            <td class="text-right"> {{ number_format($list->AMOUNT, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->WTAX_AMOUNT, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->LESS_AMOUNT, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->AMOUNT_APPLIED, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->BALANCE, 2) }} </td>
                                            <td>{{ $list->PAYMENT_METHOD }}</td>
                                            <td>{{ $list->RECEIPT_REF_NO }}</td>
                                            <td>{{ $list->RECEIPT_DATE ? date('m/d/Y', strtotime($list->RECEIPT_DATE)) : '' }}
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-center"> {{ $list->STATUS }}</td>
                                            @can('patient.payment.create')
                                                <td class="text-center">
                                                    @can('patient.payment.print')
                                                        @if ($list->FILE_PATH)
                                                            <a href="{{ asset('storage/' . $list->FILE_PATH) }}"
                                                                target="_blank" class="btn btn-xs btn-warning">
                                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    <a href="{{ route('patientsphic_pay_edit', ['id' => $list->ID]) }}"
                                                        class="btn btn-xs btn-info">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    @can('patient.payment.delete')
                                                        <button type="button" wire:click='delete({{ $list->ID }})'
                                                            wire:confirm="Are you sure you want to delete this?"
                                                            class="btn btn-xs btn-danger">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    @endcan

                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">

                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
    @livewire('PatientPayment.PaymentRecordModal');
</div>
