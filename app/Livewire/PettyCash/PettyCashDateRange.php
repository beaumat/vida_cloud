<?php

namespace App\Livewire\PettyCash;

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

    #[On('petty-cash-date-range')]
    public function generate($result)
    {
        $this->DATE_FROM   = $result['DATE_FROM'] ?? null;
        $this->DATE_TO     = $result['DATE_TO'] ?? null;
        $this->LOCATION_ID = $result['LOCATION_ID'] ?? 0;

        $this->dataList = [];

        $pettyCashList = $this->financialStatementServices
            ->getPettyCashListByDateRange(
                [0, 1, 2, 3, 4],
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                false
            );

        $this->SetData($pettyCashList);
    }

    #[On('export-daily-request')]
    public function export()
    {
        if (! $this->dataList) {
            session()->flash('error', 'Please click generate first');
            return;
        }

        try {
            $headers = ['ID', 'CODE', 'AMOUNT', 'DATE'];
            $rowdata = [];

            foreach ($this->dataList as $item) {
                $rowdata[] = [
                    'ID'     => $item['ID'] ?? '',
                    'CODE'   => $item['CODE'] ?? '',
                    'AMOUNT' => $item['AMOUNT'] ?? '',
                    'DATE'   => $item['DATE'] ?? '',
                ];
            }

            return Excel::download(
                new DynamicExport($headers, $rowdata),
                'Petty_Cash_Summary.xlsx'
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
        }
    }

    private function SetData($list): array
    {
        $TOTAL = 0;

        foreach ($list as $data) {
            $amount = $data->AMOUNT ?? $data->TOTAL ?? 0;
            $TOTAL += (float) $amount;

            $this->dataList[] = $this->getInsert(
                $data->ID ?? 0,
                $data->CODE ?? $data->ACCOUNT_NAME ?? '',
                $amount,
                $data->DATE ?? ''
            );
        }

        return [
            'TOTAL' => $TOTAL,
        ];
    }

    private function getInsert(int $ID, string $CODE, $AMOUNT = '', $DATE = ''): array
    {
        return [
            'ID'     => $ID,
            'CODE'   => $CODE,
            'AMOUNT' => is_numeric($AMOUNT)
                ? $this->numberServices->AcctFormat($AMOUNT)
                : $AMOUNT,
            'DATE'   => $DATE,
        ];
    }

    public function render()
    {
        return view('livewire.financial-report.petty-cash-date-range');
    }
}