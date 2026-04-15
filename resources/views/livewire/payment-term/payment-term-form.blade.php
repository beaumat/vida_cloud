<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancefinancialpayment_term') }}"> Payment Terms </a></h5>
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
                                            <livewire:text-input name="DESCRIPTION" titleName="Description" :isDisabled="false"
                                                wire:model='DESCRIPTION' />
                                        </div>
                                        <div class="col-md-4"  @if ($ID > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <livewire:select-option name="TYPE" :options="$paymentTermTypes" :zero="false" :isDisabled="false"
                                                titleName="Type" wire:model.live='TYPE' :key="$paymentTermTypes->pluck('ID')->join('_')" />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:number-input name="NET_DUE" titleName="Net Due" :isDisabled="false"
                                                wire:model='NET_DUE' />
                                        </div>
                                        <div class="col-md-2"  @if ($TYPE > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <livewire:number-input name="DATE_MIN_DAYS" titleName="Day of Due date" :isDisabled="false"
                                                wire:model='DATE_MIN_DAYS' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:number-input name="DISCOUNT_PCT" titleName="Discount Percent" :isDisabled="false"
                                                wire:model='DISCOUNT_PCT' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:number-input name="DISCOUNT_DUE" titleName="Discount Due"  :isDisabled="false"
                                                wire:model='DISCOUNT_DUE' />
                                        </div>

                                        @if ($TYPE == 0)
                                        
                                        @elseif ($TYPE === 1)
                                            <div class="col-md-2">
                                                <div class="mt-2">
                                                    <label class="text-sm">Weekly</label>
                                                    <select class="form-control form-control-sm"
                                                        wire:model='DATE_MONTH_PARAM' name="weekly">
                                                        @foreach ($weeklyList as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif ($TYPE === 2)
                                            <div class="col-md-2">
                                                <div class="mt-2">
                                                    <label class="text-sm">Semi-Monthly</label>
                                                    <select class="form-control form-control-sm"
                                                        wire:model='DATE_MONTH_PARAM' name="weekly">
                                                        @foreach ($semiMonthly as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif ($TYPE === 3)
                                            <div class="col-md-2">
                                                <div class="mt-2">
                                                    <label class="text-sm">Monthly</label>
                                                    <select class="form-control form-control-sm"
                                                        wire:model='DATE_DAY_PARAM' name="weekly">
                                                        @foreach ($dayList as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif ($TYPE === 4)
                                            <div class="col-md-2">
                                                <div class="mt-2">
                                                    <label class="text-sm">Semi-Annual</label>
                                                    <select class="form-control form-control-sm"
                                                        wire:model='DATE_DAY_PARAM' name="semeAnnual">
                                                        @foreach ($semiAnnualList as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mt-2">
                                                    <label class="text-sm">Day</label>
                                                    <select class="form-control form-control-sm"
                                                        wire:model='DATE_MONTH_PARAM' name="day">
                                                        @foreach ($dayList as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif ($TYPE === 5)
                                            <div class="col-md-2">
                                                <div class="mt-2">
                                                    <label class="text-sm">Annual</label>
                                                    <select class="form-control form-control-sm"
                                                        wire:model='DATE_DAY_PARAM' name="annual">
                                                        @foreach ($monthList as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mt-2">
                                                    <label class="text-sm">Day</label>
                                                    <select class="form-control form-control-sm"
                                                        wire:model='DATE_MONTH_PARAM' name="day">
                                                        @foreach ($dayList as $list)
                                                            <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="INACTIVE" titleName="Inactive" :isDisabled="false"
                                                wire:model='INACTIVE' />
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
                                                href="{{ route('maintenancefinancialpayment_term_create') }}"
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
