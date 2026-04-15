      <div class="container-fluid bg-light">

          <div class="row">
              <div class="col-12 mt-4">
                  <table class="w-100" border="1">
                      <tbody style="font-size:9px;">
                       <tr class="bgBlack text-white"  style="font-size:10px;">
                              <th>Patient</th>
                              <th>Item</th>
                              <th>(SC) Date</th>
                              <th>(SC) Code</th>
                              <th>(SC) Amount</th>
                              <th>(P) Date</th>
                              <th>(P) Code</th>
                              <th>(P) Method</th>
                              <th>(P) Deposit</th>
                              <th>(P) Paid </th>
                              <th>Running Bal.</th>
                              <th>Doctor</th>
                              <th>Location </th>
                          </tr>

                          @foreach ($dataList as $list)
                              {{-- LOGIC START --}}
                              @php
                                  if ($sc_code == $list->SC_CODE) {
                                      $is_sc = false;
                                  } else {
                                      $is_sc = true;
                                      $NO_OF_TREATMENT = $NO_OF_TREATMENT + 1;
                                  }

                                  if ($PREV_SC_ITEM_REF_ID == $list->SC_ITEM_REF_ID) {
                                      $not_to_charge = true;
                                  } else {
                                      $not_to_charge = false;
                                  }

                                  if ($tempName == $list->PATIENT_NAME) {
                                      $is_add = false;
                                      if ($not_to_charge == false) {
                                          $running_balance = $running_balance + $list->SC_AMOUNT ?? 0;
                                      }
                                  } else {
                                      $is_add = true;
                                      $is_sc = true;
                                      $running_balance = $list->SC_AMOUNT ?? 0;
                                      $NO_OF_PATIENT = $NO_OF_PATIENT + 1;
                                  }

                                  $running_balance = $running_balance - $list->PP_PAID;
                                  $tempName = $list->PATIENT_NAME;
                                  $sc_code = $list->SC_CODE;
                                  $PREV_SC_ITEM_REF_ID = $list->SC_ITEM_REF_ID ?? 0;
                              @endphp

                              @if ($is_add == true)
                                  <tr class="bgBlack" style="background-color:#000">
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
                                      <td></td>
                                      <td></td>
                                  </tr>
                              @endif
                              {{-- LOGIC END --}}
                              <tr class=" @if ($is_add == true) font-weight-bold @endif">
                                  <td>
                                      @if ($is_add == true)
                                          {{ $list->PATIENT_NAME }}
                                      @endif
                                  </td>
                                  <td>{{ $list->ITEM_NAME }}</td>
                                  <td>
                                      @if ($is_sc == true)
                                          <a target="_BLANK"
                                              href="{{ route('patientsservice_charges_edit', ['id' => $list->SC_ID]) }}">{{ $list->SC_CODE }}</a>
                                      @endif
                                  </td>
                                  <td>
                                      @if ($is_sc == true)
                                          {{ date('m/d/Y', strtotime($list->SC_DATE)) }}
                                      @endif
                                  </td>


                                  <td class="text-right">
                                      @if ($not_to_charge == false)
                                          {{ number_format($list->SC_AMOUNT, 2) }}
                                          @php
                                              $TOTAL_CHARGE = $TOTAL_CHARGE + $list->SC_AMOUNT ?? 0;
                                          @endphp
                                      @endif
                                  </td>
                                  <td class="@if ($list->PP_DATE) bgYellow @endif">
                                      @if ($list->PP_DATE)
                                          {{ date('m/d/Y', strtotime($list->PP_DATE)) }}
                                      @endif
                                  </td>
                                  <td class="@if ($list->PP_ID) bgYellow @endif">
                                      @if ($list->PP_ID)
                                          <a target="_BLANK"
                                              href="{{ route('patientspayment_edit', ['id' => $list->PP_ID]) }}">{{ $list->PP_CODE }}</a>
                                      @endif
                                  </td>
                                  <td class="@if ($list->PP_ID) bgYellow @endif">
                                      {{ $list->PAYMENT_METHOD }}</td>
                                  <td class="text-right @if ($list->PP_ID) bgYellow @endif">
                                      @if ($list->PP_DEPOSIT > 0)
                                          {{ number_format($list->PP_DEPOSIT, 2) }}
                                      @endif
                                  </td>
                                  <td class="text-right @if ($list->PP_ID) bgYellow @endif">
                                      @if ($list->PP_PAID > 0)
                                          {{ number_format($list->PP_PAID, 2) }}

                                          @php
                                              $TOTAL_PAID = $TOTAL_PAID + $list->PP_PAID ?? 0;

                                              if ($list->PAYMENT_METHOD_ID == 1) {
                                                  //Cash
                                                  $CASH_AMOUNT = $CASH_AMOUNT + $list->PP_PAID ?? 0;
                                              }

                                              if ($list->PAYMENT_METHOD_ID == 91) {
                                                  //Philhealth
                                                  $PHILHEALTH_AMOUNT = $PHILHEALTH_AMOUNT + $list->PP_PAID ?? 0;
                                              }

                                              if ($list->PAYMENT_METHOD_ID == 92) {
                                                  //DSWD
                                                  $DSWD_AMOUNT = $DSWD_AMOUNT + $list->PP_PAID ?? 0;
                                              }

                                              if ($list->PAYMENT_METHOD_ID == 93) {
                                                  //LINGAP
                                                  $LINGAP_AMOUNT = $LINGAP_AMOUNT + $list->PP_PAID ?? 0;
                                              }

                                              if ($list->PAYMENT_METHOD_ID == 94) {
                                                  //PCSO
                                                  $PCSO_AMOUNT = $PCSO_AMOUNT + $list->PP_PAID ?? 0;
                                              }
                                              if ($list->PAYMENT_METHOD_ID == 96) {
                                                  //Other GL
                                                  $OTHER_GL_AMOUNT = $OTHER_GL_AMOUNT + $list->PP_PAID ?? 0;
                                              }

                                          @endphp
                                      @endif
                                  </td>
                                  <td class="text-right">

                                      {{ number_format($running_balance, 2) }}
                                  </td>
                                  <td>
                                      @if ($is_add == true)
                                          {{ $list->DOCTOR_NAME }}
                                      @endif
                                  </td>
                                  <td>
                                      @if ($is_add == true)
                                          {{ $list->LOCATION_NAME }}
                                      @endif
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
              <div class="col-md-6">
                  <div class="row">
                      <div class="col-md-6">
                          <h6 class="text-xs"><label>No. of Patient : </label> <span class="text-primary">
                                  {{ $NO_OF_PATIENT }}</span> </h6>
                      </div>
                      <div class="col-md-6">
                          <h6 class="text-xs"><label>No. of Treatment : </label> <span class="text-primary">
                                  {{ $NO_OF_TREATMENT }}</span> </h6>
                      </div>
                      <div class="col-md-12 text-xs">
                          <label style="font-size:13px;">Previous Cash Collection </label>
                          <ol style="font-size:11px;">
                              @foreach ($preDataList as $list)
                                  <li> <b>{{ $list->PATIENT_NAME }}</b>/ <i>{{ $list->PAYMENT_METHOD }}</i> / Paid:
                                      <span class="text-success">{{ number_format($list->PP_PAID, 2) }}</span> on
                                      <span class="text-primary">{{ $list->ITEM_NAME }}</span>
                                  </li>
                              @endforeach

                          </ol>
                          <span class="h6">Total: <b
                                  class="text-success">{{ number_format($PRE_COLLECTION, 2) }}</b></span>
                      </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="row">
                              <div class=" col-12 text-xs"> <label class="text-xs">Cash Paid : </label>
                                  <span
                                      class="text-success font-weight-bold text-xs">{{ number_format($CASH_AMOUNT, 2) }}</span>
                              </div>
                              <div class="col-12  text-xs"> <label class="text-xs">Previous Cash Collection :
                                  </label>
                                  <span
                                      class="text-success font-weight-bold text-xs">{{ number_format($PRE_COLLECTION, 2) }}</span>
                              </div>
                              <div class="col-12  text-xs"> <label class="text-xs ">Net Cash Sales : </label>
                                  <span
                                      class="text-info font-weight-bold text-xs">{{ number_format($CASH_AMOUNT + $PRE_COLLECTION, 2) }}</span>
                              </div>
                              <div class="col-12  text-xs"> <label class="text-xs">Philhealth Paid : </label>
                                  <span
                                      class="text-success font-weight-bold text-xs">{{ number_format($PHILHEALTH_AMOUNT, 2) }}</span>
                              </div>
                              <div class="col-12 text-xs"> <label class="text-xs">DSWD Paid : </label>
                                  <span
                                      class="text-success font-weight-bold text-xs">{{ number_format($DSWD_AMOUNT, 2) }}</span>
                              </div>
                              <div class="col-12 text-xs"> <label class="text-xs">LINGAP Paid : </label>
                                  <span
                                      class="text-success font-weight-bold text-xs">{{ number_format($LINGAP_AMOUNT, 2) }}</span>
                              </div>
                              <div class="col-12  text-xs"> <label class="text-xs">PCSO Paid : </label>
                                  <span
                                      class="text-success active font-weight-bold text-xs">{{ number_format($PCSO_AMOUNT, 2) }}</span>
                              </div>
                              <div class="col-12 text-xs"> <label class="text-xs">OTHER GL Paid : </label>
                                  <span
                                      class="text-success font-weight-bold text-xs">{{ number_format($OTHER_GL_AMOUNT, 2) }}</span>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <h6 class="text-xs"> <label class="text-xs">TOTAL (SC) : </label>
                              <span
                                  class="text-primary font-weight-bold h6">{{ number_format($TOTAL_CHARGE, 2) }}</span>
                          </h6>
                          <h6 class="text-xs"> <label class="text-xs">TOTAL (Payment) : </label>
                              <span class="text-success font-weight-bold h6">{{ number_format($TOTAL_PAID, 2) }}</span>
                          </h6>
                          <h6 class="text-xs"> <label class="text-xs">TOTAL BALANCE : </label>
                              <span
                                  class="text-danger font-weight-bold h6">{{ number_format($TOTAL_CHARGE - $TOTAL_PAID, 2) }}</span>
                          </h6 class="text-xs">
                      </div>
                  </div>
              </div>
          </div>
      </div>
