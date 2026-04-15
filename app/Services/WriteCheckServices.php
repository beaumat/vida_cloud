<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\Check;
use App\Models\CheckExpenses;
use App\Models\CheckItems;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;

class WriteCheckServices
{
    private int $CHECK_TYPE_ID             = 0;
    public int $object_type_check          = 57;
    public int $object_type_check_items    = 75;
    public int $object_type_check_expenses = 79;
    public int $document_type_id           = 21;

    public float $ITEM_TOTAL     = 0;
    public float $EXPENSES_TOTAL = 0;

    private $object;
    private $dateServices;
    private $systemSettingServices;
    private $compute;
    private $usersLogServices;
    public function __construct(
        ObjectServices $objectServices,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        ComputeServices $computeServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                = $objectServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->compute               = $computeServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function Get(int $ID)
    {
        return Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $BANK_ACCOUNT_ID,
        int $PAY_TO_ID,
        int $LOCATION_ID,
        string $NOTES,
        int $ACCOUNTS_PAYABLE_ID = 0,
        int $INPUT_TAX_ID,
        float $INPUT_TAX_RATE,
        float $INPUT_TAX_AMOUNT,
        int $INPUT_TAX_VAT_METHOD,
        int $INPUT_TAX_ACCOUNT_ID
    ): int {
        $ID          = (int) $this->object->ObjectNextID('CHECK');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('CHECK');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Check::create([
            'ID'                   => $ID,
            'RECORDED_ON'          => $this->dateServices->Now(),
            'CODE'                 => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                 => $DATE,
            'TYPE'                 => $this->CHECK_TYPE_ID,
            'BANK_ACCOUNT_ID'      => $BANK_ACCOUNT_ID,
            'PAY_TO_ID'            => $PAY_TO_ID,
            'LOCATION_ID'          => $LOCATION_ID,
            'AMOUNT'               => 0,
            'NOTES'                => $NOTES,
            'PRINTED'              => false,
            'STATUS'               => 0,
            'STATUS_DATE'          => $this->dateServices->NowDate(),
            'ACCOUNTS_PAYABLE_ID'  => $ACCOUNTS_PAYABLE_ID > 0 ? $ACCOUNTS_PAYABLE_ID : null,
            'INPUT_TAX_ID'         => $INPUT_TAX_ID,
            'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
            'INPUT_TAX_AMOUNT'     => $INPUT_TAX_AMOUNT,
            'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
            'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CHECK, $ID);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Check::where('ID', '=', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::CHECK, $ID);
    }
    public function Update(
        int $ID,
        string $CODE,
        int $BANK_ACCOUNT_ID,
        int $PAY_TO_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        string $NOTES,
        int $INPUT_TAX_ID,
        float $INPUT_TAX_RATE,
        float $INPUT_TAX_AMOUNT,
        int $INPUT_TAX_VAT_METHOD,
        int $INPUT_TAX_ACCOUNT_ID
    ) {
        Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->update([
                'CODE'                 => $CODE,
                'BANK_ACCOUNT_ID'      => $BANK_ACCOUNT_ID,
                'PAY_TO_ID'            => $PAY_TO_ID,
                'LOCATION_ID'          => $LOCATION_ID,
                'AMOUNT'               => $AMOUNT,
                'NOTES'                => $NOTES,
                'PRINTED'              => false,
                'INPUT_TAX_ID'         => $INPUT_TAX_ID,
                'INPUT_TAX_RATE'       => $INPUT_TAX_RATE,
                'INPUT_TAX_AMOUNT'     => $INPUT_TAX_AMOUNT,
                'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
                'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CHECK, $ID);
    }
    public function Delete(int $ID)
    {
        Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CHECK, $ID);

    }
    public function Search($search, $locationId, $perPage)
    {
        $result = Check::query()
            ->select([
                'check.ID',
                'check.CODE',
                'check.DATE',
                'check.AMOUNT',
                'check.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'a.NAME as BANK_ACCOUNT_NAME',
            ])
            ->join('contact as c', 'c.ID', '=', 'check.PAY_TO_ID')
            ->join('account as a', 'a.ID', '=', 'check.BANK_ACCOUNT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'check.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'check.STATUS')
            ->where('check.TYPE', '=', $this->CHECK_TYPE_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('check.CODE', 'like', '%' . $search . '%')
                        ->orWhere('check.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('check.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('check.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function CountItems(int $CHECK_ID, bool $isItem): int
    {
        if ($isItem == true) {
            return (int) CheckItems::where('CHECK_ID', '=', $CHECK_ID)->count();
        }
        return (int) CheckExpenses::where('CHECK_ID', '=', $CHECK_ID)->count();
    }
    private function getLine(int $CHECK_ID, bool $isItem): int
    {
        if ($isItem) {
            return (int) CheckItems::where('CHECK_ID', '=', $CHECK_ID)->max('LINE_NO');
        }
        return (int) CheckExpenses::where('CHECK_ID', '=', $CHECK_ID)->max('LINE_NO');
    }
    public function isItemTab($CHECK_ID): bool
    {
        $ItemCount    = $this->getLine($CHECK_ID, true);
        $AccountCount = $this->getLine($CHECK_ID, false);
        if ($ItemCount >= $AccountCount) {
            return true;
        }
        return false;
    }
    public function ReComputed(int $ID): array
    {
        $Check = Check::where('ID', '=', $ID)->where('TYPE', '=')->first();
        if ($Check) {
            $TAX_ID = (int) $Check->INPUT_TAX_ID;

            $itemResult = CheckItems::query()
                ->select(
                    [
                        'check_items.AMOUNT',
                        'check_items.TAX_AMOUNT',
                        'check_items.TAXABLE_AMOUNT',
                        'check_items.TAXABLE',
                    ]
                )
                ->where('check_items.CHECK_ID', '=', $ID)
                ->orderBy('check_items.LINE_NO', 'asc')
                ->get();

            $expensesResult = CheckExpenses::query()
                ->select(
                    [
                        'check_expenses.AMOUNT',
                        'check_expenses.TAX_AMOUNT',
                        'check_expenses.TAXABLE_AMOUNT',
                        'check_expenses.TAXABLE',
                    ]
                )
                ->where('check_expenses.CHECK_ID', '=', $ID)
                ->orderBy('check_expenses.LINE_NO', 'asc')
                ->get();

            $result = $this->compute->taxComputeWithExpenses($itemResult, $expensesResult, $TAX_ID, 0);

            foreach ($result as $list) {
                Check::where('ID', '=', $ID)
                    ->where('TYPE', '=', $this->CHECK_TYPE_ID)
                    ->update([
                        'AMOUNT'           => $list['AMOUNT'],
                        'INPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                    ]);
            }

            return $result;
        }

        return [];
    }
    public function getUpdateTaxItem(int $CHECK_ID, int $TAX_ID)
    {
        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE ?? 0;
        $items   = CheckItems::query()
            ->select([
                'check_items.ID',
                'check_items.ITEM_ID',
                'check_items.AMOUNT',
                'check_items.TAXABLE',
            ])
            ->where('check_items.CHECK_ID', '=', $CHECK_ID)
            ->orderBy('check_items.LINE_NO', 'asc')
            ->get();

        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                CheckItems::where('ID', '=', $list->ID)
                    ->where('CHECK_ID', '=', $CHECK_ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }

        $expenses = CheckExpenses::query()
            ->select([
                'check_expenses.ID',
                'check_expenses.AMOUNT',
                'check_expenses.TAXABLE',
            ])
            ->where('check_expenses.CHECK_ID', '=', $CHECK_ID)
            ->orderBy('check_expenses.LINE_NO', 'asc')
            ->get();

        foreach ($expenses as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);

            CheckExpenses::where('ID', '=', $list->ID)
                ->where('CHECK_ID', '=', $CHECK_ID)
                ->update([
                    'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                    'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                ]);
        }
    }

    public function ItemStore(
        int $CHECK_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        int $BATCH_ID,
        int $ACCOUNT_ID,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $CLASS_ID = 0
    ): int {

        $LINE_NO = $this->getLine($CHECK_ID, true) + 1;
        $ID      = $this->object->ObjectNextID('CHECK_ITEMS');

        CheckItems::create([
            'ID'                 => $ID,
            'CHECK_ID'           => $CHECK_ID,
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

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CHECK_ITEMS, $CHECK_ID);
        return $ID;
    }
    public function ItemUpdate(
        int $ID,
        int $CHECK_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT
    ) {

        CheckItems::where('ID', $ID)
            ->where('CHECK_ID', '=', $CHECK_ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
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

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CHECK_ITEMS, $CHECK_ID);
    }

    public function ItemGet(int $ID, int $CHECK_ID)
    {
        $result = CheckItems::where('ID', '=', $ID)
            ->where('CHECK_ID', '=', $CHECK_ID)
            ->first();

        return $result;
    }
    public function ItemDelete(int $ID, int $CHECK_ID)
    {
        CheckItems::where('ID', '=', $ID)
            ->where('CHECK_ID', '=', $CHECK_ID)
            ->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CHECK_ITEMS, $CHECK_ID);
    }
    public function ItemView(int $CHECK_ID)
    {
        return CheckItems::query()
            ->select([
                'check_items.ID',
                'check_items.ITEM_ID',
                'check_items.CHECK_ID',
                'check_items.QUANTITY',
                'check_items.UNIT_ID',
                'check_items.RATE',
                'check_items.AMOUNT',
                'check_items.TAXABLE',
                'check_items.TAXABLE_AMOUNT',
                'check_items.ACCOUNT_ID',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'check_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'check_items.UNIT_ID')
            ->where('check_items.CHECK_ID', $CHECK_ID)
            ->orderBy('check_items.LINE_NO', 'asc')
            ->get();
    }
    public function ExpenseStore(
        int $CHECK_ID,
        int $ACCOUNT_ID,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        string $PARTICULARS,
        int $CLASS_ID = 0
    ): int {
        $LINE_NO = $this->getLine($CHECK_ID, false) + 1;
        $ID      = (int) $this->object->ObjectNextID('CHECK_EXPENSES');

        CheckExpenses::create([
            'ID'             => $ID,
            'CHECK_ID'       => $CHECK_ID,
            'LINE_NO'        => $LINE_NO,
            'ACCOUNT_ID'     => $ACCOUNT_ID,
            'AMOUNT'         => $AMOUNT,
            'TAXABLE'        => $TAXABLE,
            'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'     => $TAX_AMOUNT,
            'PARTICULARS'    => $PARTICULARS,
            'CLASS_ID'       => $CLASS_ID > 0 ? $CLASS_ID : null,

        ]);

        return $ID;
    }
    public function ExpenseUpdate(int $ID, int $CHECK_ID, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, string $PARTICULARS, int $CLASS_ID = 0)
    {
        CheckExpenses::where('ID', $ID)
            ->where('CHECK_ID', $CHECK_ID)
            ->update([
                'AMOUNT'         => $AMOUNT,
                'TAXABLE'        => $TAXABLE,
                'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
                'TAX_AMOUNT'     => $TAX_AMOUNT,
                'PARTICULARS'    => $PARTICULARS,
                'CLASS_ID'       => $CLASS_ID > 0 ? $CLASS_ID : null,
            ]);
    }
    public function ExpenseDelete(int $ID, int $CHECK_ID, )
    {
        CheckExpenses::where('ID', $ID)->where('CHECK_ID', $CHECK_ID)->delete();
    }
    public function ExpenseGet(int $ID, $CHECK_ID)
    {
        $data = CheckExpenses::where('ID', '=', $ID)
            ->where('CHECK_ID', '=', $CHECK_ID)
            ->first();

        if ($data) {
            return $data;
        }

        return [];
    }
    public function ExpenseView(int $CHECK_ID)
    {
        $result = CheckExpenses::query()
            ->select([
                'check_expenses.ID',
                'check_expenses.ACCOUNT_ID',
                'check_expenses.AMOUNT',
                'check_expenses.PARTICULARS',
                'check_expenses.TAXABLE',
                'check_expenses.CLASS_ID',
                'check_expenses.ACCOUNT_ID',
                'a.NAME',
                'a.TAG as CODE',
            ])
            ->leftJoin('account as a', 'a.ID', '=', 'check_expenses.ACCOUNT_ID')
            ->where('check_expenses.CHECK_ID', '=', $CHECK_ID)
            ->orderBy('check_expenses.LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function ItemInventory(int $CHECK_ID)
    {
        $result = CheckItems::query()
            ->select([
                'check_items.ID',
                'check_items.ITEM_ID',
                'check_items.RATE',
                'check_items.QUANTITY',
                'check_items.UNIT_BASE_QUANTITY',
                'check_items.RATE',
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'check_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('check_items.CHECK_ID', '=', $CHECK_ID)
            ->get();

        return $result;
    }
    public function getCheckTaxJournal(int $CHECK_ID)
    {
        $result = Check::query()
            ->select([
                'ID',
                'INPUT_TAX_ACCOUNT_ID as ACCOUNT_ID',
                'PAY_TO_ID as SUBSIDIARY_ID',
                'INPUT_TAX_AMOUNT as AMOUNT',
                Check::raw(' 0 as ENTRY_TYPE'),

            ])
            ->where('ID', '=', $CHECK_ID)
            ->get();

        return $result;
    }
    public function getCheckJournal(int $CHECK_ID)
    {
        $result = Check::query()
            ->select([
                'ID',
                'BANK_ACCOUNT_ID as ACCOUNT_ID',
                'PAY_TO_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 1 as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $CHECK_ID)
            ->get();

        return $result;
    }
    public function getCheckItemJournal(int $CHECK_ID)
    {
        $result = CheckItems::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('IF(AMOUNT >= 0, 0, 1)  as ENTRY_TYPE'),
            ])
            ->where('CHECK_ID', '=', $CHECK_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getCheckExpenseJournal(int $CHECK_ID)
    {
        $result = CheckExpenses::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ACCOUNT_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('IF(AMOUNT >= 0, 0, 1) as ENTRY_TYPE'),
            ])
            ->where('CHECK_ID', '=', $CHECK_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}
