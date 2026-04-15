<div class="content-wrapper" id="printableContent">
    <style>
        @media print {

            @page {
                size: legal;
                /* Sets the paper size to Legal */
                /* Custom long size: width 8.5in (letter width), length 14in */
                /* margin: 0.5in; */
                /* Adjust margins as desired */
                margin-left: 45px;
                margin-right: 45px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    @foreach ($PRINT_ID as $ID)
        @livewire('PhilHealth.PrintCsf', ['id' => $ID])
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
