<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\HemoJournal;
use App\Models\PullOut;
use App\Models\PullOutItems;
use Illuminate\Support\Facades\DB;

class PullOutServices
{

    public int $object_type_map_pull_out       = 113;
    public int $object_type_map_pull_out_items = 114;
    public int $document_type_id               = 31;
    public int $default_debit_account_id       = 245; // Default Debit Account ID, set to 0 if not applicable
    private $objectService;
    private $dateServices;
    private $systemSettingServices;
    private $accountJournalServices;
    private $accountServices;
    private $usersLogServices;

    public function __construct(ObjectServices $objectService,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        AccountJournalServices $accountJournalServices,
        AccountServices $accountServices,
        UsersLogServices $usersLogServices) {
        $this->objectService          = $objectService;
        $this->dateServices           = $dateServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->usersLogServices       = $usersLogServices;
        $this->accountServices        = $accountServices;

    }
    public function Get(int $ID)
    {
        return PullOut::where('ID', $ID)->first();
    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID, string $NOTES, int $PREPARED_BY_ID, int $ACCOUNT_ID): int
    {
        $ID          = (int) $this->objectService->ObjectNextID('PULL_OUT');
        $OBJECT_TYPE = (int) $this->objectService->ObjectTypeID('PULL_OUT');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        PullOut::create(
            [
                'ID'             => $ID,
                'RECORDED_ON'    => $this->dateServices->Now(),
                'CODE'           => $CODE !== '' ? $CODE : $this->objectService->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
                'DATE'           => $DATE,
                'LOCATION_ID'    => $LOCATION_ID,
                'AMOUNT'         => 0,
                'NOTES'          => $NOTES,
                'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
                'STATUS'         => 0,
                'STATUS_DATE'    => $this->dateServices->NowDate(),
                'ACCOUNT_ID'     => $ACCOUNT_ID,
            ]
        );

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PULL_OUT, $ID);

