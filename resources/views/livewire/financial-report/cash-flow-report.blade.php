<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsfinancialcash_flow_report') }}"> Cash Flow Statement </a>
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
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-3 text-right">
                                        <label>Year:</label>
                                    </div>
                                    <div class="col-3">
                                        <input type="number" class="form-control form-control-sm" wire:model='YEAR' />
                                    </div>
                                    <div class="col-3">
                                        <button wire:click='reload()'
                                            class="btn btn-xs btn-warning w-100 ">Reload</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3 text-right">
                                        <label>Month:</label>
                                    </div>
                                    <div class="col-3">
                                        <select class="form-control form-control-sm" name="MONTH"
                                            wire:model.live='MONTH'>
                                            @foreach ($monthList as $item)
                                                <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <button wire:click='generate()'
                                            class="btn btn-xs btn-primary w-100">Generate</button>
                                    </div>
                                    <div class='col-3'>
                                        <button wire:click='ExportGenerate()'
                                            class="btn btn-xs btn-success w-100">Export</button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">

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
                <div class="col-12 col-sm-12 col-md-12  col-lg-6">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                    <table class="table table-bordered table-hover ">
                        <thead class="bg-sky">
                            <tr>
                                <th class="col-8">Statement of Cash Flows</th>
                                <th class="col-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="h1">
                            @foreach ($dataList as $list)
                                <tr>
                                    @if ($list['name'] == '')
                                        <td><br /></td>
                                        <td><br /></td>
                                    @else
                                        @if ($list['underline'])
                                            <td class="text-xs {{ $list['class'] }}">
                                                <div class="top-line2">
                                                    {{ $list['name'] }}
                                                </div>
                                            </td>
                                            <td class="text-xs text-right">
                                                <div class="top-line2">
                                                    {{ $list['amount'] }}
                                                </div>
                                            </td>
                                        @else
                                            <td class="text-xs {{ $list['class'] }}">
                                                {{ $list['name'] }}
                                            </td>
                                            <td class="text-xs text-right">
                                                {{ $list['amount'] }}
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
