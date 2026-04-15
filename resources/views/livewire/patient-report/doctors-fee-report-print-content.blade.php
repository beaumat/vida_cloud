<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                    <div class="text-center">
                        <b class="print-address1 text-center">
                            RDL Building F. Torres Street, Davao City <br />
                            Telephone #:285-2403; Mobile #: 09258678600/9175041322 <br />
                            Email:avidadavao.torres@yahoo.com.ph</b>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="w-100 " border="1">
                        <thead class="">    
                            <tr>
                                <th class="col-1 h5 text-center">#</th>
                                <th class="col-3 h5">PATIENT NAME</th>
                                <th class="col-2 text-center h5">DATE ADMIITED</th>
                                <th class="col-2 text-center h5">DATE DISCHARGED</th>
                                <th class="col-1 text-center h5">NUMBER OF TREATMENT</th>
                                <th class="col-2 text-center h5">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataList as $list)
                                @php
                                    $n = $n + 1;
                                    $TOTAL_TREATMENT = $TOTAL_TREATMENT + $list->NO_TREAT;
                                    $TOTAL_AMOUNT = $TOTAL_AMOUNT + $list->TOTAL;
                                @endphp
                                <tr>
                                    <th class="text-center h5 font-weight-normalf">{{ $n }}</th>
                                    <td class="h5 font-weight-normal"> &nbsp; {{ $list->PATIENT_NAME }}</td>
                                    <td class="text-center h5 font-weight-normal">
                                        &nbsp;{{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}
                                    </td>
                                    <td class="text-center h5 font-weight-normal">
                                        &nbsp;{{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                    <td class="text-center h5 font-weight-normal">{{ $list->NO_TREAT }}</td>
                                    <td class="text-right h5 font-weight-normal">
                                        {{ number_format($list->TOTAL, 2) }} &nbsp;</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <table class="w-100 mt-4">
                        <thead>
                            <tr>
                                <th class="col-1 h5"></th>
                                <th class="col-3 h5"></th>
                                <th class="col-2 text-center h5"></th>
                                <th class="col-2 text-center h5"></th>
                                <th class="col-1 text-center h5"></th>
                                <th class="col-2 text-center h5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th class="text-center h5 text-danger">TOTAL</th>
                                <th class="text-center h5 text-danger">{{ $TOTAL_TREATMENT }} &nbsp;&nbsp;</th>
                                <th class="text-right h5 text-danger">{{ number_format($TOTAL_AMOUNT, 2) }}&nbsp;&nbsp;
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12 mt-4">

                    <div class="row mt-4">
                        <div class="col-4">
                            <div class="form-group row ">
                                <div class="col-7 h5 text-center bottom-line">
                                    <strong> {{ $USER_NAME }}</strong>
                                </div>
                                <div class="col-7 text-center "><i>Prepered By :</i></div>
                            </div>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-4 text-right">
                            <div class="form-group row ">
                                <div class="col-12 text-center bottom-line h5"><b>{{ $DOCTOR_NAME }}</b></div>
                                <div class="col-12 text-center"><i>Received By:</i></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group row  mt-4">
                                <div class="col-7 h5 text-left bottom-line">
                                    <strong class="" style="position:absolute; width:250px;">
                                        {{ $ADMINISTRATOR_NAME }}
                                    </strong>
                                    <br />
                                </div>
                                <div class="col-7 text-center"><i>Noted By:</i></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
