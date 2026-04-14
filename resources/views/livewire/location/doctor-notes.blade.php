<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> Doctor Notes </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <span class="text-primary"> {{ $LOCATION_NAME }}</span>
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
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-sm bg-sky">
                                    <tr>
                                        <th>Description</th>
                                        <th class="col-2 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                @if ($editID == $list->ID)
                                                    <input class='form-control form-control-sm'
                                                        wire:model='editDescription' placeholder="Enter description" />
                                                @else
                                                    {{ $list->DESCRIPTION }}
                                                @endif
                                            </td>
                                            <td class='text-center'>
                                                @if ($editID == $list->ID)
                                                    <button title="save" wire:click='update()'
                                                        class="btn btn-xs btn-success"><i class="fa fa-floppy-o"
                                                            aria-hidden="true"></i></button>
                                                    <button title="cancel" wire:click='cancel()'
                                                        wire:confirm='Are you sure to cancel?'
                                                        class="btn btn-xs btn-warning"><i class="fa fa-ban"
                                                            aria-hidden="true"></i></button>
                                                @else
                                                    <button title="edit" wire:click='edit({{ $list->ID }})'
                                                        class="btn btn-xs btn-info"><i class="fa fa-pencil"
                                                            aria-hidden="true"></i></button>
                                                    <button title="delete" wire:click='delete({{ $list->ID }})'
                                                        wire:confirm='Are you sure to delete?'
                                                        class="btn btn-xs btn-danger"><i class="fa fa-trash"
                                                            aria-hidden="true"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <form id="quickForm" wire:submit.prevent='store'>
                                        <tr>
                                            <td>
                                                <input class='form-control form-control-sm' wire:model='DESCRIPTION'
                                                    placeholder="Enter description" />
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-xs btn-primary w-100">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
