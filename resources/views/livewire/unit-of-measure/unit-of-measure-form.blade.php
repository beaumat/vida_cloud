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
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}  <a  class="text-light" href="{{ route('maintenanceinventoryunit_of_measure') }}"> Unit Of Measure </a></h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <livewire:text-input name="NAME" titleName="Name" :isDisabled="false"
                                                        wire:model='NAME'>
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="SYMBOL" titleName="Symbol" :isDisabled="false"
                                                        wire:model='SYMBOL'>
                                                </div>
                                                <div class="col-md-12">
                                                    <livewire:custom-check-box name="INACTIVE" titleName="Inactive" :isDisabled="false"
                                                        wire:model='INACTIVE'/>
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
                                                href="{{ route('maintenanceinventoryunit_of_measure_create') }}"
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
