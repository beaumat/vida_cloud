<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Account Code asdasd</th>
                <th class="col-3">Account Name</th>
                <th class="col-1 text-right">Amount</th>
                <th class="col-1 text-center">Tax</th>
                <th class="col-3">Particular</th>
                <th class="col-2">Class</th>
                @if ($STATUS == 0 || $STATUS == 16)
                    <th class="text-center col-1">Action</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($expenses as $list)
                <tr>
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->NAME }}</td>
                    <td class="text-right">
                        @if ($editExpensesId === $list->ID)
                            <input step="0.01" type="number" class="form-control form-control-sm" wire:model='lineAmount'
                                name="lineAmount" />
                        @else
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($editExpensesId === $list->ID)
                            <input type="checkbox" class="text-lg mt-1" wire:model='lineTaxable' name="lineTax" />
                        @else
                            @if ($list->TAXABLE)
                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($editExpensesId == $list->ID)
                            <input wire:model='lineParticulars' name="partiuclaredit" type="text"
                                class="form-control form-control-sm" />
                        @else
                            {{ $list->PARTICULARS }}
                        @endif

                    </td>
                    <td>
                        @if ($editExpensesId === $list->ID)
                            <select wire:model='lineClassId' name="CLASS_ID_Edit"
                                class="text-sm form-control form-control-sm">
                                <option value="0"></option>
                                @foreach ($classList as $listitem)
                                    <option value="{{ $listitem->ID }}">{{ $listitem->NAME }}</option>
                                @endforeach
                            </select>
                        @else
                            {{ $list->CLASS_NAME }}
                        @endif
                    </td>
                    @if ($STATUS == $openStatus || $STATUS == 16)
                        <td class="text-center">
                            @if ($editExpensesId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="updateExpenses({{ $list->ID }})"
                                    class="btn btn-xs btn-success">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="cancelExpenses()"
                                    class="btn btn-xs btn-warning">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn{{ $list->ID }}" type="button"
                                    wire:click="editExpenses( {{ $list->ID }})"
                                    class="btn btn-xs btn-info">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn" wire:click='deleteExpenses({{ $list->ID }})'
                                    wire:confirm="Are you sure you want to delete this?" class="btn btn-xs btn-danger">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach

            {{-- INSERT FORM --}}
            @if ($STATUS == $openStatus || $STATUS == 16)
              
                    <tr wire:loading.attr='disabled'>
                        <td>
                            @if ($saveSuccess)
                                @if ($codeBase)
                                    <livewire:select-option name="ACCOUNT_ID1" titleName="Account Code"
                                        :options="$acctCodeList" :zero="true" wire:model.live='ACCOUNT_ID'
                                        isDisabled="{{ false }}" :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_CODE }}</label>
                                @endif
                            @else
                                @if ($codeBase)
                                    <livewire:select-option name="ACCOUNT_ID2" titleName="Account Code"
                                        :options="$acctCodeList" :zero="true" wire:model.live='ACCOUNT_ID'
                                        isDisabled="{{ false }}" :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_CODE }}</label>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if ($saveSuccess)
                                @if (!$codeBase)
                                    <livewire:select-option name="ACCOUNT_ID3" titleName="Account Description"
                                        isDisabled="{{ false }}" :options="$acctDescList" :zero="true"
                                        wire:model.live='ACCOUNT_ID' :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_DESCRIPTION }}</label>
                                @endif
                            @else
                                @if (!$codeBase)
                                    <livewire:select-option name="ACCOUNT_ID4" titleName="Account Description"
                                        :options="$acctDescList" :zero="true" wire:model.live='ACCOUNT_ID'
                                        isDisabled="{{ false }}" :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-1"> {{ $ACCOUNT_DESCRIPTION }}</label>
                                @endif
                            @endif
                        </td>
                        <td> 
                            <livewire:number-input name="AMOUNT" titleName="" :vertical="false" wire:model="AMOUNT" isDisabled="{{ false }}" :withLabel="false" />
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="text-lg" wire:model='TAXABLE' name="taxable"
                                @if ($ACCOUNT_ID == 0) disabled @endif />
                        </td>
                        <td class="text-left">
                            <input type="text" class="form-control form-control-sm" wire:model='PARTICULARS'
                                name="PARTICULARS" />
                        </td>
                        <td>
                            @if ($saveSuccess)
                                @if (!$codeBase)
                                    <livewire:select-option name="CLASS_ID1" titleName="" :options="$classList"
                                        :zero="true" wire:model.live='CLASS_ID' :vertical="false"
                                        isDisabled="{{ false }}" :withLabel="false" />
                                @endif
                            @else
                                @if (!$codeBase)
                                    <livewire:select-option name="CLASS_ID2" titleName="" :options="$classList"
                                        :zero="true" wire:model.live='CLASS_ID' :vertical="false"
                                        isDisabled="{{ false }}" :withLabel="false" />
                                @endif
                            @endif

                        </td>
                        <td>
                            <div class="mt-1">
                                <button type="button" wire:click='saveExpenses()' wire:loading.attr='hidden'
                                    @if ($ACCOUNT_ID != 0) disabled @endif
                                    class="text-white btn bg-sky btn-sm w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>

                    </tr>

         
            @endif

        </tbody>

    </table>
    @if ($STATUS == $openStatus || $STATUS == 16)
        <livewire:custom-check-box name="codeBaseAcct" titleName="Use choose account code"
            isDisabled="{{ false }}" wire:model.live='codeBase' />
    @endif
</div>