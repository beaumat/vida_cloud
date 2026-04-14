<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\CreditMemo;
use App\Models\CreditMemoInvoices;
use App\Models\CreditMemoItems;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class CreditMemoServices
{

    use WithPagination;
    private $object;
    private $compute;
    private $locationReference;
    private $systemSettingServices;
    private $dateServices;
    private $usersLogServices;

    public function __construct(ObjectServices $objectService, ComputeServices $computeServices, LocationReferenceServices $locationReferenceServices, SystemSettingServices $systemSettingServices, DateServices $dateServices, UsersLogServices $usersLogServices)
    {
        $this->object                = $objectService;
        $this->compute               = $computeServices;
        $this->locationReference     = $locationReferenceServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->usersLogServices      = $usersLogServices;
    }

    public function Store(string $CODE, string $DATE, int $CUSTOMER_ID, int $LOCATION_ID, int $CLASS_ID, int $SALES_REP_ID, string $NOTES, int $ACCOUNTS_RECEIVABLE_ID, int $OUTPUT_TAX_ID, float $OUTPUT_TAX_RATE, float $OUTPUT_TAX_AMOUNT, int $OUTPUT_TAX_VAT_METHOD, int $OUTPUT_TAX_ACCOUNT_ID): int
    {

        $ID          = (int) $this->object->ObjectNextID('CREDIT_MEMO');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('CREDIT_MEMO');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        CreditMemo::create([
            'ID'                     => $ID,
            'RECORDED_ON'            => $this->dateServices->Now(),
            'CODE'                   => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                   => $DATE,
            'CUSTOMER_ID'            => $CUSTOMER_ID,
            'LOCATION_ID'            => $LOCATION_ID,
            'CLASS_ID'               => $CLASS_ID > 0 ? $CLASS_ID : null,
            'SALES_REP_ID'           => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
            'NOTES'                  => $NOTES,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
            'STATUS'                 => 0,
            'STATUS_DATE'            => $this->dateServices->NowDate(),
            'OUTPUT_TAX_ID'          => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
            'OUTPUT_TAX_RATE'        => $OUTPUT_TAX_RATE,
            'OUTPUT_TAX_VAT_METHOD'  => $OUTPUT_TAX_VAT_METHOD,
            'OUTPUT_TAX_ACCOUNT_ID'  => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,
            'AMOUNT'                 => 0,
            'AMOUNT_APPLIED'         => 0,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CREDIT_MEMO, $ID);

        return $ID;
    }

    public function get(int $ID)
    {
        $data = CreditMemo::where('ID', $ID)->first();
        if ($data) {
            return $data;
        }
        return [];
    }
    private function getLine($Id): int
    {
        return (int) CreditMemoItems::where('CREDIT_MEMO_ID', $Id)->max('LINE_NO');
    }
    public function ItemStore(int $CREDIT_MEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $RATE_TYPE, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, int $COGS_ACCOUNT_ID, int $ASSET_ACCOUNT_ID, int $INCOME_ACCOUNT_ID, int $BATCH_ID, int $GROUP_LINE_ID, bool $PRINT_IN_FORMS, int $PRICE_LEVEL_ID, ): int
    {

        $LINE_NO = (int) $this->getLine($CREDIT_MEMO_ID) + 1;
        $ID      = (int) $this->object->ObjectNextID('CREDIT_MEMO_ITEMS');

        CreditMemoItems::create([
            'ID'                 => $ID,
            'CREDIT_MEMO_ID'     => $CREDIT_MEMO_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
            'DESCRIPTION'        => null,
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'               => $RATE,
            'RATE_TYPE'          => $RATE_TYPE,
            'AMOUNT'             => $AMOUNT,
            'TAXABLE'            => $TAXABLE,
            'TAXABLE_AMOUNT'     => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'         => $TAX_AMOUNT,
            'COGS_ACCOUNT_ID'    => $COGS_ACCOUNT_ID > 0 ? $COGS_ACCOUNT_ID : null,
            'ASSET_ACCOUNT_ID'   => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'INCOME_ACCOUNT_ID'  => $INCOME_ACCOUNT_ID > 0 ? $INCOME_ACCOUNT_ID : null,
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'GROUP_LINE_ID'      => $GROUP_LINE_ID > 0,
            'PRINT_IN_FORMS'     => $PRINT_IN_FORMS,
            'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,

        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CREDIT_MEMO_ITEMS, $CREDIT_MEMO_ID);

        return $ID;
    }
    public function ItemUpdate(int $ID, int $CREDIT_MEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $RATE_TYPE = 0, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, int $BATCH_ID, int $PRICE_LEVEL_ID, )
    {
        CreditMemoItems::where('ID', $ID)
            ->where('CREDIT_MEMO_ID', $CREDIT_MEMO_ID)
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
                'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
                'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CREDIT_MEMO_ITEMS, $CREDIT_MEMO_ID);
    }
    public function ItemDelete(int $ID, int $CREDIT_MEMO_ID)
    {
        CreditMemoItems::where('ID', $ID)->where('CREDIT_MEMO_ID', $CREDIT_MEMO_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CREDIT_MEMO_ITEMS, $CREDIT_MEMO_ID);
    }
    public function ItemView(int $CREDIT_MEMO_ID)
    {
        return CreditMemoItems::query()
            ->select([
                'credit_memo_items.ID',
                'credit_memo_items.ITEM_ID',
                'credit_memo_items.CREDIT_MEMO_ID',
                'credit_memo_items.QUANTITY',
                'credit_memo_items.UNIT_ID',
                'credit_memo_items.RATE',
                'credit_memo_items.AMOUNT',
                'credit_memo_items.TAXABLE',
                'credit_memo_items.TAXABLE_AMOUNT',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                'c.DESCRIPTION as CLASS_DESCRIPTION',
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'credit_memo_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'credit_memo_items.UNIT_ID')
            ->leftJoin('item_sub_class as sl', 'sl.ID', '=', 'i.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 'sl.CLASS_ID')
            ->where('credit_memo_items.CREDIT_MEMO_ID', $CREDIT_MEMO_ID)
            ->orderBy('credit_memo_items.LINE_NO', 'asc')
            ->get();
    }
    public function ReComputed(int $ID): array
    {
        $creditMemoItems = CreditMemo::where('ID', $ID)->first();
        if ($creditMemoItems) {
            $TAX_ID = (int) $creditMemoItems->OUTPUT_TAX_ID;

            $itemResult = CreditMemoItems::query()
                ->select([
                    'credit_memo_Items.AMOUNT',
                    'credit_memo_Items.TAX_AMOUNT',
                    'credit_memo_Items.TAXABLE_AMOUNT',
                    'credit_memo_Items.TAXABLE',
                    'item.TYPE',
                ])
                ->join('item', 'item.ID', '=', 'credit_memo_Items.ITEM_ID')
                ->where('credit_memo_Items.CREDIT_MEMO_ID', $ID)
                ->whereIn('item.TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
                ->orderBy('credit_memo_Items.LINE_NO', 'asc')
                ->get();

            $creditApplied = (float) $this->GetCreditApplied($ID);
            $data          = $this->compute->taxCompute($itemResult, $TAX_ID);

            foreach ($data as $list) {
                $originalAmount = (float) $list['AMOUNT'];

                CreditMemo::where('ID', $ID)
                    ->update([
                        'AMOUNT'            => $originalAmount,
                        'AMOUNT_APPLIED'    => $creditApplied,
                        'OUTPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                        'TAXABLE_AMOUNT'    => $list['TAXABLE_AMOUNT'],
                        'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT'],
                    ]);

                $result = [
                    [
                        'AMOUNT'            => $originalAmount,
                        'AMOUNT_APPLIED'    => $creditApplied,
                        'TAX_AMOUNT'        => $list['TAX_AMOUNT'],
                        'TAXABLE_AMOUNT'    => $list['TAXABLE_AMOUNT'],
                        'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT'],
                    ],
                ];

                return $result;
            }
        }
        return [];
    }
    public function GetCreditApplied(int $CREDIT_MEMO_ID): float
    {
        $paymentSum = CreditMemoInvoices::query()
            ->select(DB::raw('IFNULL(SUM(credit_memo_invoices.AMOUNT_APPLIED), 0) AS pay'))
            ->where('credit_memo_invoices.CREDIT_MEMO_ID', '=', $CREDIT_MEMO_ID)
            ->first();

        return $paymentSum->pay ?? 0;
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        CreditMemo::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);
            
        $this->usersLogServices->StatusLog($STATUS, LogEntity::CREDIT_MEMO, $ID);

    }
    public function Update(int $ID, string $CODE, string $DATE, int $CUSTOMER_ID, int $LOCATION_ID, int $CLASS_ID, int $SALES_REP_ID, string $NOTES, int $ACCOUNTS_RECEIVABLE_ID, int $OUTPUT_TAX_ID, float $OUTPUT_TAX_RATE, float $OUTPUT_TAX_AMOUNT, int $OUTPUT_TAX_VAT_METHOD, int $OUTPUT_TAX_ACCOUNT_ID)
    {
        CreditMemo::where('ID', $ID)
            ->update([
                'CODE'                   => $CODE,
                'DATE'                   => $DATE,
                'CUSTOMER_ID'            => $CUSTOMER_ID,
                'LOCATION_ID'            => $LOCATION_ID,
                'CLASS_ID'               => $CLASS_ID > 0 ? $CLASS_ID : null,
                'SALES_REP_ID'           => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
                'NOTES'                  => $NOTES,
                'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
                'OUTPUT_TAX_ID'          => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
                'OUTPUT_TAX_RATE'        => $OUTPUT_TAX_RATE,
                'OUTPUT_TAX_VAT_METHOD'  => $OUTPUT_TAX_VAT_METHOD,
                'OUTPUT_TAX_ACCOUNT_ID'  => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CREDIT_MEMO, $ID);
    }
    public function Delete(int $ID)
    {
        DB::beginTransaction();
        try {

            CreditMemoItems::where('CREDIT_MEMO_ID', $ID)->delete();
            CreditMemo::where('ID', $ID)->delete();
            $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CREDIT_MEMO, $ID);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }

    public function Search($search, int $locationId, int $perPage)
    {
        return CreditMemo::query()
            ->select([
                'credit_memo.ID',
                'credit_memo.CODE',
                'credit_memo.DATE',
                'credit_memo.AMOUNT',
                'credit_memo.AMOUNT_APPLIED',
                'credit_memo.OUTPUT_TAX_RATE',
                'credit_memo.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
            ])
            ->join('contact as c', 'c.ID', '=', 'credit_memo.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'credit_memo.LOCATION_ID');

                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'credit_memo.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'credit_memo.OUTPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('credit_memo.CODE', 'like', '%' . $search . '%')
                        ->orWhere('credit_memo.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('credit_memo.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('credit_memo.ID', 'desc')
            ->paginate($perPage);
    }
    public function getUpdateTaxItem(int $CREDIT_MEMO_ID, int $TAX_ID)
    {
        $items = CreditMemoItems::query()
            ->select([
                'credit_memo_items.ID',
                'credit_memo_items.AMOUNT',
                'credit_memo_items.TAXABLE',
            ])
            ->join('item', 'item.ID', '=', 'credit_memo_items.ITEM_ID')
            ->where('credit_memo_items.CREDIT_MEMO_ID', $CREDIT_MEMO_ID)
            ->where('item.TYPE', 0)
            ->orderBy('credit_memo_items.LINE_NO', 'asc')
            ->get();

        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;
        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                CreditMemoItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }
    }
    private function UpdateCreditApplied(int $CREDIT_MEMO_ID)
    {
        $TOTAL_APPLIED = (float) $this->GetCreditApplied($CREDIT_MEMO_ID);

        CreditMemo::where('ID', $CREDIT_MEMO_ID)
            ->update([
                'AMOUNT_APPLIED' => $TOTAL_APPLIED,
            ]);

        if ($TOTAL_APPLIED > 0) {
            $this->StatusUpdate($CREDIT_MEMO_ID, 2);
        } else {
            $this->StatusUpdate($CREDIT_MEMO_ID, 0);
        }
    }

    public function CreditMemoInvoiceStore(int $CREDIT_MEMO_ID, int $INVOICE_ID, float $AMOUNT_APPLIED, ): int
    {
        $ID = $this->object->ObjectNextID('CREDIT_MEMO_INVOICES');

        CreditMemoInvoices::create([
            'ID'             => $ID,
            'CREDIT_MEMO_ID' => $CREDIT_MEMO_ID,
            'INVOICE_ID'     => $INVOICE_ID,
            'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CREDIT_MEMO_INVOICES, $CREDIT_MEMO_ID);

        $this->UpdateCreditApplied($CREDIT_MEMO_ID);

        return $ID;
    }

    public function CreditMemoInvoiceExist(int $CREDIT_MEMO_ID, int $INVOICE_ID): int
    {
        $data = CreditMemoInvoices::where('CREDIT_MEMO_ID', $CREDIT_MEMO_ID)
            ->where('INVOICE_ID', $INVOICE_ID)
            ->first();

        if ($data) {
            return $data->ID;
        }
        return 0;
    }

    public function CreditMemoInvoiceUpdate(int $ID, int $CREDIT_MEMO_ID, int $INVOICE_ID, float $AMOUNT_APPLIED)
    {
        CreditMemoInvoices::where('ID', $ID)
            ->where('CREDIT_MEMO_ID', $CREDIT_MEMO_ID)
            ->where('INVOICE_ID', $INVOICE_ID)
            ->update([
                'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CREDIT_MEMO_INVOICES, $CREDIT_MEMO_ID);

        $this->UpdateCreditApplied($CREDIT_MEMO_ID);
    }
    public function CreditMemoInvoiceDelete(int $ID, int $CREDIT_MEMO_ID, int $INVOICE_ID)
    {
        CreditMemoInvoices::where('ID', $ID)
            ->where('CREDIT_MEMO_ID', $CREDIT_MEMO_ID)
            ->where('INVOICE_ID', $INVOICE_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CREDIT_MEMO_INVOICES, $CREDIT_MEMO_ID);

        $this->UpdateCreditApplied($CREDIT_MEMO_ID);
    }
    public function CreditMemoInvoiceList(int $CREDIT_MEMO_ID)
    {
        $result = CreditMemoInvoices::query()
            ->select([
                'credit_memo_invoices.ID',
                'credit_memo_invoices.INVOICE_ID',
                'i.DATE',
                'i.CODE',
                'i.AMOUNT',
                'i.BALANCE_DUE',
                'credit_memo_invoices.AMOUNT_APPLIED',
            ])
            ->leftJoin('invoice as i', 'i.ID', '=', 'credit_memo_invoices.INVOICE_ID')
            ->where('credit_memo_invoices.CREDIT_MEMO_ID', $CREDIT_MEMO_ID)
            ->get();

        return $result;
    }
}
