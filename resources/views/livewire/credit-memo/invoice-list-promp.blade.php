<div>
    <button wire:click="openModal" class="btn btn-info btn-sm text-xs "
        @if ($AMOUNT == 0) style="opacity: 0.5;pointer-events: none;" @endif>
        Credit to invoice
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Credit to Invoice</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('CreditMemo.CreditMemoInvoiceList', ['CREDIT_MEMO_ID' => $CREDIT_MEMO_ID, 'CUSTOMER_ID' => $CUSTOMER_ID, 'LOCATION_ID' => $LOCATION_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED])

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
