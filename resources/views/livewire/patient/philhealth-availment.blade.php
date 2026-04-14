@php
    use App\Services\OtherServices;
@endphp

<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    @if (empty($LOGO_FILE))
                        <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="print-address">
                            <b>{{ $REPORT_HEADER_1 }}</b>
                        </div>
                    @else
                        {{-- nothing customize --}}
                        <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif

                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 text-center">
                            <b class="h3">SUMMARY OF PHILHEALTH AVAILMENT</b>
                        </div>
                        <div class="col-6">

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div style="padding-top:100px;">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-10">
                                <div class="row text-lg">
                                    <div class="col-3">
                                        <label>Name of Patient </label>
                                    </div>
                                    <div class="col-1">:</div>
                                    <div class="col-8 font-weight-bold">
                                        {{ $CONTACT_NAME }}
                                    </div>
                                </div>
                                <div class="row text-lg">
                                    <div class="col-3">
                                        <label>PHIC No. </label>
                                    </div>
                                    <div class="col-1">:</div>
                                    <div class="col-8 font-weight-bold">
                                        {{ $PHIC_NO }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">
                            <div class="text-lg">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                This is to certify that the said patient has
                                been diagnosed CHRONIC KIDNEY DISEASE STAGE 5 SECONDARY TO {{ $FINAL_DIAGNOSIS }}, scheduled thrice a
                                week at <b>{{ $BRANCH_NAME }}</b> and has
                                <b>{{ $TOTAL_DAYS }}</b> days for the year {{ $YEAR }}.
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>

                </div>
                <div class="col-12">
                    <div class="row pt-4">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <table class="table table-borderless text-lg">
                                <tbody class="font-weight-bold">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td class="text-lg">{{ $list['ID'] }}</td>
                                            <td class="text-lg">
                                                @if ($list['DATES'])
                                                    {{ OtherServices::formatDays($list['DATES']) }},
                                                    {{ $YEAR }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 pt-4 pb-4">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-10 text-lg ">
                            Total Dialyzer - <b>{{ $TOTAL_ITEM }}</b>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-10 text-lg ">
                            Confinement from Other Hopitals/Facility - <b>{{ $TOTAL_OTHER }}</b>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-10 text-lg ">
                            Confinement at {{ $BRANCH_NAME }} - <b>{{ $TOTAL_MAIN }}</b>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-10 text-lg">
                            Total Philhealth Availment as of {{ date('M/d/Y', strtotime($DATE)) }} -
                            <b>{{ $TOTAL_OTHER + $TOTAL_MAIN }}</b>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <div class="text-lg pb-4">
                                This certification is issued to whatever purpose it may serve best the patient.
                            </div>
                            <div class="text-lg pt-4">
                                {{ $DONE_DATE }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-lg">
                    <div class="row mt-4">
                        <div class="col-1">

                        </div>
                        <div class="col-9">
                            <div class="form-group pb-4 mt-4">
                                Prepared by :
                            </div>
                            <div class="form-group pt-4">
                                <div>
                                    <b>{{ $USER_NAME }}</b>
                                </div>
                                <div>PHIC In-Charge</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
