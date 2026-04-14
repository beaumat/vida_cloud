    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
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
                    </div>
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky">
                            <tr>
                                <th class="col-1">Ref No.</th>
                                <th class="col-1">Date</th>
                                <th class="col-1">Amount</th>
                                <th class="col-1">Balance</th>
                                <th class="col-1 text-center">Status</th>
                                <th class="col-1 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>
                                        <a target="_BLANK"
                                            href="{{ route('patientsservice_charges_edit', ['id' => $list->ID]) }}"
                                            class="text-primary">
                                            {{ $list->CODE }}
                                        </a>
                                    </td>
                                    <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <td class="text-right"> {{ number_format($list->AMOUNT, 2) }}</td>
                                    <td class="text-right"> {{ number_format($list->BALANCE_DUE, 2) }}</td>

                                    <td class="text-center"> {{ $list->STATUS }}</td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-warning w-100"
                                            wire:click='TransferRecordTo({{ $list->ID }})'><i class="fa fa-exchange"
                                                aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4 col-md-4">
            {{ $dataList->links() }}
        </div>
        <div class="col-4 col-md-4 text-sm">
    

              <livewire:select-option name="PHILHEALTH_INCHARGE_ID" :options="$contactList"
                                                isDisabled="{{ false }}" :zero="false" titleName="Prepaired By"
                                                wire:model.live='PHILHEALTH_INCHARGE_ID' vertical="{{ true }}" />
        </div>
        <div class="col-4 col-md-4 text-right">
            Year : <select wire:model.live='YEAR' class="text-md">
                @foreach ($yearList as $list)
                    <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                @endforeach
            </select>
            <a href="{{ route('maintenancecontactprint_availment', ['id' => $CONTACT_ID, 'locationid' => $LOCATION_ID, 'year' => $YEAR]) }}"
                target="_BLANK" class="btn btn-sm btn-success">
                <i class="fa fa-print" aria-hidden="true"></i>
                Print Availment
            </a>
            <button type="button" class="btn btn-danger btn-sm" wire:click='modifyPhilhealth()'><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> PHIC 156 Adjustment</button>

        </div>

        @livewire('Patient.PhilhealthModify', ['PATIENT_ID' => $CONTACT_ID, 'LOCATION_ID' => $LOCATION_ID])
    </div>
