@php
    use App\Services\AccountServices;
@endphp
<div>



    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Make Entry {{ $DOC_NAME }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div class="container-flud">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky sticky-header">
                                    <tr>
                                        <th>ACCOUNT</th>
                                        <th>ACCT EXIST</th>
                                        <th>DATE</th>
                                        <th>SOURCE TYPE</th>
                                        <th class="col-3">DESCRIPTION</th>
                                        <th class="text-center ">REFERENCE</th>
                                        <th class="text-center ">DEBIT</th>
                                        <th class="text-center ">CREDIT</th>


                                    </tr>
                                </thead>

                                {{-- end of pending --}}
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->ACCOUNT }}</td>
                                            <td>
                                                @php
                                                    $accountExist = AccountServices::getAccountNameExist(
                                                        $list->ACCOUNT,
                                                    );
                                                @endphp

                                                @if ($accountExist)
                                                    <span class="text-success">Yes</span>
                                                @else
                                                    <span class="text-danger">No</span>
                                                @endif
                                            </td>
                                            <td>{{ $list->DATE }}</td>
                                            <td>{{ $list->SOURCE_TYPE }}</td>
                                            <td>{{ $list->DESCRIPTION }}</td>
                                            <td>{{ $list->REFERENCE }}</td>
                                            <td>{{ $list->DEBIT }}</td>
                                            <td>{{ $list->CREDIT }}</td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="container-flud">
                            <div class="row">
                                @if ($contactList)
                                    <div class="col-4 form-group">
                                        <livewire:select-option-type name="CONTACT_ID" titleName="Contact Name"
                                            :options="$contactList" :zero="true" :isDisabled="false"
                                            wire:model='CONTACT_ID' />
                                    </div>
                                @endif

                                @if ($accountList)
                                    <div class="col-4 form-group">
                                        <livewire:select-option-type name="ACCOUNT_ID" titleName="BANK ACCOUNT"
                                            :options="$accountList" :zero="true" :isDisabled="false"
                                            wire:model='ACCOUNT_ID' />
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="container-fluid">
                            <div class='row'>
                                <div class="col-6">

                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" class="btn btn-success btn-sm"
                                        wire:click="save()">Save</button>
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
