<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> Doctor Location </a></h5>
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
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-sm bg-sky">
                                    <tr>
                                        <th>Doctor</th>
                                        <th class="col-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ $list->NAME }} </td>
                                            <td><button class="btn btn-xs btn-danger w-100"
                                                    wire:click='Delete({{ $list->ID }})'
                                                    wire:confirm='Are You Sure?'>
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button></td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td>
                                            @if ($refresh)
                                                <livewire:select-option name="DOCTOR_ID1" titleName=""
                                                    :options="$doctorList" :zero="true" wire:model.live='DOCTOR_ID'
                                                    :vertical="false" :withLabel="false" :isDisabled="false" />
                                            @else
                                                <livewire:select-option name="DOCTOR_ID2" titleName="" :isDisabled="false" 
                                                    :options="$doctorList" :zero="true" wire:model.live='DOCTOR_ID'
                                                    :vertical="false" :withLabel="false" />
                                            @endif

                                        </td>
                                        <td><button wire:click='Add()' class="btn btn-xs btn-success w-100">
                                                <i class="fas fa-plus" aria-hidden="true"></i>
                                            </button></td>
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
