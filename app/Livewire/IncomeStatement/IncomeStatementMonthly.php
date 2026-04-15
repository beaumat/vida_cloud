<?php
namespace App\Livewire\IncomeStatement;

use App\Exports\DynamicExport;
use App\Services\FinancialStatementServices;
use App\Services\NumberServices;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class IncomeStatementMonthly extends Component
{

    public bool $isRun   = false;
    public bool $isFocus = true;
    public $dataList     = [];
    public $LOCATION_ID;
    public $YEAR;
    private $financialStatementServices;

    private $numberServices;
    public function boot(FinancialStatementServices $financialStatementServices, NumberServices $numberServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->numberServices             = $numberServices;
    }

    #[On('income-monthly')]
    public function generate($result)
    {
        $this->YEAR        = (int) $result['YEAR'];
        $this->LOCATION_ID = (int) $result['LOCATION_ID'];
        $this->dataList    = [];

        $revenueList = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([10], $this->YEAR, $this->LOCATION_ID, true, $this->isRun, $this->isFocus);
        $r           = $this->SetData($revenueList, "Trading Income");

        $costList = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([11], $this->YEAR, $this->LOCATION_ID, false, $this->isRun, $this->isFocus);
        $c        = $this->SetData($costList, "Cost of Sales");

        $G_JAN   = $r['JAN'] - $c['JAN'];
        $G_FEB   = $r['FEB'] - $c['FEB'];
        $G_MAR   = $r['MAR'] - $c['MAR'];
        $G_APR   = $r['APR'] - $c['APR'];
        $G_MAY   = $r['MAY'] - $c['MAY'];
        $G_JUN   = $r['JUN'] - $c['JUN'];
        $G_JUL   = $r['JUL'] - $c['JUL'];
        $G_AUG   = $r['AUG'] - $c['AUG'];
        $G_SEP   = $r['SEP'] - $c['SEP'];
        $G_OCT   = $r['OCT'] - $c['OCT'];
        $G_NOV   = $r['NOV'] - $c['NOV'];
        $G_DEC   = $r['DEC'] - $c['DEC'];
        $G_TOTAL = $r['TOTAL'] - $c['TOTAL'];

        $this->dataList[] = $this->getInsert(
            0,
            'Gross Profit ',
            'grand',
            $G_JAN != 0 ? $this->numberServices->AcctFormat($G_JAN) : '-',
            $G_FEB != 0 ? $this->numberServices->AcctFormat($G_FEB) : '-',
            $G_MAR != 0 ? $this->numberServices->AcctFormat($G_MAR) : '-',
            $G_APR != 0 ? $this->numberServices->AcctFormat($G_APR) : '-',
            $G_MAY != 0 ? $this->numberServices->AcctFormat($G_MAY) : '-',
            $G_JUN != 0 ? $this->numberServices->AcctFormat($G_JUN) : '-',
            $G_JUL != 0 ? $this->numberServices->AcctFormat($G_JUL) : '-',
            $G_AUG != 0 ? $this->numberServices->AcctFormat($G_AUG) : '-',
            $G_SEP != 0 ? $this->numberServices->AcctFormat($G_SEP) : '-',
            $G_OCT != 0 ? $this->numberServices->AcctFormat($G_OCT) : '-',
            $G_NOV != 0 ? $this->numberServices->AcctFormat($G_NOV) : '-',
            $G_DEC != 0 ? $this->numberServices->AcctFormat($G_DEC) : '-',
            $G_TOTAL != 0 ? $this->numberServices->AcctFormat($G_TOTAL) : '-'
        );

        $otherincome = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([13], $this->YEAR, $this->LOCATION_ID, true, $this->isRun, $this->isFocus);
        $i           = $this->SetData($otherincome, "");

        $expense = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([12], $this->YEAR, $this->LOCATION_ID, false, $this->isRun, $this->isFocus);
        $e       = $this->SetData($expense, "Operating Expenses");
        // operating profit
        $OP_JAN   = $G_JAN - $e['JAN'];
        $OP_FEB   = $G_FEB - $e['FEB'];
        $OP_MAR   = $G_MAR - $e['MAR'];
        $OP_APR   = $G_APR - $e['APR'];
        $OP_MAY   = $G_MAY - $e['MAY'];
        $OP_JUN   = $G_JUN - $e['JUN'];
        $OP_JUL   = $G_JUL - $e['JUL'];
        $OP_AUG   = $G_AUG - $e['AUG'];
        $OP_SEP   = $G_SEP - $e['SEP'];
        $OP_OCT   = $G_OCT - $e['OCT'];
        $OP_NOV   = $G_NOV - $e['NOV'];
        $OP_DEC   = $G_DEC - $e['DEC'];
        $OP_TOTAL = $G_TOTAL - $e['TOTAL'];

        $this->dataList[] = $this->getInsert(
            0,
            'Operating Proft ',
            'grand',
            $OP_JAN != 0 ? $this->numberServices->AcctFormat($OP_JAN) : '-',
            $OP_FEB != 0 ? $this->numberServices->AcctFormat($OP_FEB) : '-',
            $OP_MAR != 0 ? $this->numberServices->AcctFormat($OP_MAR) : '-',
            $OP_APR != 0 ? $this->numberServices->AcctFormat($OP_APR) : '-',
            $OP_MAY != 0 ? $this->numberServices->AcctFormat($OP_MAY) : '-',
            $OP_JUN != 0 ? $this->numberServices->AcctFormat($OP_JUN) : '-',
            $OP_JUL != 0 ? $this->numberServices->AcctFormat($OP_JUL) : '-',
            $OP_AUG != 0 ? $this->numberServices->AcctFormat($OP_AUG) : '-',
            $OP_SEP != 0 ? $this->numberServices->AcctFormat($OP_SEP) : '-',
            $OP_OCT != 0 ? $this->numberServices->AcctFormat($OP_OCT) : '-',
            $OP_NOV != 0 ? $this->numberServices->AcctFormat($OP_NOV) : '-',
            $OP_DEC != 0 ? $this->numberServices->AcctFormat($OP_DEC) : '-',
            $OP_TOTAL != 0 ? $this->numberServices->AcctFormat($OP_TOTAL) : '-'
        );

        $otherExpense = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([14], $this->YEAR, $this->LOCATION_ID, true, $this->isRun, $this->isFocus);
        $ex           = $this->SetData($otherExpense, "");

        // NET profit
        $NET_JAN   = $OP_JAN + $i['JAN'] - $ex['JAN'];
        $NET_FEB   = $OP_FEB + $i['FEB'] - $ex['FEB'];
        $NET_MAR   = $OP_MAR + $i['MAR'] - $ex['MAR'];
        $NET_APR   = $OP_APR + $i['APR'] - $ex['APR'];
        $NET_MAY   = $OP_MAY + $i['MAY'] - $ex['MAY'];
        $NET_JUN   = $OP_JUN + $i['JUN'] - $ex['JUN'];
        $NET_JUL   = $OP_JUL + $i['JUL'] - $ex['JUL'];
        $NET_AUG   = $OP_JUL + $i['AUG'] - $ex['AUG'];
        $NET_SEP   = $OP_SEP + $i['SEP'] - $ex['SEP'];
        $NET_OCT   = $OP_OCT + $i['OCT'] - $ex['OCT'];
        $NET_NOV   = $OP_NOV + $i['NOV'] - $ex['NOV'];
        $NET_DEC   = $OP_DEC + $i['DEC'] - $ex['DEC'];
        $NET_TOTAL = $OP_TOTAL + $i['TOTAL'] - $ex['TOTAL'];

        $this->dataList[] = $this->getInsert(
            0,
            'Net Proft ',
            'grand',
            $NET_JAN != 0 ? $this->numberServices->AcctFormat($NET_JAN) : '-',
            $NET_FEB != 0 ? $this->numberServices->AcctFormat($NET_FEB) : '-',
            $NET_MAR != 0 ? $this->numberServices->AcctFormat($NET_MAR) : '-',
            $NET_APR != 0 ? $this->numberServices->AcctFormat($NET_APR) : '-',
            $NET_MAY != 0 ? $this->numberServices->AcctFormat($NET_MAY) : '-',
            $NET_JUN != 0 ? $this->numberServices->AcctFormat($NET_JUN) : '-',
            $NET_JUL != 0 ? $this->numberServices->AcctFormat($NET_JUL) : '-',
            $NET_AUG != 0 ? $this->numberServices->AcctFormat($NET_AUG) : '-',
            $NET_SEP != 0 ? $this->numberServices->AcctFormat($NET_SEP) : '-',
            $NET_OCT != 0 ? $this->numberServices->AcctFormat($NET_OCT) : '-',
            $NET_NOV != 0 ? $this->numberServices->AcctFormat($NET_NOV) : '-',
            $NET_DEC != 0 ? $this->numberServices->AcctFormat($NET_DEC) : '-',
            $NET_TOTAL != 0 ? $this->numberServices->AcctFormat($NET_TOTAL) : '-'
        );

    }

    private function SetData($list, string $title, bool $notToDisplay = false): array
    {

        $JAN   = 0;
        $FEB   = 0;
        $MAR   = 0;
        $APR   = 0;
        $MAY   = 0;
        $JUN   = 0;
        $JUL   = 0;
        $AUG   = 0;
        $SEP   = 0;
        $OCT   = 0;
        $NOV   = 0;
        $DEC   = 0;
        $TOTAL = 0;

        $T_JAN   = 0;
        $T_FEB   = 0;
        $T_MAR   = 0;
        $T_APR   = 0;
        $T_MAY   = 0;
        $T_JUN   = 0;
        $T_JUL   = 0;
        $T_AUG   = 0;
        $T_SEP   = 0;
        $T_OCT   = 0;
        $T_NOV   = 0;
        $T_DEC   = 0;
        $T_TOTAL = 0;

        $TMP      = -1;
        $TMP_NAME = "";
        if ($title != "") {
            if (! $notToDisplay) {
                $this->dataList[] = $this->getInsert(0, $title, 'grand');
            }
        }

        foreach ($list as $data) {

            $JAN   += $data->JAN;
            $FEB   += $data->FEB;
            $MAR   += $data->MAR;
            $APR   += $data->APR;
            $MAY   += $data->MAY;
            $JUN   += $data->JUN;
            $JUL   += $data->JUL;
            $AUG   += $data->AUG;
            $SEP   += $data->SEP;
            $OCT   += $data->OCT;
            $NOV   += $data->NOV;
            $DEC   += $data->DEC;
            $TOTAL += $data->TOTAL;

            if ($TMP == -1) {
                if (! $notToDisplay) {
                    $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                }
                $TMP_NAME = $data->TYPE_NAME;
            } elseif ($TMP != $data->TYPE) {
                if (! $notToDisplay) {
                    $this->dataList[] = $this->getInsert(
                        0,
                        ' Total ' . $TMP_NAME,
                        'total',
                        $T_JAN != 0 ? $this->numberServices->AcctFormat($T_JAN) : '-',
                        $T_FEB != 0 ? $this->numberServices->AcctFormat($T_FEB) : '-',
                        $T_MAR != 0 ? $this->numberServices->AcctFormat($T_MAR) : '-',
                        $T_APR != 0 ? $this->numberServices->AcctFormat($T_APR) : '-',
                        $T_MAY != 0 ? $this->numberServices->AcctFormat($T_MAY) : '-',
                        $T_JUN != 0 ? $this->numberServices->AcctFormat($T_JUN) : '-',
                        $T_JUL != 0 ? $this->numberServices->AcctFormat($T_JUL) : '-',
                        $T_AUG != 0 ? $this->numberServices->AcctFormat($T_AUG) : '-',
                        $T_SEP != 0 ? $this->numberServices->AcctFormat($T_SEP) : '-',
                        $T_OCT != 0 ? $this->numberServices->AcctFormat($T_OCT) : '-',
                        $T_NOV != 0 ? $this->numberServices->AcctFormat($T_NOV) : '-',
                        $T_DEC != 0 ? $this->numberServices->AcctFormat($T_DEC) : '-',
                        $T_TOTAL != 0 ? $this->numberServices->AcctFormat($T_TOTAL) : '-'
                    );
                }

                //CLEAR
                $T_JAN   = 0;
                $T_FEB   = 0;
                $T_MAR   = 0;
                $T_APR   = 0;
                $T_MAY   = 0;
                $T_JUN   = 0;
                $T_JUL   = 0;
                $T_AUG   = 0;
                $T_SEP   = 0;
                $T_OCT   = 0;
                $T_NOV   = 0;
                $T_DEC   = 0;
                $T_TOTAL = 0;

                if (! $notToDisplay) {
                    $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                }
                $TMP_NAME = $data->TYPE_NAME;
            }
            if (! $notToDisplay) {
                $this->dataList[] = $this->getInsert(
                    $data->ID,
                    '   ' . $data->ACCOUNT_NAME,
                    $data->TYPE_NAME,
                    $data->JAN != 0 ? $this->numberServices->AcctFormat($data->JAN) : '-',
                    $data->FEB != 0 ? $this->numberServices->AcctFormat($data->FEB) : '-',
                    $data->MAR != 0 ? $this->numberServices->AcctFormat($data->MAR) : '-',
                    $data->APR != 0 ? $this->numberServices->AcctFormat($data->APR) : '-',
                    $data->MAY != 0 ? $this->numberServices->AcctFormat($data->MAY) : '-',
                    $data->JUN != 0 ? $this->numberServices->AcctFormat($data->JUN) : '-',
                    $data->JUL != 0 ? $this->numberServices->AcctFormat($data->JUL) : '-',
                    $data->AUG != 0 ? $this->numberServices->AcctFormat($data->AUG) : '-',
                    $data->SEP != 0 ? $this->numberServices->AcctFormat($data->SEP) : '-',
                    $data->OCT != 0 ? $this->numberServices->AcctFormat($data->OCT) : '-',
                    $data->NOV != 0 ? $this->numberServices->AcctFormat($data->NOV) : '-',
                    $data->DEC != 0 ? $this->numberServices->AcctFormat($data->DEC) : '-',
                    $data->TOTAL != 0 ? $this->numberServices->AcctFormat($data->TOTAL) : '-'
                );
            }

            $T_JAN   += $data->JAN;
            $T_FEB   += $data->FEB;
            $T_MAR   += $data->MAR;
            $T_APR   += $data->APR;
            $T_MAY   += $data->MAY;
            $T_JUN   += $data->JUN;
            $T_JUL   += $data->JUL;
            $T_AUG   += $data->AUG;
            $T_SEP   += $data->SEP;
            $T_OCT   += $data->OCT;
            $T_NOV   += $data->NOV;
            $T_DEC   += $data->DEC;
            $T_TOTAL += $data->TOTAL;

            $TMP = (int) $data->TYPE;

        }
        if ($TMP_NAME != '') {
            if (! $notToDisplay) {
                $this->dataList[] = $this->getInsert(
                    0,
                    ' Total ' . $TMP_NAME,
                    'total',
                    $T_JAN != 0 ? $this->numberServices->AcctFormat($T_JAN) : '-',
                    $T_FEB != 0 ? $this->numberServices->AcctFormat($T_FEB) : '-',
                    $T_MAR != 0 ? $this->numberServices->AcctFormat($T_MAR) : '-',
                    $T_APR != 0 ? $this->numberServices->AcctFormat($T_APR) : '-',
                    $T_MAY != 0 ? $this->numberServices->AcctFormat($T_MAY) : '-',
                    $T_JUN != 0 ? $this->numberServices->AcctFormat($T_JUN) : '-',
                    $T_JUL != 0 ? $this->numberServices->AcctFormat($T_JUL) : '-',
                    $T_AUG != 0 ? $this->numberServices->AcctFormat($T_AUG) : '-',
                    $T_SEP != 0 ? $this->numberServices->AcctFormat($T_SEP) : '-',
                    $T_OCT != 0 ? $this->numberServices->AcctFormat($T_OCT) : '-',
                    $T_NOV != 0 ? $this->numberServices->AcctFormat($T_NOV) : '-',
                    $T_DEC != 0 ? $this->numberServices->AcctFormat($T_DEC) : '-',
                    $T_TOTAL != 0 ? $this->numberServices->AcctFormat($T_TOTAL) : '-'
                );
            }
        }

        //CLEAR
        $T_JAN   = 0;
        $T_FEB   = 0;
        $T_MAR   = 0;
        $T_APR   = 0;
        $T_MAY   = 0;
        $T_JUN   = 0;
        $T_JUL   = 0;
        $T_AUG   = 0;
        $T_SEP   = 0;
        $T_OCT   = 0;
        $T_NOV   = 0;
        $T_DEC   = 0;
        $T_TOTAL = 0;

        if ($title != "") {
            if (! $notToDisplay) {
                $this->dataList[] = $this->getInsert(
                    0,
                    'Total ' . $title,
                    'grand',
                    $JAN != 0 ? $this->numberServices->AcctFormat($JAN) : '-',
                    $FEB != 0 ? $this->numberServices->AcctFormat($FEB) : '-',
                    $MAR != 0 ? $this->numberServices->AcctFormat($MAR) : '-',
                    $APR != 0 ? $this->numberServices->AcctFormat($APR) : '-',
                    $MAY != 0 ? $this->numberServices->AcctFormat($MAY) : '-',
                    $JUN != 0 ? $this->numberServices->AcctFormat($JUN) : '-',
                    $JUL != 0 ? $this->numberServices->AcctFormat($JUL) : '-',
                    $AUG != 0 ? $this->numberServices->AcctFormat($AUG) : '-',
                    $SEP != 0 ? $this->numberServices->AcctFormat($SEP) : '-',
                    $OCT != 0 ? $this->numberServices->AcctFormat($OCT) : '-',
                    $NOV != 0 ? $this->numberServices->AcctFormat($NOV) : '-',
                    $DEC != 0 ? $this->numberServices->AcctFormat($DEC) : '-',
                    $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-'
                );
            }
        }

        // return total
        return [
            'JAN'   => $JAN,
            'FEB'   => $FEB,
            'MAR'   => $MAR,
            'APR'   => $APR,
            'MAY'   => $MAY,
            'JUN'   => $JUN,
            'JUL'   => $JUL,
            'AUG'   => $AUG,
            'SEP'   => $SEP,
            'OCT'   => $OCT,
            'NOV'   => $NOV,
            'DEC'   => $DEC,
            'TOTAL' => $TOTAL,
        ];
    }

    private function getInsert(int $ID, string $NAME, string $TYPE, string $JAN = '', string $FEB = '', string $MAR = '', string $APR = '', string $MAY = '', string $JUN = '', string $JUL = '', string $AUG = '', string $SEP = '', string $OCT = '', string $NOV = '', string $DEC = '', string $TOTAL = ''): array
    {

        return [
            'ACCOUNT_ID'   => $ID,
            'ACCOUNT_NAME' => $NAME,
            'ACCOUNT_TYPE' => $TYPE,
            'JAN'          => $JAN,
            'FEB'          => $FEB,
            'MAR'          => $MAR,
            'APR'          => $APR,
            'MAY'          => $MAY,
            'JUN'          => $JUN,
            'JUL'          => $JUL,
            'AUG'          => $AUG,
            'SEP'          => $SEP,
            'OCT'          => $OCT,
            'NOV'          => $NOV,
            'DEC'          => $DEC,
            'TOTAL'        => $TOTAL,
        ];

    }
    public function openAccountDetails(int $ACCOUNT_ID, int $MONTH)
    {

        $this->dispatch('open-income-account-details', result: ['ACCOUNT_ID' => $ACCOUNT_ID, 'MONTH' => $MONTH, 'YEAR' => $this->YEAR, 'LOCATION_ID' => $this->LOCATION_ID]);
    }

    #[On('export-monthly-request')]
    public function export()
    {

        if (! $this->dataList) {
            session()->flash('error', 'Please click geenerate first ');
            return;
        }

        try {

            $headers = ['ACCOUNT_NAME', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC', 'TOTAL']; // Could be dynamic based on UI
            $rowdata = [];
            foreach ($this->dataList as $item) {
                $rowdata[] = [

                    'ACCOUNT_NAME' => $item['ACCOUNT_NAME'],
                    'JAN'          => $item['JAN'],
                    'FEB'          => $item['FEB'],
                    'MAR'          => $item['MAR'],
                    'APR'          => $item['APR'],
                    'MAY'          => $item['MAY'],
                    'JUN'          => $item['JUN'],
                    'JUL'          => $item['JUL'],
                    'AUG'          => $item['AUG'],
                    'SEP'          => $item['SEP'],
                    'OCT'          => $item['OCT'],
                    'NOV'          => $item['NOV'],
                    'DEC'          => $item['DEC'],
                    'TOTAL'        => $item['TOTAL'],
                ];
            }

            return Excel::download(new DynamicExport($headers, $rowdata), 'Income_Statement_ByMonthly.xlsx');

        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.income-statement.income-statement-monthly');
    }
}
