<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportsaccountinggeneral_ledeger_report') }}"> General Ledger
                            Report
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
                                <div class="row">
                                    <div class="col-md-12">

                                        <livewire:checkbox-input name="IS_RANGE" titleName="Use Date Covered"
                                            wire:model.live='IS_RANGE' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        @if ($IS_RANGE)
                                            <livewire:date-input name="DATE_FROM1" titleName="Date From"
                                                wire:model.live='DATE_FROM' :isDisabled="false" />
                                            <livewire:date-input name="DATE_TO" titleName="Date To"
                                                wire:model.live='DATE_TO' :isDisabled="false" />
                                        @else
                                            <livewire:date-input name="DATE_FROM2" titleName="Date as of"
                                                wire:model.live='DATE_FROM' :isDisabled="false" />
                                        @endif
                                    </div>
                                    <div class="col-md-5">

                                    </div>
                                    <div class='col-md-12 mt-1'>
                                        <div class="form-group">
                                            <a target="_blank"
                                                href="{{ route('reportsaccountinggeneral_ledeger_view', [
                                                    'from' => $DATE_FROM,
                                                    'to' => !empty($DATE_TO) ? $DATE_TO : 'none',
                                                    'location' => $LOCATION_ID,
                                                    'account' => !empty($selectedAccount) ? implode(',', $selectedAccount) : 'none',
                                                    'accounttype' => !empty($selectedAccountType) ? implode(',', $selectedAccountType) : 'none',
                                                ]) }}"
                                                class="btn btn-danger btn-xs w-25" wire:loading.attr='disabled'>
                                                Generate
                                            </a>
                                            <button class="btn btn-success btn-xs w-25" wire:loading.attr='disabled'
                                                wire:click='export()'>Export</button>

                                            <div wire:loading.delay>
                                                <span class='spinner'></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <livewire:select-checkbox name="ACCOUNT_ID" titleName="Filter Account" :options="$accountList"
                                    :zero="true" :isDisabled=false wire:model.live='selectedAccount' />
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <livewire:select-checkbox name="ACCOUNT_TYPE_ID" titleName="Filter Account Type"
                                            :options="$accountTypeList" :zero="true" :isDisabled=false
                                            wire:model.live='selectedAccountType' />
                                    </div>
                                    <div class="col-md-4">
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
                </div>


            </div>
        </div>
    </section>
</div>
