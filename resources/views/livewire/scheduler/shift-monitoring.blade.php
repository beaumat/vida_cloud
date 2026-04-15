<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-body">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class='bg-sky text-xs'>
                                <tr>
                                    <th class="col-1">No.</th>
                                    <th class="col-11">Patient Name</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr
                                        class="@if ($list['CONTACT_ID'] == $CONTACT_ID) font-weight-bolder @endif">
                                        <td class="{{ $list['EXTRA_CLASS'] }}">
                                            {{ $list['ID'] }}</td>
                                        <td>{{ $list['NAME'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6">
                            <label> SHIFT : {{ $SHIFT_NAME }} </label>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" wire:click='closeModal()'
                                class="btn btn-danger btn-sm">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
