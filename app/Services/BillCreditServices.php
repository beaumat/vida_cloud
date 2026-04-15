<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\BillCredit;
use App\Models\BillCreditBills;
use App\Models\BillCreditExpenses;
use App\Models\BillCreditItems;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;

class BillCreditServices
{

    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;
    private $usersLogServices;
    public function __construct(ObjectServices $objectService,
        ComputeServices $computeServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                = $objectService;
        $this->compute               = $computeServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->usersLogServices      = $usersLogServices;
    }

    public function get(int $ID): object
    {
        return BillCredit::where('ID', $ID)->first();
    }

    public function Store(string $CODE, string $DATE, int $VENDOR_ID, int $LOCATION_ID, string $NOTES, int $ACCOUNTS_PAYABLE_ID, int $INPUT_TAX_ID, float $INPUT_TAX_RATE, float $INPUT_TAX_AMOUNT, int $INPUT_TAX_VAT_METHOD, int $INPUT_TAX_ACCOUNT_ID): int
    {
        $ID          = (int) $this->object->ObjectNextID('BILL_CREDIT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('BILL_CREDIT');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        BillCredit::create([
            'ID'                   => $ID,
            'RECORDED_ON'          => $this->dateServices->Now(),
            'DATE'                 => $DATE,
            'CODE'                 => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'VENDOR_ID'            => $VENDOR_ID,
            'LOCATION_ID'          => $LOCATION_ID,
            'AMOUNT'               => 0,
            'AMOUNT_APPLIED'       => 0,
            'NOTES'                => $NOTES,
            'ACCOUNTS_PAYABLE_ID'  => $ACCOUNTS_PAYABLE_ID > 0 ? $ACCOUNTS_PAYABLE_ID : null,
            'INPUT_TAX_ID'         => $INPUT_TAX_ID,
            'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
            'INPUT_TAX_AMOUNT'     => $INPUT_TAX_AMOUNT,
            'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
            'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID,
            'STATUS'               => 0,
            'STATUS_DATE'          => $this->dateServices->NowDate(),
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::BILL_CREDIT, $ID);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DATE, int $VENDOR_ID, int $LOCATION_ID, string $NOTES, int $ACCOUNTS_PAYABLE_ID, int $INPUT_TAX_ID, float $INPUT_TAX_RATE, float $INPUT_TAX_AMOUNT, int $INPUT_TAX_VAT_METHOD, int $INPUT_TAX_ACCOUNT_ID)
    {
        BillCredit::where('ID', $ID)->update([
            'CODE'                 => $CODE,
            'VENDOR_ID'            => $VENDOR_ID,
            'NOTES'                => $NOTES,
            'ACCOUNTS_PAYABLE_ID'  => $ACCOUNTS_PAYABLE_ID,
            'INPUT_TAX_ID'         => $INPUT_TAX_ID,
            'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
            'INPUT_TAX_AMOUNT'     => $INPUT_TAX_AMOUNT,
            'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
            'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID,
        ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::BILL_CREDIT, $ID);
    }

    public function Delete(int $ID)
    {
        BillCreditItems::where('BILL_CREDIT_ID', $ID)->delete();
        BillCreditExpenses::where('BILL_CREDIT_ID', $ID)->delete();
        BillCredit::where('ID', $ID)->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::BILL_CREDIT, $ID);
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        BillCredit::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::BILL_CREDIT, $ID);
    }

    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = BillCredit::query()
            ->select([
                'bill_credit.ID',
                'bill_credit.CODE',
                'bill_credit.DATE',
                'bill_credit.AMOUNT',
                'bill_credit.INPUT_TAX_RATE',
                'bill_credit.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
            ])
            ->join('contact as c', 'c.ID', '=', 'bill_credit.VENDOR_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'bill_credit.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'bill_credit.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'bill_credit.INPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bill_credit.CODE', 'like', '%' . $search . '%')
                        ->orWhere('bill_credit.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('bill_credit.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

    private function getLine(int $BILL_CREDIT_ID, bool $isItem): int
    {
        if ($isItem) {
            return (int) BillCreditItems::where('BILL_CREDIT_ID', $BILL_CREDIT_ID)->max('LINE_NO');
        }
        return (int) BillCreditExpenses::where('BILL_CREDIT_ID', $BILL_CREDIT_ID)->max('LINE_NO');
    }

    public function ItemStore(int $BILL_CREDIT_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $RATE_TYPE, float $AMOUNT, int $BATCH_ID, int $ACCOUNT_ID, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, int $CLASS_ID)
    {

        $LINE_NO = $this->getLine($BILL_CREDIT_ID, true) + 1;
        $ID      = $this->object->ObjectNextID('BILL_CREDIT_ITEMS');

        BillCreditItems::create([
            'ID'                 => $ID,
            'BILL_CREDIT_ID'     => $BILL_CREDIT_ID,
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
            'TAXABLE'            => $TAXABLE,
            'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'         => $TAX_AMOUNT,
            'CLASS_ID'           => $CLASS_ID > 0 ? $CLASS_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::BILL_CREDIT_ITEMS, $BILL_CREDIT_ID);
    }
    public function ItemUpdate(int $ID, int $BILL_CREDIT_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT)
    {

        BillCreditItems::where('ID', $ID)
            ->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->where('ITEM_ID', $ITEM_ID)
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

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::BILL_CREDIT_ITEMS, $BILL_CREDIT_ID);
    }

    public function ItemDelete(int $ID, int $BILL_CREDIT_ID)
    {
        BillCreditItems::where('ID', $ID)->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::BILL_CREDIT_ITEMS, $BILL_CREDIT_ID);
    }

    public function ItemView(int $BILL_CREDIT_ID)
    {
        return BillCreditItems::query()
            ->select([
                'bill_credit_items.ID',
                'bill_credit_items.ITEM_ID',
                'bill_credit_items.BILL_CREDIT_ID',
                'bill_credit_items.QUANTITY',
                'bill_credit_items.UNIT_ID',
                'bill_credit_items.RATE',
                'bill_credit_items.AMOUNT',
                'bill_credit_items.TAXABLE',
                'bill_credit_items.TAXABLE_AMOUNT',
                'i.CODE',
                'i.PURCHASE_DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'bill_credit_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'bill_credit_items.UNIT_ID')
            ->where('bill_credit_items.BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->orderBy('bill_credit_items.LINE_NO', 'asc')
            ->get();
    }

    public function ExpenseStore(int $BILL_CREDIT_ID, int $ACCOUNT_ID, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, string $PARTICULARS, int $CLASS_ID)
    {
        $LINE_NO = $this->getLine($BILL_CREDIT_ID, false) + 1;
        $ID      = $this->object->ObjectNextID('BILL_CREDIT_EXPENSES');

        BillCreditExpenses::create([
            'ID'             => $ID,
            'BILL_CREDIT_ID' => $BILL_CREDIT_ID,
            'LINE_NO'        => $LINE_NO,
            'ACCOUNT_ID'     => $ACCOUNT_ID,
            'AMOUNT'         => $AMOUNT,
            'TAXABLE'        => $TAXABLE,
            'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'     => $TAX_AMOUNT,
            'PARTICULARS'    => $PARTICULARS,
            'CLASS_ID'       => $CLASS_ID > 0 ? $CLASS_ID : null,

        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::BILL_CREDIT_EXPENSES, $BILL_CREDIT_ID);
    }

    public function ExpenseUpdate(int $ID, int $BILL_CREDIT_ID, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, string $PARTICULARS, int $CLASS_ID)
    {
        BillCreditExpenses::where('ID', $ID)
            ->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->update([
                'AMOUNT'         => $AMOUNT,
                'TAXABLE'        => $TAXABLE,
                'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
                'TAX_AMOUNT'     => $TAX_AMOUNT,
                'PARTICULARS'    => $PARTICULARS,
                'CLASS_ID'       => $CLASS_ID > 0 ? $CLASS_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::BILL_CREDIT_EXPENSES, $BILL_CREDIT_ID);
    }

    public function ExpenseDelete(int $ID, int $BILL_CREDIT_ID, )
    {
        BillCreditExpenses::where('ID', $ID)
            ->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::BILL_CREDIT_EXPENSES, $BILL_CREDIT_ID);
    }
    public function ExpenseView(int $BILL_CREDIT_ID)
    {
        $result = BillCreditExpenses::query()
            ->select([
                'bill_credit_expenses.ID',
                'bill_credit_expenses.ACCOUNT_ID',
                'bill_credit_expenses.AMOUNT',
                'bill_credit_expenses.PARTICULARS',
                'bill_credit_expenses.TAXABLE',
                'bill_credit_expenses.CLASS_ID',
                'a.NAME',
                'a.TAG as CODE',
            ])
            ->leftJoin('account as a', 'a.ID', '=', 'bill_credit_expenses.ACCOUNT_ID')
            ->where('bill_credit_expenses.BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->orderBy('bill_credit_expenses.LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function isItemTab($BILL_CREDIT_ID): bool
    {
        $ItemCount    = $this->getLine($BILL_CREDIT_ID, true);
        $AccountCount = $this->getLine($BILL_CREDIT_ID, false);

        if ($ItemCount >= $AccountCount) {
            return true;
        }
        return false;
    }

    public function ReComputed(int $ID): array
    {
        $billCredit = BillCredit::where('ID', $ID)->first();

        if ($billCredit) {
            $TAX_ID     = (int) $billCredit->INPUT_TAX_ID;
            $itemResult = BillCreditItems::query()
                ->select(
                    [
                        'AMOUNT',
                        'TAX_AMOUNT',
                        'TAXABLE_AMOUNT',
                        'TAXABLE',
                    ]
                )
                ->where('BILL_CREDIT_ID', $ID)
                ->orderBy('LINE_NO', 'asc')
                ->get();

            $expensesResult = BillCreditExpenses::query()
                ->select(
                    [
                        'AMOUNT',
                        'TAX_AMOUNT',
                        'TAXABLE_AMOUNT',
                        'TAXABLE',
                    ]
                )
                ->where('BILL_CREDIT_ID', $ID)
                ->orderBy('LINE_NO', 'asc')
                ->get();

            $result = $this->compute->taxComputeWithExpenses($itemResult, $expensesResult, $TAX_ID, 0);

            foreach ($result as $list) {
                BillCredit::where('ID', $ID)->update([
                    'AMOUNT'           => $list['AMOUNT'],
                    'INPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                ]);
            }

            return $result;
        }

        return [];
    }
    public function GetCreditApplied(int $BILL_CREDIT_ID): float
    {
        $paymentSum = BillCreditBills::query()
            ->select(DB::raw('IFNULL(SUM(bill_credit_bills.AMOUNT_APPLIED), 0) AS pay'))
            ->where('bill_credit_bills.BILL_CREDIT_ID', '=', $BILL_CREDIT_ID)
            ->first();

        return $paymentSum->pay;
    }
    public function getUpdateTaxItem(int $BILL_CREDIT_ID, int $TAX_ID)
    {
        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;

        $items = BillCreditItems::query()
            ->select([
                'bill_credit_items.ID',
                'bill_credit_items.AMOUNT',
                'bill_credit_items.TAXABLE',
            ])
            ->join('item', 'item.ID', '=', 'bill_credit_items.ITEM_ID')
            ->where('bill_credit_items.BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->where('item.TYPE', 0)
            ->orderBy('bill_credit_items.LINE_NO', 'asc')
            ->get();

        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                BillCreditItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }

        $expenses = BillCreditExpenses::query()
            ->select(
                [
                    'bill_credit_expenses.ID',
                    'bill_credit_expenses.AMOUNT',
                    'bill_credit_expenses.TAXABLE',
                ]
            )
            ->where('bill_credit_expenses.BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->orderBy('bill_credit_expenses.LINE_NO', 'asc')
            ->get();

        foreach ($expenses as $list) {

            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);

            BillCreditExpenses::where('ID', $list->ID)
                ->update([
                    'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                    'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                ]);
        }
    }
    private function UpdateCreditApplied(int $BILL_CREDIT_ID)
    {
        $TOTAL_APPLIED = (float) $this->GetCreditApplied($BILL_CREDIT_ID);

        BillCredit::where('ID', $BILL_CREDIT_ID)
            ->update([
                'AMOUNT_APPLIED' => $TOTAL_APPLIED,
            ]);

        if ($TOTAL_APPLIED > 0) {
            $this->StatusUpdate($BILL_CREDIT_ID, 2);
        } else {
            $this->StatusUpdate($BILL_CREDIT_ID, 0);
        }
    }
    public function BillCreditBillsStore(int $BILL_CREDIT_ID, int $BILL_ID, float $AMOUNT_APPLIED, ): int
    {
        $ID = $this->object->ObjectNextID('BILL_CREDIT_BILLS');
        BillCreditBills::create([
            'ID'             => $ID,
            'BILL_CREDIT_ID' => $BILL_CREDIT_ID,
            'BILL_ID'        => $BILL_ID,
            'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
        ]);
        $this->UpdateCreditApplied($BILL_CREDIT_ID);
        return $ID;
    }
    public function BillCreditBillExists(int $BILL_CREDIT_ID, int $BILL_ID): int
    {
        $data = BillCreditBills::where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->where('BILL_ID', $BILL_ID)
            ->first();

        if ($data) {
            return $data->ID;
        }
        return 0;
    }
    public function BillCreditBillsUpdate(int $ID, int $BILL_CREDIT_ID, int $BILL_ID, float $AMOUNT_APPLIED)
    {
        BillCreditBills::where('ID', $ID)
            ->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->where('BILL_ID', $BILL_ID)
            ->update([
                'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
            ]);

        $this->UpdateCreditApplied($BILL_CREDIT_ID);
    }
    public function BillCreditBillsDelete(int $ID, int $BILL_CREDIT_ID, int $BILL_ID)
    {
        BillCreditBills::where('ID', $ID)
            ->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->where('BILL_ID', $BILL_ID)
            ->delete();

        $this->UpdateCreditApplied($BILL_CREDIT_ID);
    }
    public function BillCreditBillsList(int $BILL_CREDIT_ID)
    {
        $result = BillCreditBills::query()
            ->select([
                'bill_credit_bills.ID',
                'bill_credit_bills.BILL_ID',
                'i.DATE',
                'i.CODE',
                'i.AMOUNT',
                'i.BALANCE_DUE',
                'bill_credit_bills.AMOUNT_APPLIED',
            ])
            ->leftJoin('bill as i', 'i.ID', '=', 'bill_credit_bills.BILL_ID')
            ->where('bill_credit_bills.BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->get();

        return $result;
    }
    public function CountItems(int $BILL_ID, bool $isItem): int
    {
        if ($isItem == true) {
            return (int) BillCreditItems::where('BILL_CREDIT_ID', $BILL_ID)->count();
        }
        return (int) BillCreditExpenses::where('BILL_CREDIT_ID', $BILL_ID)->count();
    }
    public function ItemInventory(int $BILL_CREDIT_ID)
    {
        $result = billcreditItems::query()
            ->select([
                'bill_credit_items.ID',
                'bill_credit_items.ITEM_ID',
                'bill_credit_items.RATE',
                'bill_credit_items.QUANTITY',
                'bill_credit_items.UNIT_BASE_QUANTITY',
                'bill_credit_items.RATE',
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'bill_credit_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('bill_credit_items.BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->get();

        return $result;
    }
    public function getBillCreditTaxJournal(int $BILL_CREDIT_ID)
    {
        $result = BillCredit::query()
            ->select([
                'ID',
                'INPUT_TAX_ACCOUNT_ID as ACCOUNT_ID',
                'VENDOR_ID as SUBSIDIARY_ID',
                'INPUT_TAX_AMOUNT as AMOUNT',
                DB::raw(' 1 as ENTRY_TYPE'),

            ])
            ->where('ID', $BILL_CREDIT_ID)
            ->where('INPUT_TAX_AMOUNT', '>', 0)
            ->get();

        return $result;
    }
    public function getBillCreditJournal(int $BILL_CREDIT_ID)
    {
        $result = BillCredit::query()
            ->select([
                'ID',
                'ACCOUNTS_PAYABLE_ID as ACCOUNT_ID',
                'VENDOR_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 0 as ENTRY_TYPE'),

            ])
            ->where('ID', $BILL_CREDIT_ID)->get();

        return $result;
    }
    public function getBillCreditItemJournal(int $BILL_CREDIT_ID)
    {
        $result = BillCreditItems::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getBillCreditExpenseJournal(int $BILL_CREDIT_ID)
    {
        $result = BillCreditExpenses::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ACCOUNT_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('BILL_CREDIT_ID', $BILL_CREDIT_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}
