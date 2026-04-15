<div id="printableContent">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">

                    @if (empty($LOGO_FILE))
                        <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="text-center">
                            <b class="print-address1 text-center">
                                {{ $REPORT_HEADER_1 }} <br />
                                {{ $REPORT_HEADER_2 }}<br />
                                {{ $REPORT_HEADER_3 }}</b>
                        </div>
                    @else
                        {{-- nothing customize --}}
                        <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif

                </div>
                <div class="col-md-12 text-center">
                    {{-- <div class="text-lg font-weight-bold pb-4">Date Period : {{ $PERIOD }}</div> --}}
                </div>
                <div class="col-md-12">
                    <table class="w-100 " border="1">
                        <thead class="">
                            <tr>
                                <th class="col-1 text-center">#</th>
                                <th class="">PATIENT NAME</th>
                                <th class="">LHIO NO.</th>
                                <th class="col-1 text-center ">DATE ADMITTED</th>
                                <th class="col-1 text-center ">DATE DISCHARGED</th>
                                <th class="col-1 text-center ">NO. OF TRMT.</th>
                                <th class="col-1 text-center ">PAID</th>
                                <th class="col-1 text-center ">WTAX</th>
                                <th class="col-1 text-center ">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($billList as $list)
                                @php
                                    $n = $n + 1;
                                    $TOTAL_TREATMENT = $TOTAL_TREATMENT + $list->NO_TREATMENT;
                                    $TOTAL_PAID = $TOTAL_PAID + $list->AMOUNT_PAID ?? 0;
                                    $TOTAL_TAX = $TOTAL_TAX + $list->TAX_AMOUNT ?? 0;
                                    $TOTAL_AMOUNT = $TOTAL_AMOUNT + $list->AMOUNT ?? 0;
                                @endphp
                                <tr>
                                    <th class="text-center  font-weight-normalf">{{ $n }}</th>
                                    <td class=" font-weight-normal"> &nbsp; {{ $list->PATIENT_NAME }}</td>
                                    <td class=" font-weight-normal"> &nbsp; {{ $list->LHIO_NO }}</td>
                                    <td class="text-center  font-weight-normal">
                                        &nbsp;{{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}
                                    </td>
                                    <td class="text-center  font-weight-normal">
                                        &nbsp;{{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                    <td class="text-center  font-weight-normal">{{ $list->NO_TREATMENT }}</td>
                                    <td class="text-right  font-weight-normal">
                                        {{ number_format($list->AMOUNT_PAID, 2) . ' ' }}</td>
                                    <td class="text-right  font-weight-normal">
                                        {{ number_format($list->TAX_AMOUNT, 2) }}</td>
                                    <td class="text-right  font-weight-normal">
                                        {{ number_format($list->AMOUNT, 2) . ' ' }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <table class="w-100 mt-4">

                        <tbody>
                            <tr>
                                <th></th>
                                <th class="text-danger ">No. Treatment : <span
                                        class="">{{ $TOTAL_TREATMENT }}</span></th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>

                                <th class="text-right  text-danger"> </th>
                                <th class="text-right  text-danger">Paid: {{ number_format($TOTAL_PAID, 2) }} </th>
                                <th class="text-right  text-danger">WTax: {{ number_format($TOTAL_TAX, 2) }} </th>
                                <th class="text-right  text-danger">Total: {{ number_format($TOTAL_AMOUNT, 2) }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12 mt-4">

                    <div class="row mt-4">
                        <div class="col-5">
                            <div class="form-group row ">
                                <div class="col-7 h5 text-center bottom-line">
                                    <strong>&nbsp; {{ $USER_NAME }}</strong>
                                </div>
                                <div class="col-7 text-center "><i>Prepared By :</i></div>
                            </div>
                        </div>
                        <div class="col-3">
                        </div>
                        <div class="col-4 text-right">
                            <div class="form-group row ">
                                <div class="col-12 text-center bottom-line h5"><b>{{ $DOCTOR_NAME }}</b></div>
                                <div class="col-12 text-center"><i>Received By:</i></div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group row  mt-4">
                                <div class="col-7 h5 text-left bottom-line">
                                    <strong class="" style="position:absolute; width:250px;">
                                        &nbsp; {{ $ADMINISTRATOR_NAME }}
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

@script
    <script>
        $wire.on('print', () => {
            var printContents = document.getElementById('printableContent').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });

        function printPageAndClose() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 100);
        }

        window.addEventListener('beforeprint', function() {
            printPageAndClose();
        });
    </script>
@endscript
