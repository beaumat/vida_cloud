<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">LHIO Form</div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <livewire:text-input name="CODE" titleName="SOA No." :isDisabled=true
                                    wire:model='CODE' />
                            </div>
                            <div class="col-md-6">
                                <livewire:date-input name="DATE" titleName="Date Created" wire:model='DATE'
                                    :isDisabled=true />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <livewire:date-input name="AR_DATE" titleName="Date Transmit" wire:model='AR_DATE'
                                    isDisabled="{{ $isPaid }}" />
                            </div>

                            <div class="col-md-6">
                                <livewire:text-input name="AR_NO" titleName="LHIO No."
                                    isDisabled="{{ $isPaid }}" wire:model='AR_NO' />
                            </div>
                        </div>
                        @if ($INVOICE_CODE)
                            <div class="form-group row text-xs">
                                <div class="col-12">Invoice Details</div>
                                <div class="col-md-6">
                                    <a target="_blank" href="{{ route('customersinvoice_edit', ['id' => $INVOICE_ID]) }}">Ref# :
                                        {{ $INVOICE_CODE }}</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    Amount : {{ number_format($INVOICE_AMOUNT, 2) }}
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">

                                </div>
                                <div class="col-6 text-right">
                                    @if (!$isPaid)
                                        <button type="button" wire:click='save()' class="btn btn-success btn-sm">
                                            Save
                                        </button>
                                    @endif

                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">
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
