<?php
namespace App\Services;

use App\Models\AccountReconciliation;
use App\Models\AccountReconciliationItems;
use Illuminate\Support\Facades\DB;

class BankReconServices
{
    private $object;
    private $dateServices;
    private $systemSettingServices;
    private $accountJournalServices;
    public function __construct(
        ObjectServices $objectServices,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->object                 = $objectServices;
        $this->dateServices           = $dateServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function get($ID)
    {
        $result = AccountReconciliation::where('ID', '=', $ID)->first();
        return $result;
    }
    public function Store(
        $CODE,
        string $DATE,
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $PREVIOUS_ID,
        int $SEQUENCE_NO,
        float $BEGINNING_BALANCE,
        float $CLEARED_DEPOSITS,
        float $CLEARED_WITHDRAWALS,
        float $CLEARED_BALANCE,
        float $ENDING_BALANCE,
        string $NOTES,
        int $SC_ACCOUNT_ID,
        float $SC_RATE,
        int $IE_ACCOUNT_ID,
        float $IE_RATE,
        $SC_DATE,
        $IE_DATE,
        int $BANK_STATEMENT_ID
    ): int {

        $ID          = $this->object->ObjectNextID('ACCOUNT_RECONCILIATION');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('ACCOUNT_RECONCILIATION');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));
        AccountReconciliation::create(
            [
                'ID'                  => $ID,
                'RECORDED_ON'         => $this->dateServices->Now(),
                'DATE'                => $DATE,
                'CODE'                => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
                'ACCOUNT_ID'          => $ACCOUNT_ID,
                'LOCATION_ID'         => $LOCATION_ID,
                'PREVIOUS_ID'         => $PREVIOUS_ID > 0 ? $PREVIOUS_ID : null,
                'SEQUENCE_NO'         => $SEQUENCE_NO,
                'BEGINNING_BALANCE'   => $BEGINNING_BALANCE,
                'CLEARED_DEPOSITS'    => $CLEARED_DEPOSITS,
                'CLEARED_WITHDRAWALS' => $CLEARED_WITHDRAWALS,
                'CLEARED_BALANCE'     => $CLEARED_BALANCE,
                'ENDING_BALANCE'      => $ENDING_BALANCE,
                'NOTES'               => $NOTES,
                'STATUS'              => 0,
                'STATUS_DATE'         => $this->dateServices->NowDate(),
                'SC_ACCOUNT_ID'       => $SC_ACCOUNT_ID > 0 ? $SC_ACCOUNT_ID : null,
                'SC_RATE'             => $SC_RATE,
                'IE_ACCOUNT_ID'       => $IE_ACCOUNT_ID > 0 ? $IE_ACCOUNT_ID : null,
                'IE_RATE'             => $IE_RATE,
                'SC_DATE'             => $SC_DATE,
                'IE_DATE'             => $IE_DATE,
                'BANK_STATEMENT_ID'   => $BANK_STATEMENT_ID,
            ]
        );

