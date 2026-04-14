<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportspatient_balance_report') }}"> Patient Balance Report
                        </a></h5>
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
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_FROM" titleName="(SC) From"
                                            wire:model.live='DATE_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TO" titleName="(SC) To"
                                            wire:model.live='DATE_TO' :isDisabled="false" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        @if ($refreshComponent)
                                            <livewire:select-option name="PATIENT_ID1" titleName="Selected patient"
                                                :options="$patientList" :zero="true" :isDisabled=false
                                                wire:model.live='PATIENT_ID' />
                                        @else
                                            <livewire:select-option name="PATIENT_ID2" titleName="Selected patient"
                                                :options="$patientList" :zero="true" :isDisabled=false
                                                wire:model.live='PATIENT_ID' />
                                        @endif

                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">
                                            <label class="text-xs ">Location:</label>
                                            <select
                                                @if (Auth::user()->locked_location) style="opacity:
                                                0.5;pointer-events: none;" @endif
                                                name="location" wire:model.live='LOCATION_ID'
                                                class="form-control form-control-sm text-xs ">
                                                <option value="0"> All Location</option>
                                                @foreach ($locationList as $item)
                                                    <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                                <button class="btn btn-sm btn-primary" wire:click='generate()'
                                    wire:loading.attr='disabled'>Filter</button>
                                <button class="btn btn-sm btn-success" wire:click='export()'
                                    wire:loading.attr='disabled'>Export</button>
                            </div>
                            <div class="col-6">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky sticky-header">
                            <tr>
                                <th>Patient Name</th>
                                <th>Item Name</th>
                                <th class="bg-info">(SC)Date</th>
                                <th class="bg-info">(SC)Code</th>
                                <th class="bg-success">Amount</th>
                                <th class="bg-success">Paid</th>
                                <th class="bg-danger">Balance</th>


                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>{{ $list->CONTACT_NAME }}</td>
                                    <td>{{ $list->ITEM_NAME }}</td>
                                    <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <td><a target="_BLANK"
                                            href="{{ route('patientsservice_charges_edit', ['id' => $list->SERVICE_CHARGES_ID]) }}">{{ $list->CODE }}</a>
                                    </td>
                                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                    <td class="text-right">{{ number_format($list->PAID_AMOUNT, 2) }}</td>
                                    <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                                    @php
                                        $BALANCE = $BALANCE + $list->BALANCE ?? 0;
                                    @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                </td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right text-danger font-weight-bold text-sm">
                                    {{ number_format($BALANCE, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
