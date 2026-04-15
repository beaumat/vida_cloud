<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactvendor') }}"> Vendor </a></h5>
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
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" wire:model.live.debounce.150ms='search'
                                        class="w-100 form-control form-control-sm" placeholder="Search" />
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Mobile No.</th>
                                        <th>Email</th>
                                        <th class="text-center">Inactive</th>
                                        <th class="text-center bg-success col-1">
                                            @can('contact.vendor.create')
                                                <a type="button" href="{{ route('maintenancecontactvendor_create') }}"
                                                    class="text-white">
                                                    <i class="fas fa-plus"></i> New</a>
                                            @endcan

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('maintenancecontactvendor_edit', ['id' => $list->ID]) }}">
                                                    {{ $list->ACCOUNT_NO }}</a>
                                            </td>
                                            <td> {{ $list->NAME }}</td>
                                            <td> {{ $list->POSTAL_ADDRESS }}</td>
                                            <td> {{ $list->MOBILE_NO }}</td>
                                            <td> {{ $list->EMAIL }}</td>
                                            <td class="text-center">
                                                @if ($list->INACTIVE)
                                                    <strong class="text-danger">Yes</strong>
                                                @else
                                                    <strong class="text-primary">No</strong>
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('maintenancecontactvendor_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>

                                                </a>
                                                @can('contact.vendor.delete')
                                                    <button type="button" title="Delete Active"
                                                        wire:click='delete({{ $list->ID }})'
                                                        wire:confirm="Are you sure you want to delete this?"
                                                        class="btn btn-xs btn-danger">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    <button type="button" title="Delete Disabled"
                                                        class="btn btn-xs btn-secondary">
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
                <div class="col-12">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
