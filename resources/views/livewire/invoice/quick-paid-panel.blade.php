<div>


    @if ($showModal)
        <div class="modal show" id="modal-md" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-md" role="document"
                style="width: 70%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Invoice Details</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                    <form id="quickForm" wire:submit.prevent='save'>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-3">
                                    <div class="card text-xs">
                                        <div class="card-header">
                                            <div class="card-title">
                                                Invoice No. : <b>{{ $CODE }}</b>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4"><label>Date : </label></div>
                                                <div class="col-8 text-primary">{{ date('M/d/Y', strtotime($DATE)) }}
                                                </div>
                                                <div class="col-4"><label>LHIO # : </label></div>
                                                <div class="col-8 text-primary">{{ $PO_NUMBER }}</div>
                                                <div class="col-4"><label>Due Date : </label></div>
                                                <div class="col-8 text-primary">
                                                    {{ date('M/d/Y', strtotime($DUE_DATE)) }}</div>
                                                <div class="col-4 "><label>Name : </label></div>
                                                <div class="col-8 text-primary">{{ $NAME }}</div>
                                                <div class="col-4"><label>Amount : </label></div>
                                                <div class="col-8 text-primary">{{ number_format($AMOUNT, 2) }}</div>
                                                <div class="col-4"><label>Balance : </label></div>
                                                <div class="col-8 text-primary">{{ number_format($BALANCE_DUE, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                <div class="col-3">
                                    @if ($isPhilHealth)
                                        <div class="card text-xs">
                                            <div class="card-header">
                                                <div class='card-title'>
                                                    Philhealth Summary
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class='row'>
                                                    <div class="col-4">
                                                        <label>SOA NO.: </label>
                                                    </div>
                                                    <div class='col-8 text-info'>
                                                        {{ $PH_CODE }}
                                                    </div>
                                                    <div class="col-4">
                                                        <label>Admitted: </label>
                                                    </div>
                                                    <div class='col-8 text-info'>
                                                        {{ date('M/d/Y', strtotime($PH_DATE_ADMITTED)) }}
                                                    </div>
                                                    <div class="col-4">
                                                        <label>Discharged:</label>
                                                    </div>
                                                    <div class='col-8 text-info'>
                                                        {{ date('M/d/Y', strtotime($PH_DATE_DISCHARGED)) }}
                                                    </div>
                                                    <div class="col-4">
                                                        <label>Doctor: </label>
                                                    </div>
                                                    <div class='col-8 text-info'>
                                                        {{ $PH_DOCTOR_NAME }}
                                                    </div>
                                                    <div class="col-4">
                                                        <label>PF Rate: </label>
                                                    </div>
                                                    <div class='col-8 text-info'>
                                                        {{ number_format($DOCTOR_FEE, 2) }}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    {{-- payment --}}
                                    <div class="card text-xs">
                                        <div class="card-header">
                                            <div class="card-title">
                                                Make Payment
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-2 text-right">
                                                    WTax :
                                                </div>
                                                <div class="col-10">
                                                    <select class="form-control form-control-sm w-50"
                                                        wire:model.live='TAX_ID'>
                                                        <option value='0'></option>
                                                        @foreach ($taxList as $list)
                                                            <option value='{{ $list->ID }}'>{{ $list->NAME }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-2 text-right">
                                                    Less :
                                                </div>
                                                <div class="col-10">
                                                    <label
                                                        class='text-sm text-danger'>-{{ number_format($AMOUNT_WITHHELD, 2) }}</label>
                                                </div>
                                                <div class="col-2 text-right">
                                                    Gross :
                                                </div>
                                                <div class="col-10">
                                                    <input type="number" step="0.01" wire:model='PAYMENT_AMOUNT'
                                                        class="form-control form-control-sm w-50" />
                                                </div>
                                                <div class="col-2 text-right">
                                                    ACPN:
                                                </div>
                                                <div class="col-10">
                                                    <div class="row mt-1">
                                                        <div class="col-12">
                                                            @if ($refreshComponent)
                                                                <livewire:select-option name="PAYMENT_PERIOD_ID1"
                                                                    titleName="" :options="$paymentPeriodList" :zero="true"
                                                                    :isDisabled="false" :withLabel="false"
                                                                    wire:model='PAYMENT_PERIOD_ID' />
                                                            @else
                                                                <livewire:select-option name="PAYMENT_PERIOD_ID2"
                                                                    titleName="" :options="$paymentPeriodList" :zero="true"
                                                                    :isDisabled="false" :withLabel="false"
                                                                    wire:model='PAYMENT_PERIOD_ID' />
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-2"></div>
                                                <div class="col-10">
                                                    @livewire('PaymentPeriod.PaymentPeriodModal', ['LOCATION_ID' => $LOCATION_ID])
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                            <button type="button" class="btn btn-secondary btn-sm"
                                wire:click="closeModal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
