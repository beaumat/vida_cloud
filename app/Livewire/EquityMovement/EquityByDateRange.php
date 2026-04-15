<?php

namespace App\Livewire\EquityMovement;

use App\Services\FinancialStatementServices;
use App\Services\NumberServices;
use Livewire\Attributes\On;
use Livewire\Component;

class EquityByDateRange extends Component
{
    public $DATE_FROM;
    public $DATE_TO;
    public $LOCATION_ID;

    public $dataList = [];
    private $financialStatementServices;
    private $numberServices;
    public function boot(FinancialStatementServices $financialStatementServices, NumberServices $numberServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->numberServices = $numberServices;
    }

    #[On('equity-movement-date-range')]
    public function generate($result)
    {
        $this->DATE_FROM = $result['DATE_FROM'];
        $this->DATE_TO = $result['DATE_TO'];
        $this->LOCATION_ID = $result['LOCATION_ID'];

        $this->dataList = [];
        $this->equitySide();
    }
    private function equitySide()
    {


        $equityList = $this->financialStatementServices->getBalanceSheetAccountTypeListByDateRange([9], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, true);
        $e = $this->SetData($equityList, '', false);
        $dataIS = $this->getIncomeStatement();
        $TOTAL = (float) $e['TOTAL'] + $dataIS['TOTAL'];

        $this->dataList[] = $this->getInsert( 0, 'Total Equity ', 'grand', $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-' );
    }
    private function SetData($list, string $title, bool $notToDisplay = false,): array
    {

        $TOTAL = 0;

        $T_TOTAL = 0;

        $TMP = -1;
        $TMP_NAME = "";
  


        foreach ($list as $data) {
            $TOTAL += $data->TOTAL;

            if ($TMP == -1) {
                if (!$notToDisplay) {
                    // $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                }

                $TMP_NAME = $data->TYPE_NAME;
            } elseif ($TMP <> $data->TYPE) {
                if (!$notToDisplay) {
                    $this->dataList[] = $this->getInsert(
                        0,
                        ' Total ' . $TMP_NAME,
                        'total',

                        $T_TOTAL != 0 ? $this->numberServices->AcctFormat($T_TOTAL) : '-'
                    );
                }

                //CLEAR

                $T_DEC = 0;
                $T_TOTAL = 0;


                if (!$notToDisplay) {
                    $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                }
                $TMP_NAME = $data->TYPE_NAME;
            }
            if (!$notToDisplay ) {
                $this->dataList[] = $this->getInsert(
                    $data->ID,
                    '   ' . $data->ACCOUNT_NAME,
                    $data->TYPE_NAME,

                    $data->TOTAL != 0 ? $this->numberServices->AcctFormat($data->TOTAL) : '-'
                );
            }

            $T_TOTAL += $data->TOTAL;

            $TMP = (int) $data->TYPE;

        }


        //CLEAR
        $T_TOTAL = 0;
        if ($title <> '') {
            if (!$notToDisplay) {
                $this->dataList[] = $this->getInsert(
                    0,
                    'Total ' . $title,
                    'grand',
                    $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-'
                );
            }
        }
        // return total
        return [

            'TOTAL' => $TOTAL
        ];
    }

    public function getIncomeStatement(): array
    {
        $revenueList = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([10], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, true);
        $r = $this->SetData($revenueList, "Trading Income", true);

        $costList = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([11], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, false);
        $c = $this->SetData($costList, "Cost of Sales", true);


        $G_TOTAL = $r['TOTAL'] - $c['TOTAL'];

        $otherincome = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([13], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, true);
        $i = $this->SetData($otherincome, "", true);

        $expense = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([12], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, false);
        $e = $this->SetData($expense, "Operating Expenses", true);
        // operating profit

        $OP_TOTAL = $G_TOTAL - $e['TOTAL'];

        $otherExpense = $this->financialStatementServices->getIncomeStatementAccountTypeByDate([14], $this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, true);
        $ex = $this->SetData($otherExpense, "", true);

        // NET profit

        $NET_TOTAL = $OP_TOTAL + $i['TOTAL'] - $ex['TOTAL'];


        // MUST BE
        $this->dataList[] = $this->getInsert(
            0,
            'Current Year Earnings ',
            '',
            $NET_TOTAL != 0 ? $this->numberServices->AcctFormat($NET_TOTAL) : '-'
        );

        return [
            'TOTAL' => $NET_TOTAL
        ];
    }

    public function getIncomeStatementLastRange()
    {
        $LASTYEAR = $this->YEAR - 1;

    }

    private function getInsert(int $ID, string $NAME, string $TYPE, string $TOTAL = ''): array
    {

        return [
            'ACCOUNT_ID' => $ID,
            'ACCOUNT_NAME' => $NAME,
            'ACCOUNT_TYPE' => $TYPE,
            'TOTAL' => $TOTAL
        ];

    }
    public function render()
    {
        return view('livewire.equity-movement.equity-by-date-range');
    }
}
