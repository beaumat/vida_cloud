<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingsroles') }}"> Roles & Permission </a></h5>
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
                <div class="col-md-4 col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Roles</th>
                                        <th class="col-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($roles as $list)
                                        <tr>
                                            <td> {{ $list->name }}</td>
                                            <td>
                                                <a href="{{ route('maintenancesettingsroles_permission', ['id' => $list->id]) }}"
                                                    class="btn btn-xs btn-info w-100">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <input type="text" class="text-xs w-100" placeholder="Enter new role"
                                                wire:model='name' />
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-success w-100" wire:click='addRole()'>
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>
</div>
