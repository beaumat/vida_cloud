<div class="font-weight-light p-3">
    <div class="row">
        <div class="col-12 blackbox2">
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class='row'>
                <div class="col-12">
                    <span style='font-size:17px' class="font-weight-bold"> 10. Accreditation Number/Name of Accredited
                        Health Care
                        Professional/Date Signed and Professional Fees/Charges </span>
                </div>
                <div class="col-12">
                    <span class="p-0" style="margin-left:10px;">(Use additional CF2 if necessary):</span>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <div style="margin:10px;"> Accreditation number/Name of Accredited Health Care
                                        Professional/Date
                                        Signed</div>
                                </div>
                                <div class="col-6 text-center">
                                    <div style="margin:10px;"> Details</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <div class="form-group row">
                                        <div class="col-12 mb-1" style="height: 10px; position:absolute;left:-70px; ">
                                            <span> Accreditation No.: </span>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 0, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 1, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 2, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 3, 1) }}
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 4, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 5, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 6, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 7, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 8, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 9, 1) }}
                                            </div>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 10, 1) }}
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box   font-weight-bold">
                                                {{ substr($HCP_1_AN, 11, 1) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row pt-3">
                                            <div class="col-2"></div>
                                            <div class="col-8 bottom-line2 text-center">
                                                <span class="font-weight-bold h5">{{ $HCP_1_NAME }}</span>
                                            </div>
                                            <div class="col-2"></div>
                                        </div>
                                        <span class="text-sm">Signature Over Printed Name</span>
                                        <div class="row " style="margin-left:50px; margin-bottom:-16px;">
                                            <div class="col-3 text-right">
                                                <span class="text-sm"> Date Signed:</span>
                                            </div>
                                            <div class="col-9 text-left">
                                                <div class="form-group text-md" style="width:300px;">
                                                    <div class="box   font-weight-bold">
                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 5, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box   font-weight-bold">

                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 6, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box   font-weight-bold">
                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 8, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 9, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box   font-weight-bold">
                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 0, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 1, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 2, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        @if ($DATE_DISCHARGED)
                                                            {{ substr($DATE_DISCHARGED, 3, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <br>
                                                    <p style="position: absolute;top:27px;" class="text-xs">
                                                        &nbsp; &nbsp;month
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;day
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp; &nbsp;&nbsp;
                                                        year</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-left">
                                    <div class="row" style="top:20px;position:relative;">
                                        <div class="col-12">
                                            <div class="box   font-weight-bold">
                                                &#10004;
                                            </div> &nbsp;
                                            No co-pay on top of PhilHealth Benefit
                                        </div>
                                        <div class="col-12">

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div> &nbsp;
                                                    <span style="position:absolute;top:5px;width:300px;"> With co-pay on
                                                        top of
                                                        PhilHealth Benefit</span>
                                                </div>
                                                <div class="col-1">
                                                    <span style="position:absolute;right:0px;">P</span>
                                                </div>
                                                <div class="col-5 ">
                                                    <div class='form-group bottom-line2'>
                                                        <span>
                                                            &nbsp;
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <div class="form-group row">
                                        <div class="col-12 mb-1" style="height: 10px; position:absolute;left:-70px; ">
                                            <span> Accreditation No.: </span>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row pt-3">
                                            <div class="col-2"></div>
                                            <div class="col-8 bottom-line2">
                                                &nbsp;
                                            </div>
                                            <div class="col-2"></div>
                                        </div>
                                        <span class="text-sm">Signature Over Printed Name</span>

                                        <div class="row " style="margin-left:50px; margin-bottom:-16px;">

                                            <div class="col-3 text-right">
                                                <span class="text-sm"> Date Signed:</span>
                                            </div>
                                            <div class="col-9 text-left">
                                                <div class="form-group text-md" style="width:300px;">
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">

                                                        &nbsp;
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <br>
                                                    <p style="position: absolute;top:27px;" class="text-xs">
                                                        &nbsp; &nbsp;month
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;day
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp; &nbsp;&nbsp;
                                                        year</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-left">
                                    <div class="row" style="top:20px;position:relative;">
                                        <div class="col-12">
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div> &nbsp;&nbsp;
                                            No co-pay on top of PhilHealth Benefit
                                        </div>
                                        <div class="col-12">

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div> &nbsp;
                                                    <span style="position:absolute;top:5px;width:300px;">
                                                        With co-pay on top of PhilHealth Benefit
                                                    </span>
                                                </div>
                                                <div class="col-1">
                                                    <span style="position:absolute;right:0px;">P</span>
                                                </div>
                                                <div class="col-5 ">
                                                    <div class='form-group bottom-line2'>
                                                        <span>
                                                            &nbsp;
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <div class="form-group row">
                                        <div class="col-12 mb-1" style="height: 10px; position:absolute;left:-70px; ">
                                            <span> Accreditation No.: </span>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row pt-3">
                                            <div class="col-2"></div>
                                            <div class="col-8 bottom-line2">
                                                &nbsp;
                                            </div>
                                            <div class="col-2"></div>
                                        </div>
                                        <span class="text-sm">Signature Over Printed Name</span>

                                        <div class="row " style="margin-left:50px; margin-bottom:-16px;">

                                            <div class="col-3 text-right">
                                                <span class="text-sm"> Date Signed:</span>
                                            </div>
                                            <div class="col-9 text-left">
                                                <div class="form-group text-md" style="width:300px;">
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div>
                                                    <br>
                                                    <p style="position: absolute;top:27px;" class="text-xs">
                                                        &nbsp; &nbsp;month
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;day
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp; &nbsp;&nbsp;
                                                        year</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-left">
                                    <div class="row" style="top:20px;position:relative;">
                                        <div class="col-12">
                                            <div class="box   font-weight-bold">
                                                &nbsp;
                                            </div> &nbsp;&nbsp;
                                            No co-pay on top of PhilHealth Benefit
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="box   font-weight-bold">
                                                        &nbsp;
                                                    </div> &nbsp;
                                                    <span style="position:absolute;top:5px;width:300px;"> With co-pay
                                                        on
                                                        top of
                                                        PhilHealth Benefit</span>
                                                </div>
                                                <div class="col-1">
                                                    <span style="position:absolute;right:0px;">P</span>
                                                </div>
                                                <div class="col-5 ">
                                                    <div class='form-group bottom-line2'>
                                                        <span>
                                                            &nbsp;
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-center font-weight-light bgBlack pb-2">
            <b class="text-white arial font-weight-bold" style="font-size: 18px">
                PART III - CERTIFICATION OF CONSUMPTION OF BENEFITS AND CONSENT TO ACCESS PATIENT RECORD/S
            </b><br />
            <span class="text-white">NOTE: Member/Patient should sign only after the applicable charges have
                been filled-out</span>
        </div>
        <div class="col-12 ubox2 font-weight-light">
            <label style='font-size:17px;height:20px;'> A. CERTIFICATION OF CONSUMPTION OF BENEFITS:</label>
            <div class="row">
                <div class="col-12">
                    <table class="width:100%">
                        <thead>
                            <tr>
                                <td style='width:1%' class="text-center">
                                    <div class="box   font-weight-bold"> &nbsp; </div>
                                </td>
                                <td style='width:25%'>
                                    <span> PhilHealth benefit is enough to cover HCI and PF Charges.</span>
                                </td>
                                <td style='width:1%'></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td> No purchase of drugs/medicines, supplies, diagnostics, and co-pay for professional
                                    fees by the member/patient.</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12">
                    <table class="width:100%">
                        <thead>
                            <tr>
                                <td style='width:1%' class="text-center">

                                </td>
                                <td style='width:25%'>
                                    <div class="form-group">
                                        <table border='1' stlye="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <td style='width: 15%' class="text-center">&nbsp;</td>
                                                    <td style='width: 15%' class="text-center"> Total Actual Charges*
                                                        </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> <span class="ml-1"> Total Health Care Institution
                                                            Fees</span> </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> <span class="ml-1"> Total Professional Fees </span> </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> <span class="ml-1"> Grand Total</span> </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td style='width:1%'></td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-12">
                    <table class="width:100%">
                        <tr>
                            <td style='width:1%' class="text-center">
                                <div class="box   font-weight-bold"> &#10004; </div>
                            </td>
                            <td style='width:25%'> The benefit of the member/patient was completely consumed prior to
                                co-pay OR the
                                benefit of
                                the member/patient is not completely consumed BUT with</th>
                            <td style='width:1%'></td>
                        </tr>
                        <tbody>
                            <tr>
                                <td></td>
                                <td> purchases/expenses for drugs/medicines, supplies, diagnostics and others.</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td> </td>
                                <td>a.) The total co-pay for the following are:</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12">
                    <table>
                        <thead>
                            <tr>
                                <td style='width:1.5%' class="text-center">
                                </td>
                                <td style='width:25%'>
                                    <div class="form-group">
                                        <table border='1' stlye=" width: 100%;">
                                            <thead>
                                                <tr>
                                                    <td class="text-center" style="width:160px;">&nbsp;</td>
                                                    <td class="text-center" style="width:200px;"> Total Actual
                                                        Charges* </td>
                                                    <td class="text-center"style="width:250px;">Amount after
                                                        Application <br /> of
                                                        Discount (i.e., personal<br /> discount, Senior Citizen/PWD)
                                                    </td>
                                                    <td class="text-center" style="width:160px;">PhilHealth Benefit
                                                    </td>
                                                    <td class="text-center"> Amount after PhilHealth Deduction </td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="pl-1"> <span> Total Health Care <br /> Institution
                                                            Fees</span>
                                                    </td>
                                                    <td class="text-center  font-weight-bold">
                                                        {{ number_format($CHARGES_SUB_TOTAL, 2) }}</td>
                                                    <td class="text-center  font-weight-bold">
                                                        {{ number_format($P1_SUB_TOTAL, 2) }}</td>
                                                    <td class="text-center  font-weight-bold">
                                                        {{ number_format($P1_SUB_TOTAL, 2) }}</td>
                                                    <td class="pl-1">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-3 text-left"
                                                                        style="width:300px;position:relative;">
                                                                        <span>Amount &nbsp;&nbsp;P</span>
                                                                    </div>
                                                                    <div class="col-8 bottom-line2">
                                                                        <span>&nbsp;</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <span> Paid by (check all that applies):</span>
                                                            </div>
                                                            <div class="col-12">
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp; Member/Patient
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </span>
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp; HMO
                                                                </span>
                                                            </div>
                                                            <div class="col-12">
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp;&nbsp;Others (i.e., PCSO, Promisory note,
                                                                    etc.)
                                                                </span>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="pl-1">
                                                        <span> Total Professional <br /> Fees (for
                                                            accredited
                                                            <br /> and
                                                            non-accredited <br /> professionals)</span>
                                                    </td>
                                                    <td class="text-center font-weight-bold">
                                                        {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL, 2) }}</td>
                                                    <td class="text-center  font-weight-bold">
                                                        {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}</td>
                                                    <td class="text-center  font-weight-bold">
                                                        {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}</td>
                                                    <td class="pl-1">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-3 text-left"
                                                                        style="width:300px;position:relative;">
                                                                        <span>Amount &nbsp;&nbsp;P</span>
                                                                    </div>
                                                                    <div class="col-8 bottom-line2">
                                                                        <span>&nbsp;</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <span> Paid by (check all that applies):</span>
                                                            </div>
                                                            <div class="col-12">
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp; Member/Patient
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </span>
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp; HMO
                                                                </span>
                                                            </div>
                                                            <div class="col-12">
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp;&nbsp;Others (i.e., PCSO, Promisory note,
                                                                    etc.)
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td style='width:1.5%'></td>
                            </tr>
                        </thead>
                    </table>


                    <div class="row">
                        <div class="col-1 text-right"></div>

                        <div class="col-1 text-right">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <table class="width:100%">
                        <thead>
                            <tr>
                                <td style='width:1%' class="text-center">
                                    &nbsp;
                                </td>
                                <td style='width:25%'>
                                    <div class="form-group">
                                        <span>
                                            b.) Purchases/Expenses <b>NOT</b> included in the Health Care Institution
                                            Charges
                                        </span>
                                        <table border='1' stlye="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <td style='width:25%' class="text-left pl-1">
                                                        <span> Total cost of purchase/s for drugs/medicines and/or
                                                            medical
                                                            supplies bought by the <br />patient/member
                                                            within/outside the HCI
                                                            during confinement</span>
                                                    </td>
                                                    <td style=' width: 15%'>
                                                        <div class="row">
                                                            <div class="col-4 text-center">
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp; None
                                                                </span>
                                                            </div>
                                                            <div class="col-8">
                                                                <div class="row">
                                                                    <div class="col-5 text-left">
                                                                        <span>
                                                                            <div
                                                                                class="box   font-weight-bold">
                                                                                &nbsp; </div> &nbsp;
                                                                            <span
                                                                                style="top:10px;width:500px;position:absolute;">Total
                                                                                Amount</span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span
                                                                            style="top:10px;position:relative">P</span>
                                                                    </div>
                                                                    <div class="col-5 bottom-line2">
                                                                        <span>&nbsp;</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr>
                                                    <td class="pl-1"> <span>
                                                            Total cost of diagnostic/laboratory examinations paid by the
                                                            patient/member done <br />
                                                            within/outside the HCI during confinement</span>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-4 text-center">
                                                                <span>
                                                                    <div
                                                                        class="box   font-weight-bold">
                                                                        &nbsp;
                                                                    </div>
                                                                    &nbsp; None
                                                                </span>
                                                            </div>
                                                            <div class="col-8">
                                                                <div class="row">
                                                                    <div class="col-5 text-left">
                                                                        <span>
                                                                            <div
                                                                                class="box   font-weight-bold">
                                                                                &nbsp; </div> &nbsp;
                                                                            <span
                                                                                style="top:10px;width:500px;position:absolute;">Total
                                                                                Amount</span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span
                                                                            style="top:10px;position:relative">P</span>
                                                                    </div>
                                                                    <div class="col-5 bottom-line2">
                                                                        <span>&nbsp;</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                        <span>
                                            <b>* NOTE</span>: Total Actual Charges should be based on Statement
                                        of Account
                                        (SOA)
                                        </span>
                                    </div>
                                </td>
                                <td style='width:1%'> &nbsp;</td>
                            </tr>
                        </thead>
                    </table>
                </div>


                <div class="col-12">
                    <label style='font-size:17px;height:20px;'> B. CONSENT TO ACCESS PATIENT RECORD/S:</label>
                    <div class="form-group px-2">
                        <i class="font-weight-bold" style="color:#ccc">
                            I hereby consent to the submission and examination of the patient’s pertinent medical
                            records
                            for the purpose of verifying the veracity of this claim to effect
                            efficient processing of benefit payment.<br />
                            I hereby hold PhilHealth or any of its officers, employees and/or representatives free from
                            any
                            and all legal liabilities relative to the herein-mentioned consent
                            which I have voluntarily and willingly given in connection with this claim for reimbursement
                            before PhilHealth.
                        </i>
                        <div class="row">
                            <div class="col-7">
                                <div class="form-group row">
                                    <div class="col-10">
                                        <div class="form-group">
                                            <div class="bottom-line2 text-center">
                                                <span class=" font-weight-bold h5">
                                                    {{ $MEMBER_FIRST_NAME }} @if ($MEMBER_MIDDLE_NAME)
                                                        {{ substr($MEMBER_MIDDLE_NAME, 0, 1) }}.
                                                        @endif {{ $MEMBER_LAST_NAME }} @if ($MEMBER_EXTENSION)
                                                            {{ $MEMBER_EXTENSION . '.' }}
                                                        @endif
                                                        &nbsp;
                                                </span>
                                            </div>
                                            <div class="text-center">
                                                Signature Over Printed Name of Member/Patient/Authorized Representative
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-3 text-right">
                                                    <span> Date Signed:</span>
                                                </div>
                                                <div class="col-9 text-left">
                                                    <div class="form-group text-md" style="width:300px;">
                                                        <div class="box   font-weight-bold">
                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 5, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <div class="box   font-weight-bold">

                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 6, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <label class="px-1">&nbsp;-</label>
                                                        <div class="box   font-weight-bold">
                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 8, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <div class="box   font-weight-bold">
                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 9, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <label class="px-1">&nbsp;-</label>
                                                        <div class="box   font-weight-bold">
                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 0, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <div class="box   font-weight-bold">
                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 1, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <div class="box   font-weight-bold">
                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 2, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <div class="box   font-weight-bold">
                                                            @if ($DATE_DISCHARGED)
                                                                {{ substr($DATE_DISCHARGED, 3, 1) }}
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </div>
                                                        <br>
                                                        <p style="position: absolute;top:27px;" class="text-xs">
                                                            &nbsp; &nbsp;month
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            &nbsp;day
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            &nbsp; &nbsp;&nbsp;
                                                            year</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-left">
                                            Relationship of the representative to <br /> the member/patient
                                        </p>
                                        <p class="mt-2 text-left ">
                                            Reason for signing on behalf of the <br /> member/patient
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="box   font-weight-bold">&nbsp;</div>
                                            &nbsp;&nbsp;Spouse&nbsp;
                                            <div class="box   font-weight-bold">&nbsp;</div>
                                            &nbsp;&nbsp;Child&nbsp;
                                            <div class="box   font-weight-bold">&nbsp;</div>
                                            &nbsp;&nbsp;Parent<br />
                                            <div class="box   font-weight-bold">&nbsp;</div>
                                            &nbsp;&nbsp;Sibling&nbsp;&nbsp;
                                            <div class="box   font-weight-bold"
                                                style="margin-left:1px;">&nbsp;</div>&nbsp;&nbsp;Others,
                                            Specify&nbsp;________________<br />
                                            <div class="box   font-weight-bold">&nbsp;</div>
                                            &nbsp;&nbsp;Patient is incapacitated&nbsp;&nbsp;<br />
                                            <div class="box   font-weight-bold">&nbsp;</div>
                                            &nbsp;&nbsp;Other reasons:&nbsp;&nbsp; ______________________
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group mt-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="font-weight-light">
                                                If patient/representative <br />
                                                is unable to write, put <br />
                                                right thumbmark. Patient/<br />
                                                Representative should be <br />
                                                assisted by an HCI representative.

                                            </span>
                                            <div class='row' style="margin-top:60px;">
                                                <div class="col-12">
                                                    <div class="box   font-weight-bold"> &nbsp;
                                                    </div> &nbsp; <span> Patient</span>

                                                </div>
                                                <div class="col-12">
                                                    <div class="box   font-weight-bold"> &nbsp;
                                                    </div> &nbsp; <span> Representative</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-4">
                                            <div class='row'>
                                                <div class="col-12 bottom-line top-line left-line right-line"
                                                    style="height:150px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center font-weight-light bgBlack pb-1 pt-1">
                    <b class="text-white arial font-weight-bold" style="font-size: 18px">
                        PART IV - CERTIFICATION OF CONSUMPTION OF HEALTH CARE INSTITUTION
                    </b>
                </div>
                <div class="col-12 text-center">
                    <div class="font-weight-bold mt-2"><i> I certify that services rendered were recorded in the
                            patient’s chart and
                            health care institution records and that the herein information given are true and
                            correct.</i>
                    </div>
                </div>
                <div class="col-12 text-center mb-2" >
                    <div class="row" style="position: relative;bottom:10px;">
                        <div class="col-5  text-center">
                            <div class="ml-2 mr-2 mt-3">
                                <div class="row">
                                    <div class="col-12 text-center"style="height: 25px; ">
                                        <b class="h5 times-new-roman"><strong > {{ $HCI_NAME }} &nbsp;
                                            </strong></b>
                                    </div>
                                    <div class="col-12 text-center top-line2 ">
                                        Signature Over Printed Name of Authorized HCI Representative
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-3 text-center">
                            <div class="ml-2 mr-2 mt-3">
                                <div class="row">
                                    <div class="col-12 text-center"style="height: 25px; ">
                                        <b class="h5 times-new-roman"><strong> {{ $HCI_POSITION }} &nbsp;
                                            </strong></b>
                                    </div>
                                    <div class="col-12 text-center top-line2 ">
                                        Official Capacity/Designation
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="row mt-3" style="margin-left:20px;">
                                <div class="col-4 text-right">
                                    <span> Date Signed:</span>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="form-group text-md" style="width:300px;">
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 5, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">

                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 6, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 8, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 9, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <label class="px-1">&nbsp;-</label>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 0, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 1, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 2, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <div class="box   font-weight-bold">
                                            @if ($DATE_DISCHARGED)
                                                {{ substr($DATE_DISCHARGED, 3, 1) }}
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>
                                        <br>
                                        <p style="position: absolute;top:27px;" class="text-xs">
                                            &nbsp; &nbsp;month
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;day
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp; &nbsp;&nbsp;
                                            year</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
