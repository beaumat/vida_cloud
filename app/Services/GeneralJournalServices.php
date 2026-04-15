<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetails;
use App\Models\GeneralJournalDetailsTemp;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GeneralJournalServices
{

    public int $object_type_general_journal_details_id = 84;
    private $dateServices;
    private $systemSettingServices;
    private $object;
    private $usersLogServices;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices, UsersLogServices $usersLogServices)
    {
        $this->object                = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function Store(string $DATE, string $CODE, int $LOCATION_ID, bool $ADJUSTING_ENTRY, string $NOTES, int $CONTACT_ID = 0): int
    {

        $ID          = (int) $this->object->ObjectNextID('GENERAL_JOURNAL');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('GENERAL_JOURNAL');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        GeneralJournal::create([
            'ID'              => $ID,
            'DATE'            => $DATE,
            'RECORDED_ON'     => $this->dateServices->Now(),
            'CODE'            => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'LOCATION_ID'     => $LOCATION_ID,
            'ADJUSTING_ENTRY' => $ADJUSTING_ENTRY,
            'NOTES'           => $NOTES,
            'STATUS'          => 0,
            'STRATUS_DATE'    => $this->dateServices->NowDate(),
            'CONTACT_ID'      => $CONTACT_ID > 0 ? $CONTACT_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::GENERAL_JOURNAL, $ID);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        GeneralJournal::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::GENERAL_JOURNAL, $ID);
    }
    public function Update(int $ID, string $CODE, int $LOCATION_ID, bool $ADJUSTING_ENTRY, string $NOTES, int $CONTACT_ID = 0, string $DATE)
    {
        GeneralJournal::where('ID', '=', $ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->update([
                'CODE'            => $CODE,
                'ADJUSTING_ENTRY' => $ADJUSTING_ENTRY,
                'NOTES'           => $NOTES,
                'CONTACT_ID'      => $CONTACT_ID > 0 ? $CONTACT_ID : null,
                'DATE'            => $DATE,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::GENERAL_JOURNAL, $ID);
    }
    public function Delete(int $ID)
    {
        GeneralJournalDetails::where('GENERAL_JOURNAL_ID', $ID)->delete();
        GeneralJournal::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::GENERAL_JOURNAL, $ID);
    }
    public function Get(int $ID)
    {
        return GeneralJournal::where('ID', $ID)->first();
    }
    public function Search($search, int $locationId, int $perPage): LengthAwarePaginator
    {
        $result = GeneralJournal::query()
            ->select([
                'general_journal.ID',
                'general_journal.CODE',
                'general_journal.DATE',
                'general_journal.NOTES',
                'general_journal.ADJUSTING_ENTRY',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'general_journal.STATUS as STATUS_ID',
                'general_journal.CONTACT_ID',
                'c.PRINT_NAME_AS as CONTACT_NAME',
            ])
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'general_journal.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'general_journal.STATUS')
            ->leftJoin('contact as c', 'c.ID', '=', 'general_journal.CONTACT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('general_journal.CODE', 'like', '%' . $search . '%')
                        ->orWhere('general_journal.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('general_journal.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

    private function getLine($Id): int
    {
        return (int) GeneralJournalDetails::where('GENERAL_JOURNAL_ID', $Id)->max('LINE_NO');
    }
    public function StoreDetails(int $GENERAL_JOURNAL_ID, int $ACCOUNT_ID, float $DEBIT, float $CREDIT, string $NOTES, int $CLASS_ID = 0)
    {

        $ENTRY_TYPE = 0;
        if ($CREDIT != 0) {
            $ENTRY_TYPE = 1;
        }
        $ID      = (int) $this->object->ObjectNextID('GENERAL_JOURNAL_DETAILS');
        $LINE_NO = (int) $this->getLine($GENERAL_JOURNAL_ID) + 1;

        GeneralJournalDetails::create([
            'ID'                 => $ID,
            'GENERAL_JOURNAL_ID' => $GENERAL_JOURNAL_ID,
            'LINE_NO'            => $LINE_NO,
            'ACCOUNT_ID'         => $ACCOUNT_ID,
            'ENTRY_TYPE'         => $ENTRY_TYPE,
            'DEBIT'              => $DEBIT,
            'CREDIT'             => $CREDIT,
            'AMOUNT'             => $ENTRY_TYPE == 0 ? $DEBIT : $CREDIT,
            'NOTES'              => $NOTES,
            'CLASS_ID'           => $CLASS_ID > 0 ? $CLASS_ID : null,
        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::GENERAL_JOURNAL_DETAILS, $GENERAL_JOURNAL_ID);
        return $ID;
    }
    public function UpdateDetails(int $ID, int $GENERAL_JOURNAL_ID, int $ACCOUNT_ID, float $DEBIT, float $CREDIT, string $NOTES, int $CLASS_ID = 0)
    {
        $ENTRY_TYPE = 0;
        if ($CREDIT != 0) {
            $ENTRY_TYPE = 1;
        }
         GeneralJournalDetails::where('ID', $ID)
            ->where('GENERAL_JOURNAL_ID', $GENERAL_JOURNAL_ID)
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->update([
                'ENTRY_TYPE' => $ENTRY_TYPE,
                'DEBIT'      => $DEBIT,
                'CREDIT'     => $CREDIT,
                'AMOUNT'     => $ENTRY_TYPE == 0 ? $DEBIT : $CREDIT,
                'NOTES'      => $NOTES,
                'CLASS_ID'   => $CLASS_ID > 0 ? $CLASS_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::GENERAL_JOURNAL_DETAILS, $GENERAL_JOURNAL_ID);
    }
    public function DeleteDetails(int $ID)
    {
        $data = $this->getDetails($ID);
        if ($data) {
            GeneralJournalDetails::where('ID', $data->ID)
                ->where('GENERAL_JOURNAL_ID', $data->GENERAL_JOURNAL_ID)
                ->where('ACCOUNT_ID', $data->ACCOUNT_ID)
                ->delete();

            $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::GENERAL_JOURNAL_DETAILS, $data->GENERAL_JOURNAL_ID);
        }
    }
    public function ListDetails(int $GENERAL_JOURNAL_ID)
    {
        $result = GeneralJournalDetails::query()
            ->select([
                'general_journal_details.ID',
                'general_journal_details.ACCOUNT_ID',
                'general_journal_details.DEBIT',
                'general_journal_details.CREDIT',
                'general_journal_details.ENTRY_TYPE',
                'general_journal_details.NOTES',
                'general_journal_details.CLASS_ID',
                'account.NAME as ACCOUNT_DESCRIPTION',
                'account.TAG as CODE',
                'class.NAME as CLASS_NAME',

            ])
            ->leftJoin('account', 'account.ID', '=', 'general_journal_details.ACCOUNT_ID')
            ->leftJoin('class', 'class.ID', '=', 'general_journal_details.CLASS_ID')
            ->where('general_journal_details.GENERAL_JOURNAL_ID', $GENERAL_JOURNAL_ID)
            ->get();

        return $result;
    }

    public function GetTotal(int $GENERAL_JOURNAL_ID)
    {

        $result = GeneralJournalDetails::query()
            ->select([
                DB::raw('ifnull(sum(DEBIT),0) as TOTAL_DEBIT'),
                DB::raw('ifnull(sum(CREDIT),0) as TOTAL_CREDIT'),
            ])
            ->where('GENERAL_JOURNAL_ID', $GENERAL_JOURNAL_ID)
            ->first();

        if ($result) {
            return [
                'TOTAL_DEBIT'  => $result->TOTAL_DEBIT,
                'TOTAL_CREDIT' => $result->TOTAL_CREDIT,
            ];
        }

        return [
            'TOTAL_DEBIT'  => 0,
            'TOTAL_CREDIT' => 0,
        ];
    }
    public function getDetails(int $Id)
    {
        return GeneralJournalDetails::where('ID', $Id)->first();
    }
    public function getFirstDetailsID($GENERAL_JOURNAL_ID): int
    {
        $data = GeneralJournalDetails::where('GENERAL_JOURNAL_ID', $GENERAL_JOURNAL_ID)->first();

        if ($data) {
            return (int) $data->ID ?? 0;
        }

        return 0;
    }
    public function getGeneralJournalEntries(int $ID)
    {
        $result = GeneralJournalDetails::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                'ENTRY_TYPE',
            ])
            ->where('GENERAL_JOURNAL_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getListTemplete()
    {
        $result = GeneralJournalDetailsTemp::query()
            ->select([
                'general_journal_details_temp.ID',
                'general_journal_details_temp.ACCOUNT_ID',
                'general_journal_details_temp.NOTES',
                'account.NAME as ACCOUNT_NAME',
            ])
            ->join('account', 'account.ID', '=', 'general_journal_details_temp.ACCOUNT_ID')
            ->get();

        return $result;
    }

    public function setToDetails(int $GENERAL_JOURNAL_ID, array $array)
    {
        foreach ($array as $list) {
            $this->StoreDetails(
                $GENERAL_JOURNAL_ID,
                $list['ACCOUNT_ID'],
                (float) $list['DEBIT'],
                (float) $list['CREDIT'],
                $list['NOTES'],
                0
            );
        }
    }
    public function updateIsXero(int $ID, bool $IS_XERO)
    {
        GeneralJournal::where('ID', $ID)
            ->update([
                'IS_XERO' => $IS_XERO,
            ]);
    }
    public function listViaContact(int $CONTACT_ID)
    {
        $result = GeneralJournal::query()
            ->select([
                'general_journal.ID',
                'general_journal.CODE',
                'general_journal.DATE',
                DB::raw('(select sum(w.AMOUNT) from general_journal_details as w where w.GENERAL_JOURNAL_ID = general_journal.ID and w.ENTRY_TYPE = 0 ) as AMOUNT'),
                'general_journal.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', 'l.ID', '=', 'general_journal.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'general_journal.STATUS')
            ->where('general_journal.CONTACT_ID', '=', $CONTACT_ID)
            ->orderBy('general_journal.DATE', 'desc')
            ->get();

        return $result;
    }
}
