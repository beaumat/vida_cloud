<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientsphic_paid') }}"> Philhealth Paid (2026) </a></h5>
                </div>
                <div class="col-sm-6 text-right">

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
                                        <div class="col-md-7">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
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
                                        <div class="col-2">
                                            <div class="mt-4">
                                                <livewire:checkbox-input name="SHOW_PAID" titleName="Show Paid"
                                                    wire:model.live='showPaid' :isDisabled="false" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover mt-1">
                                <thead class='bg-sky'>
                                    <th>SOA No.</th>
                                    <th>LHIO Date</th>
                                    <th>LHIO No.</th>
                                    <th class="text-center">No. Treatment</th>
                                    <th>Admitted</th>
                                    <th>Discharged</th>
                                    <th>Patient</th>
                                    <th>Nephro</th>
                                    <th>Amount</th>
                                    <th>Location</th>
                                    @if (!$showPaid)
                                        <th>Action</th>
                                    @else
                                        <th>Summary</th>
                                        <th>Payment</th>
                                        <th>Doc Bills</th>
                                    @endif
                                </thead>
                                <tbody>
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->SOA_NO }}</td>
                                            <td>{{ date('m/d/Y', strtotime($list->AR_DATE)) }}</td>
                                            <td>
                                                <a href="#"
                                                    wire:click='openARform({{ $list->PHILHEALTH_ID }})'>{{ $list->AR_NO }}</a>

                                            </td>

                                            <td class="text-center">{{ $list->TOTAL_TREATMENT }}</td>
                                            <td>{{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                            <td>{{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                            <td>{{ $list->CUSTOMER_NAME }}</td>
                                            <td>{{ $list->DOCTOR_NAME }}</td>
                                            <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                            </td>
                                            <td>{{ $list->LOCATION_NAME }}</td>
                                            @if (!$showPaid)
                                                <td>
                                                    <button class="btn btn-xs btn-success w-100"
                                                        wire:click='makePaidNew({{ $list->PHILHEALTH_ID }})'>Paid</button>
                                                </td>
                                            @else
                                                <td>
                                                    <a target="_blank" class="btn btn-xs btn-primary w-100"
                                                        href="{{ route('patientspayment_period_details', ['id' => $list->PAYMENT_PERIOD_ID]) }}">View</a>

                                                </td>
                                                <td>
                                                    <a target="_blank" class="btn btn-xs btn-success w-100"
                                                        href="{{ route('customerspayment_edit', ['id' => $list->PAYMENT_ID]) }}">View</a>
                                                </td>
                                                <td>
                                                    <a target="_blank" class="btn btn-xs btn-warning w-100"
                                                        href="{{ route('vendorsbills_edit', ['id' => $list->BILL_ID]) }}">View</a>
                                                </td>
                                            @endif
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
        </div>
    </section>


    @livewire('Invoice.QuickPhilhealthPaidPanel')
    @livewire('PhilHealth.ArForm')
</div>
