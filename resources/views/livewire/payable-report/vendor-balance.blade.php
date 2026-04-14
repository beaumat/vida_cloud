<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsvendor_balance') }}"> Billing Balance Reports </a>
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
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE" titleName="As of Date "
                                            wire:model.live='DATE' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        {{-- <livewire:date-input name="DATE_TO" titleName="Date To"
                                            wire:model.live='DATE_TO' :isDisabled="false" /> --}}
                                    </div>
                                    <div class='col-md-12 mt-1'>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-xs w-25"
                                                wire:click='generate()'>Genrate</button>

                                        </div>
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

                <div class=" col-12 col-sm-12 col-md-12  col-lg-8" style="max-height: 80vh; overflow-y: auto;">
                    @php
                        $BALANCE = 0;
                    @endphp
                    <table class="table table-sm  table-bordered table-hover ">
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
                    </table>



                </div>
            </div>
        </div>
    </section>
</div>
