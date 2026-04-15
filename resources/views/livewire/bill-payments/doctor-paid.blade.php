<?php
use App\Services\OtherServices;
?>


<div>

    <button wire:click="openModal" class="btn btn-success btn-sm text-xs ">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Billing List</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div class="container-flud">
                            <div class="form-group">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-sky text-xs ">

                                        <tr>

                                            <th class="col-4">Patient</th>
                                            <th class="col-2">Confine Period </th>
                                            <th class="col-1">First Case Amt.</th>
                                            <th class="col-1">Date</th>
                                            <th class="col-1">Bill No.</th>
                                            <th class="col-1">Amount</th>
                                            <th class="col-1">Balance</th>
                                            <th class="col-1">Action</th>
                                        </tr>


                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach ($invoiceList as $list)
                                            <tr>
                                                <td>{{ $list->PATIENT_NAME }}</td>
                                                <td>{{ OtherServices::formatDates($list->CONFINE_PERIOD) }}</td>
                                                <td class="text-right">{{ number_format($list->P1_TOTAL, 2) }}</td>
                                                <td>{{ date('m/d/Y', strtotime($list->DATE)) }} </td>
                                                <td>{{ $list->CODE }}</td>
                                                <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                                <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }} </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-success w-100"
                                                        wire:click="addItem('{{ $list->ID }}', '{{ $list->BALANCE_DUE }}')"
                                                        wire:loading.attr='disabled'
                                                        wire:target="addItem('{{ $list->ID }}', '{{ $list->BALANCE_DUE }}')">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>

                                                    <div wire:loading
                                                        wire:target="addItem('{{ $list->ID }}', '{{ $list->BALANCE_DUE }}')">
                                                        <span class='spinner'></span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="container-fluid">
                            <div class='row'>
                                <div class="col-6">
                                    {{-- <label class="text-sm text-primary"> Payment Applied same as Deposit Amount</label>
                                    <input type="checkbox" name="SAME_AMOUNT" class="text-lg"
                                        wire:model='SAME_AMOUNT' /> --}}
                                </div>
                                <div class="col-6 text-right">
                                    {{-- <button type="button" class="btn btn-success btn-sm" wire:click="save">Add</button> --}}
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        wire:click="closeModal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
