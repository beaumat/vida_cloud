<?php

namespace App\Services;

use App\Models\CashFlowDetails;
use App\Models\CashFlowHeader;
use App\Models\CashFlowKey;

class CashFlowServices
{
    private int $AR_TYPE_ID = 1;
    private int $CR_TYPE_ID = 2;
    private int $N_CR_TYPE_ID = 4;
    private int  $AP_TYPE_ID = 5;
    private $dateServices;
    private $financialStatementServices;
    public function __construct(DateServices $dateServices, FinancialStatementServices $financialStatementServices)
    {
        $this->dateServices = $dateServices;
        $this->financialStatementServices = $financialStatementServices;
    }
    public function ACCOUNT_BASE_LIST()
    {
        return [
            ['ID' => 0, 'NAME' => 'ACCOUNT_ID'],
            ['ID' => 1, 'NAME' => 'ACCOUNT_TYPE'],
            ['ID' => 2, 'NAME' => 'ACCOUNT_IN'],
            ['ID' => 3, 'NAME' => 'ACCOUNT_TYPE_IN'],
            ['ID' => 4, 'NAME' => 'I/(D) in Cash'],
            ['ID' => 5, 'NAME' => 'Cash End on Last Year']

        ];
    }
    private function getHeader_LINE_NO(int $LOCATION_ID): int
    {
        return (int) CashFlowHeader::where('LOCATION_ID', '=', $LOCATION_ID)
            ->max('LINE_NO');
    }
    public static function getHeader(int $ID)
    {
        $result = CashFlowHeader::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }

