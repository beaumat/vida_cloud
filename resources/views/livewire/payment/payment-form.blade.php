<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{ $ID == 0 ? 'Create' : '' }}
                                    <a class="text-white" href="{{ route('customerspayment') }}"> Receive Payments </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if ($ID > 0)
                                        <i> {{ $STATUS_DESCRIPTION }}</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if ($Modify)
                                                <livewire:select-option-type name="CUSTOMER_ID1" titleName="Customer"
                                                    :options="$contactList" :zero="true" :isDisabled=false
                                                    wire:model='CUSTOMER_ID' />
                                            @else
                                                <livewire:select-option-type name="CUSTOMER_ID2" titleName="Customer"
                                                    :options="$contactList" :zero="true" :isDisabled=true
                                                    wire:model='CUSTOMER_ID' />
                                            @endif

                                            <div class="row">
                                                <div class="col-md-3">
                                                    @if ($Modify && $AMOUNT_APPLIED == 0)
                                                        <livewire:number-input name="AMOUNT" titleName="Amount"
                                                            :isDisabled=false wire:model='AMOUNT' />
                                                    @else
                                                        <livewire:number-input name="AMOUNT" titleName="Amount"
                                                            :isDisabled=true wire:model='AMOUNT' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:dropdown-option name="PAYMENT_METHOD_ID"
                                                            :isDisabled=false titleName="Payment Method"
                                                            :options="$paymentMethodList" :zero="true"
                                                            wire:model.live='PAYMENT_METHOD_ID' />
                                                    @else
                                                        <livewire:dropdown-option name="PAYMENT_METHOD_ID"
                                                            :isDisabled=true titleName="Payment Method"
                                                            :options="$paymentMethodList" :zero="true"
                                                            wire:model.live='PAYMENT_METHOD_ID' />
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    @if ($BANK_MODE)
                                                        @if ($Modify)
                                                            <livewire:select-option name="UNDEPOSITED_FUNDS_ACCOUNT_ID"
                                                                titleName="Deposit to Bank Account" :options="$accountList"
                                                                :zero="true" :isDisabled=false
                                                                wire:model='UNDEPOSITED_FUNDS_ACCOUNT_ID' />
                                                        @else
                                                            <livewire:select-option name="UNDEPOSITED_FUNDS_ACCOUNT_ID"
                                                                titleName="Deposit to Bank Account" :options="$accountList"
                                                                :zero="true" :isDisabled=true
                                                                wire:model='UNDEPOSITED_FUNDS_ACCOUNT_ID' />
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                @if ($showReceiptNo)
                                                    <div class="col-md-6">
                                                        @if ($Modify)
                                                            <livewire:text-input name="RECEIPT_REF_NO"
                                                                titleName="{{ $TITLE_REF }}" :isDisabled=false
                                                                wire:model='RECEIPT_REF_NO' />
                                                        @else
                                                            <livewire:text-input name="RECEIPT_REF_NO"
                                                                titleName="{{ $TITLE_REF }}" :isDisabled=true
                                                                wire:model='RECEIPT_REF_NO' />
                                                        @endif
                                                    </div>
                                                @endif
                                                @if ($showReceiptDate)
                                                    <div class="col-md-6">
                                                        @if ($Modify)
                                                            <livewire:date-input name="RECEIPT_DATE"
                                                                titleName="{{ $TITLE_DATE }}"
                                                                wire:model='RECEIPT_DATE' :isDisabled="false" />
                                                        @else
                                                            <livewire:date-input name="RECEIPT_DATE"
                                                                titleName="{{ $TITLE_DATE }}"
                                                                wire:model='RECEIPT_DATE' :isDisabled="true" />
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if (($ID == 0 && auth()->user()->date_enabled) || ($Modify && auth()->user()->date_enabled))
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=true wire:model='CODE' />
                                                    @endif
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    @if ($Modify && $AMOUNT == 0)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model='LOCATION_ID' />
                                                    @endif
                                                </div>


                                                @if ($showCardNo)
                                                    <div class="col-md-6">
                                                        @if ($Modify)
                                                            <livewire:text-input name="CARD_NO" titleName="Card No."
                                                                :isDisabled=false wire:model='CARD_NO' />
                                                        @else
                                                            <livewire:text-input name="CARD_NO" titleName="Card No."
                                                                :isDisabled=true wire:model='CARD_NO' />
                                                        @endif
                                                    </div>

                                                @endif

                                                @if ($showCardDateExpire)
                                                    <div class="col-md-6">
                                                        @if ($Modify)
                                                            <livewire:date-input name="CARD_EXPIRY_DATE"
                                                                titleName="Card Expired" wire:model='CARD_EXPIRY_DATE'
                                                                :isDisabled="false" />
                                                        @else
                                                            <livewire:date-input name="CARD_EXPIRY_DATE"
                                                                titleName="Card Expired" wire:model='CARD_EXPIRY_DATE'
                                                                :isDisabled="true" />
                                                        @endif
                                                    </div>
                                                @endif



                                                <div class="col-md-12">
                                                    @if ($Modify)
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=false wire:model='NOTES' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=true wire:model='NOTES' :vertical="false" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS == 0 || $STATUS == 16)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        wire:confirm='Want to cancel?'
                                                        class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>

                                                <button type="button" wire:click='getPosted()'
                                                    class="btn btn-sm btn-warning"
                                                    wire:confirm="Are you sure you want to post?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                </button>
                                            @endif
                                        @endif
                                        @if ($STATUS == 15)
                                            @can('customer.received-payment.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($STATUS != 16)
                                            @if ($ID > 0 && $STATUS > 0)
                                                <button type="button" wire:click='OpenJournal()'
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                </button>
                                                @can('customer.received-payment.create')
                                                    <a id="new" title="Create"
                                                        href="{{ route('customerspayment_create') }}"
                                                        class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New
                                                    </a>
                                                @endcan
                                            @endif
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
    <section class="content">
        <div class="container-fluid bg-light ">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav text-sm nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-item-tab" data-toggle="pill"
                                        href="#custom-tabs-four-item" role="tab"
                                        aria-controls="custom-tabs-four-item" aria-selected="true">
                                        <div class="text-xs"> Invoice List </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active " id="custom-tabs-four-item" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-item-tab">
                                    <div class="row"
                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                        <div class="col-md-12"
                                            @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                            @livewire('Payment.PaymentInvoices', ['PAYMENT_ID' => $ID, 'CUSTOMER_ID' => $CUSTOMER_ID, 'LOCATION_ID' => $LOCATION_ID, 'STATUS' => $STATUS, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6 text-left">

                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <label class="text-sm">Payment Applied:</label>
                                            <label
                                                class="text-primary text-lg">{{ number_format($AMOUNT_APPLIED, 2) }}</label>
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
    @livewire('AccountJournal.AccountJournalModal')
</div>
