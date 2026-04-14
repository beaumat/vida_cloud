<?php

namespace App\Livewire\FinancialReport;

use App\Exports\CashFlowExport;
use App\Services\CashFlowServices;
use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Cash Flow Report')]
class CashFlowReport extends Component
{

    public float $TOTAL_AFTER_NEW_HEADER = 0;
    public int $YEAR;
    public int $MONTH;
    public  $modify = false;
    public int $LOCATION_ID;
    public $locationList = [];
    public $monthList = [];

    public $headerList = [];
    public $dataList = [];

    private $financialStatementServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    private $cashFlowServices;
    public function boot(
        FinancialStatementServices $financialStatementServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        CashFlowServices $cashFlowServices,
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->cashFlowServices = $cashFlowServices;
    }
    public function mount()
    {
        $this->monthList = $this->dateServices->MonthList();
        $this->locationList = $this->locationServices->getList();
        $this->YEAR = $this->dateServices->NowYear();
        $this->MONTH = $this->dateServices->NowMonth();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
    }

    private function AcctFormat(float $AMOUNT)
    {
        if ($AMOUNT >= 0) {
            return  (string) number_format($AMOUNT, 2);
        } else {
            $newText = str_replace("-", "", $AMOUNT);
            return  (string) "(" . number_format($newText, 2) . ")";
        }
    }
    public function generate()
    {

        $data = $this->cashFlowServices->CashFlowCompute($this->YEAR, $this->MONTH, $this->LOCATION_ID);

        if ($data) {
            $NET_INCOME = (float) $data['NET_INCOME'];
            $DEPRECIATION_EXP = (float) $data['DEPRECIATION_EXP'];
            $INTEREST_EXP = (float) $data['INTEREST_EXP'];
            $IMPAIRMENT_LOSS = (float) $data['IMPAIRMENT_LOSS'];
            $DISPOSAL_ASSET = (float) $data['DISPOSAL_ASSET'];
            $INCOME_TAX_EXP = (float) $data['INCOME_TAX_EXP'];

            $CASH_FLOW_BEFORE_WC_CHANGE =  (float) $data['CASH_FLOW_BEFORE_WC_CHANGE'];

            $TRADE_N_OTHER_RECEIVABLE = (float) $data['TRADE_N_OTHER_RECEIVABLE'];
            $OTHER_CURRENT_ASSET = (float) $data['OTHER_CURRENT_ASSET'];
            $OTHER_NON_CURRENT_ASSET = (float) $data['OTHER_NON_CURRENT_ASSET'];
            $TRADE_N_OTHER_PAYABLE = (float) $data['TRADE_N_OTHER_PAYABLE'];

            $CASH_GENERATE_OPENING_ACTIVITIES = (float) $data['CASH_GENERATE_OPENING_ACTIVITIES'];

            $INCOME_TAX_PAID = (float) $data['INCOME_TAX_PAID'];
            $NET_CASH_OPENING_ACTIVIES = (float) $data['NET_CASH_OPENING_ACTIVIES'];

            $ADVANCE_TO_RP = (float) $data['ADVANCE_TO_RP'];
            $PROCEED_FROM_SALES_PURCHASE =  (float) $data['PROCEED_FROM_SALES_PURCHASE'];
            $INVESTING_ACTIVIES = (float) $data['INVESTING_ACTIVIES'];

            $ADVANCE_FROM_RP = (float) $data['ADVANCE_FROM_RP'];
            $PROCEED_FROM_ISSURANCE_TO_STOCK_DEPOSIT = (float) $data['PROCEED_FROM_ISSURANCE_TO_STOCK_DEPOSIT'];
            $LEASE_LIABILITY = (float) $data['LEASE_LIABILITY'];
            $PROCEED_TO_LOAN_AVAILMENT = (float) $data['PROCEED_TO_LOAN_AVAILMENT'];
            $PAYMENT_OF_DIVIDENDS = (float) $data['PAYMENT_OF_DIVIDENDS'];
            $INTEREST_EXPENSES_PAID = (float) $data['INTEREST_EXPENSES_PAID'];
            $CASH_GENERATE_FINANCING_ACTIVITIES = (float) $data['CASH_GENERATE_FINANCING_ACTIVITIES'];

            $IN_CASH  = (float) $data['IN_CASH'];
            $PREV_END = (float) $data['PREV_END'];
            $END_CASH = (float) $data['END_CASH'];

            $this->dataList = [];
            $this->AddLine('OPERATING ACTIVITIES', 'text-primary');
            $this->AddLine('Income', 'text-info', $this->AcctFormat($NET_INCOME));

            $this->AddLine('Adjustments for:');
            $this->AddLine('+ Depreciation', 'text-info', $this->AcctFormat($DEPRECIATION_EXP));
            $this->AddLine('+ Interest Expense', 'text-info', $this->AcctFormat($INTEREST_EXP));
            $this->AddLine('+ Impairment Losses', 'text-info', $this->AcctFormat($IMPAIRMENT_LOSS));
            $this->AddLine('+ Loss / (Gain) on Disposal of Assets', 'text-info', $this->AcctFormat($DISPOSAL_ASSET));
            $this->AddLine('+ Income Tax Expense', 'text-info', $this->AcctFormat($INCOME_TAX_EXP));

            $this->AddLine('Cash Flow Before WC Changes', '', $this->AcctFormat($CASH_FLOW_BEFORE_WC_CHANGE), true);


            $this->AddLine('Working Capital Changes:');
            $this->AddLine('+- Receivables', 'text-info', $this->AcctFormat($TRADE_N_OTHER_RECEIVABLE));
            $this->AddLine('+- Current Assets', 'text-info', $this->AcctFormat($OTHER_CURRENT_ASSET));
            $this->AddLine('+- Non-Current Assets', 'text-info', $this->AcctFormat($OTHER_NON_CURRENT_ASSET));
            $this->AddLine('+- Other Payables', 'text-info', $this->AcctFormat($TRADE_N_OTHER_PAYABLE));

            $this->AddLine('Cash Generated By/(Used in) Operating Activities', 'text-primary', $this->AcctFormat($CASH_GENERATE_OPENING_ACTIVITIES), true);

            $this->AddLine('Income Tax Paid', 'text-info', $this->AcctFormat($INCOME_TAX_PAID));

            $this->AddLine('Net Cash from Operating Activities', 'text-primary', $this->AcctFormat($NET_CASH_OPENING_ACTIVIES), true);
            $this->AddSpace();



            $this->AddLine('INVESTING ACTIVITIES', 'text-primary');
            $this->AddLine('Advances to Related Parties', 'text-info', $this->AcctFormat($ADVANCE_TO_RP));
            $this->AddLine('Proceeds from Sale / (Purchase) of Property, Plant and Equipment', 'text-info', $this->AcctFormat($PROCEED_FROM_SALES_PURCHASE));
            $this->AddLine('Cash Generated By/(Used In) Investing Activities', 'text-primary', $this->AcctFormat($INVESTING_ACTIVIES), true);
            $this->AddSpace();



            $this->AddLine('FINANCING ACTIVITIES', 'text-primary');
            $this->AddLine('Advances from Related Parties', 'text-info', $this->AcctFormat($ADVANCE_FROM_RP));
            $this->AddLine('Proceeds from The Issuance of Capital Stock/Deposit For Future Stock Subscription', 'text-info', $this->AcctFormat($PROCEED_FROM_ISSURANCE_TO_STOCK_DEPOSIT));
            $this->AddLine('Lease Liability', 'text-info', $this->AcctFormat($LEASE_LIABILITY));
            $this->AddLine('Proceeds from Loan Availments (Payment of Loans)', 'text-info', $this->AcctFormat($PROCEED_TO_LOAN_AVAILMENT));
            $this->AddLine('Payments of Dividends', 'text-info', $this->AcctFormat($PAYMENT_OF_DIVIDENDS));
            $this->AddLine('Interest Expense Paid', 'text-info', $this->AcctFormat($INTEREST_EXPENSES_PAID));

            $this->AddLine('Cash Generated By/(Used In) Financing Activities', 'text-primary', $this->AcctFormat($CASH_GENERATE_FINANCING_ACTIVITIES), true);
            $this->AddSpace();

            $this->AddLine('Increase / (Decrease) in Cash', 'text-info', $this->AcctFormat($IN_CASH));
            $this->AddLine('Cash at Beginning of Period', 'text-info', $this->AcctFormat($PREV_END));
            $this->AddLine('Cash at the End of Period', 'text-primary', $this->AcctFormat($END_CASH), true);
        }
    }

    private function AddLine(string $name,  string $strClass = "", string $amount = '', bool $underline = false)
    {
        $data = [
            'name'      => $name,
            'amount'    => $amount,
            'class'     => $strClass,
            'underline' => $underline
        ];

        $this->dataList[] = $data;
    }
    private function AddSpace()
    {
        $data = [
            'name'      => '',
            'amount'    => '',
            'class'     => '',
            'underline' => false
        ];
        $this->dataList[] = $data;
    }

    public function ExportGenerate()
    {
        if (!$this->dataList) {
            session()->flash('error', 'Please generate first');
            return;
        }

        return Excel::download(new CashFlowExport(
            $this->dataList
        ), 'cash-flow-export.xlsx');
    }
    public function render()
    {

        return view('livewire.financial-report.cash-flow-report');
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
}
