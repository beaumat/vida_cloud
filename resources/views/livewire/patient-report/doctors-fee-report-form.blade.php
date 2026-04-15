<div>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> NEPHRO : {{ $DOCTOR_NAME }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <table @if ($isDisabled == true) style="opacity: 0.5;pointer-events: none;" @endif
                            class="table table-sm table-bordered table-hover">
                            <thead class="bg-sky text-xs">
                                <tr>
                                    <th>Patient Name</th>
                                    <th class="col-1 ">Date Admiited</th>
                                    <th class="col-1 ">Date Discharged</th>
                                    <th class="col-2 text-center">No. of Treatment</th>
                                    <th class="col-1 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->PATIENT_NAME }}</td>
                                        <td>{{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                        <td>{{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                        <td class="text-center">{{ $list->NO_TREAT }}</td>
                                        <td class="text-right">{{ number_format($list->TOTAL, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a target="_BLANK"
                            href="{{ route('reportspatient_doctor_fee_report_print', ['id' => $DOCTOR_ID,'locationid' => $LOCATION_ID]) }}"
                            class="btn btn-sm btn-danger"> Print </a>

                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
