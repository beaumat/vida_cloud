<div>
    <button wire:click="openModal" class="btn btn-info btn-sm text-xs "
        @if ($AMOUNT == 0) style="opacity: 0.5;pointer-events: none;" @endif>
        Bill Credit to Bills
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Bill Credit to Bills</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('BillCredit.BillCreditBillList', ['BILL_CREDIT_ID' => $BILL_CREDIT_ID, 'VENDOR_ID' => $VENDOR_ID, 'LOCATION_ID' => $LOCATION_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED])

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
