<?php
use App\Services\UserServices;
?>
<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    <div class="row">
        <div class="col-md-6">
            @if (UserServices::GetUserRightAccess('customer.invoice.view') && UserServices::GetUserRightAccess('customer.invoice.create'))
                @if ($INVOICE_ID == 0)
                    {{-- <button class="btn btn-sm btn-success" wire:click='makeInvoice()'>Make Invoice</button> --}}
                @else
                    <a target="_BLANK" href="{{ route('customersinvoice_edit', ['id' => $INVOICE_ID]) }}"
                        class="btn btn-sm btn-success">
                        View Invoice</a>
                @endif


            @endif
        </div>

        <div class="col-md-6 text-right">
            <div class="row">
                <div class="col-md-8 text-right">
                    <label>Invoice :</label>
                </div>
                <div class="col-md-4 ">
                    <label class="text-info"> {{ number_format($INVOICE_AMOUNT, 2) }}</label>
                </div>
                <div class="col-md-8 text-right">
                    <label>Received Payment :</label>
                </div>
                <div class="col-md-4 ">
                    <label class="text-success">{{ number_format($RECEIVED_AMOUNT, 2) }}</label>
                </div>
                <div class="col-md-8 text-right">
                    <label>WTax :</label>
                </div>
                <div class="col-md-4 ">
                    <label class="text-success">{{ number_format($TAX_CREDIT_AMOUNT, 2) }}</label>
                </div>
                <div class="col-md-8 text-right">
                    <label> Balance :</label>
                </div>
                <div class="col-md-4 ">
                    <label class="text-danger">{{ number_format($INVOICE_BALANCE, 2) }}</label>
                </div>
            </div>
        </div>
    </div>

    @livewire('Invoice.MakeInvoice')
</div>
