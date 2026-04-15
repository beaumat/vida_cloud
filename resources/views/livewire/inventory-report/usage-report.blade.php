<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsinventory_usage_report') }}">
                            Inventory Usage Report
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
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-12">

                                        <label class="text-xs ">Location:</label>
                                        <div>
                                            <select
                                                @if (Auth::user()->locked_location) style="opacity:
                                                0.5;pointer-events: none;" @endif
                                                name="location" wire:model.live='LOCATION_ID'
                                                class="form-control form-control-sm text-xs ">
                                                <option value="0"> All Location</option>
                                                @foreach ($locationList as $item)
                                                    <option value="{{ $item->ID }}">{{ $item->NAME }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-12">
                                        <label class="text-xs ">Report Type :</label>
                                        <div>
                                            <select wire:model.live='REPORT_TYPE'
                                                class="form-control form-control-sm text-xs ">
                                                <option value="MONTHLY"> Monthly</option>
                                                <option value="YEARLY"> Yearly</option>
                                            </select>
                                        </div>
                                    </div>

                                    @if ($REPORT_TYPE == 'MONTHLY')
                                        <div class="col-12 ">
                                            <label class="text-xs ">Year:</label>
                                            <div>
                                                <select wire:model='SelectYear' class="form-control form-control-sm ">

                                                    @foreach ($yearList as $list)
                                                        <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                        </option>
                                                    @endforeach


                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 ">
                                            <label class="text-xs ">Select Month:</label>
                                            <div>
                                                <select wire:model='SelectMonth' class="form-control form-control-sm ">

                                                    @foreach ($monthList as $list)
                                                        <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                        </option>
                                                    @endforeach


                                                </select>
                                            </div>
                                        @else
                                            <div class="col-12 ">
                                                <label class="text-xs ">Select Year:</label>
                                                <div>
                                                    <select wire:model='SelectYear'
                                                        class="form-control form-control-sm ">

                                                        @foreach ($yearList as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach


                                                    </select>
                                                </div>

                                            </div>
                                    @endif


                                    <div class="col-12">
                                        <div class="form-group mt-4">
                                            <div class="row">
                                                <div class="col-6 ">

                                                    <button class="btn btn-sm btn-primary" wire:click='generate()'
                                                        wire:loading.attr='disabled'>Generate</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
