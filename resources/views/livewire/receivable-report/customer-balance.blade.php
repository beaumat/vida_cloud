<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportscustomer_balance') }}"> Invoice Balance Reports </a>
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
                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <livewire:date-input name="DATE" titleName="As of Date "
                                                    wire:model.live='DATE' :isDisabled="false" />
                                            </div>
                                            <div class="col-12 col-md-12 text-center mt-1">
                                                <button class="btn btn-primary btn-xs" wire:click='generate()'>Summary
                                                </button>
                                                <button class="btn btn-primary btn-xs"
                                                    wire:click='generateDetails()'>Details
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-6">
                                                <livewire:date-input name="DATE_FROM" titleName="From Date "
                                                    wire:model.live='DATE_FROM' :isDisabled="false" />
                                            </div>
                                            <div class="col-6">
                                                <livewire:date-input name="DATE_TO" titleName="Date To"
                                                    wire:model.live='DATE_TO' :isDisabled="false" />
                                            </div>
                                            <div class="col-12 text-center">
                                                <button class="btn btn-info btn-xs mt-1"
                                                    wire:click='generateByRange()'>Summary
                                                </button>
                                                <button class="btn btn-info btn-xs mt-1"
                                                    wire:click='generateByRangeDetails()'>Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                </div>

                <div class=" col-12 col-sm-12 col-md-12  col-lg-8" style="max-height: 80vh; overflow-y: auto;">
                    @php
                        $BALANCE = 0;
                    @endphp
                    <table class="table table-sm  table-bordered table-hover ">
                        @if ($IS_SUMMARY)
                            <thead class="bg-sky h1">
                                <tr>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-left">Location</th>
                                </tr>
                            </thead>
                            <tbody class="h1">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->CONTACT_NAME }}</td>
                                        <td>{{ $list->TYPE }}</td>
                                        <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                                        <td>{{ $list->LOCATION_NAME }}</td>

                                        @php
                                            $BALANCE = $BALANCE + $list->BALANCE;
                                        @endphp
                                    </tr>
                                @endforeach
                                @if ($BALANCE > 0)
                                    <tr>
                                        <td class="text-danger">TOTAL</td>
                                        <td></td>
                                        <td class="text-danger text-right">{{ number_format($BALANCE, 2) }}</td>
                                        <td> </td>
                                    </tr>
                                @endif
                            </tbody>
                        @else
                            <thead class="bg-sky h1">
                                <tr>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Ref No.</th>
                                    <th>Terms</th>
                                    <th>Due Date</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-left">Location</th>
                                </tr>
                            </thead>
                            @php
                                $TEMP_NAME = '';
                            @endphp
                            <tbody class="h1">
                                @foreach ($dataList as $list)
                                    <tr>
                                        @if ($TEMP_NAME != $list->CONTACT_NAME)
                                            <td>{{ $list->CONTACT_NAME }}</td>
                                            <td>{{ $list->TYPE }}</td>
                                        @else
                                            <td></td>
                                            <td></td>
                                        @endif
                                        <td> {{ date('M/d/Y', strtotime($list->DATE)) }}</td>
                                        <td><a target="_blank"
                                                href="{{ route('customersinvoice_edit', ['id' => $list->INVOICE_ID]) }}">{{ $list->CODE }}</a>
                                        </td>
                                        <td>{{ $list->TERMS }}</td>
                                        <td> {{ date('M/d/Y', strtotime($list->DUE_DATE)) }}</td>
                                        <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                                        <td>{{ $list->LOCATION_NAME }}</td>
                                        @php
                                            $BALANCE = $BALANCE + $list->BALANCE;
                                            $TEMP_NAME = $list->CONTACT_NAME;
                                        @endphp
                                    </tr>
                                @endforeach
                                @if ($BALANCE > 0)
                                    <tr>
                                        <td colspan="5"></td>
                                        <td class="text-danger text-right">TOTAL</td>

                                        <td class="text-danger text-right">{{ number_format($BALANCE, 2) }}</td>
                                        <td> </td>
                                    </tr>
                                @endif
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
