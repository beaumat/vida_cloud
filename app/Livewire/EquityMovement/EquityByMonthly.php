<?php

namespace App\Livewire\EquityMovement;

use App\Services\FinancialStatementServices;
use App\Services\NumberServices;
use Livewire\Attributes\On;
use Livewire\Component;

class EquityByMonthly extends Component
{

    public $isFocus = true;
    public $isRunning = false;
    public $YEAR;
    public $LOCATION_ID;
    public $dataList = [];
    private $financialStatementServices;

    private $numberServices;
    public function boot(FinancialStatementServices $financialStatementServices, NumberServices $numberServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->numberServices = $numberServices;
    }
    #[On('equity-movement-monthly')]

    public function generate($result)
    {
        $this->dataList = [];
        $this->YEAR = $result['YEAR'];
        $this->LOCATION_ID = $result['LOCATION_ID'];

      
        $this->equitySide();

    }

    private function equitySide()
    {

        $this->dataList[] = $this->getInsert(
            0,
            "EQUITY",
            "grand"
        );
        $equityList = $this->financialStatementServices->getBalanceSheetAccountTypeListByMonth([9], $this->YEAR, $this->LOCATION_ID, true, $this->isRunning, $this->isFocus);
        $e = $this->SetData($equityList, '', true, true);
        $dataIS = $this->getIncomeStatement(); // Current Year Earnings 

     

        $E_JAN = (float) $dataIS['JAN'] + $e['JAN'];
        $E_FEB = (float) $dataIS['FEB'] + $e['FEB'];
        $E_MAR = (float) $dataIS['MAR'] + $e['MAR'];
        $E_APR = (float) $dataIS['APR'] + $e['APR'];
        $E_MAY = (float) $dataIS['MAY'] + $e['MAY'];
        $E_JUN = (float) $dataIS['JUN'] + $e['JUN'];
        $E_JUL = (float) $dataIS['JUL'] + $e['JUL'];
        $E_AUG = (float) $dataIS['AUG'] + $e['AUG'];
        $E_SEP = (float) $dataIS['SEP'] + $e['SEP'];
        $E_OCT = (float) $dataIS['OCT'] + $e['OCT'];
        $E_NOV = (float) $dataIS['NOV'] + $e['NOV'];
        $E_DEC = (float) $dataIS['DEC'] + $e['DEC'];
        $E_TOTAL = (float) $dataIS['TOTAL'] + $e['TOTAL'];


        $this->dataList[] = $this->getInsert(
            0,
            "TOTAL EQUITY",
            "grand",
            $E_JAN != 0 ? $this->numberServices->AcctFormat($E_JAN) : '-',
            $E_FEB != 0 ? $this->numberServices->AcctFormat($E_FEB) : '-',
            $E_MAR != 0 ? $this->numberServices->AcctFormat($E_MAR) : '-',
            $E_APR != 0 ? $this->numberServices->AcctFormat($E_APR) : '-',
            $E_MAY != 0 ? $this->numberServices->AcctFormat($E_MAY) : '-',
            $E_JUN != 0 ? $this->numberServices->AcctFormat($E_JUN) : '-',
            $E_JUL != 0 ? $this->numberServices->AcctFormat($E_JUL) : '-',
            $E_AUG != 0 ? $this->numberServices->AcctFormat($E_AUG) : '-',
            $E_SEP != 0 ? $this->numberServices->AcctFormat($E_SEP) : '-',
            $E_OCT != 0 ? $this->numberServices->AcctFormat($E_OCT) : '-',
            $E_NOV != 0 ? $this->numberServices->AcctFormat($E_NOV) : '-',
            $E_DEC != 0 ? $this->numberServices->AcctFormat($E_DEC) : '-',
            $E_TOTAL != 0 ? $this->numberServices->AcctFormat($E_TOTAL) : '-',
        );

    }
    private function SetData($list, string $title, bool $notToDisplay = false, bool $showItem = true): array
    {


        $JAN = 0;
        $FEB = 0;
        $MAR = 0;
        $APR = 0;
        $MAY = 0;
        $JUN = 0;
        $JUL = 0;
        $AUG = 0;
        $SEP = 0;
        $OCT = 0;
        $NOV = 0;
        $DEC = 0;
        $TOTAL = 0;

        $T_JAN = 0;
        $T_FEB = 0;
        $T_MAR = 0;
        $T_APR = 0;
        $T_MAY = 0;
        $T_JUN = 0;
        $T_JUL = 0;
        $T_AUG = 0;
        $T_SEP = 0;
        $T_OCT = 0;
        $T_NOV = 0;
        $T_DEC = 0;
        $T_TOTAL = 0;

        $TMP = -1;
        $TMP_NAME = "";
        if (!$notToDisplay) {
            $this->dataList[] = $this->getInsert(0, $title, 'grand');
        }


        foreach ($list as $data) {

            $JAN += $data->JAN;
            $FEB += $data->FEB;
            $MAR += $data->MAR;
            $APR += $data->APR;
            $MAY += $data->MAY;
            $JUN += $data->JUN;
            $JUL += $data->JUL;
            $AUG += $data->AUG;
            $SEP += $data->SEP;
            $OCT += $data->OCT;
            $NOV += $data->NOV;
            $DEC += $data->DEC;
            $TOTAL += $data->TOTAL;

            if ($TMP == -1) {
                if (!$notToDisplay) {
                    $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                }

                $TMP_NAME = $data->TYPE_NAME;
            } elseif ($TMP <> $data->TYPE) {
                if (!$notToDisplay) {
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
                $T_JAN = 0;
                $T_FEB = 0;
                $T_MAR = 0;
                $T_APR = 0;
                $T_MAY = 0;
                $T_JUN = 0;
                $T_JUL = 0;
                $T_AUG = 0;
                $T_SEP = 0;
                $T_OCT = 0;
                $T_NOV = 0;
                $T_DEC = 0;
                $T_TOTAL = 0;


                if (!$notToDisplay) {
                    $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                }
                $TMP_NAME = $data->TYPE_NAME;
            }


            if (!$notToDisplay || $showItem) {

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

            $T_JAN += $data->JAN;
            $T_FEB += $data->FEB;
            $T_MAR += $data->MAR;
            $T_APR += $data->APR;
            $T_MAY += $data->MAY;
            $T_JUN += $data->JUN;
            $T_JUL += $data->JUL;
            $T_AUG += $data->AUG;
            $T_SEP += $data->SEP;
            $T_OCT += $data->OCT;
            $T_NOV += $data->NOV;
            $T_DEC += $data->DEC;
            $T_TOTAL += $data->TOTAL;

            $TMP = (int) $data->TYPE;

        }
        if ($TMP_NAME <> '') {
            if (!$notToDisplay) {
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
        $T_JAN = 0;
        $T_FEB = 0;
        $T_MAR = 0;
        $T_APR = 0;
        $T_MAY = 0;
        $T_JUN = 0;
        $T_JUL = 0;
        $T_AUG = 0;
        $T_SEP = 0;
        $T_OCT = 0;
        $T_NOV = 0;
        $T_DEC = 0;
        $T_TOTAL = 0;

        if ($title <> '') {
            if (!$notToDisplay) {
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
            'JAN' => $JAN,
            'FEB' => $FEB,
            'MAR' => $MAR,
            'APR' => $APR,
            'MAY' => $MAY,
            'JUN' => $JUN,
            'JUL' => $JUL,
            'AUG' => $AUG,
            'SEP' => $SEP,
            'OCT' => $OCT,
            'NOV' => $NOV,
            'DEC' => $DEC,
            'TOTAL' => $TOTAL
        ];
    }

    public function getIncomeStatement(): array
    {

        $revenueList = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([10], $this->YEAR, $this->LOCATION_ID, true, $this->isRunning, $this->isFocus);
        $r = $this->SetData($revenueList, "Trading Income", true, false);

        $costList = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([11], $this->YEAR, $this->LOCATION_ID, false, $this->isRunning, $this->isFocus);
        $c = $this->SetData($costList, "Cost of Sales", true, false);

        $G_JAN = $r['JAN'] - $c['JAN'];
        $G_FEB = $r['FEB'] - $c['FEB'];
        $G_MAR = $r['MAR'] - $c['MAR'];
        $G_APR = $r['APR'] - $c['APR'];
        $G_MAY = $r['MAY'] - $c['MAY'];
        $G_JUN = $r['JUN'] - $c['JUN'];
        $G_JUL = $r['JUL'] - $c['JUL'];
        $G_AUG = $r['AUG'] - $c['AUG'];
        $G_SEP = $r['SEP'] - $c['SEP'];
        $G_OCT = $r['OCT'] - $c['OCT'];
        $G_NOV = $r['NOV'] - $c['NOV'];
        $G_DEC = $r['DEC'] - $c['DEC'];
        $G_TOTAL = $r['TOTAL'] - $c['TOTAL'];

        $otherincome = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([13], $this->YEAR, $this->LOCATION_ID, true, $this->isRunning, $this->isFocus);
        $i = $this->SetData($otherincome, "", true, false);

        $expense = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([12], $this->YEAR, $this->LOCATION_ID, false, $this->isRunning, $this->isFocus);
        $e = $this->SetData($expense, "Operating Expenses", true, false);
        // operating profit
        $OP_JAN = $G_JAN - $e['JAN'];
        $OP_FEB = $G_FEB - $e['FEB'];
        $OP_MAR = $G_MAR - $e['MAR'];
        $OP_APR = $G_APR - $e['APR'];
        $OP_MAY = $G_MAY - $e['MAY'];
        $OP_JUN = $G_JUN - $e['JUN'];
        $OP_JUL = $G_JUL - $e['JUL'];
        $OP_AUG = $G_AUG - $e['AUG'];
        $OP_SEP = $G_SEP - $e['SEP'];
        $OP_OCT = $G_OCT - $e['OCT'];
        $OP_NOV = $G_NOV - $e['NOV'];
        $OP_DEC = $G_DEC - $e['DEC'];
        $OP_TOTAL = $G_TOTAL - $e['TOTAL'];

        $otherExpense = $this->financialStatementServices->getIncomeStatementAccountTypeByMonth([14], $this->YEAR, $this->LOCATION_ID, false, $this->isRunning, $this->isFocus);
        $ex = $this->SetData($otherExpense, "", true, false);

        // NET profit
        $NET_JAN = $OP_JAN + $i['JAN'] - $ex['JAN'];
        $NET_FEB = $OP_FEB + $i['FEB'] - $ex['FEB'];
        $NET_MAR = $OP_MAR + $i['MAR'] - $ex['MAR'];
        $NET_APR = $OP_APR + $i['APR'] - $ex['APR'];
        $NET_MAY = $OP_MAY + $i['MAY'] - $ex['MAY'];
        $NET_JUN = $OP_JUN + $i['JUN'] - $ex['JUN'];
        $NET_JUL = $OP_JUL + $i['JUL'] - $ex['JUL'];
        $NET_AUG = $OP_AUG + $i['AUG'] - $ex['AUG'];
        $NET_SEP = $OP_SEP + $i['SEP'] - $ex['SEP'];
        $NET_OCT = $OP_OCT + $i['OCT'] - $ex['OCT'];
        $NET_NOV = $OP_NOV + $i['NOV'] - $ex['NOV'];
        $NET_DEC = $OP_DEC + $i['DEC'] - $ex['DEC'];
        $NET_TOTAL = $OP_TOTAL + $i['TOTAL'] - $ex['TOTAL'];


        // MUST BE
        $this->dataList[] = $this->getInsert(
            0,
            'Current Year Earnings ',
            '',
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




        return [
            'JAN' => $NET_JAN,
            'FEB' => $NET_FEB,
            'MAR' => $NET_MAR,
            'APR' => $NET_APR,
            'MAY' => $NET_MAY,
            'JUN' => $NET_JUN,
            'JUL' => $NET_JUL,
            'AUG' => $NET_AUG,
            'SEP' => $NET_SEP,
            'OCT' => $NET_OCT,
            'NOV' => $NET_NOV,
            'DEC' => $NET_DEC,
            'TOTAL' => $NET_TOTAL
        ];
    }


    private function getInsert(int $ID, string $NAME, string $TYPE, string $JAN = '', string $FEB = '', string $MAR = '', string $APR = '', string $MAY = '', string $JUN = '', string $JUL = '', string $AUG = '', string $SEP = '', string $OCT = '', string $NOV = '', string $DEC = '', string $TOTAL = ''): array
    {

        return [
            'ACCOUNT_ID' => $ID,
            'ACCOUNT_NAME' => $NAME,
            'ACCOUNT_TYPE' => $TYPE,
            'JAN' => $JAN,
            'FEB' => $FEB,
            'MAR' => $MAR,
            'APR' => $APR,
            'MAY' => $MAY,
            'JUN' => $JUN,
            'JUL' => $JUL,
            'AUG' => $AUG,
            'SEP' => $SEP,
            'OCT' => $OCT,
            'NOV' => $NOV,
            'DEC' => $DEC,
            'TOTAL' => $TOTAL


        ];

    }

    public function render()
    {
        return view('livewire.equity-movement.equity-by-monthly');
    }
}
