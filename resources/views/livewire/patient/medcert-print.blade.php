<div id="printableContent">
    {{-- Start Content --}}

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    @if (empty($LOGO_FILE))
                        <img class="print-logo" style="position: fixed;left:25%"
                            src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="" style="position:fixed;left:30%;top:40px;">
                            <b>{{ $REPORT_HEADER_1 }}</b> <br />
                            <b>{{ $REPORT_HEADER_2 }}</b> <br />
                            <b>{{ $REPORT_HEADER_3 }}</b>
                        </div>
                    @else
                        <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif

                </div>
                <div class="col-12">
                    <div class="row" style="position:fixed;left:35%; top:170px;">
                        <div class="col-12 mt-4">
                            <b class="text-xl times-new-roman"><u>MEDICAL CERTIFICATE</u></b>
                        </div>
                    </div>
                </div>
                <div class="form-group times-new-roman"
                    style="position:fixed;top:300px;left:100px;margin-right:130px;font-size:23px;">
                    <div class="row">
                        <div class="col-12 text-left ">
                            <p>{{ $DATE }}</p> <br />
                        </div>
                        <div class="col-12 text-left">
                            <p>To whom it may concern:</p> <br />
                        </div>
                        <div class="col-12 text-left">
                            <p class="text-justify">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify
                                that patient <b>{{ $FULLNAME }}</b>., {{ $AGE }} years old,
                                {{ $GENDER }},
                                resident of {{ $ADDRESS }} was diagnosed with End Stage Renal Disease secondary to
                                {{ $FINAL_DIAGNOSIS }} and is enrolled/scheduled for Hemodialysis at
                                {{ $BRANCH_NAME }},
                                {{ $SCHED_SHORT_DESC }} a week, every {{ $SCHED_FULL_DESC }}.
                            </p>
                        </div>

                        <div class="col-12 text-left">
                            <br /> <br /> <br />
                            <p class="text-justify">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is
                                issued to {{ $PX_LASTNAME }} for whatever reason it may serve his purpose.
                            </p>
                        </div>
                        <div class="col-7">
                        </div>
                        <div class="col-5 text-center">
                            <br /> <br /> <br /> <br />
                            <div class="center">
                                <div class="font-weight-bold ">
                                    {{ $NURSE_NAME }}
                                </div>
                                <div class="">Duty Physician</div>
                                <div class="">LIC No. {{ $LIC_NO }}</div>
                            </div>
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            {{-- --}}
                            <div class="font-weight-bold ">
                                @if ($LOCATION_ID == 1)
                                    NOT VALID WITHOUT SEAL
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    {{-- End Content --}}
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
