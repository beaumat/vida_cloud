<?php
use App\Services\UserServices;
?>

<section class="content">
    <!-- Default box -->
    @if ($HEMO_ID > 0)
        <div class="card">
            <div class="card-body p-2">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-info ">
                                <tr>
                                    <th class="col-1" wire:click='OpenJournal()'>Code</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th class="col-1 text-center">Qty</th>
                                    <th class="col-1 text-center">Unit</th>
                                    <th class="col-1 text-center">Post</th>
                                    {{-- <th class="col-1 text-center">Justify <br/> Notes</th> --}}
                                    @if ($STATUS == $openStatus || UserServices::GetUserRightAccess('patient.treatment.update'))
                                        <th class="col-2 text-center" wire:click='gotJournal'
                                            wire:confirm="Make Journal ?">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr
                                        class="@if ($list->IS_CASHIER) font-weight-bold text-success @else font-weight-normal text-dark @endif">
                                        <td> {{ $list->CODE }} </td>
                                        <td> {{ $list->DESCRIPTION }} </td>
                                        <td> {{ $list->CLASS_NAME }} </td>

                                        <td class="text-center">
                                            @if ($lineId == $list->ID)
                                                <input type="number" step="0.01" class="w-100 text-xs text-right"
                                                    name="lineQty" wire:model='lineQty' />
                                            @else
                                                @if ($list->NO_OF_USED > 1)
                                                    <a wire:click='OpenUsageHistory({{ $list->ITEM_ID }})'
                                                        href="#" class="font-weight-bold text-info">
                                                        {{ number_format($list->QUANTITY, 0) }}
                                                    </a>
                                                @else
                                                    {{ number_format($list->QUANTITY, 0) }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $list->SYMBOL }}
                                        </td>
                                        <td class="text-center">
                                            @if ($list->IS_POST)
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        {{-- <td class="text-center">
                                            @if ($list->IS_JUSTIFY)
                                                <i type="button" onclick="alert('Justification: {{ $list->JUSTIFY_NOTES }}')" class="fa fa-envelope fa-2x text-primary" aria-hidden="true"></i>
                                            @endif
                                        </td> --}}
                                        @if ($STATUS == $openStatus || $STATUS == 4 || UserServices::GetUserRightAccess('patient.treatment.update'))
                                            <td class="text-center">
                                                @if ($lineId == $list->ID)
                                                    <button type="button" title="Update" id="updatebtn"
                                                        wire:click="updateItem()" class="btn btn-xs btn-success">
                                                        <i class="fas fa-check" aria-hidden="true"></i>
                                                    </button>
                                                    <button type="button" title="Cancel" id="cancelbtn" href="#"
                                                        wire:click="cancelItem()" class="btn btn-xs btn-warning">
                                                        <i class="fas fa-ban" aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    @if ($CAN_BE_EDIT)
                                                        <button type="button" title="Edit" id="editbtn"
                                                            wire:click='editItem({{ $list->ID }})'
                                                            class="btn btn-xs btn-primary">
                                                            <i class="fas fa-edit " aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                    @if ($list->IS_DEFAULT && $list->SK_LINE_ID == 0)
                                                        <button type="button" title="Delete" id="deletebtn"
                                                            wire:click='deleteItem({{ $list->ID }}, {{ $list->ITEM_ID }})'
                                                            wire:confirm="Are you sure you want to delete this?"
                                                            class="btn btn-xs btn-danger">
                                                            <i class="fas fa-trash " aria-hidden="true"></i>
                                                        </button>
                                                    @elseif ($list->IS_CASHIER && $list->SK_LINE_ID == 0)
                                                        <button type="button" title="Delete" id="deletebtn"
                                                            wire:click='deleteItemInCash({{ $list->ID }}, {{ $list->ITEM_ID }})'
                                                            wire:confirm="Are you sure you want to delete this?"
                                                            class="btn btn-xs btn-danger">
                                                            <i class="fas fa-trash " aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                        @if (UserServices::GetUserRightAccess('patient.treatment.update'))
                                                            <button type="button" title="RePost" id="repost"
                                                                wire:click='rePost({{ $list->ID }})'
                                                                wire:confirm="Are you sure you want to unpost this particular item?"
                                                                class="btn btn-xs btn-warning">
                                                                <i class="fas fa-tools "
                                                                    aria-hidden="true"></i></button>
                                                        @else
                                                            <button type="button" title="Delete" id="repost"
                                                                class="btn btn-xs btn-secondary">
                                                                <i class="fas fa-trash "
                                                                    aria-hidden="true"></i></button>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        @if ($ActiveRequired)

                            @if ($STATUS == $openStatus || $STATUS == 4)


                                @foreach ($ItemRequiredList as $list)
                                    <button wire:click='addItem({{ $list->ID }})'
                                        class="btn btn-warning btn-sm m-1">
                                        <i class="fas fa-plus " aria-hidden="true"></i> {{ $list->ITEM_NAME }}
                                    </button>
                                @endforeach
                                @foreach ($subClassList as $list)
                                    <button wire:click='openSubClass({{ $list->ID }})'
                                        class="btn btn-success active btn-sm m-1">
                                        <i class="fas fa-star " aria-hidden="true"></i> {{ $list->DESCRIPTION }}
                                    </button>
                                @endforeach
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endif
    @livewire('Hemodialysis.OtherCharges', ['HEMO_ID' => $HEMO_ID])
    @livewire('AccountJournal.AccountJournalModal')
</section>
