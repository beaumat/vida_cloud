<section class="content">

    <div class="container-fluid">
        <div class="row">
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th>Assigned Roles</th>
                                    <th class="col-1">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($assignedRoles as $list)
                                    <tr>
                                        <td> {{ $list->name }}</td>
                                        <td> <button wire:click="deleterole({{ $id }},'{{ $list->name }}')"
                                                class="btn btn-xs btn-danger w-100">
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th>Unassigned Roles</th>
                                    <th class="col-1">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($unassignedRoles as $list)
                                    <tr>
                                        <td> {{ $list->name }}</td>
                                        <td> <button wire:click="addrole({{ $id }},'{{ $list->name }}')"
                                                class="btn btn-xs btn-success w-100">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
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
