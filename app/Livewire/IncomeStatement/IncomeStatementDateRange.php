<?php
namespace App\Livewire\IncomeStatement;

use App\Exports\DynamicExport;
use App\Services\FinancialStatementServices;
use App\Services\NumberServices;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class IncomeStatementDateRange extends Component
{

    public string $DATE_FROM;
    public string $DATE_TO;
    public string $LOCATION_ID;
    public $dataList = [];
    private $financialStatementServices;
    private $numberServices;
    public function boot(FinancialStatementServices $financialStatementServices, NumberServices $numberServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->numberServices             = $numberServices;
    }
    #[On('income-date-range')]
    public function generate($result)
    {
        $this->dataList    = [];
        $this->DATE_FROM   = $result['DATE_FROM'];
        $this->DATE_TO     = $result['DATE_TO'];
        $this->LOCATION_ID = $result['LOCATION_ID'];

        $revenueList = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([10], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, true);
        $r           = $this->SetData($revenueList, "Trading Income");

        $costList = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([11], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, false);
        $c        = $this->SetData($costList, "Cost of Sales");
        $G_TOTAL  = $r['TOTAL'] - $c['TOTAL'];

        $this->dataList[] = $this->getInsert(
            0,
            'Gross Profit ',
            'grand',
            $G_TOTAL != 0 ? $this->numberServices->AcctFormat($G_TOTAL) : '-'
        );
        $otherincome = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([13], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, true);
        $i           = $this->SetData($otherincome, "");

        $expense = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([12], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, false);
        $e       = $this->SetData($expense, "Operating Expenses");
        // operating profit
        $OP_TOTAL = $G_TOTAL - $e['TOTAL'];

        $this->dataList[] = $this->getInsert(0, 'Operating Proft ', 'grand', $OP_TOTAL != 0 ? $this->numberServices->AcctFormat($OP_TOTAL) : '-');

        $otherExpense = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([14], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, true);
        $ex           = $this->SetData($otherExpense, "");

        // NET profit
        $NET_TOTAL = $OP_TOTAL + $i['TOTAL'] - $ex['TOTAL'];

        $this->dataList[] = $this->getInsert(0, 'Net Proft ', 'grand', $NET_TOTAL != 0 ? $this->numberServices->AcctFormat($NET_TOTAL) : '-');

    }
    #[On('export-daily-request')]
    public function export()
    {

        if (! $this->dataList) {
            session()->flash('error', 'Please click geenerate first ');
            return;
        }
        try {

            $headers = ['ACCOUNT_NAME', 'TOTAL']; // Could be dynamic based on UI
            $rowdata = [];
            foreach ($this->dataList as $item) {
                $rowdata[] = [
                    'ACCOUNT_NAME' => $item['ACCOUNT_NAME'],
                    'TOTAL'        => $item['TOTAL'],
                ];
            }

            return Excel::download(new DynamicExport($headers, $rowdata), 'Income_statement_Summary.xlsx');

        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
        }
    }
    private function getInsert(int $ID, string $NAME, string $TYPE, string $TOTAL = ''): array
    {

        return [
            'ACCOUNT_ID'   => $ID,
            'ACCOUNT_NAME' => $NAME,
            'ACCOUNT_TYPE' => $TYPE,
            'TOTAL'        => $TOTAL,
        ];

    }
    private function SetData($list, string $title): array
    {

        $TOTAL   = 0;
        $T_TOTAL = 0;

        $TMP      = -1;
        $TMP_NAME = "";
        if ($title != "") {
            $this->dataList[] = $this->getInsert(0, $title, 'grand');
        }
        foreach ($list as $data) {
            $TOTAL += $data->TOTAL;

            if ($TMP == -1) {
                $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                $TMP_NAME         = $data->TYPE_NAME;
            } elseif ($TMP != $data->TYPE) {
                $this->dataList[] = $this->getInsert(0, ' Total ' . $TMP_NAME, 'total', $T_TOTAL != 0 ? $this->numberServices->AcctFormat($T_TOTAL) : '-');

                //CLEAR
                $T_TOTAL          = 0;
                $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                $TMP_NAME         = $data->TYPE_NAME;
            }
            $this->dataList[] = $this->getInsert($data->ID, '   ' . $data->ACCOUNT_NAME, $data->TYPE_NAME, $data->TOTAL != 0 ? $this->numberServices->AcctFormat($data->TOTAL) : '-');

            $T_TOTAL += $data->TOTAL;
            $TMP     = (int) $data->TYPE;

        }
        if ($TMP_NAME != '') {
            $this->dataList[] = $this->getInsert(0, ' Total ' . $TMP_NAME, 'total', $T_TOTAL != 0 ? $this->numberServices->AcctFormat($T_TOTAL) : '-');
        }
        //CLEAR
        $T_TOTAL = 0;

        if ($title != "") {
            $this->dataList[] = $this->getInsert(0, 'Total ' . $title, 'grand', $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-');
        }

        // return total
        return [
            'TOTAL' => $TOTAL,
        ];

    }
    public function render()
    {
        return view('livewire.income-statement.income-statement-date-range');
    }
}
