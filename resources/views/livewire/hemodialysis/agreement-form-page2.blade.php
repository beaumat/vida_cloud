<div class="row" style=" font-family: Georgia, serif;color:black;padding-left:100px;padding-right:100px;">
    <div class="col-12 top text-right">
        <div style="font-size:23px;" class="font-weight-bold rr20">Annex A: Agreement Form</div>
    </div>
    <div class="col-12 header">
        <div class="row">
            <div class="col-6 text-center">
                <img src="{{ asset('dist/logo/form/bago.jpg') }}" style="width:400px;" />

            </div>
            <div class="col-6">
                <img src="{{ asset('dist/logo/form/address.jpg') }}" style="width:430px;" />
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="container-fluid pt-4">
            <div class="row bottom-line2 top-line2 left-line2 right-line2">
                <div class="col-12  font-weight-bold"> Laboratory tests </div>
                @foreach ($typeFiveList as $list)
                    <div class="col-7 top-line2 right-line2">{{ $list->LINE }}. {{ $list->DESCRIPTION }}
                    </div>
                    <div class="col-5 top-line2 text-center text-md">
                        @if ($list->IS_CHECK)
                            <i class="fa fa-check " aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times " aria-hidden="true"></i>
                        @endif
                    </div>
                @endforeach
                <div class="col-12 top-line2  font-weight-bold">Supplies </div>
                @foreach ($typeSixList as $list)
                    <div class="col-7 top-line2 right-line2"> {{ $list->DESCRIPTION }}
                    </div>
                    <div class="col-5 top-line2 text-center text-md">
                        <div class="row">
                            <div class="col-3">

                            </div>
                            <div class="col-6">
                                @if ($list->IS_CHECK)
                                    <i class="fa fa-check " aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-times " aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="col-3 text-center">
                                @if ($list->ID == 24 || $list->ID == 25)
                                    @if ($list->IS_CHECK)
                                        @if ($QTY > 0)
                                            <span class="font-weight-bold text-primary h5">#{{ $QTY }}</span>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-7 top-line2  font-weight-bold right-line2">Administrative & Other Fees, specify: </div>
                <div class="col-5 top-line2"> &nbsp; </div>
                @if (!$LEAVE_BLANK_AG_ADMIN_OFFICE_FEE)
                    <div class="col-7 top-line2 right-line2">&nbsp; HD MACHINE
                    </div>
                    <div class="col-5 top-line2 text-center text-md"> &nbsp;
                        <i class="fa fa-check " aria-hidden="true"></i>
                    </div>
                    <div class="col-7 top-line2 right-line2">&nbsp; FACILITY FEES
                    </div>
                    <div class="col-5 top-line2 text-center text-md"> &nbsp;
                        <i class="fa fa-check " aria-hidden="true"></i>
                    </div>
                    <div class="col-7 top-line2 right-line2">&nbsp; UTILITIES/OPERATING FEES
                    </div>
                    <div class="col-5 top-line2 text-center text-md"> &nbsp;
                        <i class="fa fa-check " aria-hidden="true"></i>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="rr20 mt-3">
            I understand that I may be charged a copayment for the following items, amenities, additional services, and
            premium services that are not covered by PhilHealth (attach additional sheet as necessary):
        </div>
    </div>
    <div class="col-12">
        <div class="container-fluid pt-4">
            <div class="row bottom-line2 top-line2 left-line2 right-line2">
                <div class="col-6 right-line2 font-weight-bold  text-center">Item </div>
                <div class="col-3 right-line2 font-weight-bold text-center"> Unit/Quantity</div>
                <div class="col-3  font-weight-bold text-center"> Price (PHP) </div>
                @php
                    $limit = 2;
                    $total = 0;
                @endphp

                @foreach ($itemList as $list)
                    @php
                        $limit--;
                        $total = $total + ($list->QUANTITY * $list->RATE ?? 0);
                    @endphp
                    <div class="col-6 top-line2 right-line2 text-left "> &nbsp; {{ $list->DESCRIPTION }} </div>
                    <div class="col-3 top-line2 right-line2 text-center"> &nbsp; {{ $list->QUANTITY }} </div>
                    <div class="col-3 top-line2 text-center "> &nbsp; {{ number_format($list->RATE, 2) }} </div>
                @endforeach

                @for ($n = 1; $n <= $limit; $n++)
                    <div class="col-6 top-line2 right-line2 text-center "> &nbsp;
                        <strong class=" font-weight-bold">N/A</strong>
                    </div>
                    <div class="col-3 top-line2 right-line2 "> &nbsp; </div>
                    <div class="col-3 top-line2  "> &nbsp; </div>
                @endfor

                <div class="col-6 top-line2  right-line2"> &nbsp; </div>
                <div class="col-3 top-line2  right-line2 "> &nbsp; </div>
                <div class="col-3 top-line2 font-weight-bold ">
                    <div class="row">
                        <div class="col-4">
                            Total
                        </div>
                        <div class="col-8 ">
                            @if ($total > 0)
                                {{ number_format($total, 2) }}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="rr20 mt-3 pb-4">
            I have been furnished with a list of possible funding sources for medical assistance that may complement the
            PhilHealth benefits for HD.
        </div>
    </div>
    <div class="col-12">
        <div class="rr20 pt-4 pb-4">
            Conforme:
        </div>
    </div>
    <div class="col-12 rr20">
        <div class="container-fluid pt-4">
            <div class="row">
                <div class="col-5 bottom-line2">{{ $PATIENT_NAME }}</div>
                <div class="col-2"></div>
                <div class="col-5 bottom-line2">
                    <div class="text-left" style="position: absolute;left:0%; width: 600px;">
                        {{ $HD_FACILITY_REP_NAME }}{{ $HD_FACILITY_REP_POS ? '/' . $HD_FACILITY_REP_POS : '' }}
                    </div>
                </div>
                <div class="col-5">Printed name and signature of patient </div>
                <div class="col-3"></div>
                <div class="col-4">Printed name & signature </div>
                <div class="col-5">&nbsp;</div>
                <div class="col-3">&nbsp;</div>
                <div class="col-4">HD Facility Representative</div>
            </div>
        </div>
    </div>

    <div class="col-12 rr20">
        <div class="container-fluid pt-4">
            <div class="row">
                <div class="col-5">
                    <div class="row">
                        <div class="col-2">
                            Date:
                        </div>
                        <div class="col-10  bottom-line2">
                            &nbsp;{{ date('m/d/Y', strtotime($DATE)) }}
                        </div>
                    </div>
                </div>
                <div class="col-3"></div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-2">
                            Date:
                        </div>
                        <div class="col-10  bottom-line2">
                            &nbsp;{{ date('m/d/Y', strtotime($DATE)) }}
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="col-12 rr20">
        <div class="container-fluid pt-4">
            <div class="row">
                <div class="col-5">
                    Witness:
                </div>
                <div class="col-3"></div>
                <div class="col-4">


                </div>
            </div>
        </div>
    </div>
    <div class="col-12 rr20">
        <div class="container-fluid pt-4">
            <div class="row">
                <div class="col-5 bottom-line2">
                    <div class="text-left" style="position: absolute;left:0%; width: 600px;">
                        {{ $WITNESS_NAME }}
                    </div>
                </div>
                <div class="col-3"></div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-2">
                            Date:
                        </div>
                        <div class="col-10  bottom-line2">
                            &nbsp;{{ date('m/d/Y', strtotime($DATE)) }}
                        </div>
                    </div>
                </div>

                <div class="col-5">Printed Name and signature </div>
                <div class="col-3"></div>
                <div class="col-4">&nbsp;</div>


            </div>
        </div>
    </div>
    <div class="col-12 footer pt-2">
        <div class="pt-2">
            <div class="row">
                <div class="col-7 text-left pt-4">
                    <img src="{{ asset('dist/logo/form/footer.jpg') }}" style="width:150px;" />

                </div>
                <div class="col-5">

                </div>
            </div>
        </div>
    </div>
</div>
