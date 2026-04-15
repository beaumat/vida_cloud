<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsphilhealth_annex_two_report') }}">Philhealth Annex C (IBNR)
                        </a>
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
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-1 text-right">
                                        <label class="text-xs ">Show All &nbsp;&nbsp;</label>
                                    </div>
                                    <div class="col-1">
                                        <input type="checkbox" wire:model='showAll'
                                            class="form-check-input form-check-inline">
                                    </div>

                                    <div class="col-1 text-right">
                                        <label class="text-xs">Year:</label>
                                    </div>
                                    <div class="col-2">
                                        <select class="form-control form-control-sm text-xs" name="YEAR"
                                            wire:model.live='YEAR'>
                                            @foreach ($yearList as $item)
                                                <option value="{{ $item['ID'] }}">
                                                    {{ $item['NAME'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 text-left">
                                        <button wire:click='generate()' wire:loading.attr="disabled" type="button"
                                            class="btn btn-xs btn-primary w-100">
                                            Generate
                                        </button>
                                    </div>
                                    <div class="col-2 text-left">
                                        <a href="{{ route('reportsphilhealth_annex_two_print', ['locationid' => $LOCATION_ID, 'year' => $YEAR,'show' => $showAll ? 1: 0]) }}"
                                            target="_blank" class="btn btn-xs btn-warning w-100"> Print </a>
                                    </div>
                                    <div class="col-2 text-right">
                                            <button wire:click='export()' wire:loading.attr='disabled' type="button" class=" btn btn-xs btn-success w-100"> Export</button>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8 text-right">
                                        <label class="text-xs ">Location:</label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">
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
            </div>
            <div class="row">
                {{-- header --}}
                <div class="col-md-12">
                    <div style="max-height: 75vh; overflow-y: auto;">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs  sticky-header">

                                <tr>
                                    <th class=" bg-dark">Item No.</th>
                                    <th class="bg-dark">Yr. Start <br /> From.</th>
                                    <th class="bg-dark">Claims Series Reference</th>
                                    <th class=" bg-primary">Patient Surname</th>
                                    <th class=" bg-primary">Patient Firstname</th>
                                    <th class=" bg-primary">Patient Middlename</th>
                                    <th class="bg-info">Member Surname</th>
                                    <th class=" bg-info">Member Patient Firstname</th>
                                    <th class=" bg-info">Member Middlename</th>
                                    <th class=" bg-dark">Member's PIN</th>
                                    <th class=" bg-dark">Date of Admission</th>
                                    <th class=" bg-dark">Date of Discharged</th>
                                    <th class=" bg-dark">Date of Filed</th>
                                    <th class=" bg-dark">Date of Refiled</th>
                                    <th class=" bg-dark">ICD 10/RVS code</th>
                                    <th class=" bg-dark">Case Rate/ Claim Amt.</th>
                                    <th class="bg-dark">*Claim Status</th>
                                </tr>


                            </thead>
                            <div wire:loading.delay>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </div>

                            <tbody class="text-xs" wire:loading.attr='hidden'>
                                @php
                                    $r = 0;
                                    $TOTAL = 0;
                                @endphp
                                @foreach ($dataList as $list)
                                    @php
                                        $r++;
                                    @endphp
                                    <tr>
                                        <td>{{ $r }}</td>
                                        <td>{{ $list->YEAR }}</td>
                                        <td>{{ $list->AR_NO }}</td>
                                        <td>{{ $list->LAST_NAME }}</td>
                                        <td>{{ $list->FIRST_NAME }}</td>
                                        <td>{{ $list->MIDDLE_NAME }}</td>
                                        @if ($list->IS_PATIENT)
                                            <td>{{ $list->LAST_NAME }}</td>
                                            <td>{{ $list->FIRST_NAME }}</td>
                                            <td>{{ $list->MIDDLE_NAME }}</td>
                                        @else
                                            <td>{{ $list->MEMBER_LAST_NAME }}</td>
                                            <td>{{ $list->MEMBER_FIRST_NAME }}</td>
                                            <td>{{ $list->MEMBER_MIDDLE_NAME }}</td>
                                        @endif

                                        <td>{{ $list->PIN_NO }}</td>
                                        <td>{{ date('M/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                        <td>{{ date('M/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                        <td>{{ date('M/d/Y', strtotime($list->AR_DATE)) }}</td>
                                        <td>N/A</td>
                                        <td>90935</td>
                                        <td class="text-right">{{ number_format($list->P1_TOTAL, 2) }}</td>
                                        @php
                                            $TOTAL += $list->P1_TOTAL;
                                        @endphp
                                        <td>
                                            @if ($list->PAYMENT_AMOUNT > 0)
                                                <strong class="text-success">Paid</strong>
                                            @else
                                                <strong class="text-danger">In-Progress</strong>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-secondary">
                                    <td colspan="15" class="text-right">Total</td>
                                    <td class="text-right">{{ number_format($TOTAL, 2) }}</td>
                                    <td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
        </div>
    </section>
</div>
