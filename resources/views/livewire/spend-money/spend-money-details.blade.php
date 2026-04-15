<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Account Code</th>
                <th class="col-3">Account Name</th>
                <th class="col-1 text-right">Amount</th>
                <th class="col-3">Particular</th>
                @if ($STATUS == 0 || $STATUS == 16)
                    <th class="text-center col-1">Action</th>
                @endif

            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ $list->ACCOUNT_CODE }}</td>
                    <td>{{ $list->ACCOUNT_NAME }}</td>
                    <td class="text-right">
                        @if ($editID === $list->ID)
                            <input step="0.01" type="number" class="form-control form-control-sm"
                                wire:model='editAMOUNT' name="editAMOUNT" />
                        @else
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>

                    <td>
                        @if ($editID === $list->ID)
                            <input wire:model='editNOTES' name="editNOTES" type="text"
                                class="form-control form-control-sm" />
                        @else
                            {{ $list->NOTES }}
                        @endif

                    </td>

                    @if ($STATUS == 0 || $STATUS == 16)
                        <td class="text-center">
                            @if ($editID === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="update()"
                                    class="btn btn-xs btn-success">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" wire:click="editCancel()"
                                    class="btn btn-warning btn-xs">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn" wire:click="edit( {{ $list->ID }})"
                                    class="btn btn-xs btn-info">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn" wire:click='delete({{ $list->ID }})'
                                    wire:confirm="Are you sure you want to delete this?" class=" btn btn-xs btn-danger">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach

            {{-- INSERT FORM --}}
            @if ($STATUS == 0 || $STATUS == 16)
                <form wire:submit.prevent='save' wire:loading.attr='disabled'>
                    <tr>
                        <td>
                            @if ($saveSuccess)
                                @if ($codeBase)
                                    <livewire:select-option name="ACCOUNT_ID1" titleName="Account Code"
                                        :options="$acctCodeList" :zero="true" wire:model.live='ACCOUNT_ID'
                                        :isDisabled=false :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_CODE }}</label>
                                @endif
                            @else
                                @if ($codeBase)
                                    <livewire:select-option name="ACCOUNT_ID2" titleName="Account Code"
                                        :options="$acctCodeList" :zero="true" wire:model.live='ACCOUNT_ID'
                                        :isDisabled=false :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_CODE }}</label>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if ($saveSuccess)
                                @if (!$codeBase)
                                    <livewire:select-option-type name="ACCOUNT_ID3" titleName="Account Description"
                                        :options="$acctDescList" :zero="true" wire:model.live='ACCOUNT_ID'
                                        :isDisabled=false :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_DESCRIPTION }}</label>
                                @endif
                            @else
                                @if (!$codeBase)
                                    <livewire:select-option-type name="ACCOUNT_ID4" titleName="Account Description"
                                        :options="$acctDescList" :zero="true" wire:model.live='ACCOUNT_ID'
                                        :isDisabled=false :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_DESCRIPTION }}</label>
                                @endif
                            @endif
                        </td>
                        <td>
                            <input step="0.01" type="number" class="form-control form-control-sm  text-right"
                                name="AMOUNT" wire:model='AMOUNT' />
                        </td>
                        <td class="text-left">
                            <input type="text" class="form-control form-control-sm " wire:model='NOTES'
                                name="NOTES" />
                        </td>

                        <td>
                            <div class="">
                                <button type="submit" wire:loading.attr='hidden'
                                    @if ($ACCOUNT_ID == 0) disabled @endif
                                    class="text-white btn bg-sky btn-sm w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </form>
            @endif
            <tr>
                <td></td>
                <td class='text-right'>

                </td>
                <td class='text-right'>
                    <label class='text-primary text-xs'>{{ number_format($TOTAL_AMOUNT, 2) }}</label>
                </td>
                <td></td>
                {{-- <td></td> --}}
                @if ($STATUS == 0 || $STATUS == 16)
                    <td></td>
                @endif
            </tr>
        </tbody>

    </table>
    @if ($STATUS == 0 || $STATUS == 16)
        <livewire:custom-check-box name="codeBaseAcct" titleName="Use choose account code" :isDisabled=false
            wire:model.live='codeBase' />
    @endif
</div>
