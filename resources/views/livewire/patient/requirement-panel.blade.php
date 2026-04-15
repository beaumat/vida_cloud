<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="row">
        <div class="col-md-12 col-12">
            <table class="table table-sm table-bordered table-hover">
                <thead class="text-xs bg-sky">
                    <tr>
                        <th class="col-2 col-md-2 text-left">Description</th>
                        <th class="col-1 col-md-1 text-center">Completed</th>
                        <th class="col-1 col-md-1 text-center">Not-Applicable</th>
                        <th class="col-8 col-md-8">Document Files</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($dataList as $list)
                        <tr>
                            <td>{{ $list->DESCRIPTION }}</td>
                            <td class="text-center"
                                @if ($list->FILE_CONFIRM_DATE) style="opacity: 0.5;pointer-events: none;" @endif>
                                @livewire('Patient.RequirementPanelComplete', ['ID' => $list->ID, 'VALUE' => $list->IS_COMPLETE, 'CONTACT_ID' => $CONTACT_ID], key('complete-' . $list->ID))
                            </td>
                            <td class="text-center"
                                @if ($list->FILE_CONFIRM_DATE) style="opacity: 0.5;pointer-events: none;" @endif>
                                @livewire('Patient.RequirementPanelNa', ['ID' => $list->ID, 'VALUE' => $list->NOT_APPLICABLE, 'CONTACT_ID' => $CONTACT_ID], key( 'na-' . $list->ID))
                            </td>
                            <td>
                                @if ($list->IS_COMPLETE)
                                    @livewire('Patient.RequirementUpload', ['ID' => $list->ID, 'FILE_PATH' => $list->FILE_PATH, 'FILE_NAME' => $list->FILE_NAME, 'FILE_CONFIRM_DATE' => $list->FILE_CONFIRM_DATE], key('upload-' . $list->ID))
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    {{-- @can('contact.patient.update')
                        <tr>
                            <td></td>
                            <td>
                                <button type='button' wire:click='markAsCompleted()' class="btn btn-xs btn-info w-100">
                                    Check All </button>
                            </td>
                            <td>
                                <button type='button' wire:click='markAsNotApplicable()'
                                    class="btn btn-xs btn-warning w-100"> Check All</button>
                            </td>
                            <td></td>
                        </tr>
                    @endcan --}}

                </tbody>
            </table>
        </div>
    </div>
</div>
