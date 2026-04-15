<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\AccountJournal;
use App\Models\Bill;
use App\Models\BillCreditBills;
use App\Models\BillExpenses;
use App\Models\BillItems;
use App\Models\CheckBills;
use App\Models\Tax;
use App\Models\WithholdingTaxBills;
use Illuminate\Support\Facades\DB;

class BillingServices
{

    public int $object_type_map_bill          = 2;
    public int $object_type_map_bill_item     = 3;
    public int $object_type_map_bill_expenses = 78;
    public int $document_type_id              = 1;
    public int $ACCOUNTS_PAYABLE_ID           = 21;
    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;
    private $paymentTermServices;
    private $usersLogServices;
    public function __invoke()
    {
        throw new \Exception('Not implemented');
    }
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        PaymentTermServices $paymentTermServices,
        UsersLogServices $usersLogServices

    ) {
        $this->object                = $objectService;
        $this->compute               = $computeServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->paymentTermServices   = $paymentTermServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function ConfirmProccess(int $ID)
    {
        Bill::where('ID', '=', $ID)
            ->update(['DATE_CONFIRM' => $this->dateServices->NowDate()]);
    }
    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        Bill::where('ID', $ID)
            ->update([
                'FILE_NAME' => $FILE_NAME,
                'FILE_PATH' => $FILE_PATH,
            ]);
    }
    public function get(int $ID)
    {
        try {

            $data = Bill::where('ID', $ID)->first();
            if ($data) {
                return $data;
            }
            return [];
        } catch (\Throwable $th) {
            return [];
        }
    }
    public function Store(string $CODE, string $DATE, int $VENDOR_ID, int $LOCATION_ID, int $PAYMENT_TERMS_ID, string $DUE_DATE, string $DISCOUNT_DATE, float $DISCOUNT_PCT, string $NOTES, int $ACCOUNTS_PAYABLE_ID, int $INPUT_TAX_ID, float $INPUT_TAX_RATE, float $INPUT_TAX_AMOUNT, int $INPUT_TAX_VAT_METHOD, int $INPUT_TAX_ACCOUNT_ID, int $STATUS): int
    {
        $ID          = (int) $this->object->ObjectNextID('BILL');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('BILL');
        $isLocRef    = (bool) boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Bill::create([
            'ID'                   => $ID,
            'RECORDED_ON'          => $this->dateServices->Now(),
            'DATE'                 => $DATE,
            'CODE'                 => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'VENDOR_ID'            => $VENDOR_ID,
            'LOCATION_ID'          => $LOCATION_ID,
            'PAYMENT_TERMS_ID'     => $PAYMENT_TERMS_ID > 0 ? $PAYMENT_TERMS_ID : 0,
            'DUE_DATE'             => $DUE_DATE ? $DUE_DATE : null,
            'DISCOUNT_DATE'        => $DISCOUNT_DATE ? $DISCOUNT_DATE : null,
            'DISCOUNT_PCT'         => $DISCOUNT_PCT > 0 ? $DISCOUNT_PCT : 0,
            'AMOUNT'               => 0,
            'BALANCE_DUE'          => 0,
            'NOTES'                => $NOTES,
            'ACCOUNTS_PAYABLE_ID'  => $ACCOUNTS_PAYABLE_ID > 0 ? $ACCOUNTS_PAYABLE_ID : null,
            'INPUT_TAX_ID'         => $INPUT_TAX_ID,
            'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
            'INPUT_TAX_AMOUNT'     => $INPUT_TAX_AMOUNT,
            'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
            'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID,
            'STATUS'               => $STATUS,
            'STATUS_DATE'          => $this->dateServices->NowDate(),
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::BILL, $ID);

        return $ID;
    }
    public function Update(int $ID, string $CODE, int $VENDOR_ID, int $PAYMENT_TERMS_ID, string $DUE_DATE, string $NOTES, int $ACCOUNTS_PAYABLE_ID, int $INPUT_TAX_ID, float $INPUT_TAX_RATE, float $INPUT_TAX_AMOUNT, int $INPUT_TAX_VAT_METHOD, int $INPUT_TAX_ACCOUNT_ID, string $DATE, )
    {

        Bill::where('ID', $ID)
            ->update([
                'CODE'                 => $CODE,
                'VENDOR_ID'            => $VENDOR_ID,
                'PAYMENT_TERMS_ID'     => $PAYMENT_TERMS_ID > 0 ? $PAYMENT_TERMS_ID : null,
                'DUE_DATE'             => $DUE_DATE ? $DUE_DATE : null,
                'NOTES'                => $NOTES,
                'ACCOUNTS_PAYABLE_ID'  => $ACCOUNTS_PAYABLE_ID,
                'INPUT_TAX_ID'         => $INPUT_TAX_ID,
                'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
                'INPUT_TAX_AMOUNT'     => $INPUT_TAX_AMOUNT,
                'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
                'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID,
                'DATE'                 => $DATE,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::BILL, $ID);
    }

    public function Delete(int $ID)
    {
        $data = $this->get($ID);
        if ($data->STATUS == 0 || $data->STATUS == 16) {
            BillItems::where('BILL_ID', $ID)->delete();
            BillExpenses::where('BILL_ID', $ID)->delete();
            Bill::where('ID', $ID)->delete();

            $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::BILL, $ID);

            return true;
        }
        return false;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Bill::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::BILL, $ID);
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = Bill::query()
            ->select([
                'bill.ID',
                'bill.CODE',
                'bill.DATE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                'bill.INPUT_TAX_RATE',
                'bill.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
                'bill.STATUS  as STATUS_ID',
            ])
            ->join('contact as c', 'c.ID', '=', 'bill.VENDOR_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'bill.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'bill.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'bill.INPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bill.CODE', 'like', '%' . $search . '%')
                        ->orWhere('bill.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('bill.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function CountItems(int $BILL_ID, bool $isItem): int
    {
        if ($isItem == true) {
            return (int) BillItems::where('BILL_ID', $BILL_ID)->count();
        }
        return (int) BillExpenses::where('BILL_ID', $BILL_ID)->count();
    }
    private function getLine(int $Id, bool $isItem): int
    {
        if ($isItem) {
            return (int) BillItems::where('BILL_ID', $Id)->max('LINE_NO');
        }
        return (int) BillExpenses::where('BILL_ID', $Id)->max('LINE_NO');
    }
    public function ItemStore(int $BILL_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $RATE_TYPE, float $AMOUNT, int $BATCH_ID, int $ACCOUNT_ID, int $PO_ITEM_ID = 9, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, int $CLASS_ID = 0): int
    {

        $LINE_NO = $this->getLine($BILL_ID, true) + 1;
        $ID      = $this->object->ObjectNextID('BILL_ITEMS');

        BillItems::create([
            'ID'                 => $ID,
            'BILL_ID'            => $BILL_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
            'DESCRIPTION'        => null,
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'               => $RATE,
            'RATE_TYPE'          => $RATE_TYPE,
            'AMOUNT'             => $AMOUNT,
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'ACCOUNT_ID'         => $ACCOUNT_ID,
            'PO_ITEM_ID'         => $PO_ITEM_ID > 0 ? $PO_ITEM_ID : null,
            'TAXABLE'            => $TAXABLE,
            'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'         => $TAX_AMOUNT,
            'CLASS_ID'           => $CLASS_ID > 0 ? $CLASS_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::BILL_ITEMS, $BILL_ID);

        return $ID;
    }
    public function ItemUpdate(int $ID, int $BILL_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT)
    {

        BillItems::where('ID', $ID)->where('BILL_ID', $BILL_ID)->where('ITEM_ID', $ITEM_ID)
            ->update([
                'QUANTITY'           => $QUANTITY,
                'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'RATE'               => $RATE,
                'AMOUNT'             => $AMOUNT,
                'TAXABLE'            => $TAXABLE,
                'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
                'TAX_AMOUNT'         => $TAX_AMOUNT,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::BILL_ITEMS, $BILL_ID);
    }

    public function ItemGet(int $ID, int $BILL_ID)
    {
        $result = BillItems::where('ID', '=', $ID)
            ->where('BILL_ID', '=', $BILL_ID)
            ->first();

        return $result;
    }
    public function ItemDelete(int $ID, int $BILL_ID)
    {
        BillItems::where('ID', $ID)
            ->where('BILL_ID', $BILL_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::BILL_ITEMS, $BILL_ID);
    }
    public function ItemView(int $BILL_ID)
    {
        return BillItems::query()
            ->select([
                'bill_items.ID',
                'bill_items.ITEM_ID',
                'bill_items.BILL_ID',
                'bill_items.QUANTITY',
                'bill_items.UNIT_ID',
                'bill_items.RATE',
                'bill_items.AMOUNT',
                'bill_items.TAXABLE',
                'bill_items.TAXABLE_AMOUNT',
                'bill_items.ACCOUNT_ID',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'bill_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'bill_items.UNIT_ID')
            ->where('bill_items.BILL_ID', $BILL_ID)
            ->orderBy('bill_items.LINE_NO', 'asc')
            ->get();
    }
    public function ExpenseStore(
        int $BILL_ID,
        int $ACCOUNT_ID,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        string $PARTICULARS,
        int $CLASS_ID = 0
    ): int {
        $LINE_NO = $this->getLine($BILL_ID, false) + 1;
        $ID      = (int) $this->object->ObjectNextID('BILL_EXPENSES');

        BillExpenses::create([
            'ID'             => $ID,
            'BILL_ID'        => $BILL_ID,
            'LINE_NO'        => $LINE_NO,
            'ACCOUNT_ID'     => $ACCOUNT_ID,
            'AMOUNT'         => $AMOUNT,
            'TAXABLE'        => $TAXABLE,
            'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'     => $TAX_AMOUNT,
            'PARTICULARS'    => $PARTICULARS,
            'CLASS_ID'       => $CLASS_ID > 0 ? $CLASS_ID : null,

        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::BILL_EXPENSES, $BILL_ID);
        return $ID;
    }
    public function ExpenseUpdate(int $ID, int $BILL_ID, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, string $PARTICULARS, int $CLASS_ID = 0)
    {
        BillExpenses::where('ID', $ID)
            ->where('BILL_ID', $BILL_ID)
            ->update([
                'AMOUNT'         => $AMOUNT,
                'TAXABLE'        => $TAXABLE,
                'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
                'TAX_AMOUNT'     => $TAX_AMOUNT,
                'PARTICULARS'    => $PARTICULARS,
                'CLASS_ID'       => $CLASS_ID > 0 ? $CLASS_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::BILL_EXPENSES, $BILL_ID);
    }
    public function ExpenseDelete(int $ID, int $BILL_ID, )
    {
        BillExpenses::where('ID', $ID)->where('BILL_ID', $BILL_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::BILL_EXPENSES, $BILL_ID);
    }
    public function ExpenseGet(int $ID, $BILL_ID)
    {
        $data = BillExpenses::where('ID', '=', $ID)
            ->where('BILL_ID', '=', $BILL_ID)
            ->first();

        if ($data) {
            return $data;
        }

        return [];
    }
    public function ExpenseView(int $BILL_ID)
    {
        $result = BillExpenses::query()
            ->select([
                'bill_expenses.ID',
                'bill_expenses.ACCOUNT_ID',
                'bill_expenses.AMOUNT',
                'bill_expenses.PARTICULARS',
                'bill_expenses.TAXABLE',
                'bill_expenses.CLASS_ID',
                'bill_expenses.ACCOUNT_ID',
                'a.NAME',
                'a.TAG as CODE',
            ])
            ->leftJoin('account as a', 'a.ID', '=', 'bill_expenses.ACCOUNT_ID')
            ->where('bill_expenses.BILL_ID', $BILL_ID)
            ->orderBy('bill_expenses.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function isItemTab($BILL_ID): bool
    {
        $ItemCount    = $this->getLine($BILL_ID, true);
        $AccountCount = $this->getLine($BILL_ID, false);
        if ($ItemCount >= $AccountCount) {
            return true;
        }
        return false;
    }
    public function ReComputed(int $ID): array
    {
        $BILL = Bill::where('ID', $ID)->first();
        if ($BILL) {
            $TAX_ID     = (int) $BILL->INPUT_TAX_ID;
            $itemResult = BillItems::query()
                ->select(
                    [
                        'bill_items.AMOUNT',
                        'bill_items.TAX_AMOUNT',
                        'bill_items.TAXABLE_AMOUNT',
                        'bill_items.TAXABLE',
                    ]
                )
                ->where('bill_items.BILL_ID', $ID)
                ->orderBy('bill_items.LINE_NO', 'asc')
                ->get();
            $expensesResult = BillExpenses::query()
                ->select(
                    [
                        'bill_expenses.AMOUNT',
                        'bill_expenses.TAX_AMOUNT',
                        'bill_expenses.TAXABLE_AMOUNT',
                        'bill_expenses.TAXABLE',
                    ]
                )
                ->where('bill_expenses.BILL_ID', '=', $ID)
                ->orderBy('bill_expenses.LINE_NO', 'asc')
                ->get();
            $totalPay = (float) $this->GetTotalPayment($ID);
            $result   = $this->compute->taxComputeWithExpenses($itemResult, $expensesResult, $TAX_ID, $totalPay);

            foreach ($result as $list) {

                Bill::where('ID', $ID)
                    ->update([
                        'AMOUNT'           => $list['AMOUNT'],
                        'BALANCE_DUE'      => $list['BALANCE_DUE'],
                        'INPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                    ]);
            }
            return $result;
        }

        return [];
    }
    public function getUpdateTaxItem(int $BILL_ID, int $TAX_ID)
    {
        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE ?? 0;
        $items   = BillItems::query()
            ->select([
                'bill_items.ID',
                'bill_items.ITEM_ID',
                'bill_items.AMOUNT',
                'bill_items.TAXABLE',
            ])
            ->where('bill_items.BILL_ID', $BILL_ID)
            ->orderBy('bill_items.LINE_NO', 'asc')
            ->get();

        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                BillItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }

        $expenses = BillExpenses::query()
            ->select([
                'bill_expenses.ID',
                'bill_expenses.AMOUNT',
                'bill_expenses.TAXABLE',
            ])
            ->where('bill_expenses.BILL_ID', $BILL_ID)
            ->orderBy('bill_expenses.LINE_NO', 'asc')
            ->get();

        foreach ($expenses as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);

            BillExpenses::where('ID', $list->ID)
                ->update([
                    'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                    'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                ]);
        }
    }
    public function GetBillPaymentApplied(int $BILL_ID): float
    {
        $result = CheckBills::query()
            ->select(DB::raw('IFNULL(SUM(check_bills.AMOUNT_PAID), 0) AS pay'))
            ->where('check_bills.BILL_ID', '=', $BILL_ID)
            ->first();

        return (float) $result->pay ?? 0;
    }

    public function GetBillCreditApplied(int $BILL_ID): float
    {
        $result = BillCreditBills::query()
            ->select(DB::raw('IFNULL(SUM(bill_credit_bills.AMOUNT_APPLIED), 0) AS pay'))
            ->where('bill_credit_bills.BILL_ID', '=', $BILL_ID)
            ->first();

        return (float) $result->pay ?? 0;
    }
    public function GetWTax(int $BILL_ID): float
    {
        $paymentSum = WithholdingTaxBills::query()
            ->select(DB::raw('IFNULL(SUM(withholding_tax_bills.AMOUNT_WITHHELD), 0) AS pay'))
            ->where('withholding_tax_bills.BILL_ID', '=', $BILL_ID)
            ->first();

        return $paymentSum->pay ?? 0;
    }
    public function GetTotalPayment(int $BILL_ID): float
    {
        $WTAX    = $this->GetWTax($BILL_ID);
        $PAYMENT = $this->GetBillPaymentApplied($BILL_ID);
        $CREDIT  = $this->GetBillCreditApplied($BILL_ID);
        $PAY     = (float) $PAYMENT + $CREDIT + $WTAX;

        return $PAY;
    }
    public function UpdateBalance(int $BILL_ID)
    {
        $PAY  = (float) $this->GetTotalPayment($BILL_ID);
        $data = Bill::where('ID', $BILL_ID)->first();
        if ($data) {
            $AMOUNT  = (float) $data->AMOUNT;
            $BALANCE = $AMOUNT - $PAY;

            Bill::where('ID', $BILL_ID)
                ->update([
                    'BALANCE_DUE' => $BALANCE,
                ]);
        }
    }
    public function getBalance(int $BILL_ID): float
    {
        $data = Bill::where('ID', '=', $BILL_ID)->first();
        if ($data) {
            return (float) $data->BALANCE_DUE;
        }
        return 0;
    }
    public function getBillListViaBillPayment(int $VENDOR_ID, int $LOCATION_ID, int $CHECK_ID)
    {
        $result = Bill::query()
            ->select([
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
            ])
            ->whereNotExists(function ($query) use (&$CHECK_ID) {
                $query->select(DB::raw(1))
                    ->from('check_bills as b')
                    ->whereRaw('b.BILL_ID = bill.ID')
                    ->where('b.CHECK_ID', '=', $CHECK_ID);
            })
            ->where('bill.VENDOR_ID', $VENDOR_ID)
            ->where('bill.LOCATION_ID', $LOCATION_ID)
            ->where('bill.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }

    public function getBillListViaBillPaymentExistOnPhilealth(int $VENDOR_ID, int $LOCATION_ID, int $CHECK_ID, int $PF_PERIOD_ID)
    {

        $result = Bill::query()
            ->select([
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                'ph.DATE_ADMITTED',
                'ph.DATE_DISCHARGED',
                'ph.P1_TOTAL',
                'pp.RECEIPT_NO as OR_NO',
                'pp.DATE as OR_DATE',
                'pp.DATE_FROM as DT_PERIOD_FORM',
                'pp.DATE_TO as DT_PERIOD_TO',
                'c.NAME as PATIENT_NAME',
                DB::raw(" (select  GROUP_CONCAT(hemodialysis.DATE ORDER BY hemodialysis.DATE ASC SEPARATOR ', ') from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = ph.CONTACT_ID and hemodialysis.DATE between ph.DATE_ADMITTED and ph.DATE_DISCHARGED) as CONFINE_PERIOD "),
            ])
            ->join('philhealth_prof_fee as pf', 'pf.BILL_ID', '=', 'bill.ID')
            ->join('philhealth as ph', 'ph.ID', '=', 'pf.PHIC_ID')
            ->join('payment as p', 'p.ID', '=', 'ph.PAYMENT_ID')
            ->join('payment_period as pp', 'pp.ID', '=', 'p.PAYMENT_PERIOD_ID')
            ->join('contact as c', 'c.ID', '=', 'ph.CONTACT_ID')
            ->whereNotExists(function ($query) use (&$CHECK_ID) {
                $query->select(DB::raw(1))
                    ->from('check_bills as b')
                    ->whereRaw('b.BILL_ID = bill.ID')
                    ->where('b.CHECK_ID', '=', $CHECK_ID);
            })
            ->where('p.PAYMENT_PERIOD_ID', '=', $PF_PERIOD_ID)
            ->where('bill.VENDOR_ID', '=', $VENDOR_ID)
            ->where('bill.LOCATION_ID', '=', $LOCATION_ID)
            ->where('bill.BALANCE_DUE', '>', 0)
            ->groupBy(
            [
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                'ph.DATE_ADMITTED',
                'ph.DATE_DISCHARGED',
                'ph.P1_TOTAL',
                'pp.RECEIPT_NO',
                'pp.DATE',
                'pp.DATE_FROM',
                'pp.DATE_TO',
                'c.NAME',
                'ph.CONTACT_ID'
            ] )
            ->get();

        return $result;
    }
    public function getBillListViaBillCredit(int $VENDOR_ID, int $LOCATION_ID, int $BILL_CREDIT_ID)
    {
        return Bill::query()
            ->select([
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
            ])
            ->whereNotExists(function ($query) use (&$BILL_CREDIT_ID) {
                $query->select(DB::raw(1))
                    ->from('bill_credit_bills as p')
                    ->whereRaw('p.BILL_ID = bill.ID')
                    ->where('p.BILL_CREDIT_ID', '=', $BILL_CREDIT_ID);
            })
            ->where('bill.VENDOR_ID', $VENDOR_ID)
            ->where('bill.LOCATION_ID', $LOCATION_ID)
            ->where('bill.BALANCE_DUE', '>', 0)
            ->get();
    }
    public function PaymentHistory($BILL_ID)
    {
        $results = DB::table(DB::raw('
        (
            SELECT \'Pay Bills\' AS `TYPE`, check_bills.`ID`, check_bills.`CHECK_ID` AS MAIN_ID, check_bills.`BILL_ID`, check_bills.AMOUNT_PAID as `AMOUNT_APPLIED`, `check`.`RECORDED_ON`,`check`.CODE,`check`.DATE
            FROM check_bills
            INNER JOIN `check` ON `check`.`ID` = check_bills.`CHECK_ID`

            UNION
            SELECT \'Bill Credits\' AS `TYPE`, bill_credit_bills.`ID`, bill_credit_bills.`BILL_CREDIT_ID` AS MAIN_ID, bill_credit_bills.`BILL_ID`, bill_credit_bills.`AMOUNT_APPLIED`, bill_credit.`RECORDED_ON`,bill_credit.CODE,bill_credit.DATE
            FROM bill_credit_bills
            INNER JOIN bill_credit ON bill_credit.`ID` = bill_credit_bills.`BILL_CREDIT_ID`
            UNION

            SELECT \'Withholding Tax\' AS `TYPE`, withholding_tax.`ID`, withholding_tax_bills.`WITHHOLDING_TAX_ID` AS MAIN_ID, withholding_tax_bills.`BILL_ID`, withholding_tax_bills.`AMOUNT_WITHHELD` as AMOUNT_APPLIED, withholding_tax.`RECORDED_ON`,withholding_tax.CODE,withholding_tax.DATE
            FROM withholding_tax_bills
            INNER JOIN withholding_tax ON withholding_tax.`ID` = withholding_tax_bills.`WITHHOLDING_TAX_ID`

        ) AS pay'))

            ->select('pay.TYPE', 'pay.ID', 'pay.MAIN_ID', 'pay.BILL_ID', 'pay.AMOUNT_APPLIED', 'pay.RECORDED_ON', 'pay.CODE', 'pay.DATE')
            ->where('pay.BILL_ID', '=', $BILL_ID)
            ->orderBy('pay.RECORDED_ON')
            ->get();

        return $results;
    }

    public function ItemInventory(int $BILL_ID)
    {
        $result = BillItems::query()
            ->select([
                'bill_items.ID',
                'bill_items.ITEM_ID',
                'bill_items.RATE',
                'bill_items.QUANTITY',
                'bill_items.UNIT_BASE_QUANTITY',
                'bill_items.RATE',
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'bill_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('bill_items.BILL_ID', $BILL_ID)
            ->get();

        return $result;
    }

    public function getBillTaxJournal(int $BILL_ID)
    {
        $result = Bill::query()
            ->select([
                'ID',
                'INPUT_TAX_ACCOUNT_ID as ACCOUNT_ID',
                'VENDOR_ID as SUBSIDIARY_ID',
                'INPUT_TAX_AMOUNT as AMOUNT',
                DB::raw(' 0 as ENTRY_TYPE'),

            ])
            ->where('ID', $BILL_ID)
            ->get();

        return $result;
    }
    public function getBillJournal(int $BILL_ID)
    {
        $result = Bill::query()
            ->select([
                'ID',
                'ACCOUNTS_PAYABLE_ID as ACCOUNT_ID',
                'VENDOR_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 1 as ENTRY_TYPE'),
            ])
            ->where('ID', $BILL_ID)->get();

        return $result;
    }
    public function getBillItemJournal(int $BILL_ID)
    {
        $result = BillItems::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT,  IF(AMOUNT > 0, AMOUNT, AMOUNT * -1)) as AMOUNT'),
                DB::raw('IF(AMOUNT >= 0, 0, 1)  as ENTRY_TYPE'),
            ])
            ->where('BILL_ID', $BILL_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getBillExpenseJournal(int $BILL_ID)
    {
        $result = BillExpenses::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ACCOUNT_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, IF(AMOUNT > 0, AMOUNT, AMOUNT * -1)) as AMOUNT'),
                DB::raw('IF(AMOUNT >= 0, 0, 1) as ENTRY_TYPE'),
            ])
            ->where('BILL_ID', '=', $BILL_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function getBillListViaWTax(int $VENDOR_ID, int $LOCATION_ID, int $WITHHOLDING_TAX_ID)
    {
        $result = Bill::query()
            ->select([
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',

            ])
            ->whereNotExists(function ($query) use (&$WITHHOLDING_TAX_ID) {
                $query->select(DB::raw(1))
                    ->from('withholding_tax_bills as p')
                    ->whereRaw('p.BILL_ID = bill.ID')
                    ->where('p.WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID);
            })
            ->where('bill.VENDOR_ID', $VENDOR_ID)
            ->where('bill.LOCATION_ID', $LOCATION_ID)
            ->where('bill.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }
    public function billChangeVendor(int $BILL_ID, int $NewVendor_ID)
    {
        $data = Bill::where('ID', '=', $BILL_ID)->first();
        if ($data) {
            Bill::where('ID', '=', $BILL_ID)
                ->update(['VENDOR_ID' => $NewVendor_ID]);

            AccountJournal::where('OBJECT_ID', '=', $BILL_ID)
                ->where('OBJECT_TYPE', '=', $this->object_type_map_bill)
                ->where('OBJECT_DATE', '=', $data->DATE)
                ->where('ACCOUNT_ID', '=', $data->ACCOUNTS_PAYABLE_ID)
                ->where('LOCATION_ID', '=', $data->LOCATION_ID)
                ->update(['SUBSIDIARY_ID' => $NewVendor_ID]);

            AccountJournal::where('OBJECT_ID', '=', $BILL_ID)
                ->where('OBJECT_TYPE', '=', $this->object_type_map_bill)
                ->where('OBJECT_DATE', '=', $data->DATE)
                ->where('ACCOUNT_ID', '=', $data->INPUT_TAX_ACCOUNT_ID)
                ->where('LOCATION_ID', '=', $data->LOCATION_ID)
                ->update(['SUBSIDIARY_ID' => $NewVendor_ID]);

        }

    }
    public function billingUpdateDateOnly(int $BILL_ID, string $NEW_DATE)
    {
        if ($BILL_ID > 0) {
            $data = $this->get($BILL_ID);
            if ($data) {
                $DUE_DATE = $this->paymentTermServices->getDueDate($data->PAYMENT_TERMS_ID, $NEW_DATE);

                Bill::where('ID', '=', $BILL_ID)
                    ->update(['DATE' => $NEW_DATE, 'DUE_DATE' => $DUE_DATE]);
            }

        }

    }
    public function getBillByXero(string $CODE)
    {
        $result = Bill::query()
            ->select(['ID'])
            ->where('CODE', '=', $CODE)
            ->where('IS_XERO', '=', true)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }

        return 0;
    }
    public function UpdateAmount(int $BILL_ID, float $AMOUNT)
    {
        Bill::where('ID', '=', $BILL_ID)
            ->update(['AMOUNT' => $AMOUNT, 'BALANCE_DUE' => $AMOUNT, 'IS_XERO' => true]);
    }
    public function CallBillHeader($CODE, $DATE, $LOCATION_ID): int
    {
        $result = Bill::query()->select(['ID'])
            ->where('CODE', '=', $CODE)
            ->where('DATE', '=', $DATE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }

        return 0;

    }

    public function listViaContact(int $CONTACT_ID)
    {
        $result = Bill::query()
            ->select([
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                's.DESCRIPTION as STATUS',
                'l.NAME as LOCATION_NAME',
            ])
            ->join('location as l', 'l.ID', '=', 'bill.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'bill.STATUS')
            ->where('bill.VENDOR_ID', '=', $CONTACT_ID)
            ->orderBy('bill.DATE', 'desc')
            ->get();

        return $result;
    }
}