        return $ID;
    }
    public function Update(
        int $ID,
        string $DATE,
        string $CODE,
        string $NOTES,
        int $SC_ACCOUNT_ID,
        float $SC_RATE,
        int $IE_ACCOUNT_ID,
        float $IE_RATE,
        $SC_DATE,
        $IE_DATE,
        int $BANK_STATEMENT_ID
    ) {

        AccountReconciliation::where('ID', '=', $ID)
            ->update([
                'DATE'              => $DATE,
                'CODE'              => $CODE,
                'NOTES'             => $NOTES,
                'SC_ACCOUNT_ID'     => $SC_ACCOUNT_ID > 0 ? $SC_ACCOUNT_ID : null,
                'SC_RATE'           => $SC_RATE,
                'IE_ACCOUNT_ID'     => $IE_ACCOUNT_ID > 0 ? $IE_ACCOUNT_ID : null,
                'IE_RATE'           => $IE_RATE,
                'SC_DATE'           => $SC_DATE ?? null,
                'IE_DATE'           => $IE_DATE ?? null,
                'BANK_STATEMENT_ID' => $BANK_STATEMENT_ID,
            ]);
    }

    public function UpdateAmount(int $ID, float $CLEARED_DEPOSITS, float $CLEARED_WITHDRAWALS, float $SC_RATE, float $IE_RATE)
    {
        $CLEARED_BALANCE = ($CLEARED_DEPOSITS - $CLEARED_WITHDRAWALS);
        $CLEARED_BALANCE = $CLEARED_BALANCE + $IE_RATE;
        $CLEARED_BALANCE = $CLEARED_BALANCE - $SC_RATE;

        AccountReconciliation::where('ID', '=', $ID)
            ->update([
                'CLEARED_DEPOSITS'    => $CLEARED_DEPOSITS,
                'CLEARED_WITHDRAWALS' => $CLEARED_WITHDRAWALS,
                'CLEARED_BALANCE'     => $CLEARED_BALANCE,
            ]);
    }
    public function Delete(int $ID)
    {
        AccountReconciliationItems::where('ACCOUNT_RECONCILIATION_ID', '=', $ID)->delete();
        AccountReconciliation::where('ID', '=', $ID)->delete();
    }
    public function Recomputed(int $ID)
    {
        $CLEARED_DEPOSITS    = 0;
        $CLEARED_WITHDRAWALS = 0;
        $SC_RATE             = 0;
        $IE_RATE             = 0;
        $mainData            = AccountReconciliation::where('ID', '=', $ID)->first();
        if ($mainData) {
            $SC_RATE = $mainData->SC_RATE ?? 0;
            $IE_RATE = $mainData->IE_RATE ?? 0;
            $item    = AccountReconciliationItems::where('ACCOUNT_RECONCILIATION_ID', '=', $ID)->get();
            foreach ($item as $list) {
                if ($list->ENTRY_TYPE == 0) {
                    //DEPOSIT
                    $CLEARED_DEPOSITS = $CLEARED_DEPOSITS + $list->AMOUNT ?? 0;
                } else {
                    //WITHDRAWAL
                    $CLEARED_WITHDRAWALS = $CLEARED_WITHDRAWALS + $list->AMOUNT ?? 0;
                }
            }

            $this->UpdateAmount($ID, $CLEARED_DEPOSITS, $CLEARED_WITHDRAWALS, $SC_RATE, $IE_RATE);
        }
    }
    public function HavePreviousHistory(int $BANK_ACCOUNT_ID, int $LOCATION_ID)
    {
        $result = AccountReconciliation::query()
            ->where('ACCOUNT_ID', '=', $BANK_ACCOUNT_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('STATUS', '=', 15)
            ->orderBy('ID', 'DESC')
            ->first();

        if ($result) {
            return $result;
        }

        return [];
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {

        $result = AccountReconciliation::query()
            ->select([
                'account_reconciliation.ID',
                'account_reconciliation.CODE',
                'account_reconciliation.DATE',
                'a.NAME as ACCOUNT_NAME',
                'account_reconciliation.BEGINNING_BALANCE',
                'account_reconciliation.ENDING_BALANCE',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'account_reconciliation.NOTES',
            ])
            ->leftJoin('account as a', 'a.ID', '=', 'account_reconciliation.ACCOUNT_ID')
            ->leftJoin('bank_statement as bs', 'bs.ID', 'account_reconciliation.BANK_STATEMENT_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'account_reconciliation.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'account_reconciliation.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('account_reconciliation.CODE', 'like', '%' . $search . '%')
                        ->orWhere('account_reconciliation.ENDING_BALANCE', 'like', '%' . $search . '%')
                        ->orWhere('account_reconciliation.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('a.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('account_reconciliation.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    private function GetLineNo(int $ACCOUNT_RECONCILIATION_ID)
    {
        return (int) AccountReconciliationItems::where('ACCOUNT_RECONCILIATION_ID', '=', $ACCOUNT_RECONCILIATION_ID)
            ->max('LINE_NO');
    }
    public function GetItem(int $ID)
    {
        return AccountReconciliationItems::where('ID', '=', $ID)->first();
    }
    public function ItemStore(int $ACCOUNT_RECONCILIATION_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT)
    {
        $ID = $this->object->ObjectNextID('ACCOUNT_RECONCILIATION_ITEMS');
        AccountReconciliationItems::create([
            'ID'                        => $ID,
            'ACCOUNT_RECONCILIATION_ID' => $ACCOUNT_RECONCILIATION_ID,
            'LINE_NO'                   => $this->GetLineNo($ACCOUNT_RECONCILIATION_ID) + 1,
            'OBJECT_ID'                 => $OBJECT_ID,
            'OBJECT_TYPE'               => $OBJECT_TYPE,
            'ENTRY_TYPE'                => $ENTRY_TYPE,
            'CLEARED_DEBIT'             => $ENTRY_TYPE == 0 ? $AMOUNT : 0,
            'CLEARED_CREDIT'            => $ENTRY_TYPE == 1 ? $AMOUNT : 0,
            'AMOUNT'                    => $AMOUNT,
            'OBJECT_DATE'               => $OBJECT_DATE,
        ]);

        $this->Recomputed($ACCOUNT_RECONCILIATION_ID);
    }

    public function ItemDelete(int $ID, int $ACCOUNT_RECONCILIATION_ID)
    {
        AccountReconciliationItems::where('ID', '=', $ID)
            ->where('ACCOUNT_RECONCILIATION_ID', '=', $ACCOUNT_RECONCILIATION_ID)
            ->delete();

        $this->Recomputed($ACCOUNT_RECONCILIATION_ID);
    }
    public function ItemList(int $ACCOUNT_RECONCILIATION_ID, $search): object
    {
        $result = DB::table('account_reconciliation_items as recon_item')
            ->select([
                'recon_item.ID',
                'aj.OBJECT_ID',
                'aj.OBJECT_TYPE',
                'aj.OBJECT_DATE',
                'aj.ENTRY_TYPE',
                'recon_item.AMOUNT',
                'aj.OBJECT_DATE as DATE',
                DB::raw($this->accountJournalServices->GetFullDescription()),
                DB::raw($this->accountJournalServices->TX_CODE),
                DB::raw($this->accountJournalServices->TX_NAME),
                DB::raw($this->accountJournalServices->TX_NOTES),
                DB::raw($this->accountJournalServices->TX_PO),
                'l.NAME as LOCATION_NAME',
            ])
            ->join('account_reconciliation as recon', 'recon.ID', '=', 'recon_item.ACCOUNT_RECONCILIATION_ID')
            ->leftJoin('account_journal as aj', function ($join) {
                $join->on('aj.OBJECT_ID', '=', 'recon_item.OBJECT_ID');
                $join->on('aj.OBJECT_TYPE', '=', 'recon_item.OBJECT_TYPE');
                $join->on('aj.OBJECT_DATE', '=', 'recon_item.OBJECT_DATE');
                $join->on('aj.ENTRY_TYPE', '=', 'recon_item.ENTRY_TYPE');
                $join->on('aj.ACCOUNT_ID', '=', 'recon.ACCOUNT_ID');

            })
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')

            ->where('recon_item.ACCOUNT_RECONCILIATION_ID', '=', $ACCOUNT_RECONCILIATION_ID)
            ->whereExists(function ($query) use (&$ACCOUNT_RECONCILIATION_ID) {
                $query->select(DB::raw(1))
                    ->from('account_reconciliation_items as r')
                    ->whereRaw('r.OBJECT_ID = aj.OBJECT_ID')
                    ->whereRaw('r.OBJECT_TYPE = aj.OBJECT_TYPE')
                    ->whereRaw('r.OBJECT_DATE = aj.OBJECT_DATE')
                    ->whereRaw('r.ENTRY_TYPE = aj.ENTRY_TYPE')
                    ->where('r.ACCOUNT_RECONCILIATION_ID', '=', $ACCOUNT_RECONCILIATION_ID);
            })
            ->when($search, function ($query) use ($search) {
                $query->havingRaw(
                    '(TX_CODE like ? OR TX_NAME like ? OR TX_NOTES like ? OR TX_PO like ?)',
                    [
                        '%' . $search . '%', // For TX_CODE
                        '%' . $search . '%', // For TX_NAME
                        '%' . $search . '%', // For TX_NOTES
                        '%' . $search . '%', // For TX_PO
                    ]
                );
            })
            ->orderBy('recon_item.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function ItemListByEntry(int $ACCOUNT_RECONCILIATION_ID, int $ENTRY_TYPE)
    {
        $result = DB::table('account_reconciliation_items as recon_item')
            ->select([
                'recon_item.ID',
                'aj.OBJECT_ID',
                'aj.OBJECT_TYPE',
                'aj.OBJECT_DATE',
                'aj.ENTRY_TYPE',
                'recon_item.AMOUNT',
                'aj.OBJECT_DATE as DATE',
                DB::raw($this->accountJournalServices->GetFullDescription()),
                DB::raw($this->accountJournalServices->TX_CODE),
                DB::raw($this->accountJournalServices->TX_NAME),
                DB::raw($this->accountJournalServices->TX_NOTES),
                DB::raw($this->accountJournalServices->TX_PO),
                'l.NAME as LOCATION_NAME',
                'a.BANK_ACCOUNT_NO',

            ])
            ->join('account_reconciliation as recon', 'recon.ID', '=', 'recon_item.ACCOUNT_RECONCILIATION_ID')
            ->leftJoin('account_journal as aj', function ($join) {
                $join->on('aj.OBJECT_ID', '=', 'recon_item.OBJECT_ID');
                $join->on('aj.OBJECT_TYPE', '=', 'recon_item.OBJECT_TYPE');
                $join->on('aj.OBJECT_DATE', '=', 'recon_item.OBJECT_DATE');
                $join->on('aj.ENTRY_TYPE', '=', 'recon_item.ENTRY_TYPE');
                $join->on('aj.ACCOUNT_ID', '=', 'recon.ACCOUNT_ID');

            })
            ->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')

            ->where('recon_item.ACCOUNT_RECONCILIATION_ID', '=', $ACCOUNT_RECONCILIATION_ID)
            ->whereExists(function ($query) use (&$ACCOUNT_RECONCILIATION_ID) {
                $query->select(DB::raw(1))
                    ->from('account_reconciliation_items as r')
                    ->whereRaw('r.OBJECT_ID = aj.OBJECT_ID')
                    ->whereRaw('r.OBJECT_TYPE = aj.OBJECT_TYPE')
                    ->whereRaw('r.OBJECT_DATE = aj.OBJECT_DATE')
                    ->whereRaw('r.ENTRY_TYPE = aj.ENTRY_TYPE')
                    ->where('r.ACCOUNT_RECONCILIATION_ID', '=', $ACCOUNT_RECONCILIATION_ID);
            })
            ->where('aj.ENTRY_TYPE', '=', $ENTRY_TYPE)
            ->orderBy('recon_item.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        AccountReconciliation::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);
    }
    public function getPayList(int $ACCOUNT_ID, $dateEntry, float $AMOUNT = 0): array
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.OBJECT_ID',
                'aj.OBJECT_TYPE',
                'aj.OBJECT_DATE',
                'aj.ENTRY_TYPE',
                'aj.AMOUNT',
            ])
            ->whereDate('aj.OBJECT_DATE', '=', $dateEntry)
            ->where('aj.ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->where('aj.AMOUNT', '=', $AMOUNT)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('account_reconciliation_items as r')
                    ->whereRaw('r.OBJECT_ID = aj.OBJECT_ID')
                    ->whereRaw('r.OBJECT_TYPE = aj.OBJECT_TYPE')
                    ->whereRaw('r.OBJECT_DATE = aj.OBJECT_DATE')
                    ->whereRaw('r.ENTRY_TYPE = aj.ENTRY_TYPE');
            })
            ->first();

        if ($result) {
            return [
                'OBJECT_ID'   => $result->OBJECT_ID,
                'OBJECT_TYPE' => $result->OBJECT_TYPE,
                'OBJECT_DATE' => $result->OBJECT_DATE,
                'ENTRY_TYPE'  => $result->ENTRY_TYPE,
                'IS_EXIST'    => true,
            ];
        }

        return [
            'OBJECT_ID'   => '',
            'OBJECT_TYPE' => '',
            'OBJECT_DATE' => '',
            'ENTRY_TYPE'  => '',
            'IS_EXIST'    => false,
        ];

    }
    public function getPaymentList(int $ACCOUNT_ID, int $LOCATION_ID = 0, $search, string $dateEntry, float $AMOUNT = 0): object
    {

        $result = DB::table('account_journal as aj')
            ->select([
                'aj.OBJECT_ID',
                'aj.OBJECT_TYPE',
                'aj.OBJECT_DATE',
                'aj.ENTRY_TYPE',
                'aj.AMOUNT',
                'aj.OBJECT_DATE as DATE',
                DB::raw($this->accountJournalServices->GetFullDescription()),
                DB::raw($this->accountJournalServices->TX_CODE),
                DB::raw($this->accountJournalServices->TX_NAME),
                DB::raw($this->accountJournalServices->TX_NOTES),
                DB::raw($this->accountJournalServices->TX_PO),
                'l.NAME as LOCATION_NAME',
            ])

            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'aj.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })

            ->whereDate('aj.OBJECT_DATE', '=', $dateEntry)
            ->where('aj.ACCOUNT_ID', '=', $ACCOUNT_ID)

            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('account_reconciliation_items as r')
                    ->whereRaw('r.OBJECT_ID = aj.OBJECT_ID')
                    ->whereRaw('r.OBJECT_TYPE = aj.OBJECT_TYPE')
                    ->whereRaw('r.OBJECT_DATE = aj.OBJECT_DATE')
                    ->whereRaw('r.ENTRY_TYPE = aj.ENTRY_TYPE');
            })
            ->when($search, function ($query) use ($search) {
                $query->havingRaw(
                    '(TX_CODE like ? OR TX_NAME like ? OR TX_NOTES like ? OR TX_PO like ?)',
                    [
                        '%' . $search . '%', // For TX_CODE
                        '%' . $search . '%', // For TX_NAME
                        '%' . $search . '%', // For TX_NOTES
                        '%' . $search . '%', // For TX_PO
                    ]
                );
            })
            ->when($AMOUNT > 0, function ($query) use (&$AMOUNT) {
                $query->where('aj.AMOUNT', '=', $AMOUNT);
            })
            ->orderBy('aj.OBJECT_DATE', 'asc')
            ->get();

        return $result;
    }
    public function getSumDebitCredit(int $ACCOUNT_RECONCILIATION_ID)
    {
        $result = AccountReconciliationItems::query()
            ->select([
                DB::raw('SUM(CLEARED_DEBIT) as TOTAL_DEBIT'),
                DB::raw('SUM(CLEARED_CREDIT) as TOTAL_CREDIT'),
            ])
            ->where('ACCOUNT_RECONCILIATION_ID', '=', $ACCOUNT_RECONCILIATION_ID)
            ->first();

        if ($result) {
            return ['DEBIT' => $result->TOTAL_DEBIT ?? 0, 'CREDIT' => $result->TOTAL_CREDIT ?? 0];
        }

        return ['DEBIT' => 0, 'CREDIT' => 0];

    }
}
