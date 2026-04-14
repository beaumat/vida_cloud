<div class="content-wrapper" id="printableContent">

    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    @foreach ($PRINT_ID as $ID)
        @livewire('PatientReport.DoctorsFeeReportPrintContent', ['DOCTOR_ID' => $ID, 'LOCATION_ID' => $locationid])
        <div class="page-break"></div>
    @endforeach
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
