<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ARAgingExportReport implements FromCollection, ShouldAutoSize
{

    protected  $dataList = [];
    protected  bool $isSummary;
    public function __construct($dataList, bool $isSummary)
    {
        $this->dataList = $dataList;
        $this->isSummary = $isSummary;
    }
    private function SetSummary(string $CUSTOMER, string $CURRENT, string $N1_30, string $N31_60, string $N61_90, string $N90_OVER, string $BALANCE)
    {

        $headers = [
            'CUSTOMER'  => $CUSTOMER,
            'CURRENT'   => $CURRENT,
            '1-30'      => $N1_30,
            '31-60'     => $N31_60,
            '61-90'     => $N61_90,
            '90-OVER'   => $N90_OVER,
            'BALANCE'   => $BALANCE
        ];
        return array_values($headers);
    }
    public function SetDetails(string $DATE, string $REFERENCE, string $CUSTOMER, string $TERMS, string $DUE_DATE, string $AGING, string $OPEN_BALANCE, string $LOCATION)
    {
        $rowData = [
            'DATE'=> $DATE,
            'REFERENCE'        => $REFERENCE,
            'CUSTOMER'         => $CUSTOMER,
            'TERMS'            => $TERMS,
            'DUE-DATE'        => $DUE_DATE,
            'AGING'           => $AGING,
            'OPEN-BALANCE'    =>  $OPEN_BALANCE,
            'LOCATION'        => $LOCATION
            ];

        return array_values($rowData);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $finalData = [];

        if ($this->isSummary) {
            $finalData[] = $this->SetSummary('CUSTOMER', 'CURRENT', '1-30', '1-60', '61-90', '90-OVER', 'BALANCE');
            //  SUMMARY START
            $DUE_CURRENT = 0;
            $DUE_1_30 = 0;
            $DUE_31_60 = 0;
            $DUE_61_90 = 0;
            $DUE_90_OVER = 0;
            $BALANCE = 0;

            foreach ($this->dataList as $list) {
                $finalData[] = $this->SetSummary(
                    $list->CONTACT_NAME,
                    number_format($list->DUE_CURRENT, 2),
                    number_format($list->DUE_1_30, 2),
                    number_format($list->DUE_31_60, 2),
                    number_format($list->DUE_61_90, 2),
                    number_format($list->DUE_90_OVER, 2),
                    number_format($list->BALANCE, 2)
                );
                $DUE_CURRENT = $DUE_CURRENT + $list->DUE_CURRENT;
                $DUE_1_30 = $DUE_1_30 + $list->DUE_1_30;
                $DUE_31_60 = $DUE_31_60 + $list->DUE_31_60;
                $DUE_61_90 = $DUE_61_90 + $list->DUE_61_90;
                $DUE_90_OVER = $DUE_90_OVER + $list->DUE_90_OVER;
                $BALANCE = $BALANCE + $list->BALANCE;
            }

            $finalData[] =   $this->SetSummary('', '', '', '', '', '', '');
            $finalData[] =   $this->SetSummary(
                number_format($DUE_CURRENT, 2),
                number_format($DUE_1_30, 2),
                number_format($DUE_31_60, 2),
                number_format($DUE_61_90, 2),
                number_format($DUE_61_90, 2),
                number_format($DUE_90_OVER, 2),
                number_format($BALANCE, 2)
            );
            // SUMMARY END

        } else {

            $finalData[] = $this->SetDetails('DATE',
                 'REFERENCE',
                 'CUSTOMER',
                 'TERMS',
                 'DUE-DATE',
                 'AGING',
                 'OPEN-BALANCE',
                 'LOCATION');

            $D_CURRENT = false;
            $D_1_30 = false;
            $D_31_60 = false;
            $D_61_90 = false;
            $D_91_OVER = false;

            $TMP_AGING = '';
            $COMPARE = '';
            $RUN_BALANCE = 0;
            $RUN_TOTAL = 0;

            foreach ($this->dataList as $list) {
                if ($list->AGING <= 0) {
                    if ($D_CURRENT == false) {
                        if ($COMPARE != $TMP_AGING && $RUN_BALANCE > 0) {
                            $finalData[] = $this->SetDetails('TOTAL ' .  $TMP_AGING, '', '', '', '', '', number_format($RUN_BALANCE, 2), '');
                        }
                        $finalData[] = $this->SetDetails('CURRENT ', '', '', '', '', '', '', '');
                        $D_CURRENT = true;
                        $TMP_AGING = 'CURRENT';
                        $RUN_BALANCE = 0;
                    }
                } else if ($list->AGING <= 30) {
                    if ($D_1_30 == false) {
                        if ($RUN_BALANCE > 0) {
                            $finalData[] = $this->SetDetails('TOTAL ' .  $TMP_AGING, '', '', '', '', '', number_format($RUN_BALANCE, 2), '');
                        }
                        $finalData[] = $this->SetDetails('1-30 ', '', '', '', '', '', '', '');
                        $D_1_30 = true;
                        $TMP_AGING = '1-30';
                        $RUN_BALANCE = 0;
                    }
                } else if ($list->AGING <= 60) {
                    if ($D_31_60 == false) {
                        if ($RUN_BALANCE > 0) {
                            $finalData[] = $this->SetDetails('TOTAL ' .  $TMP_AGING, '', '', '', '', '', number_format($RUN_BALANCE, 2), '');
                        }
                        $finalData[] = $this->SetDetails('31-60 ', '', '', '', '', '', '', '');
                        $D_31_60 = true;
                        $TMP_AGING = '31-60';
                        $RUN_BALANCE = 0;
                    }
                } else if ($list->AGING <= 90) {
                    if ($D_61_90 == false) {
                        if ($RUN_BALANCE > 0) {
                            $finalData[] = $this->SetDetails('TOTAL ' .  $TMP_AGING, '', '', '', '', '', number_format($RUN_BALANCE, 2), '');
                        }

                        $finalData[] = $this->SetDetails('61-90 ', '', '', '', '', '', '', '');

                        $D_61_90 = true;
                        $TMP_AGING = '61-90';
                        $RUN_BALANCE = 0;
                    }
                } else {

                    if ($D_91_OVER == false) {
                        if ($RUN_BALANCE > 0) {
                            $finalData[] = $this->SetDetails('TOTAL ' .  $TMP_AGING, '', '', '', '', '', number_format($RUN_BALANCE, 2), '');
                        }

                        $finalData[] = $this->SetDetails('91 OVER ', '', '', '', '', '', '', '');

                        $D_91_OVER = true;
                        $TMP_AGING = '91 OVER';
                        $RUN_BALANCE = 0;
                    }
                }

                $RUN_BALANCE = $RUN_BALANCE + $list->BALANCE_DUE;

                $finalData[] = $this->SetDetails(date('M/d/Y', strtotime($list->DATE)), $list->CODE, $list->CONTACT_NAME, $list->PAYMENT_TERMS, date('M/d/Y', strtotime($list->DUE_DATE)), $list->AGING < 1 ? '' : $list->AGING, number_format($list->BALANCE_DUE, 2), $list->LOCATION_NAME);
                $COMPARE = $TMP_AGING;
                $RUN_TOTAL = $RUN_TOTAL + $list->BALANCE_DUE ?? 0;
            }

            $finalData[] = $this->SetDetails('TOTAL ' .  $TMP_AGING, '', '', '', '', '', number_format($RUN_BALANCE, 2), '');
            $finalData[] = $this->SetDetails('', '', '', '', '', '', number_format($RUN_TOTAL, 2), '');
        }
        return collect($finalData);
    }
}
