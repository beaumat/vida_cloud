<div class="content-wrapper" id="printableContent">

    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    @if ($SHIFT_LIST)
        @foreach ($SHIFT_LIST as $list)
            @livewire('Scheduler.PrintWeekly', ['week_id' => $WEEKLY_ID, 'year' => $YEAR, 'month' => $MONTH, 'locationid' => $LOCATION_ID, 'shift' => $list->ID])
            <div class="page-break"></div>
        @endforeach
    @else
        @livewire('Scheduler.PrintWeekly', ['week_id' => $WEEKLY_ID, 'year' => $YEAR, 'month' => $MONTH, 'locationid' => $LOCATION_ID, 'shift' => $SHIFT_ID])
    @endif
</div>
@script
    <script>
        $wire.on('print', () => {
            var printContents = document.getElementById('printableContent').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            // Set landscape orientation
            var style = document.createElement('style');
            style.innerHTML = '@page { size: landscape; }';
            document.head.appendChild(style);
            window.print();
            document.body.innerHTML = originalContents;
        });

        function printPageAndClose() {
            // Set landscape orientation
            var style = document.createElement('style');
            style.innerHTML = '@page { size: landscape; }';
            document.head.appendChild(style);
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
