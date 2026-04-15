<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsphilhealth_annex_report') }}">Philhealth Annex B (IBNR)
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
                                        <label class="text-xs">Year:</label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" class="form-control form-control-sm text-xs"
                                            wire:model='YEAR' />
                                    </div>
                                    <div class="col-1 text-right">
                                        <label class="text-xs">Month:</label>
                                    </div>
                                    <div class="col-2">
                                        <select class="form-control form-control-sm text-xs" name="MONTH"
                                            wire:model.live='MONTH'>
                                            @foreach ($monthList as $item)
                                                <option value="{{ $item['ID'] }}">
                                                    {{ $item['NAME'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <button wire:click='generate()' class="btn btn-xs btn-primary w-100"
                                            wire:loading.attr="disabled">
                                            Generate
                                        </button>
                                    </div>
                                    <div class='col-2'>
                                        <a href="{{ route('reportsphilhealth_annex_one_print', ['locationid' => $LOCATION_ID, 'year' => $YEAR, 'month' => $MONTH]) }}"
                                            target="_blank" class="btn btn-xs btn-warning w-100"> Print </a>

                                    </div>
                                    <div class='col-2'>
                                        <button wire:click='export()' class="btn btn-xs btn-success w-100"
                                            wire:loading.attr="disabled">Export</button>
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
                                    <td class=" bg-dark">Item No.</td>
                                    <td class=" bg-dark">Claims Ref#
                                        <button class="btn btn-xs btn-success text-xs w-100" wire:click='autoSet()'
                                            wire:confirm='Are you sure to Auto Set?' wire:loading.attr="disabled">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                    <td class=" bg-primary">Patient Surname</td>
                                    <td class=" bg-primary">Patient Firstname</td>
                                    <td class=" bg-primary">Patient Middlename</td>
                                    <td class="bg-info">Member Surname</td>
                                    <td class=" bg-info">Member Firstname</td>
                                    <td class=" bg-info">Member Middlename</td>
                                    <td class="col-1 bg-dark">Member's PIN</td>
                                    <td class="col-1 bg-dark">Member's Category</td>
                                    <td class="col-1 bg-dark">Date of Admission</td>
                                    <td class="col-1 bg-dark">Date of Discharged</td>
                                    <td class="col-1 bg-dark">Case Rate/ Claim Amount</td>
                                    <td class="col-1 bg-dark">ICD 10/RVS code</td>
                                    <td class="col-1 bg-dark">*Claim Status</td>
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
                                        <td>{{ $list->CLAIM_NO }}</td>
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
                                        <td>{{ $list->CLASS }}</td>
                                        <td>{{ date('M/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                        <td>{{ date('M/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                        <td class="text-right">{{ number_format($list->P1_TOTAL, 2) }}</td>
                                        @php
                                            $TOTAL += $list->P1_TOTAL;
                                        @endphp
                                        <td class="text-center">90935</td>
                                        <td class="text-center">FOR FILE</td>
                                    </tr>
                                @endforeach

                                <tr class="bg-secondary">
                                    <td colspan="12" class="text-right">Total</td>
                                    <td class="text-right">{{ number_format($TOTAL, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>



                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
        </div>
    </section>
</div>
