<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingsusers') }}"> User </a></h5>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <!-- left column -->
                <div class="col-md-4">
                    <!-- jquery validation -->
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $id === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <livewire:text-input name="name" titleName="Username"
                                                isDisabled="{{ false }}" wire:model='name' />
                                        </div>
                                        <div class="col-md-12">
                                            <label for="pass" class="text-xs">
                                                @if ($id > 0)
                                                    Change Password
                                                @else
                                                    Password
                                                @endif
                                            </label>
                                            <input type="password" class="form-control form-control-sm" name="password"
                                                placeholder="Enter password" wire:model='password' />
                                        </div>

                                        <div class="col-md-12">
                                            <livewire:select-option name="contact_id" titleName="Empoyees"
                                                isDisabled="{{ false }}" :options="$employees" :zero="true"
                                                :isDisabled=false wire:model='contact_id' />
                                        </div>
                                        <div class="col-md-12">
                                            <livewire:select-option name="location_id" titleName="Location"
                                                isDisabled="{{ false }}" :options="$locationList" :zero="true"
                                                :isDisabled=false wire:model='location_id' />
                                        </div>
                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="locked_location"
                                                isDisabled="{{ false }}" titleName="Locked Location"
                                                wire:model='locked_location' />
                                        </div>

                                        <div class="col-md-12">
                                            <livewire:date-input name="trans_date" titleName="Transaction Date"
                                                isDisabled="{{ false }}" wire:model='trans_date'
                                                :isDisabled="false" />
                                        </div>
                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="date_enabled"
                                                isDisabled="{{ false }}" titleName="Date Enabled"
                                                wire:model='date_enabled' />
                                        </div>
                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="inactive" titleName="Inactive"
                                                isDisabled="{{ false }}" wire:model='inactive' />
                                        </div>
                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="logs_disabled" titleName="Logs Disabled"
                                                isDisabled="{{ false }}" wire:model='logs_disabled' />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="submit"
                                            class="btn btn-sm btn-success">{{ $id === 0 ? 'Save' : 'Update' }}</button>
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($id > 0)
                                            <a id="new" title="Create"
                                                href="{{ route('maintenancesettingsusers_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-8">
                    @if ($id > 0)
                        @livewire('User.UserRoles', ['id' => $id])
                    @endif
                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>




</div>
