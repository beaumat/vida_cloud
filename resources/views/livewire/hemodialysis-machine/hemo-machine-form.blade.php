<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">

        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }} <a class="text-light"
                                    href="{{ route('maintenanceothershemo_machine') }}"> Hemodialysis Machine </a></h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <livewire:text-input name="CODE" titleName="Machine No."
                                                        wire:model='CODE'  isDisabled="{{ false }}"  />
                                                </div>

                                                <div class="col-md-6">
                                                    <livewire:text-input name="DESCRIPTION" titleName="Description"
                                                        wire:model='DESCRIPTION'  isDisabled="{{ false }}"  />
                                                </div>

                                                <div class="col-md-6">
                                                    <livewire:dropdown-option name="TYPE" titleName="Type"
                                                        :options="$typeList" :zero="false" :isDisabled=false
                                                        wire:model='TYPE'  isDisabled="{{ false }}"  />
                                                </div>
                                                <div class="col-md-6">
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false" :isDisabled=false
                                                        wire:model='LOCATION_ID'  isDisabled="{{ false }}"  />
                                                </div>
                                                <div class="col-md-6">
                                                    <livewire:number-input name="CAPACITY" titleName="Capacity"
                                                    wire:model='CAPACITY'  isDisabled="{{ false }}"  /> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="submit"
                                            class="btn btn-sm btn-success">{{ $ID === 0 ? 'Save' : 'Update' }}</button>
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0)
                                            <a id="new" title="Create"
                                                href="{{ route('maintenanceothershemo_machine_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">

                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>




</div>
