<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('maintenanceothersitem-active-list') }}">Item Inventory </a>
                    </h5>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <input class="form-check-input" type="checkbox" wire:model.live="isControl" />
                            @if ($isControl)
                                <label class="text-sm "><br /></label>
                                <button type="button" wire:click='showNotInclude()' class="btn btn-danger btn-xs">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Not Include List
                                </button>
                            @endif
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
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
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mt-0">
                                                <label class="text-xs">Search:</label>
                                                <input type="text" wire:model.live.debounce.120ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0">
                                                <label class="text-xs">Location:</label>
                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='LOCATION_ID'
                                                    class="form-control form-control-sm">
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}">
                                                            {{ $item->NAME }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="text-center mt-4 pt-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:model.live="showOutofStock" />
                                                        <span class="text-xs text-info font-weight-bold">
                                                            OUT OF STOCKS
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0">

                                                <label class="text-xs">As of Date:</label>
                                                <input type="DATE" wire:model.live='DATE'
                                                    class="form-control form-control-sm" />

                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mt-4 pt-1">
                                                {{-- @if ($isControl)
                                                    <label class="text-sm "><br /></label>
                                                    <button type="button" wire:click='showNotInclude()'
                                                        class="btn btn-danger btn-xs">
                                                        <i class="fa fa-download" aria-hidden="true"></i> Not Include
                                                        List
                                                    </button>
                                                @endif --}}
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mt-4 pt-1">
                                                <label class="text-sm "><br /></label>
                                                <button type="button" wire:click='exportData()'
                                                    class="btn btn-success btn-xs">
                                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="max-height: 75vh; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-sky sticky-header">
                                        <tr>
                                            <th>
                                                <span name="category" type='button'
                                                    wire:click="sorting('c.DESCRIPTION')">Category</span>
                                                @if ($sortby == 'c.DESCRIPTION')
                                                    @if ($isDesc)
                                                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th>
                                                <span name="sub_category" type='button'
                                                    wire:click="sorting('s.DESCRIPTION')">
                                                    Sub Category
                                                </span>
                                                @if ($sortby == 's.DESCRIPTION')
                                                    @if ($isDesc)
                                                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="bg-primary">
                                                <span name="item_code" type='button'
                                                    wire:click="sorting('item.CODE')">Code</span>
                                                @if ($sortby == 'item.CODE')
                                                    @if ($isDesc)
                                                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="bg-primary">
                                                <span name="item_description" type='button'
                                                    wire:click="sorting('item.DESCRIPTION')">
                                                    Item Description
                                                </span>
                                                @if ($sortby == 'item.DESCRIPTION')
                                                    @if ($isDesc)
                                                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="text-center bg-info">
                                                <span name="symbol" type='button'
                                                    wire:click="sorting('u.SYMBOL')">Unit</span>
                                                @if ($sortby == 'u.SYMBOL')
                                                    @if ($isDesc)
                                                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="text-center bg-warning">
                                                <span name="qty_onhand" type='button'
                                                    wire:click="sorting('QTY_ON_HAND')">Onhand</span>
                                                @if ($sortby == 'QTY_ON_HAND')
                                                    @if ($isDesc)
                                                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="text-center ">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach ($dataList as $list)
                                            <tr>
                                                <td> {{ $list->CLASS_NAME }} </td>
                                                <td> {{ $list->SUB_NAME }}</td>
                                                <td> {{ $list->CODE }}</td>
                                                <td> {{ $list->DESCRIPTION }}</td>
                                                <td class="text-center"> {{ $list->SYMBOL }} </td>
                                                <td class="text-center">
                                                    <span
                                                        class="text-sm @if ($list->QTY_ON_HAND < 0) text-danger @elseif ($list->QTY_ON_HAND == 0) text-info  @else text-primary @endif"
                                                        wire:click='OnClick({{ $list->ID }})'>
                                                        {{ number_format($list->QTY_ON_HAND ?? 0, 2) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{-- <button name="qtyDetails{{ $list->ID }}" title="Qty Details"
                                                        type="button" class="btn btn-xs btn-info"
                                                        wire:click='OnClick({{ $list->ID }})'>
                                                        <i class="fas fa-eye" aria-hidden="true"></i> history
                                                    </button> --}}

                                                    <a href="{{ route('maintenanceothersitem-active-list_details', ['id' => $list->ID, 'locationid' => $LOCATION_ID]) }}"
                                                        class="btn btn-primary btn-xs" target="_blank"> <i
                                                            class="fas fa-eye" aria-hidden="true"></i> View Details
                                                        </a>
                                                    @if ($isControl)
                                                        <button type="button" class="btn btn-xs btn-danger"
                                                            title="Not Include in your list"
                                                            wire:click='itemNotInclude({{ $list->ID }})'
                                                            title="Not Include">
                                                            <i class="fas fa-times" aria-hidden="true"></i>
                                                            Not Include
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @livewire('List.ShowListNotInclude')
                @livewire('List.InventoryDetailsModal')
            </div>
        </div>
    </section>




</div>
