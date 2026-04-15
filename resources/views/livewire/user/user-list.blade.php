<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingsusers') }}"> Users </a></h5>
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
                                        <th class="col-2">Username</th>
                                        <th class="col-4">Employee Name</th>
                                        <th class="col-2">Location</th>
                                        <th class="text-center">Locked</th>
                                        <th class="text-center">Trans Date</th>
                                        <th class="text-center">Inactive</th>
                                        <th class ="text-center ">Logs Disabled</th>
                                        <th class="text-center bg-success col-1">
                                            <a href="{{ route('maintenancesettingsusers_create') }}" class="text-white">
                                                <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($users as $list)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('maintenancesettingsusers_edit', ['id' => $list->id]) }}">
                                                    {{ $list->name }}
                                                </a>
                                            </td>
                                            <td> {{ $list->employee }}</td>
                                            <td> {{ $list->location }}</td>
                                            <td class="text-center">
                                                @if ($list->locked)
                                                    <strong class="text-danger">Yes</strong>
                                                @endif
                                            </td>
                                            <td class='text-center'> {{ $list->trans_date }}</td>
                                            <td class="text-center">
                                                @if ($list->inactive)
                                                    <strong class="text-danger">Yes</strong>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($list->logs_disabled)
                                                    <strong class="text-danger">Yes</strong>
                                                @endif
                                            <td class="text-center">
                                                <a type="button" title="View"
                                                    href="{{ route('maintenancesettingsusers_edit', ['id' => $list->id]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                                                {{-- <button title="Delete" type="button" wire:click='delete({{ $list->ID }})'
                                                    wire:confirm="Are you sure you want to delete this?"
                                                    class="btn btn-xs btn-danger">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button> --}}
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
