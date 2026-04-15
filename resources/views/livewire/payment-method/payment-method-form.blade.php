<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancefinancialpayment_method') }}"> Payment Method </a></h5>
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
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <livewire:text-input name="CODE" titleName="Code" wire:model='CODE' :isDisabled="false" />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="DESCRIPTION" titleName="Description" wire:model='DESCRIPTION' :isDisabled="false" maxlength='50' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:select-option name="PAYMENT_TYPE" :options="$paymentTypes" :isDisabled="false"
                                                :zero="false" titleName="Type"
                                                wire:model.live='PAYMENT_TYPE' :key="$paymentTypes->pluck('ID')->join('_')"/>
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:select-option name="GL_ACCOUNT_ID" :options="$accountList" :isDisabled="false"
                                                :zero="true" titleName="GL Account"
                                                wire:model.live='GL_ACCOUNT_ID' :key="$accountList->pluck('ID')->join('_')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="submit"
                                            class="btn btn-sm btn-success">{{ $ID === 0 ? 'Save' : 'Update' }}</button>
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0)
                                            <a id="new" title="Create"
                                                href="{{ route('maintenancefinancialpayment_method_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
