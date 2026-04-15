<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenanceothersitem_treatment') }}"> Item Treatment </a>
                    </h5>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
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
                                        <div class="col-md-9">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
                                                <select name="location" wire:model.live='locationid'
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
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Location</th>
                                        <th>Item </th>
                                        <th>Default Qty</th>
                                        <th>UOM</th>
                                        <th>No. of Used</th>
                                        <th>Inactive</th>
                                        <th>Auto</th>
                                        <th>Is Req.</th>
                                        <th>New Treat. Qty</th>
                                        <th class="text-center col-1 bg-success">
                                            <a href="{{ route('maintenanceothersitem_treatment_create') }}"
                                                class="text-white btn-sm"> <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td> {{ $list->ITEM_NAME }}</td>
                                            <td> {{ $list->QUANTITY }}</td>
                                            <td> {{ $list->SYMBOL }}</td>
                                            <td> {{ $list->NO_OF_USED }}</td>
                                            <td>
                                                @if ($list->INACTIVE)
                                                    YES
                                                @else
                                                    NO
                                                @endif
                                            </td>
                                            <td>
                                                @if ($list->IS_AUTO)
                                                    YES
                                                @else
                                                    NO
                                                @endif
                                            </td>
                                            <td>
                                                @if ($list->IS_REQUIRED)
                                                    YES
                                                @else
                                                    NO
                                                @endif
                                            </td>
                                            <td> {{ $list->NEW_TREATMENT_QTY }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('maintenanceothersitem_treatment_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <button wire:click='delete({{ $list->ID }})'
                                                    wire:confirm="Are you sure you want to delete this?"
                                                    class="btn btn-xs btn-danger">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
