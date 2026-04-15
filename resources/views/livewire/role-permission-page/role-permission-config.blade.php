<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingsroles') }}"> Roles & Permission </a> ::
                        {{ $role_name }}</h5>
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
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" wire:model.live.debounce.150ms='searchSign'
                                        class="w-100 form-control form-control-sm mt-1 mb-1" placeholder="Search" />
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Assigned Permission</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($assignedPermissions as $list)
                                        @if (stripos($list->name, $searchSign) !== false)
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-10">
                                                            <button class="btn btn-xs w-100"
                                                                wire:click="deletePermission('{{ $list->name }}')">
                                                                {{ $list->name }}
                                                            </button>
                                                        </div>
                                                        <div class="col-2 text-center">
                                                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                        </div>

                                                    </div>

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" wire:model.live.debounce.150ms='searchUnsign'
                                        class="w-100 form-control form-control-sm mt-1 mb-1" placeholder="Search" />
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Unassigned Permission</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($unassignedPermissions as $list)
                                        @if (stripos($list->name, $searchUnsign) !== false)
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-2 text-center">
                                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="col-10">
                                                            <button type="button" class="btn btn-xs w-100"
                                                                wire:click="addPermission('{{ $list->name }}')">
                                                                {{ $list->name }}
                                                            </button>
                                                        </div>

                                                    </div>

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-sm">
                        <div class=card-header>
                            <h6>New Permission</h6>
                        </div>
                        <form wire:submit.prevent='newPermission'>

                            <div class="card-body">
                                <input type="text" class="form-control form-control-sm" wire:model='permission_name'
                                    placeholder="Enter permission name" />
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-success btn-xs">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Create </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