        return $ID;
    }
    public function Update(int $ID, string $CODE, string $NOTES, int $PREPARED_BY_ID, int $ACCOUNT_ID)
    {
        PullOut::where('ID', $ID)
            ->update([
                'CODE'           => $CODE,
                'NOTES'          => $NOTES,
                'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
                'ACCOUNT_ID'     => $ACCOUNT_ID,
            ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PULL_OUT, $ID);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        PullOut::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::PULL_OUT, $ID);
    }
    public function Delete(int $ID)
    {
        PullOutItems::where('PULL_OUT_ID', $ID)->delete();
        PullOut::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PULL_OUT, $ID);
    }
    public function PostedToCanceld(int $ID)
    {
        $data = PullOut::where('ID', $ID)->first();
        if ($data) {
            if ($data->STATUS_ID == 15) {
                // Canceled
                PullOut::where("ID", $ID)->update(['STATUS', 6]);
            } else {
                // Void
                PullOut::where("ID", $ID)->update(['STATUS', 7]);
            }
        }
    }

    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = PullOut::query()
            ->select([
                'pull_out.ID',
                'pull_out.CODE',
                'pull_out.DATE',
                'pull_out.AMOUNT',
                'pull_out.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'c.NAME as PREPARED_BY',
                'pull_out.STATUS as STATUS_ID',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'pull_out.PREPARED_BY_ID')
            ->join('document_status_map as s', 's.ID', '=', 'pull_out.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'pull_out.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('pull_out.CODE', 'like', '%' . $search . '%')
                        ->orWhere('pull_out.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('pull_out.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('pull_out.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

    public function CountItems(int $PULL_OUT_ID): int
    {
        return (int) PullOutItems::where('PULL_OUT_ID', $PULL_OUT_ID)->count();
    }
    private function getLine($PULL_OUT_ID): int
    {
        return (int) PullOutItems::where('PULL_OUT_ID', $PULL_OUT_ID)->max('LINE_NO');
    }
    public function ItemStore(int $PULL_OUT_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $BATCH_ID, int $ASSET_ACCOUNT_ID)
    {
        $ID = $this->objectService->ObjectNextID('PULL_OUT');

        $LINE_NO = $this->getLine($PULL_OUT_ID) + 1;

        PullOutItems::create([
            'ID'                 => $ID,
            'PULL_OUT_ID'        => $PULL_OUT_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
            'DESCRIPTION'        => null,
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'               => $RATE,
            'AMOUNT'             => $RATE * $QUANTITY,
            'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            'ASSET_ACCOUNT_ID'   => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PULL_OUT_ITEMS, $PULL_OUT_ID);
        $this->UpdateTotal($PULL_OUT_ID);
    }
    public function GetItem(int $ID, int $PULL_OUT_ID)
    {
        return PullOutItems::where('ID', $ID)
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->first();
    }
    public function ItemUpdate(int $ID, int $PULL_OUT_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $BATCH_ID)
    {
        PullOutItems::where('ID', $ID)
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'ID'                 => $ID,
                'ITEM_ID'            => $ITEM_ID,
                'QUANTITY'           => $QUANTITY,
                'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'RATE'               => $RATE,
                'AMOUNT'             => $RATE * $QUANTITY,
                'BATCH_ID'           => $BATCH_ID > 0 ? $BATCH_ID : null,
            ]);
        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::PULL_OUT_ITEMS, $PULL_OUT_ID);
        $this->UpdateTotal($PULL_OUT_ID);
    }
    public function ItemDelete(int $ID, int $PULL_OUT_ID, )
    {
        PullOutItems::where('ID', $ID)
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PULL_OUT_ITEMS, $PULL_OUT_ID);
        $this->UpdateTotal($PULL_OUT_ID);
    }
    public function UpdateTotal(int $PULL_OUT_ID)
    {
        $result = $this->GetTotal($PULL_OUT_ID);

        PullOut::where('ID', $PULL_OUT_ID)
            ->update(
                [
                    'AMOUNT' => $result['AMOUNT'],
                ]
            );
    }

    private function GetTotal(int $PULL_OUT_ID)
    {

        $result = PullOutItems::query()
            ->select([
                DB::raw(' ifnull(sum(AMOUNT),2) as AMOUNT'),
            ])
            ->where('PULL_OUT_ID', '=', $PULL_OUT_ID)
            ->first();

        if ($result) {
            return [
                'AMOUNT' => $result->AMOUNT,
            ];
        }

        return [
            'AMOUNT' => 0,
        ];
    }
    public function ItemView(int $PULL_OUT_ID)
    {
        $result = PullOutItems::query()
            ->select([
                'pull_out_items.ID',
                'pull_out_items.ITEM_ID',
                'pull_out_items.QUANTITY',
                'pull_out_items.UNIT_ID',
                'pull_out_items.RATE',
                'pull_out_items.AMOUNT',
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
            ])
            ->leftJoin('item', 'item.ID', '=', 'pull_out_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'pull_out_items.UNIT_ID')
            ->where('pull_out_items.PULL_OUT_ID', $PULL_OUT_ID)
            ->orderBy('pull_out_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function ItemInventory(int $PULL_OUT_ID)
    {
        $result = PullOutItems::query()
            ->select([
                'pull_out_items.ID',
                'pull_out_items.ITEM_ID',
                'pull_out_items.QUANTITY',
                'pull_out_items.UNIT_BASE_QUANTITY',
                DB::raw(" (select IFNULL(price_level_lines.CUSTOM_COST,0) from price_level_lines where price_level_lines.ITEM_ID = pull_out_items.ITEM_ID and price_level_lines.PRICE_LEVEL_ID = (select location.ID from location where location.ID = p.LOCATION_ID ) ) as COST "),
            ])
            ->join('pull_out as p', 'p.ID', '=', 'pull_out_items.PULL_OUT_ID')
            ->where('pull_out_items.PULL_OUT_ID', $PULL_OUT_ID)
            ->get();

        return $result;
    }

    public function getPullOutJournal(int $PULL_OUT_ID)
    {
        $result = PullOut::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw(" 0 as SUBSIDIARY_ID"),
                'AMOUNT',
                DB::raw(" 0 as ENTRY_TYPE"),
                DB::raw("'SOURCEACCOUNT' as EXTENDED_OPTIONS"),
                DB::raw("YEAR(DATE) as SEQUENCE_GROUP"),
            ])
            ->where('ID', $PULL_OUT_ID)->get();

        return $result;
    }
    public function getPullOutItemsJournal(int $PULL_OUT_ID)
    {
        $result = PullOutItems::query()
            ->select([
                'ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
                DB::raw("'DESTACCOUNT' as EXTENDED_OPTIONS"),
            ])
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getFixedAccounts(int $ID)
    {

    }

    public function getMakeJournal(int $PULLOUT_ID)
    {
        $gotUpdate = false;
        try {
            $dataHemo = $this->get($PULLOUT_ID);
            if ($dataHemo) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord($this->object_type_map_pull_out, $PULLOUT_ID);
                if ($JOURNAL_NO == 0) {
                    $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->object_type_map_pull_out, $PULLOUT_ID) + 1;
                } else {
                    // make adjustment
                    $gotUpdate = true;
                }
                $resultDebit = $this->getJournalByItemDebit($PULLOUT_ID);

                if ($gotUpdate) {

                    foreach ($resultDebit as $list) {
                        $acctID = $this->accountServices->EXPENSE_ACCOUNT_ID; //
                        $this->accountJournalServices->DeleteJournal($acctID, $dataHemo->LOCATION_ID, $JOURNAL_NO, 0, $PULLOUT_ID, $this->object_type_map_pull_out, $dataHemo->DATE, 0);
                        // if ($acctID != $list->ACCOUNT_ID) {
                        //     $this->accountJournalServices->updateAccount(
                        //         $list->ID,
                        //         $this->object_type_map_pull_out,
                        //         $dataHemo->DATE,
                        //         $dataHemo->LOCATION_ID,
                        //         $acctID,
                        //         $list->ACCOUNT_ID,
                        //     );

                        // }

                    }
                }

                $this->accountJournalServices->JournalExecute(
                    $JOURNAL_NO,
                    $resultDebit,
                    $dataHemo->LOCATION_ID,
                    $this->object_type_map_pull_out,
                    $dataHemo->DATE
                );

                $resultCredit = $this->getJournalByItemCredit($PULLOUT_ID);

                if ($gotUpdate) {
                    foreach ($resultCredit as $list) {
                        $acctID = 6; // inventory asset

                        if ($list->ACCOUNT_ID != $acctID) {
                            $this->accountJournalServices->updateAccount(
                                $list->ID,
                                $this->object_type_map_pull_out_items,
                                $dataHemo->DATE,
                                $dataHemo->LOCATION_ID,
                                $acctID,
                                $list->ACCOUNT_ID,
                            );
                        }
                    }
                }

                $this->accountJournalServices->JournalExecute(
                    $JOURNAL_NO,
                    $resultCredit,
                    $dataHemo->LOCATION_ID,
                    $this->object_type_map_pull_out_items,
                    $dataHemo->DATE
                );

                $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
                $debit_sum  = (float) $data['DEBIT'];
                $credit_sum = (float) $data['CREDIT'];

                if ($debit_sum == $credit_sum) {
                    return true;
                }

                return false;

            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return false;
        }
    }

    private function getJournalByItemCredit(int $PULL_OUT_ID)
    {
        $exSQL  = "select IFNULL(pll.CUSTOM_COST,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = hi.ITEM_ID and l.ID = h.LOCATION_ID limit 1";
        $result = HemoJournal::query()
            ->select([
                'hi.ID',
                'hemo_journal.CREDIT_ACCOUNT_ID as ACCOUNT_ID',
                'hi.ITEM_ID as SUBSIDIARY_ID',
                DB::raw("((($exSQL) * hi.UNIT_BASE_QUANTITY ) *  hi.QUANTITY ) as AMOUNT"),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->join('item_sub_class as s', 's.CLASS_ID', '=', 'hemo_journal.ITEM_CLASS_ID')
            ->join('item as i', 'i.SUB_CLASS_ID', '=', 's.ID')
            ->join('pull_out_items as hi', 'hi.ITEM_ID', '=', 'i.ID')
            ->join('pull_out as h', 'h.ID', '=', 'hi.PULL_OUT_ID')
            ->where('hi.PULL_OUT_ID', '=', $PULL_OUT_ID)
            ->whereIn('i.TYPE', ['0', '1'])
            ->orderBy('hi.ID', 'asc')
            ->get();

        return $result;

    }

    private function getJournalByItemDebit(int $PULL_OUT_ID)
    {
        $exSQL  = "select IFNULL(pll.CUSTOM_COST,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = hi.ITEM_ID and l.ID = h.LOCATION_ID Limit 1";
        $result = HemoJournal::query()
            ->select([
                'h.ID',
                'hemo_journal.DEBIT_ACCOUNT_ID as ACCOUNT_ID',
                DB::raw("SUM(((($exSQL) * hi.UNIT_BASE_QUANTITY ) *  hi.QUANTITY )) as AMOUNT"),
                'h.PREPARED_BY_ID as SUBSIDIARY_ID',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->join('item_sub_class as s', 's.CLASS_ID', '=', 'hemo_journal.ITEM_CLASS_ID')
            ->join('item as i', 'i.SUB_CLASS_ID', '=', 's.ID')
            ->join('pull_out_items as hi', 'hi.ITEM_ID', '=', 'i.ID')
            ->join('pull_out as h', 'h.ID', '=', 'hi.PULL_OUT_ID')
            ->where('hi.PULL_OUT_ID', '=', $PULL_OUT_ID)
            ->whereIn('i.TYPE', ['0', '1'])
            ->orderBy('hemo_journal.DEBIT_ACCOUNT_ID', 'asc')
            ->groupBy([
                'h.ID',
                'hemo_journal.DEBIT_ACCOUNT_ID',
                'h.PREPARED_BY_ID',
            ])
            ->get();

        return $result;

    }

    public function getPullOutPreviousAccount()
    {
        $OLD_ACCOUNT_ID = 100;

        return PullOut::where('ACCOUNT_ID', '=', $OLD_ACCOUNT_ID)->first();
    }
    public function getCountPrevousAccount(): int
    {
        $OLD_ACCOUNT_ID = 100;

        return (int) PullOut::where('ACCOUNT_ID', '=', $OLD_ACCOUNT_ID)->count();
    }
    public function setUpdateParameter(int $ID)
    {
        DB::beginTransaction();

        try {

            PullOut::where('ID', $ID)
                ->update([
                    'ACCOUNT_ID' => $this->default_debit_account_id,
                ]);

            $this->getMakeJournal($ID);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage() . ' - ID: ' . $ID);
            return;
        }

    }
}
