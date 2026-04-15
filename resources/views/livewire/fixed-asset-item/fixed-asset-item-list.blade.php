<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('maintenanceinventoryfixed_asset_item') }}">
                            Fixed Asset Items
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
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" wire:model.live.debounce.150ms='search'
                                        class="form-control form-control-sm text-xs mb-1 bg-light"
                                        placeholder="Search" />
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="mt-0">

                                        <select
                                            @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                            name="location" wire:model.live='LOCATION_ID'
                                            class="form-control form-control-sm">
                                            <option value="0"> All Location</option>
                                            @foreach ($locationList as $item)
                                                <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover custom-table">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>ASSET No.</th>
                                        <th>CODE</th>
                                        <th>DESCRIPTION</th>
                                        <th>UNIT</th>
                                        <th>P.O No.</th>
                                        <th>SERIAL No.</th>
                                        <th class="text-left">P.O DATE</th>
                                        <th class="text-center">YEAR MODEL</th>
                                        <th class="text-center">QTY</th>
                                        <th>ACQ. COST</th>
                                        <th class="text-center">USEFUL LIFE</th>
                                        <th>LOCATION</th>
                                        <th>YEARLY</th>
                                        <th>MONTHLY</th>
                                        <th class="bg-cyan">DEP.UNTIL</th>
                                        <th class="bg-purple">DEP.COUNT</th>
                                        <th class="bg-orange">DEP.REMAIN</th>
                                        <th class="bg-info">M.B REMAIN</th>
                                        <th class="text-center">INACTIVE</th>
                                        <th class="text-center col-1 bg-success">
                                            @can('items.create')
                                                @livewire('FixedAssetItem.ItemRegisterModal', ['LOCATION_ID' => $LOCATION_ID])
                                            @endcan
                                        </th>


                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($items as $list)
                                        <tr>
                                            <td>{{ sprintf('%05d', $list->ID) }}</td>
                                            <td>{{ $list->ITEM_CODE }}</td>
                                            <td>{{ $list->ITEM_NAME }}</td>
                                            <td>{{ $list->UNIT_NAME }}</td>
                                            <td>{{ $list->PO_NUMBER }}</td>
                                            <td>{{ $list->SERIAL_NO }}</td>
                                            <td> {{ date('m/d/Y', strtotime($list->PO_DATE)) }}</td>

                                            <td class="text-center">{{ $list->YEAR_MODEL }}</td>
                                            <td class="text-center">{{ $list->QUANTITY }}</td>
                                            <td class="text-right">{{ number_format($list->AQ_COST, 2) }}</td>
                                            <td class="text-center">{{ $list->USEFUL_LIFE }}</td>
                                            <td>{{ $list->LOCATION_NAME }}</td>
                                            <td class="text-right">{{ number_format($list->PER_YEAR, 2) }}</td>
                                            <td class="text-right">{{ number_format($list->PER_MONTH, 2) }}</td>

                                            <td class="text-center">{{ number_format($list->DEPRECIATION_UNTIL, 0) }}
                                            <td class="text-center">{{ number_format($list->DEPRECIATION_COUNT, 0) }}
                                            <td class="text-center">
                                                {{ number_format($list->DEPRECIATION_UNTIL - $list->DEPRECIATION_COUNT, 0) }}
                                            </td>
                                            <td class="text-center">{{ number_format($list->REMAINING_MONTHS, 0) }} </td>
                                            <td class="text-center">
                                                @if ($list->INACTIVE)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <button class="btn btn-primary btn-xs w-100"
                                                            wire:click='edit({{ $list->ID }})'>
                                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="col-4">
                                                        <button class="btn btn-warning btn-xs w-100"
                                                            wire:click='dep({{ $list->ID }})'>
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </button>
                                                    </div>

                                                    <div class="col-4">
                                                        <button wire:confirm='Are you sure to delete?'
                                                            class="btn btn-danger btn-xs w-100"
                                                            wire:click='delete({{ $list->ID }})'>
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </section>
    @livewire('FixedAssetItem.FixedAssetItemForm')
    @livewire('FixedAssetItem.FixedAssetDepreciation')
</div>
