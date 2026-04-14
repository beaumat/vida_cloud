<div class="content-wrapper" id="printableContent">
    <style>
        @media print {
            @page {
                size: letter landscape;

                /* Sets the paper size to Legal */
                /* Custom long size: width 8.5in (letter width), length 14in */
                /* margin: 0.5in; */
                /* Adjust margins as desired */
                margin-left: 14px;
                margin-right: 14px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    @livewire('PatientReport.PatientSalesReportPrintContent', ['DATE_FROM' => $DATE_TRANSACTION_FROM, 'DATE_TO' => $DATE_TRANSACTION_TO, 'LOCATION_ID' => $LOCATION_ID])


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
