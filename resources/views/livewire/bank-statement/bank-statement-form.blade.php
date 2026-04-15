<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-sky">
                            {{ $ID == 0 ? 'Create' : '' }}
                            <a class="text-white" href="{{ route('bankingbank_statement') }}"> Bank Statement
                            </a>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-6">
                                                        @if ($Modify && $ID == 0)
                                                            <livewire:select-option name="ACCOUNT_ID1"
                                                                titleName="Bank Account" :options="$accountList"
                                                                :zero="true" :isDisabled="false"
                                                                wire:model.live='BANK_ACCOUNT_ID' />
                                                        @else
                                                            <livewire:select-option name="ACCOUNT_ID2"
                                                                titleName="Bank Account" :options="$accountList"
                                                                :zero="true" :isDisabled="true"
                                                                wire:model.live='BANK_ACCOUNT_ID' />
                                                        @endif

                                                    </div>
                                                    <div class="col-md-3 col-3">
                                                        @if ($ID > 0)
                                                            @if ($Modify)
                                                                <livewire:date-input name="DATE_FROM1"
                                                                    titleName="Date From" wire:model.live='DATE_FROM'
                                                                    :isDisabled="false" />
                                                            @else
                                                                <livewire:date-input name="DATE_FROM2"
                                                                    titleName="Date From" wire:model.live='DATE_FROM'
                                                                    :isDisabled="true" />
                                                            @endif

                                                        @endif

                                                        @if ($ID == 0)
                                                            <label class="text-xs">Year Statement</label>
                                                            <select class="text-xs w-100 form-control form-control-sm"
                                                                wire:model.live='SELECT_YEAR'>
                                                                @foreach ($yearList as $list)
                                                                    <option value="{{ $list['ID'] }}">
                                                                        {{ $list['NAME'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 col-3">
                                                        @if ($ID > 0)
                                                            @if ($Modify)
                                                                <livewire:date-input name="DATE_TO1" titleName="Date To"
                                                                    wire:model.live='DATE_TO' :isDisabled="false" />
                                                            @else
                                                                <livewire:date-input name="DATE_TO2" titleName="Date To"
                                                                    wire:model.live='DATE_TO' :isDisabled="true" />
                                                            @endif

                                                        @endif

                                                        @if ($ID == 0)
                                                            <label class="text-xs">Month Statement</label>
                                                            <select class="text-xs w-100 form-control form-control-sm"
                                                                wire:model.live='SELECT_MONTH'>
                                                                @foreach ($monthList as $list)
                                                                    <option value="{{ $list['ID'] }}">
                                                                        {{ $list['NAME'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-3">
                                                        @if ($Modify)
                                                            <livewire:select-option name="FILE_TYPE1"
                                                                titleName="File Type" :options="$fileTypeList"
                                                                :zero="true" :isDisabled="false"
                                                                wire:model.live='FILE_TYPE' />
                                                        @else
                                                            <livewire:select-option name="FILE_TYPE2"
                                                                titleName="File Type" :options="$fileTypeList"
                                                                :zero="true" :isDisabled="true"
                                                                wire:model.live='FILE_TYPE' />
                                                        @endif
                                                    </div>
                                                    <div class="col-9">
                                                        @if ($Modify)
                                                            <livewire:text-input name="DESCRIPTION1"
                                                                titleName="Description" :isDisabled=false
                                                                wire:model='DESCRIPTION' :vertical="false" />
                                                        @else
                                                            <livewire:text-input name="DESCRIPTION2"
                                                                titleName="Description" :isDisabled=true
                                                                wire:model='DESCRIPTION' :vertical="false" />
                                                        @endif
                                                    </div>
                                                </div>


                                            </div>

                                        </div>




                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if ($Modify)
                                                <livewire:text-input name="NOTES" titleName="Notes" :isDisabled=false
                                                    wire:model='NOTES' :vertical="false" />
                                            @else
                                                <livewire:text-input name="NOTES" titleName="Notes" :isDisabled=true
                                                    wire:model='NOTES' :vertical="false" />
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">

                                                <table class="mt-4 w-100">
                                                    <tr>
                                                        <td class="text-right"> <label class="h5 "> BEGINNING BALANCE :</label></td>
                                                        <td class="h4 text-success"> {{ number_format($BEGINNING_BALANCE, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right"> <label class="h5 "> ENDING BALANCE : </label></td>
                                                        <td class="h4 text-danger"> {{ number_format($ENDING_BALANCE, 2) }}</td>
                                                    </tr>
                                                </table>

                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" wire:loading.class='loading-form'>
                            <div class="row">
                                <div class="col-md-6 col-6">
                                    @if ($Modify)

                                        <button type="button" wire:click='Save' class="btn btn-sm btn-primary"> <i
                                                class="fa fa-floppy-o" aria-hidden="true"></i>
                                            {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                        @if ($ID > 0)
                                            <button type="button" wire:click='updateCancel()' name="cancelbtn"
                                                wire:confirm='Want to cancel?' class="btn btn-sm btn-danger"><i
                                                    class="fa fa-ban" aria-hidden="true"></i> Cancel</button>
                                        @endif
                                    @else
                                        <button type="button" wire:click='getModify()' class="btn btn-sm btn-info">
                                            <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                        </button>
                                    @endif
                                </div>
                                <div class="text-right col-md-6 col-6">
                                    @if ($STATUS > 0)
                                        @can('banking.bank-statement.create')
                                            <a id="new" title="Create"
                                                href="{{ route('bankingbank_statement_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                        @endcan
                                    @endif

                                    @if (!$Modify)
                                        <a type="button" target="_BLANK"
                                            href="{{ route('bankingbank_statement_print', ['id' => $ID]) }}"
                                            class="btn btn-sm btn-dark">
                                            <i class="fa fa-print" aria-hidden="true"></i> Print
                                        </a>
                                    @endif
                                </div>
                                <div class="col-12" wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            @if ($ID > 0)
                @livewire('BankStatement.BankStatementFormDetails', ['BANK_STATEMENT_ID' => $ID, 'FILE_TYPE' => $FILE_TYPE])
            @endif
        </div>
    </section>

</div>
