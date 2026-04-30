<?php

namespace App\Livewire\BalanceSheet;

use App\Exports\DynamicExport;
use App\Services\FinancialStatementServices;
use App\Services\NumberServices;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PettyCashDateRange extends Component
{
    public $DATE_FROM;
    public $DATE_TO;
    public $LOCATION_ID;

    public $dataList = [];

    private $financialStatementServices;
    private $numberServices;

    public function boot(
        FinancialStatementServices $financialStatementServices,
        NumberServices $numberServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->numberServices = $numberServices;
    }

    #[On('balance-sheet-date-range')]
    public function generate($result)
    {
        $this->DATE_FROM = $result['DATE_FROM'];
        $this->DATE_TO = $result['DATE_TO'];
        $this->LOCATION_ID = $result['LOCATION_ID'];

        $this->dataList = [];

        $assetList = $this->financialStatementServices
            ->getPettyCashListByDateRange(
                [0, 1, 2, 3, 4],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                false
            );

        $a = $this->SetData($assetList);

        // $liabilityList = $this->financialStatementServices
        //     ->getBalanceSheetAccountTypeListByDateRange(
        //         [5, 6, 7, 8],
        //         $this->DATE_FROM,
        //         $this->DATE_TO,
        //         $this->LOCATION_ID,
        //         true
        //     );

        // $l = $this->SetData($liabilityList, 'Liabilities');

        // $TOTAL = $a['TOTAL'] - $l['TOTAL'];

        // $this->dataList[] = $this->getInsert(
        //     0,
            
        //     'total',
        //     $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-'
        // );

       // $this->equitySide();
    }

    #[On('export-daily-request')]
    public function export()
    {
        if (! $this->dataList) {
            session()->flash('error', 'Please click generate first');
            return;
        }

        try {
            $headers = ['CODE', 'ACCOUNT_NAME', 'TOTAL'];
            $rowdata = [];

            foreach ($this->dataList as $item) {
                $rowdata[] = [
                    'CODE' => $item['CODE'] ?? '',
                    'ACCOUNT_NAME' => $item['ACCOUNT_NAME'] ?? '',
                    'TOTAL' => $item['TOTAL'] ?? '',
                ];
            }

            return Excel::download(
                new DynamicExport($headers, $rowdata),
                'Balance_Sheet_Summary.xlsx'
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
        }
    }

    private function equitySide()
    {
        $this->dataList[] = $this->getInsert(
            0,
            'Equity',
            'grand',
            ''
        );

        $equityList = $this->financialStatementServices
            ->getBalanceSheetAccountTypeListByDateRange(
                [9],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                true
            );

        $e = $this->SetData($equityList, '', true);

        $dataIS = $this->getIncomeStatement();

        $TOTAL = (float) $e['TOTAL'] + (float) $dataIS['TOTAL'];

        $this->dataList[] = $this->getInsert(
            0,
            'Total Equity',
            'grand',
            $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-'
        );
    }

   private function SetData($list, bool $notToDisplay = false): array
{
    $TOTAL = 0;

    foreach ($list as $data) {
        $amount = $data->TOTAL
            ?? $data->AMOUNT
            ?? $data->BALANCE_DUE
            ?? 0;

        $CODE = $data->CODE ?? '';

        $accountName = $data->ACCOUNT_NAME
            ?? $data->CONTACT_NAME
            ?? $CODE
            ?? '';

        $id = $data->ID ?? 0;
        $amount = (float) $amount;

        if (trim($accountName) === '') {
            continue;
        }

        $TOTAL += $amount;

        if (! $notToDisplay) {
            $this->dataList[] = $this->getInsert(
                (int) $id,
                $accountName,
                '',
                $amount != 0 ? $this->numberServices->AcctFormat($amount) : '-',
                $CODE
            );
        }
    }

    return [
        'TOTAL' => $TOTAL,
    ];
}

    public function getIncomeStatement(): array
    {
        $revenueList = $this->financialStatementServices
            ->getIncomeStatementAccountTypeByDate(
                [10],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                true
            );

        $r = $this->SetData($revenueList, true);

        $costList = $this->financialStatementServices
            ->getIncomeStatementAccountTypeByDate(
                [11],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                false
            );

        $c = $this->SetData($costList, true);

        $G_TOTAL = $r['TOTAL'] - $c['TOTAL'];

        $otherincome = $this->financialStatementServices
            ->getIncomeStatementAccountTypeByDate(
                [13],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                true
            );

        $i = $this->SetData($otherincome, true);

        $expense = $this->financialStatementServices
            ->getIncomeStatementAccountTypeByDate(
                [12],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                false
            );

        $e = $this->SetData($expense, true);

        $OP_TOTAL = $G_TOTAL - $e['TOTAL'];

        $otherExpense = $this->financialStatementServices
            ->getIncomeStatementAccountTypeByDate(
                [14],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                true
            );

        $ex = $this->SetData($otherExpense, '', true);

        $NET_TOTAL = $OP_TOTAL + $i['TOTAL'] - $ex['TOTAL'];

        $this->dataList[] = $this->getInsert(
            0,
            'Current Year Earnings',
            '',
            $NET_TOTAL != 0 ? $this->numberServices->AcctFormat($NET_TOTAL) : '-'
        );

        return [
            'TOTAL' => $NET_TOTAL,
        ];
    }

    public function getIncomeStatementLastRange()
    {
        // Optional: add logic here if needed.
    }

    private function getInsert(
        int $ID,
        string $ACCOUNT_NAME,
        string $TYPE,
        string $TOTAL = '',
        string $CODE = ''
    ): array {
        return [
            'ACCOUNT_ID' => $ID,
            'CODE' => $CODE,
            'ACCOUNT_NAME' => $ACCOUNT_NAME,
            'ACCOUNT_TYPE' => $TYPE,
            'TOTAL' => $TOTAL,
        ];
    }

    public function render()
    {
        return view('livewire.balance-sheet.petty-cash-sheet-date-range');
    }
}