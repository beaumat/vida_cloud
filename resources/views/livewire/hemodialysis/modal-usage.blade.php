<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">Usage History </div>
                    <div class="modal-body">
                        <table class="table table-sm table-boreded table-hover">
                            <thead class="text-xs bg-sky">
                                <th>Date</th>
                                <th class="text-center">New</th>
                                <th class="text-center">Usaged</th>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr
                                        class="@if ($list->IS_NEW) text-success @else text-secondary @endif">
                                        <td>{{ $list->DATE }}</td>

                                        @if ($list->IS_NEW)
                                            <td class="text-center"> {{ number_format($list->QUANTITY, 2) }}</td>
                                            <td class="text-center"></td>
                                        @else
                                            <td class="text-center"></td>
                                            <td class="text-center"> {{ number_format($list->QUANTITY, 2) }}</td>
                                        @endif



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" wire:click='create()' class="btn btn-success btn-sm">Create</button> --}}
                        <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
