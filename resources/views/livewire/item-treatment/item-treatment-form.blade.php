<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenanceothersitem_treatment') }}"> Item Treatment </a>
                    </h5>
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
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent="save">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <livewire:select-option name="LOCATION_ID" :options="$locationList" isDisabled="{{ false }}"
                                                :zero="true" titleName="Location" wire:model='LOCATION_ID' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:select-option name="ITEM_ID" :options="$itemList" :zero="true" isDisabled="{{ false }}"
                                                titleName="Item Name" wire:model.live='ITEM_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="text-xs">Unit of Measure</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <select wire:model='UNIT_ID' name="UNIT_ID"
                                                        class="text-sm form-control form-control-sm mt-2">
                                                        <option value="0">

                                                        </option>
                                                        @foreach ($unitList as $list)
                                                            <option value="{{ $list->ID }}">
                                                                {{ $list->SYMBOL }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:number-input name="NO_OF_USED" titleName="No. of Used" isDisabled="{{ false }}"
                                                wire:model='NO_OF_USED' />
                                        </div>
                                        <div class="col-md-2">

                                        </div>

                                        <div class="col-md-2">
                                            <livewire:custom-check-box name="IS_REQUIRED" titleName="Is Required" isDisabled="{{ false }}"
                                                wire:model='IS_REQUIRED' />
                                            <livewire:custom-check-box name="INACTIVE" titleName="Inactive" isDisabled="{{ false }}"
                                                wire:model='INACTIVE' />
                                        </div>

                                        <div class="col-md-2">
                                            <livewire:number-input name="QUANTITY" titleName="Default Qty" isDisabled="{{ false }}"
                                                wire:model='QUANTITY' />
                                            <livewire:custom-check-box name="IS_AUTO" titleName="Auto Default" isDisabled="{{ false }}"
                                                wire:model='IS_AUTO' />
                                            <livewire:custom-check-box name="IS_AUTO_SC" titleName="Auto Charges" isDisabled="{{ false }}"
                                                wire:model='IS_AUTO_SC' />
                                        </div>



                                        <div class="col-md-2">
                                            <livewire:number-input name="NEW_TREATMENT_QTY" isDisabled="{{ false }}"
                                                titleName="New Treatment Qty" wire:model='NEW_TREATMENT_QTY' />
                                            {{-- FIRST_TIME_AUTO_NEW --}}
                                            <livewire:custom-check-box name="FIRST_TIME_AUTO_NEW" isDisabled="{{ false }}"
                                                titleName="Auto on New" wire:model='FIRST_TIME_AUTO_NEW' />
                                            <livewire:custom-check-box name="FT_AUTO_SC" titleName="Auto New Charges" isDisabled="{{ false }}"
                                                wire:model='FT_AUTO_SC' />
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            {{ $ID === 0 ? 'Save' : 'Update' }}
                                        </button>
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0)
                                            <a id="new" title="Create"
                                                href="{{ route('maintenanceothersitem_treatment_create') }}"
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
                    @if ($ID > 0)
                        @livewire('ItemTreatment.ItemTriggerModal', ['ITEM_TREATMENT_ID' => $ID])
                    @endif
                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>
