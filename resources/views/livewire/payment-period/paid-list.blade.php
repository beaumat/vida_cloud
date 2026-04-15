<?php
use App\Services\OtherServices;
?>
<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="">Date Transmitted</th>
                <th class="col-1">LHIO #</th>
                <th class="col-2">Patient</th>
                <th class="">Admitted</th>
                <th class="">Discharge</th>
                <th class="col-1">No. of Treatment</th>
                <th class="col-">Confinement Period</th>
                <th class="text-right">First Case Amt </th>
                <th class="text-right">Paid Amt</th>
                <th class="text-right">WTax Amt</th>
                <th class="text-right">PF Amt</th>
                <th class="">Doctor Name</th>
                <th class="">Delete</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @php
                $TOTAL_INVOICE = 0;
                $TOTAL_TAX = 0;
                $TOTAL_PAID = 0;
                $TOTAL_PF = 0;
            @endphp
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($list->AR_DATE)->format('m/d/Y') }}</td>
                    <td> <a target="_blank"
                            href="{{ route('patientsphic_edit', ['id' => $list->PHILHEALTH_ID]) }}">{{ $list->AR_NO }}</a>
                    </td>
                    <td>{{ $list->PATIENT_NAME }}</td>
                    <td>{{ \Carbon\Carbon::parse($list->DATE_ADMITTED)->format('m/d/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($list->DATE_DISCHARGED)->format('m/d/Y') }}</td>
                    <td class="text-center">{{ $list->HEMO_TOTAL }}</td>

                    <td class=""> {{ OtherServices::formatDates($list->CONFINE_PERIOD) }}</td>
                    <td class="text-right">{{ number_format($list->INVOICE_AMOUNT, 2) }} </td>
                    <td class="text-right"><a  style="cursor: pointer;color:rgb(255, 0, 221);text-decoration:underline;" target="_blank"
                            href="{{ route('customerspayment_edit', ['id' => $list->PAYMENT_ID]) }}">{{ number_format($list->PAYMENT_AMOUNT, 2) }}</a>
                    </td>

                    <td class="text-right"><strong wire:click='callTaxCreditByPaymentID({{ $list->PAYMENT_ID }})'
                            style="cursor: pointer;color:blue;text-decoration:underline;">

                            {{ number_format($list->TAX_AMOUNT, 2) }}</strong></td>
                    <td class="text-right text-info">{{ number_format($list->BILL_AMOUNT, 2) }}</td>
                    <td class="text-info">{{ $list->DOCTOR_NAME }}</td>
                    <td><button class="btn btn-xs btn-danger w-100" wire:confirm='Are you sure to delete this paid?'
                            wire:click='DeletePaid({{ $list->PAYMENT_ID }})'>
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button></td>
                    @php
                        $TOTAL_INVOICE += $list->INVOICE_AMOUNT;
                        $TOTAL_PAID += $list->PAYMENT_AMOUNT;
                        $TOTAL_TAX += $list->TAX_AMOUNT;
                        $TOTAL_PF += $list->BILL_AMOUNT;
                    @endphp

                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-primary font-weight-bold text-right">Grand Total</td>
                <td class="text-primary font-weight-bold text-right">{{ number_format($TOTAL_INVOICE, 2) }}</td>
                <td class="text-danger font-weight-bold text-right">{{ number_format($TOTAL_PAID, 2) }}</td>
                <td class="text-danger font-weight-bold text-right">{{ number_format($TOTAL_TAX, 2) }}</td>
                <td class="text-info font-weight-bold text-right">{{ number_format($TOTAL_PF, 2) }}</td>
                <td class="text-orange font-weight-bold text-right">

                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-orange font-weight-bold text-right">Difference</td>
                <td class="text-orange font-weight-bold text-right">
                    {{ number_format($TOTAL_INVOICE - $GROSS_TOTAL, 2) }}</td>
                <td class="text-danger font-weight-bold text-right"></td>
                <td class="text-danger font-weight-bold text-right"></td>
                <td class="text-danger font-weight-bold text-right"></td>
                <td class="text-orange font-weight-bold text-right">

                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
