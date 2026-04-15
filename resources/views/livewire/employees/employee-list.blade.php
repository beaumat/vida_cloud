<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactemployees') }}"> Employees List </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <button wire:click='export' class="btn btn-success btn-sm"> Export</button>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col-md-8">
                                    <div class="mt-0">
                                        <label class="text-sm">Search:</label>
                                        <input type="text" wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm" placeholder="Search" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mt-0">
                                        <label class="text-xs">Location:</label>
                                        <select name="location" wire:model.live='locationid'
                                            class="form-control form-control-sm text-xs">
                                            <option value="0"> All Location</option>
                                            @foreach ($locationList as $item)
                                                <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Emp ID</th>
                                        <th>Name</th>
                                        <th>Address</th>

                                        <th>Mobile No.</th>
                                        <th>Email</th>
                                        <th>Pin</th>
                                        <th>Location</th>
                                        <th class="text-center">Inactive</th>
                                        <th class="text-center bg-success col-1">
                                            <a href="{{ route('maintenancecontactemployees_create') }}"
                                                class="text-white w-100 btn btn-xs btn-success">
                                                <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ $list->ACCOUNT_NO }}</td>
                                            <td> {{ $list->NAME }}</td>
                                            <td> {{ $list->POSTAL_ADDRESS }}</td>
                                            <td> {{ $list->MOBILE_NO }}</td>
                                            <td> {{ $list->EMAIL }}</td>
                                            <td>{{ $list->PIN }}</td>
                                            <td> {{ $list->LOCATION }}</td>
                                            <td class="text-center">
                                                @if ($list->INACTIVE)
                                                    <strong class="text-danger">Yes</strong>
                                                @else
                                                    <strong class="text-primary">No</strong>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a type="button"
                                                    href="{{ route('maintenancecontactemployees_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                                                @can('contact.employee.delete')
                                                    <button type="button" wire:click='delete({{ $list->ID }})'
                                                        wire:confirm="Are you sure you want to delete this?"
                                                        class="btn btn-xs btn-danger">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-xs btn-secondary">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
