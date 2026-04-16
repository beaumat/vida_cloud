<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content text-left">
                    <div class="modal-body">
                        <div class="form-group">
                            <span class="text-xs">Pre-sign output</span> 
                            <input type="checkbox" wire:model.live='BASE_PRESIGN' />
                        </div>
                        <div class="form-group">
                           
                            <table class="table table-sm table-bordered">
                                <thead class="text-xs">
                                    <tr class="bg-sky">
                                        <th class='col-4'>Print </th>
                                        <th class="col-8"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">

                                    <tr>
                                        <td>SOA No. :</td>
                                        <td>{{ $CODE }}</td>
                                    </tr>

                                    <tr>
                                        <td>Date Created :</td>
                                        <td>{{ date('m/d/Y', strtotime($DATE)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Patient Name :</td>
                                        <td>{{ $PATIENT_NAME }}</td>
                                    </tr>
                                    <tr>
                                        <td>Date Admitted :</td>
                                        <td>{{ date('m/d/Y', strtotime($DATE_ADMITTED)) }}</td>
                                    </tr>

                                    <tr>
                                        <td>Date Discharged :</td>
                                        <td>{{ date('m/d/Y', strtotime($DATE_DISCHARGED)) }}</td>
                                    </tr>

                                    <tr>
                                        <td>No of Treatment :</td>
                                        <td>{{ $HEMO_TOTAL }}</td>
                                    </tr>
                                    <tr>
                                        <td>First Case Amount: </td>
                                        <td>{{ number_format($FIRST_CASE, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Diagnosis :</td>
                                        <td>{{ $FINAL_DIAGNOSIS }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                     <div class='modal-footer'>
    <div class="w-100 text-center">

        <!-- ALL BUTTONS -->
        <div class="d-inline-flex flex-wrap justify-content-center">

            @if ($BASE_PRESIGN)
                <a target="_BLANK"
                    href="{{ route('patientsprintout_soa_temp_out', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-primary m-1">
                    <i class="fa fa-print mr-1"></i> SOA
                </a>

                <a target="_BLANK"
                    href="{{ route('patientsprintout_summary_temp_out', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-primary m-1">
                    <i class="fa fa-print mr-1"></i> Treatment
                </a>
            @else
                <a target="_BLANK"
                    href="{{ route('patientsprintout_soa', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-primary m-1">
                    <i class="fa fa-print mr-1"></i> SOA
                </a>

                <a target="_BLANK"
                    href="{{ route('patientsprintout_summary', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-primary m-1">
                    <i class="fa fa-print mr-1"></i> Treatment
                </a>
            @endif

            @if ($BASE_PRESIGN)
                <a target="_BLANK"
                    href="{{ route('patientsprintout_csf_temp_out', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-info m-1">
                    <i class="fa fa-print mr-1"></i> CSF
                </a>
            @else
                <a target="_BLANK"
                    href="{{ route('patientsprintout_csf', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-info m-1">
                    <i class="fa fa-print mr-1"></i> CSF
                </a>

                <a target="_BLANK"
                    href="{{ route('patientsprintout_cf4', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-info m-1">
                    <i class="fa fa-print mr-1"></i> CF4
                </a>

                <a target="_BLANK"
                    href="{{ route('patientsprintout_cf2', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-info m-1">
                    <i class="fa fa-print mr-1"></i> CF2
                </a>

               @if ( in_array((int)$LOCATION_ID, [39, 40, 51, 36, 38]))
                <a target="_BLANK"
                    href="{{ route('patientsprintout_ncr', ['id' => $PHILHEALTH_ID]) }}"
                    class="btn btn-sm btn-info m-1">
                    <i class="fa fa-print mr-1"></i> BPN Consent Form
                </a>
            @endif

               
            @endif

            <!-- CLOSE BUTTON (same row) -->
            <button type="button" wire:click='closeModal()'
                class="btn btn-danger btn-sm m-1">
                Close
            </button>

        </div>

    </div>
</div>
                </div>
            </div>
        </div>
    @endif
</div>