        return [];
    }
    public static function getHeaderAmount(int $ID, float $AMOUNT): float
    {
        $data =  self::getHeader($ID);
        if ($data) {
            if ($data->NAME == "END") {
                return $AMOUNT;
            }
            return 0;
        }

        return 0;
    }
    public function StoreHeader(string $NAME, int $LOCATION_ID, int $LINE_NO, bool $INACTIVE = false)
    {
        CashFlowHeader::create([
            'NAME'          => $NAME,
            'LOCATION_ID'   => $LOCATION_ID,
            'LINE_NO'       => $LINE_NO > 0 ? $LINE_NO : $this->getHeader_LINE_NO($LOCATION_ID) + 1,
            'INACTIVE'      => $INACTIVE,
            'RECORDED_ON'   => $this->dateServices->Now()
        ]);
    }
    public function UpdateHeader(int $ID, string $NAME, int $LINE_NO, bool $INACTIVE)
    {

        CashFlowHeader::where('ID', '=', $ID)
            ->update([
                'NAME'      => $NAME,
                'LINE_NO'   => $LINE_NO,
                'INACTIVE'  => $INACTIVE
            ]);
    }
    public function DeleteHeader(int $ID)
    {
        CashFlowHeader::where('ID', '=', $ID)
            ->delete();
    }
    public static function GetHeaderList(int $LOCATION_ID)
    {
        $result = CashFlowHeader::where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->orderBy('LINE_NO')
            ->get();

        return $result;
    }
    private function getDetails_LINE_NO(int $CF_HEADER_ID): int
    {
        return (int) CashFlowDetails::where('CF_HEADER_ID', '=', $CF_HEADER_ID)
            ->max('LINE_NO');
    }
    public static function GetDetails(int $ID)
    {
        $result = CashFlowDetails::where('ID', '=', $ID)->first();
        return $result;
    }
    public function StoreDetails(int $CF_HEADER_ID, string $NAME, int $LINE_NO, bool $IS_TOTAL)
    {
        CashFlowDetails::create([
            'CF_HEADER_ID'  => $CF_HEADER_ID,
            'NAME'          => $NAME,
            'LINE_NO'       => $LINE_NO > 0 ? $LINE_NO : $this->getDetails_LINE_NO($CF_HEADER_ID) + 1,
            'RECORDED_ON'   => $this->dateServices->Now(),
            'INACTIVE'      => false,
            'IS_TOTAL'      => $IS_TOTAL
        ]);
    }
    public function UpdateDetails(int $ID, string $NAME, int $LINE_NO, bool $INACTIVE, bool $IS_TOTAL)
    {
        CashFlowDetails::where('ID', '=',  $ID)
            ->update([
                'NAME'      => $NAME,
                'LINE_NO'   => $LINE_NO,
                'INACTIVE'  => $INACTIVE,
                'IS_TOTAL'  => $IS_TOTAL
            ]);
    }
    public function DeleteDetails(int $ID)
    {
        CashFlowDetails::where('ID', '=',  $ID)->delete();
    }
    public static function GetDetailList(int $CF_HEADER_ID)
    {
        $result = CashFlowDetails::where('CF_HEADER_ID', '=', $CF_HEADER_ID)
            ->where('INACTIVE', '=', false)
            ->orderBy('LINE_NO')
            ->get();

        return $result;
    }
    private function getKey_LINE_NO(int $CS_FLOW_DETAILS_ID): int
    {
        return (int) CashFlowKey::where('CS_FLOW_DETAILS_ID', '=', $CS_FLOW_DETAILS_ID)
            ->max('LINE_NO');
    }
    public function StoreKey(int $CS_FLOW_DETAILS_ID, int $ACCOUNT_BASE, string $ACCOUNT_KEY, bool $DEBIT_DEFAULT, int $LINE_NO, string $NAME)
    {

        CashFlowKey::create([
            'ACCOUNT_BASE'          => $ACCOUNT_BASE,
            'ACCOUNT_KEY'           => $ACCOUNT_KEY,
            'DEBIT_DEFAULT'         => $DEBIT_DEFAULT,
            'LINE_NO'               => $LINE_NO  > 0 ? $LINE_NO : $this->getKey_LINE_NO($CS_FLOW_DETAILS_ID) + 1,
            'INACTIVE'              => false,
            'CS_FLOW_DETAILS_ID'    => $CS_FLOW_DETAILS_ID,
            'NAME'                  => $NAME
        ]);
    }
    public function UpdateKey(int $ID, int $ACCOUNT_BASE, string $ACCOUNT_KEY, bool $DEBIT_DEFAULT, int $LINE_NO, bool $INACTIVE, string $NAME)
    {
        CashFlowKey::where('ID', $ID)
            ->update([
                'ACCOUNT_BASE'  => $ACCOUNT_BASE,
                'ACCOUNT_KEY'   => $ACCOUNT_KEY,
                'DEBIT_DEFAULT' => $DEBIT_DEFAULT,
                'LINE_NO'       => $LINE_NO,
                'INACTIVE'      => $INACTIVE,
                'NAME'          => $NAME
            ]);
    }
    public function DeleteKey(int $ID)
    {
        CashFlowKey::where('ID', '=', $ID)->delete();
    }
    public static function GetKeyList(int $CS_FLOW_DETAILS_ID)
    {
        $result =  CashFlowKey::where('CS_FLOW_DETAILS_ID', '=', $CS_FLOW_DETAILS_ID)
            ->where('INACTIVE', '=', false)
            ->orderBy('LINE_NO')
            ->get();

        return $result;
    }
    public static function GetKey(int $ID)
    {
        $result = CashFlowKey::where('ID', '=', $ID)->first();

        return $result;
    }
    public function CashFlowCompute(int $YEAR, int $MONTH, int $LOCATION_ID)
    {
        $BASE_DATE = $YEAR . '-' . $MONTH . '-15';
        $FIRST_DAY = $this->dateServices->GetFirstDay_Month($BASE_DATE);
        $LAST_DAY =  $this->dateServices->GetLastDay_Month($BASE_DATE);


        if ($YEAR == 2023) {
            return [];
        }

        $NET_INCOME = (float) $this->financialStatementServices->getTotalNetIncome($FIRST_DAY, $LAST_DAY, $LOCATION_ID);

        $DEPRECIATION_EXP = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 32, false);
        $INTEREST_EXP = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 31, false);
        $IMPAIRMENT_LOSS = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 31, false);
        $DISPOSAL_ASSET = 0;
        $INCOME_TAX_EXP = 0;
        $CASH_FLOW_BEFORE_WC_CHANGE =  $NET_INCOME +  $DEPRECIATION_EXP + $INTEREST_EXP + $IMPAIRMENT_LOSS  + $DISPOSAL_ASSET + $INCOME_TAX_EXP;

        $TRADE_N_OTHER_RECEIVABLE = (float) $this->financialStatementServices->getIncomeStatementAccountByTypeSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, $this->AR_TYPE_ID, false);
        $OTHER_CURRENT_ASSET = (float) $this->financialStatementServices->getIncomeStatementAccountByTypeSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, $this->CR_TYPE_ID, false);
        $OTHER_NON_CURRENT_ASSET = (float) $this->financialStatementServices->getIncomeStatementAccountByTypeSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, $this->N_CR_TYPE_ID, false);
        $TRADE_N_OTHER_PAYABLE = (float) $this->financialStatementServices->getIncomeStatementAccountByTypeSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, $this->AP_TYPE_ID, false);
        $CASH_GENERATE_OPENING_ACTIVITIES = $TRADE_N_OTHER_RECEIVABLE + $OTHER_CURRENT_ASSET + $OTHER_NON_CURRENT_ASSET + $TRADE_N_OTHER_PAYABLE + $CASH_FLOW_BEFORE_WC_CHANGE;

        $INCOME_TAX_PAID = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID,  811, false);
        $NET_CASH_OPENING_ACTIVIES = (float) $INCOME_TAX_PAID +  $CASH_GENERATE_OPENING_ACTIVITIES;

        $ADVANCE_TO_RP = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $PROCEED_FROM_SALES_PURCHASE =  (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $INVESTING_ACTIVIES = $ADVANCE_TO_RP + $PROCEED_FROM_SALES_PURCHASE;

        $ADVANCE_FROM_RP = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $PROCEED_FROM_ISSURANCE_TO_STOCK_DEPOSIT = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $LEASE_LIABILITY = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $PROCEED_TO_LOAN_AVAILMENT = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $PAYMENT_OF_DIVIDENDS = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $INTEREST_EXPENSES_PAID = (float) $this->financialStatementServices->getIncomeStatementAccountByIDSum($FIRST_DAY, $LAST_DAY, $LOCATION_ID, 62, false);
        $CASH_GENERATE_FINANCING_ACTIVITIES = $ADVANCE_FROM_RP +  $PROCEED_FROM_ISSURANCE_TO_STOCK_DEPOSIT + $LEASE_LIABILITY + $PROCEED_TO_LOAN_AVAILMENT + $PAYMENT_OF_DIVIDENDS + $INTEREST_EXPENSES_PAID;


        $IN_CASH  = $NET_CASH_OPENING_ACTIVIES +  $INVESTING_ACTIVIES + $CASH_GENERATE_FINANCING_ACTIVITIES;

        if ($MONTH - 1  == 0) {
            $PREV_YEAR = $YEAR - 1;
            $PREV_MONTH = 12;
        } else {
            $PREV_MONTH = $MONTH - 1;
            $PREV_YEAR = $YEAR;
        }
        $PREV_END = 0;
        $PREV_DATA = $this->CashFlowCompute($PREV_YEAR, $PREV_MONTH, $LOCATION_ID);
        if ($PREV_DATA) {
            $PREV_END = (float) $PREV_DATA['END_CASH'];
        }

        $END_CASH = $IN_CASH + $PREV_END;

        return [
            'NET_INCOME' =>  $NET_INCOME,
            'DEPRECIATION_EXP'  =>  $DEPRECIATION_EXP,
            'INTEREST_EXP' => $INTEREST_EXP,
            'IMPAIRMENT_LOSS' =>  $IMPAIRMENT_LOSS,
            'DISPOSAL_ASSET' =>   $DISPOSAL_ASSET,
            'INCOME_TAX_EXP' =>   $INCOME_TAX_EXP,
            'CASH_FLOW_BEFORE_WC_CHANGE' =>   $CASH_FLOW_BEFORE_WC_CHANGE,
            'TRADE_N_OTHER_RECEIVABLE' =>   $TRADE_N_OTHER_RECEIVABLE,
            'OTHER_CURRENT_ASSET' =>  $OTHER_CURRENT_ASSET,
            'OTHER_NON_CURRENT_ASSET'=> $OTHER_NON_CURRENT_ASSET,
            'TRADE_N_OTHER_PAYABLE' =>  $TRADE_N_OTHER_PAYABLE,
            'CASH_GENERATE_OPENING_ACTIVITIES' => $CASH_GENERATE_OPENING_ACTIVITIES,
            'INCOME_TAX_PAID' =>  $INCOME_TAX_PAID,
            'NET_CASH_OPENING_ACTIVIES' => $NET_CASH_OPENING_ACTIVIES,
            'ADVANCE_TO_RP' => $ADVANCE_TO_RP,
            'PROCEED_FROM_SALES_PURCHASE' => $PROCEED_FROM_SALES_PURCHASE,
            'INVESTING_ACTIVIES' =>   $INVESTING_ACTIVIES,
            'ADVANCE_FROM_RP'  =>  $ADVANCE_FROM_RP,
            'PROCEED_FROM_ISSURANCE_TO_STOCK_DEPOSIT' =>  $PROCEED_FROM_ISSURANCE_TO_STOCK_DEPOSIT,
            'LEASE_LIABILITY'  =>  $LEASE_LIABILITY,
            'PROCEED_TO_LOAN_AVAILMENT' => $PROCEED_TO_LOAN_AVAILMENT,
            'PAYMENT_OF_DIVIDENDS'    =>  $PAYMENT_OF_DIVIDENDS,
            'INTEREST_EXPENSES_PAID' =>   $INTEREST_EXPENSES_PAID,
            'CASH_GENERATE_FINANCING_ACTIVITIES' =>  $CASH_GENERATE_FINANCING_ACTIVITIES,
            'IN_CASH'  => $IN_CASH,
            'PREV_END' => $PREV_END,
            'END_CASH' => $END_CASH
        ];
    }
}
