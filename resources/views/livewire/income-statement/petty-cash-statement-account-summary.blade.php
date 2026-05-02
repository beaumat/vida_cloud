<style>
    .petty-cash-page {
        background: #fff;
        padding: 20px 35px;
        font-size: 10px;
        color: #000;
    }

    .petty-cash-header {
        position: relative;
        margin-bottom: 8px;
    }

    .petty-cash-title-center {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .petty-cash-info {
        line-height: 1.4;
        font-size: 10px;
    }

    .petty-cash-table {
        width: 100%;
        border-collapse: collapse !important;
        font-size: 9px;
        table-layout: auto;
    }

    .petty-cash-table th,
    .petty-cash-table td {
        border: 1px solid #000 !important;
        padding: 3px 4px !important;
        vertical-align: middle;
        color: #000;
        background: #fff;
    }

    .petty-cash-table th {
        text-align: center;
        font-weight: bold;
        white-space: nowrap;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .font-weight-bold {
        font-weight: bold;
    }

    @media print {
        .content-wrapper {
            margin: 0 !important;
        }

        .petty-cash-page {
            padding: 10px;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid petty-cash-page">

            <div class="petty-cash-header">
                <div class="petty-cash-title-center">
                    {{ request()->getHost() }}/pettycash
                </div>

                <div class="petty-cash-info">
                    <strong>PETTY CASH SUMMARY</strong><br>
                    <strong>Replenishment Date:</strong>
                    {{ isset($replenishmentDate) ? date('F d, Y', strtotime($replenishmentDate)) : '' }}<br>
                    <strong>PCV #:</strong> {{ $ACCOUNT_CODE ?? '' }}<br>
                    <strong>Account:</strong> PETTY CASH
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="petty-cash-table">
                    <thead>
                        <tr>
                         
                            <th>Company Name</th>
                            <th>Description</th>
                            <th>AMOUNT</th>
                            <th>Input VAT</th>
                            <th>General &amp; Admin Expense</th>
                            <th>IC RECEIVABLE - CALACARE</th>
                            <th>IC Receivable - Crecare Cebu</th>
                            <th>IC Receivable Buhangin</th>
                            <th>IC Receivable Davao</th>
                            <th>IC Receivable Balance</th>
                            <th>IC Receivable GenSan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $TOTAL_AMOUNT = 0;
                            $TOTAL_INPUT_VAT = 0;
                            $TOTAL_ADMIN = 0;
                            $TOTAL_CALACARE = 0;
                            $TOTAL_CRECARE_CEBU = 0;
                            $TOTAL_BUHANGIN = 0;
                            $TOTAL_DAVAO = 0;
                            $TOTAL_BALANCE = 0;
                            $TOTAL_GENSAN = 0;
                        @endphp

                        @forelse ($dataList as $list)
                            @php
                                $amount = (float) ($list->AMOUNT ?? 0);
                                $accountTitle = strtoupper($list->NAME ?? $list->ACCOUNT_TITLE ?? '');
                                $particulars = $list->PARTICULARS ?? '';

                                $inputVat = str_contains($accountTitle, 'INPUT VAT') ? $amount : 0;
                                $admin = str_contains($accountTitle, 'GENERAL') || str_contains($accountTitle, 'ADMIN') ? $amount : 0;
                                $calacare = str_contains($accountTitle, 'CALACARE') ? $amount : 0;
                                $crecareCebu = str_contains($accountTitle, 'CRECARE CEBU') ? $amount : 0;
                                $buhangin = str_contains($accountTitle, 'BUHANGIN') ? $amount : 0;
                                $davao = str_contains($accountTitle, 'DAVAO') ? $amount : 0;
                                $gensan = str_contains($accountTitle, 'GENSAN') ? $amount : 0;

                                $allocated = $inputVat + $admin + $calacare + $crecareCebu + $buhangin + $davao + $gensan;
                                $balance = $allocated == 0 ? 0 : max($amount - $allocated, 0);

                                $TOTAL_AMOUNT += $amount;
                                $TOTAL_INPUT_VAT += $inputVat;
                                $TOTAL_ADMIN += $admin;
                                $TOTAL_CALACARE += $calacare;
                                $TOTAL_CRECARE_CEBU += $crecareCebu;
                                $TOTAL_BUHANGIN += $buhangin;
                                $TOTAL_DAVAO += $davao;
                                $TOTAL_BALANCE += $balance;
                                $TOTAL_GENSAN += $gensan;
                            @endphp

                            <tr>
                                
                                <td>PETTY CASH</td>
                                <td>{{ $particulars }}</td>
                                <td class="text-right">{{ $amount != 0 ? number_format($amount, 2) : '' }}</td>
                                <td class="text-right">{{ $inputVat != 0 ? number_format($inputVat, 2) : '' }}</td>
                                <td class="text-right">{{ $admin != 0 ? number_format($admin, 2) : '' }}</td>
                                <td class="text-right">{{ $calacare != 0 ? number_format($calacare, 2) : '' }}</td>
                                <td class="text-right">{{ $crecareCebu != 0 ? number_format($crecareCebu, 2) : '' }}</td>
                                <td class="text-right">{{ $buhangin != 0 ? number_format($buhangin, 2) : '' }}</td>
                                <td class="text-right">{{ $davao != 0 ? number_format($davao, 2) : '' }}</td>
                                <td class="text-right">{{ $balance != 0 ? number_format($balance, 2) : '' }}</td>
                                <td class="text-right">{{ $gensan != 0 ? number_format($gensan, 2) : '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No data available</td>
                            </tr>
                        @endforelse

                        <tr class="font-weight-bold">
                            <td colspan="2" class="text-right">TOTAL</td>
                            <td class="text-right">{{ number_format($TOTAL_AMOUNT, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_INPUT_VAT, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_ADMIN, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_CALACARE, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_CRECARE_CEBU, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_BUHANGIN, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_DAVAO, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_BALANCE, 2) }}</td>
                            <td class="text-right">{{ number_format($TOTAL_GENSAN, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</div>