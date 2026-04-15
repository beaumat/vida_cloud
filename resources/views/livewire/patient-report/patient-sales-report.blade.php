<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportspatient_sales_report') }}"> Patient Collection Report
                        </a></h5>
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
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row" wire:loading.class='loading-form'>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TRANSACTION_FROM" titleName="Date From"
                                            wire:model.live='DATE_TRANSACTION_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TRANSACTION_TO" titleName="Date To"
                                            wire:model.live='DATE_TRANSACTION_TO' :isDisabled="false" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" wire:loading.class='loading-form'>

                                <livewire:checkbox-input name="is_filter" titleName="Show Filter"
                                    wire:model.live='showFilter' :isDisabled="false" />
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">

                                    </div>
                                    <div class="col-md-4" wire:loading.class='loading-form'>
                                        <div class="mt-0">
                                            <label class="text-xs ">Location:</label>
                                            <select
                                                @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                name="location" wire:model.live='LOCATION_ID'
                                                class="form-control form-control-sm text-xs ">
                                                <option value="0"> All Location</option>
                                                @foreach ($locationList as $item)
                                                    <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            @if ($showFilter)
                                <div class="col-4" wire:loading.class='loading-form'>
                                    @if ($refreshComponent)
                                        <livewire:select-checkbox name="PATIENT_ID1" titleName="Filter patient"
                                            :options="$filterPatient" :zero="true" :isDisabled=false
                                            wire:model.live='selectedPatient' />
                                    @else
                                        <livewire:select-checkbox name="PATIENT_ID2" titleName="Filter patient"
                                            :options="$filterPatient" :zero="true" :isDisabled=false
                                            wire:model.live='selectedPatient' />
                                    @endif
                                </div>
                                <div class="col-4" wire:loading.class='loading-form'>
                                    @if ($refreshComponent)
                                        <livewire:select-checkbox name="ITEM1" titleName="Filter item"
                                            :options="$filterItem" :zero="true" :isDisabled=false
                                            wire:model.live='selectedItem' />
                                    @else
                                        <livewire:select-checkbox name="ITEM2" titleName="Filter item"
                                            :options="$filterItem" :zero="true" :isDisabled=false
                                            wire:model.live='selectedItem' />
                                    @endif
                                </div>
                                <div class="col-4" wire:loading.class='loading-form'>
                                    @if ($refreshComponent)
                                        <livewire:select-checkbox name="METHOD1" titleName="Filter method"
                                            :options="$filterMethod" :zero="true" :isDisabled=false
                                            wire:model.live='selectedMethod' />
                                    @else
                                        <livewire:select-checkbox name="METHOD2" titleName="Filter method"
                                            :options="$filterMethod" :zero="true" :isDisabled=false
                                            wire:model.live='selectedMethod' />
                                    @endif
                                </div>
                            @endif
                            <div wire:loading.delay>
                                <div class="col-6 p-1">
                                    <span class="spinner" role="status" aria-hidden="true"></span>
                                </div>

                            </div>
                            <div class="col-6 p-1" wire:loading.class='loading-form'>
                                <a target="_blank"
                                    href="{{ route('reportspatient_sales_report_view', [
                                        'date_from' => $DATE_TRANSACTION_FROM,
                                        'date_to' => $DATE_TRANSACTION_TO,
                                        'location_id' => $LOCATION_ID,
                                        'patient' => !empty($selectedPatient) ? implode(',', $selectedPatient) : 'none',
                                        'item' => !empty($selectedItem) ? implode(',', $selectedItem) : 'none',
                                        'method' => !empty($selectedMethod) ? implode(',', $selectedMethod) : 'none',
                                    ]) }}"
                                    class="btn btn-xs btn-danger w-25">
                                    View Report
                                </a>
                            </div>




                        </div>

                    </div>
                </div>
            </div>
        </div>
</div>
</section>



</div>

@script
    <script>
        Livewire.on('open-new-tab', e => {
            window.open(e.url, '_blank');
        });
    </script>
@endscript
