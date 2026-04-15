<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportspatient_inventory_report') }}"> Patient Inventory Report </a>
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
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_FROM" titleName="From" wire:model='DATE_FROM'
                                            :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TO" titleName="To" wire:model='DATE_TO'
                                            :isDisabled="false" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <livewire:select-option name="ITEM_ID" titleName="Item Select" :options="$itemList"
                                    :zero="true" wire:model='ITEM_ID' :vertical="false"
                                    isDisabled="{{ false }}" :withLabel="true" />
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">

                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">
                                            <label class="text-xs ">Location:</label>
                                            <select
                                                @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
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
                                <button class="btn btn-xs btn-danger w-25" wire:click='generate()'
                                    wire:loading.attr='disabled'>Generate</button>
                                <button class="btn btn-xs btn-success w-25" wire:click='generateExcel()'
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
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-right">Quantity</th>
                                <th>Unit</th>
                                <th>Post</th>
                                <th>Walk-in</th>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Location </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @php
                                $totalQuantity = 0;

                            @endphp
                            @foreach ($dataList as $list)
                                @php
                                    $totalQuantity += $list->QUANTITY ?? 0;
                                @endphp
                                <tr>
                                    <td>{{ $list->PATIENT_NAME }}</td>
                                    <th>{{ $list->ITEM_CODE }}</th>
                                    <td>{{ $list->ITEM_NAME }}</td>
                                    <td class="text-right">{{ number_format($list->QUANTITY, 1) }}</td>
                                    <td>{{ $list->UNIT }}</td>
                                    <td class="text-center">
                                        @if ($list->POST)
                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($list->WALKIN)
                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <th><a target="_blank"
                                            href="{{ route('patientshemo_edit', ['id' => $list->HEMO_ID]) }}">{{ $list->REFERENCE }}</a>
                                    </th>
                                    <td>{{ $list->LOCATION_NAME }}</td>
                                </tr>
                            @endforeach
                            <tr>
                            <tr>
                                <td></td>
                                <th></th>
                                <td class="text-right text-danger">TOTAL QTY:</td>
                                <td class="text-right font-weight-bold text-danger">
                                    {{ number_format($totalQuantity, 1) }}</td>
                                <td></td>
                                <td class="text-center"> </td>
                                <td class="text-center"> </td>
                                <td></td>
                                <th></a>
                                </th>
                                <td></td>
                            </tr>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
