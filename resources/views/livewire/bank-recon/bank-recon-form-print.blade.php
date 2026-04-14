<div id="printableContent">

     <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            margin: 40px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
        }

        .report-title {
            font-size: 19px;
            font-weight: bold;
            margin-top: 5px;
        }

        .info-table, .summary-table, .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 4px;
        }

        .summary-table td {
            padding: 4px;
        }

        .details-table th, .details-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .details-table th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .status-balanced {
            color: green;
            font-weight: bold;
        }

        .status-not-balanced {
            color: red;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
        }

        .signature-line {
            margin-top: 40px;
        }

        @media print {
            body {
                margin: 20px;
            }
        }
    </style>


    @livewire('bank-recon-report.bank-recon-header-report', ['BANK_RECON_ID' => $ID])
    @livewire('bank-recon-report.bank-recon-summary-report', ['BANK_RECON_ID' => $ID])
    @livewire('bank-recon-report.bank-recon-cleared-report', ['BANK_RECON_ID' => $ID])
    @livewire('bank-recon-report.bank-recon-outstanding-check-report', ['BANK_RECON_ID' => $ID])
    @livewire('bank-recon-report.bank-recon-deposit-in-transit-report', ['BANK_RECON_ID' => $ID])
    @livewire('bank-recon-report.bank-recon-footer-report', ['BANK_RECON_ID' => $ID])


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
        setTimeout(function () {
            window.close();
        }, 100);
    }

    window.addEventListener('beforeprint', function () {
        printPageAndClose();
    });
</script>
@endscript