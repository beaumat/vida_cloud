<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-3">Received From asdasd</th>
                <th class="col-3">Account</th>
                <th class="col-1">Payment Method</th>
                <th class="col-2">Check No.</th>
                <th class="col-2 text-right">Amount</th>
                @if ($STATUS == 0 || $STATUS == 16)
                    <th class="text-center col-1">Action</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>
                        @if ($editFundId === $list->ID)
                            <livewire:select-option name="RECEIVED_FROM_ID5" titleName="" :options="$contactList"
                                :zero="true" wire:model='editReceivedFromId' :isDisabled=false :vertical="false"
                                :withLabel="false" />
                        @else
                            {{ $list->RECEIVED_FROM_NAME }}
                        @endif
                    </td>
                    <td>
                        @if ($editFundId === $list->ID)
                            <livewire:select-option name="ACCOUNT_ID5" titleName="" :options="$accountList"
                                :zero="true" wire:model='editAccountId' :isDisabled=false :vertical="false"
                                :withLabel="false" />
                        @else
                            {{ $list->ACCOUNT_NAME }}
                        @endif
                    </td>
                    <td>
                        @if ($editFundId === $list->ID)
                            <livewire:select-option name="PAYMENT_METHOD_ID5" titleName="" :options="$paymentMethodList"
                                :zero="true" wire:model='editPaymentMethodId' :isDisabled=false :vertical="false"
                                :withLabel="false" />
                        @else
                            {{ $list->PAYMENT_METHOD }}
                        @endif
                    </td>
                    <td>
                        @if ($editFundId === $list->ID)
                            <input type="text" class="form-control form-control-sm text-left" name="CHECK_NO1"
                                wire:model='editCheckNo' maxlength='20' />
                        @else
                            {{ $list->CHECK_NO }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($editFundId === $list->ID)
                            <input step="0.01" type="number" class="form-control form-control-sm text-right"
                                name="AMOUNT1" wire:model='editAmount' />
                        @else
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif

                    </td>
                    @if ($STATUS == 0 || $STATUS == 16)
                        <td class="text-center">
                            @if ($editFundId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="UpdateFund()"
                                    class="btn btn-xs btn-success">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="CancelFund()"
                                    class="btn btn-xs btn-warning">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                @if ($list->SOURCE_OBJECT_ID == null)
                                    <button title="Edit" id="editbtn" wire:click='EditFund( {{ $list->ID }})'
                                        class="btn btn-xs btn-info">
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                    </button>
                                @endif
                                <button title="Delete" id="deletebtn" wire:click='DeleteFund({{ $list->ID }})'
                                    wire:confirm="Are you sure you want to delete this?" class="btn btn-xs btn-danger">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            @if ($STATUS == 0 || $STATUS == 16)
                <tr>
                    <form wire:submit.prevent='AddFund()' wire:loading.attr='disabled'>
                        <td>
                            @if ($saveSuccess)
                                <livewire:select-option name="RECEIVED_FROM_ID1" titleName="" :options="$contactList"
                                    :zero="true" wire:model='RECEIVED_FROM_ID' :isDisabled=false
                                    :vertical="false" :withLabel="false" />
                            @else
                                <livewire:select-option name="RECEIVED_FROM_ID2" titleName="" :options="$contactList"
                                    :zero="true" wire:model='RECEIVED_FROM_ID' :isDisabled=false
                                    :vertical="false" :withLabel="false" />
                            @endif
                        </td>
                        <td>
                            @if ($saveSuccess)
                                <livewire:select-option name="ACCOUNT_ID1" titleName="" :options="$accountList"
                                    :zero="true" wire:model.live='ACCOUNT_ID' :isDisabled=false :vertical="false"
                                    :withLabel="false" />
                            @else
                                <livewire:select-option name="ACCOUNT_ID2" titleName="" :options="$accountList"
                                    :zero="true" wire:model.live='ACCOUNT_ID' :isDisabled=false :vertical="false"
                                    :withLabel="false" />
                            @endif
                        </td>
                        <td>
                            @if ($saveSuccess)
                                <livewire:select-option name="PAYMENT_METHOD_ID1" titleName="" :options="$paymentMethodList"
                                    :zero="true" wire:model='PAYMENT_METHOD_ID' :isDisabled=false
                                    :vertical="false" :withLabel="false" />
                            @else
                                <livewire:select-option name="PAYMENT_METHOD_ID2" titleName="" :options="$paymentMethodList"
                                    :zero="true" wire:model='PAYMENT_METHOD_ID' :isDisabled=false
                                    :vertical="false" :withLabel="false" />
                            @endif
                        </td>

                        <td>
                            <input type="text" class="form-control form-control-sm text-left" name="CHECK_NO"
                                wire:model='CHECK_NO' maxlength='20' />

                        </td>
                        <td>
                            <input step="0.01" type="number" class="form-control form-control-sm text-right"
                                name="AMOUNT" wire:model='AMOUNT' />
                        </td>
                        <td>
                            <div>
                                <button type="submit" wire:loading.attr='hidden'
                                    @if ($ACCOUNT_ID == 0) disabled @endif
                                    class="btn btn-primary btn-xs w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>
                    </form>
                </tr>
            @endif
        </tbody>
    </table>

</div>
