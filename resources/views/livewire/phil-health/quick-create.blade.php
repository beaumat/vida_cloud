<div>
    <button wire:click="openModal" class="btn btn-warning btn-sm text-xs">
        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Quick Create
    </button>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">Quick Create</div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                        <div class="row">
                            <div class="col-md-6">
                                <livewire:text-input name="search" titleName="Search" :isDisabled=false
                                    wire:model.live='search' :vertical="false" />

                            </div>
                            <div class="col-md-2">
                                @if ($isDaily)
                                    <livewire:date-input name="DATE_FROM" titleName="Date Treatment" :isDisabled=false
                                        wire:model.live='DATE_FROM' />
                                @else
                                    <livewire:date-input name="DATE_FROM" titleName="Date From" :isDisabled=false
                                        wire:model.live='DATE_FROM' />
                                @endif
                            </div>
                            <div class="col-md-2">
                                @if (!$isDaily)
                                    <livewire:date-input name="DATE_TO" titleName="Date To" wire:model.live='DATE_TO'
                                        :isDisabled=false />
                                @endif

                            </div>
                            <div class="col-md-2">
                                @if (!$isDaily)
                                    @can('phic-quick-create-advance')
                                        <livewire:checkbox-input name="IS_EXISTS" titleName="Not Exists"
                                            wire:model.live='showExists' :isDisabled=false />
                                    @endcan
                                @endif
                            </div>
                        </div>
                        <table class="table table-sm table-bordered table-hover mt-2">
                            <thead class="bg-sky text-xs">
                                <tr>
                                    <th class="text-center col-1"> <input type="checkbox" wire:model.live='SelectAll' />
                                    </th>
                                    <th class="col-4">Patient Name</th>
                                    <th class="col-2 text-center">No. of Treatment</th>
                                    <th class="col-2">Date Admiited</th>
                                    <th class="col-2">Date Discharged</th>
                                    <th class="col-2">Philhealth No.</th>

                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="patientList{{ $list->ID }}"
                                                wire:model.live='patientSelected.{{ $list->ID }}' />
                                        </td>
                                        <td>{{ $list->PATIENT }}</td>
                                        <td class="text-center">{{ $list->TOTAL_HEMO }}</td>
                                        <td> {{ date('m/d/Y', strtotime($list->FIRST_DATE)) }}</td>
                                        <td> {{ date('m/d/Y', strtotime($list->LAST_DATE)) }}</td>
                                        <td>{{ $list->PIN }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">
                                </div>
                                <div class="col-6 text-right">
                                    <div wire:loading.delay>
                                        <span class="spinner"></span>
                                    </div>
                                    <button class="btn btn-success btn-sm" wire:click='create()'
                                        wire:loading.attr='disabled'>Create</button>
                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm"
                                        wire:loading.attr='disabled'>
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
