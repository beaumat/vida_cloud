<div class="content-wrapper" id="printableContent">

    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    @foreach ($PRINT_ID as $ID)
        @livewire('PhilHealth.PrintTreatment', ['PRINT_ID' => 0, 'PATIENT_ID' => $ID])
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
