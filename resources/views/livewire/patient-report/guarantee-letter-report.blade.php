<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportsguarantee_letter') }}"> Guarentee Letter Report
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
                                        <livewire:checkbox-input name="INCLUDE_ZERO" wire:model='includeZero'
                                            titleName="Inlclude Zero balance" :isDisabled=false />
                                    </div>
                                    <div class="col-md-5">
                                     
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
                                    wire:loading.attr='disabled'>Generate</button>
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
                                <th class="">GL Type</th>
                                <th class="">GL Date</th>
                                <th class="">GL NO.</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Usage</th>
                                <th class="bg-danger text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @php
                                $BALANCE = 0;
                                $TEMP_NAME = '';
                            @endphp
                            @foreach ($dataList as $list)
                                <tr>

                                    <td>
                                        @if ($TEMP_NAME != $list->PATIENT_NAME)
                                            {{ $list->PATIENT_NAME }}
                                        @endif
                                        @php
                                            $TEMP_NAME = $list->PATIENT_NAME;
                                            $BALANCE += $list->BALANCE;
                                        @endphp
                                    </td>
                                    <td>{{ $list->METHOD }}</td>
                                    <td>{{ date('M/d/Y', strtotime($list->TRANS_DATE)) }}</td>
                                    <td>{{ $list->TRANS_CODE }}</td>
                                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                    <td class="text-right">{{ number_format($list->AMOUNT_APPLIED, 2) }}</td>
                                    <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right text-danger font-weight-bold">{{ number_format($BALANCE, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
