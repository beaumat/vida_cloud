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
    <div class="col-12 title">
        <div class="text-center pt-3 font-weight-bold rr20">PHILHEALTH HEMODIALYSIS BENEFITS PACKAGE
            <br />
            AGREEMENT FORM
        </div>
    </div>
    <div class="col-12  pb-2 pt-2">
        <div>
            <div class="row">
                <div class="col-4 font-weight-bold">
                    <div class="rr20">HD Treatment Session No.</div>
                </div>

                <div class="col-3 bottom-line2 font-weight-bold rr20">
                    &nbsp; {{ $NO_OF_TREATMENT }}
                </div>
                <div class="col-5 ">
                    &nbsp;
                </div>

                <div class="col-4 font-weight-bold  text-md">
                    <div class="rr20"> Date (Month/Day/Year)</div>
                </div>
                <div class="col-3 bottom-line2 font-weight-bold rr20">
                    &nbsp; {{ date('m/d/Y', strtotime($DATE)) }}
                </div>
                <div class="col-5 ">
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="pt-2">
            <div class="rrBox">
                <div class="rr20">
                    This document is intended to verify that you have received adequate information verbally and in
                    writing,
                    including PhilHealth's guidelines for availing of the benefits package for hemodialysis (HD). The HD
                    facility should clearly explain to you the significance of the contents of this Agreement Form in
                    the
                    language that you understand and will furnish you with a copy of the form for each unique treatment
                    session.
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="rr20 mt-3">
            I have been fully informed by Dr./Mr./Ms.
            <b>{{ $PHIC_INCHARGE_NAME }}{{ $PHIC_INCHARGE_POSITION ? '/' . $PHIC_INCHARGE_POSITION : '' }}</b> of the
            PhilHealth policies on
            availing of the
            benefits package for HD.
        </div>
    </div>
    <div class="col-12">
        <div class="rr20 mt-3">
            I understand that PhilHealth covers up to 156 treatment sessions per calendar year for patients with chronic
            kidney disease stage 5 (CKD5).
        </div>
    </div>

    <div class="col-12">
        <div class="rr20 mt-3">
            I understand that the HD package provides coverage for the minimum standards required by CKD5 patients, as
            enumerated in the applicable PhilHealth policy.
        </div>
    </div>

    <div class="col-12">
        <div class="mt-3 rr20">
            I understand that the package rate for HD is PHP 6,350 per treatment session. This includes the fees for the
            health facility and the professional.
        </div>
    </div>
    <div class="col-12">
        <div class="mt-3 rr20">
            I understand that the provision of the services depends on the patient’s status; therefore, I will receive
            the following services that are clinically indicated and necessary for my treatment session:
        </div>
    </div>
    <div class="col-12">
        <div class="container-fluid pt-4">
            <div class="row bottom-line2 top-line2 left-line2 right-line2">
                <div class="col-7 right-line2 text-center">
                    <div class="text-md font-weight-bold">Items Covered by PhilHealth </div>
                </div>
                <div class="col-5 text-center">
                    <div class="text-md font-weight-bold">Put a check (<i class="fa fa-check" aria-hidden="true"></i>)
                        if indicated and a <br /> cross
                        mark (<i class="fa fa-times" aria-hidden="true"></i>) if not indicated </div>
                </div>
                <div class="col-7 top-line2 font-weight-bold"> Drugs/Medicines </div>
                <div class="col-5 top-line2"> &nbsp; </div>
                <div class="col-12 top-line2 "> Epoetin alpha (Human Recombinant Erythropoietin) </div>
                @foreach ($typeOneList as $list)
                    <div class="col-7 top-line2 right-line2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $list->LINE }}.
                        {{ $list->DESCRIPTION }}
                    </div>
                    <div class="col-5 top-line2 text-center text-md">
                        @if ($list->IS_CHECK)
                            <i class="fa fa-check " aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times " aria-hidden="true"></i>
                        @endif
                    </div>
                @endforeach
                <div class="col-12 top-line2"> Epoetin beta (Recombinant Erythropoietin) </div>
                @foreach ($typeTwoList as $list)
                    <div class="col-7 top-line2 right-line2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $list->LINE }}.
                        {{ $list->DESCRIPTION }}
                    </div>
                    <div class="col-5 top-line2 text-center text-md">
                        @if ($list->IS_CHECK)
                            <i class="fa fa-check " aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times " aria-hidden="true"></i>
                        @endif
                    </div>
                @endforeach
                @foreach ($typeThreeList as $list)
                    <div class="col-7 top-line2 right-line2">
                        {{ $list->DESCRIPTION }}
                    </div>
                    <div class="col-5 top-line2 text-center text-md">
                        @if ($list->IS_CHECK)
                            <i class="fa fa-check " aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times " aria-hidden="true"></i>
                        @endif
                    </div>
                @endforeach
                <div class="col-12  top-line2"> Heparin</div>
                @foreach ($typeFourList as $list)
                    <div class="col-7 top-line2 right-line2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $list->LINE }}.
                        {{ $list->DESCRIPTION }}
                    </div>
                    <div class="col-5 top-line2 text-center text-md">
                        @if ($list->IS_CHECK)
                            <i class="fa fa-check " aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times " aria-hidden="true"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12 footer pt-2">
        <div>
            <div class="row">
                <div class="col-7 text-left">
                    <img src="{{ asset('dist/logo/form/footer.jpg') }}" style="width:150px;" />

                </div>
                <div class="col-5">

                </div>
            </div>
        </div>
    </div>
</div>
