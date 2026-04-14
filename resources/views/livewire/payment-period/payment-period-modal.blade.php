<div class="mt-1">
    <button wire:click="openModal()" type="button" class="btn btn-primary btn-xs text-xs">
        <i class="fa fa-plus" aria-hidden="true"></i> Make Payment Period
    </button>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content text-left">

                    <div class="modal-header">Make Payment Period</div>
                    <form id="quickForm" wire:submit.prevent='save'>
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-4 text-right">
                                    <label class="text-sm">OR Date :</label>
                                </div>
                                <div class="col-8">
                                    <input type="date" class="form-control form-control-sm" wire:model='DATE' />
                                </div>
                                <div class="col-4 text-right">
                                    <label class="text-sm">OR No. :</label>
                                </div>
                                <div class="col-8">
                                    <input type="text" maxlength='20' class="form-control form-control-sm"
                                        wire:model='RECEIPT_NO' />
                                </div>
                                <div class="col-4 text-right">
                                    <label class="text-sm">Date Period From :</label>
                                </div>
                                <div class="col-8">
                                    <input type="date" class="form-control form-control-sm" wire:model='DATE_FROM' />
                                </div>
                                <div class="col-4 text-right">
                                    <label class="text-sm">Date Period To :</label>
                                </div>
                                <div class="col-8">
                                    <input type="date" class="form-control form-control-sm" wire:model='DATE_TO' />
                                </div>
                                <div class="col-4 text-right">
                                    <label class="text-sm"> Total Payment :</label>
                                </div>
                                <div class="col-8">
                                    <input type="number" class="form-control form-control-sm"
                                        wire:model='TOTAL_PAYMENT' />
                                </div>
                                {{-- <div class="col-4 text-right">
                                    <label class="text-sm"> Total WTax :</label>
                                </div>
                                <div class="col-8">
                                    <input type="number" class="form-control form-control-sm"
                                        wire:model='TOTAL_WTAX' />
                                </div> --}}
                                <div class="col-4 text-right">
                                    <label class="text-sm"> Deposit to Bank :</label>
                                </div>
                                <div class="col-8">
                                    <select class="form-control form-control-sm" wire:model='BANK_ACCOUNT_ID'>
                                        <option value="0"> </option>
                                        @foreach ($accountList as $list)
                                            <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                            <button type="button" wire:click='closeModal()'
                                class="btn btn-secondary btn-sm">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
