<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionJournalReportExport implements FromGenerator, ShouldAutoSize
{
    protected $dataList = [];

    public function __construct($dataList)
    {
        $this->dataList = $dataList;
    }

    /**
     * @return \Generator
     */
    public function generator(): \Generator
    {
        set_time_limit(0);

        $TOTAL_DEBIT  = 0;
        $TOTAL_CREDIT = 0;

        // Headers
        yield [
            'Jrnl#', 'Date', 'Type', 'Code', 'Name',
            'Location', 'Account Title', 'Particulars',
            'Debit', 'Credit',
        ];

        $chunkSize = 10000; // adjust based on memory

        foreach ($this->dataList->chunk($chunkSize) as $chunk) {
            foreach ($chunk as $list) {

                if ($list->DEBIT > 0) {
                    $TOTAL_DEBIT += $list->DEBIT;
                }

                if ($list->CREDIT > 0) {
                    $TOTAL_CREDIT += $list->CREDIT;
                }

                yield [
                    $list->JOURNAL_NO,
                    date('m/d/Y', strtotime($list->DATE)),
                    $list->TYPE,
                    $list->TX_CODE,
                    $list->TX_NAME,
                    $list->LOCATION,
                    $list->ACCOUNT_TITLE,
                    $list->TX_NOTES,
                    $list->DEBIT > 0 ? $list->DEBIT : '',
                    $list->CREDIT > 0 ? $list->CREDIT : '',
                ];
            }

            unset($chunk);
            gc_collect_cycles();
        }

        // Totals row
        yield ['', '', '', '', '', '', '', '', $TOTAL_DEBIT, $TOTAL_CREDIT];
    }
}
