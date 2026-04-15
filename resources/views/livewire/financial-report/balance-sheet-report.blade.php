<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsfinancialbalance_sheet_report') }}"> Balance Sheet Statement </a>
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
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0 text-xs" wire:loading.class='loading-form'>
                            <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link @if ($tab == 'date') active @endif"
                                        id="custom-tabs-four-date-tab" wire:click="SelectTab('date')" data-toggle="pill"
                                        href="#custom-tabs-four-date" role="tab"
                                        aria-controls="custom-tabs-four-date" aria-selected="true">Date Range</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link @if ($tab == 'monthly') active @endif"
                                        id="custom-tabs-four-monthly-tab" wire:click="SelectTab('monthly')"
                                        data-toggle="pill" href="#custom-tabs-four-monthly" role="tab"
                                        aria-controls="custom-tabs-four-monthly" aria-selected="true">Monthly</a>
                                </li>
                                <li wire:loading.delay>
                                    <span class="spinner"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade @if ($tab == 'date') show active @endif "
                                    id="custom-tabs-four-item" role="tabpanel">
                                    @if ($tab == 'date')
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="mt-0">
                                                                    <label class="text-xs ">Location:</label>
                                                                    <select
                                                                        @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                                        name="location" wire:model.live='LOCATION_ID'
                                                                        class="form-control form-control-sm text-xs ">
                                                                        <option value="0"> All Location</option>
                                                                        @foreach ($locationList as $item)
                                                                            <option value="{{ $item->ID }}">
                                                                                {{ $item->NAME }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:date-input name="DATE_FROM"
                                                                    titleName="Start Date " wire:model.live='DATE_FROM'
                                                                    :isDisabled="false" />
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:date-input name="DATE_TO" titleName="End Date"
                                                                    wire:model.live='DATE_TO' :isDisabled="false" />
                                                            </div>
                                                            <div class='col-md-12'>
                                                                <div class="form-group mt-1">
                                                                    <button class="btn btn-danger btn-xs"
                                                                        wire:click='generate()'>Generate</button>
                                                                    <button class="btn btn-success btn-xs "
                                                                        wire:click='exportDaily()'>Export</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class=" col-12 col-sm-12 col-md-12  col-lg-6"
                                                style="max-height: 80vh; overflow-y: auto;">
                                                @livewire('BalanceSheet.BalanceSheetDateRange')
                                            </div>

                                        </div>
                                    @endif
                                </div>

                                <div class="tab-pane fade @if ($tab == 'monthly') show active @endif "
                                    id="custom-tabs-four-account" role="tabpanel">
                                    @if ($tab == 'monthly')
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="mt-0">
                                                                    <label class="text-xs ">Location:</label>
                                                                    <select
                                                                        @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                                        name="location" wire:model.live='LOCATION_ID'
                                                                        class="form-control form-control-sm text-xs ">
                                                                        <option value="0"> All Location</option>
                                                                        @foreach ($locationList as $item)
                                                                            <option value="{{ $item->ID }}">
                                                                                {{ $item->NAME }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <livewire:number-input name="YEAR" titleName="Year"
                                                                    wire:model.live='YEAR' :isDisabled="false" />
                                                            </div>

                                                            <div class='col-md-12 mt-1'>
                                                                <div class="form-group">
                                                                    <button class="btn btn-danger btn-xs"
                                                                        wire:click='generateMonthly()'>Generate</button>
                                                                    <button class="btn btn-success btn-xs "
                                                                        wire:click='exportMonthly()'>Export</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-12 col-sm-12 col-md-12  col-lg-12"
                                                style="max-height: 80vh; overflow-y: auto;">
                                                @livewire('BalanceSheet.BalanceSheetMonthly')
                                            </div>
                                        </div>
                                    @endif
                                </div>


                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


</div>
