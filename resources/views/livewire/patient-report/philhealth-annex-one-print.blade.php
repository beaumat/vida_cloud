<div class="content-wrapper" id="printableContent">
    <style>
        @media print {
            @page {
                size: legal landscape;
                /* Letter size in landscape */

                /* Sets the paper size to Legal */
                /* Custom long size: width 8.5in (letter width), length 14in */
                /* margin: 0.5in; */
                /* Adjust margins as desired */
                margin-left: 10px;
                margin-right: 10px;
                margin-top: 20px;
                margin-bottom: 10px;
            }

        }
    </style>
    <div class="row">
        <div class="col-12 text-center mb-4">

            @if (empty($LOGO_FILE))
                <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                <div class="text-center">
                    <b class="print-address1 text-center">
                        {{ $REPORT_HEADER_1 }} <br />
                        {{ $REPORT_HEADER_2 }} <br />
                        {{ $REPORT_HEADER_3 }}</b>
                </div>
            @else
                {{-- nothing customize --}}
                <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
            @endif

        </div>
        <div class="col-12">
            <table border="1">
                <thead style="font-size: 11px;">
                    <tr>
                        <th>ITEM NO.</th>
                        <th>CLAIMS CODE/ REFERENCE NUMBER</th>
                        <th>PATIENT SURNAME</th>
                        <th>PATIENT FIRSTNAME</th>
                        <th>PATIENT MIDDLENAME</th>
                        <th>MEMBERS SURNAME</th>
                        <th>MEMBERS FIRSTNAME</th>
                        <th>MEMBERS MIDDLENAME</th>
                        <th>MEMBER'S PIN</th>
                        <th class="col-1">MEMBER'S CATEGORY</th>
                        <th>DATE OF ADMISSION</th>
                        <th>DATE OF DISCHARED</th>
                        <th>ICD-10 CODE/ RVS</th>
                        <th>CASE RATE/ AMOUNT</th>
                    </tr>
                </thead>
                <tbody style="font-size: 10px;">
                    @php

                        $r = 0;
                        $TOTAL = 0;
                    @endphp
                    @foreach ($dataList as $list)
                        @php
                            $r++;
                        @endphp
                        <tr>
                            <td>{{ $r }}</td>
                            <td>{{ $list->CLAIM_NO }}</td>
                            <td>{{ $list->LAST_NAME }}</td>
                            <td>{{ $list->FIRST_NAME }}</td>
                            <td>{{ $list->MIDDLE_NAME }}</td>
                            @if ($list->IS_PATIENT)
                                <td>{{ $list->LAST_NAME }}</td>
                                <td>{{ $list->FIRST_NAME }}</td>
                                <td>{{ $list->MIDDLE_NAME }}</td>
                            @else
                                <td>{{ $list->MEMBER_LAST_NAME }}</td>
                                <td>{{ $list->MEMBER_FIRST_NAME }}</td>
                                <td>{{ $list->MEMBER_MIDDLE_NAME }}</td>
                            @endif
                            <td>{{ $list->PIN_NO }}</td>
                            <td>{{ $list->CLASS }}</td>
                            <td>{{ date('M/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                            <td>{{ date('M/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>

                            <td>90935</td>
                            <td class="text-right">{{ number_format($list->P1_TOTAL, 2) }}</td>
                            @php
                                $TOTAL += $list->P1_TOTAL;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="font-weight-bold">TOTAL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ number_format($TOTAL, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="col-12">


            <div class="row mt-4" style="font-size: 11px; ">
                <div class="col-4">
                    Prepared by:
                    <div class="form-group row ">
                        <div class="col-7 text-center bottom-line">
                            <strong>
                                &nbsp;{{ $EMPLOYEE_NAME }}
                            </strong>
                        </div>
                        <div class="col-7 text-center"><i>{{ $EMPLOYEE_POSITION }}</i></div>
                    </div>

                </div>
                <div class="col-4">
                    <div class="form-group row ">
                        <div class="col-12">Certified Complete and Accurate</div>
                        <div class="col-7 text-center bottom-line">
                            <strong>
                                &nbsp;{{ $MANAGER_NAME }}
                            </strong>
                        </div>
                        <div class="col-7 text-center"><i>Administrator</i></div>
                    </div>

                </div>
                <div class="col-4">
                    <div class="form-group row ">
                        <div class="col-12">Approved By:</div>
                        <div class="col-7 text-center bottom-line"><strong class="">
                                &nbsp;{{ $APPROVED_BY }}
                            </strong>
                        </div>
                        <div class="col-7 text-center"><i>Medical Director/ Chief of Hospital</i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
