<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportspatient_sales_report') }}"> Doctor PF Report
                        </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-sm btn-primary" wire:click='Generate()'>Filter</button>
                            </div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class='col-md-6  text-right'>
                                                <label class="text-xs pt-2">Location:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif name="location" wire:model.live='LOCATION_ID'
                                                    class="form-control form-control-sm text-xs mt-1">
                                                    <option value="0"> All Location</option>
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                            </div>
                            <div class="col-6 text-right">
                                {{-- <button class="btn btn-sm btn-success " wire:click='export()'> Export </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8" style="max-height: 80vh; overflow-y: auto;">

                    <table class="table table-sm table-bordered table-hover">
                        <thead class='text-xs bg-sky'>
                            <tr>
                                <th>Nephro Name</th>
                                <th class="col-2 text-center">No. Treatment</th>
                                <th class="col-2 text-right">PF Amount</th>
                                <th class="col-2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($doctorList as $list)
                                <tr>
                                    <td> {{ $list->DOCTOR_NAME }} </td>
                                    <td class="text-center">{{ $list->NO_TREAT }}</td>
                                    <th class="text-right">{{ number_format($list->TOTAL, 2) }}</th>
                                    <td>
                                        <button class="btn btn-primary btn-xs"
                                            wire:click='openList({{ $list->DOCTOR_ID }})'>Preview</button>


                                        <a target="_BLANK"
                                            href="{{ route('reportspatient_doctor_fee_report_print', ['id' => $list->DOCTOR_ID, 'locationid' => $LOCATION_ID]) }}"
                                            class="btn btn-xs btn-danger"> Print </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>



                </div>

            </div>
        </div>
    </section>

    @livewire('PatientReport.DoctorsFeeReportForm')
</div>
