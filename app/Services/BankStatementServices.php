<?php
namespace App\Services;

use App\Enums\TableName;
use App\Models\BankStatement;
use App\Models\BankStatementDetails;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BankStatementServices
{
    private $dateServices;
    private $objectServices;
    private $accountJournalSerivces;
    public function __construct(DateServices $dateServices, ObjectServices $objectServices, AccountJournalServices $accountJournalSerivces)
    {
        $this->dateServices           = $dateServices;
        $this->objectServices         = $objectServices;
        $this->accountJournalSerivces = $accountJournalSerivces;

    }
    public function get(int $id)
    {
        $data = BankStatement::where("ID", $id)->first();
        return $data;
    }
    public function getList(int $BANK_ACCOUNT_ID): array | Collection
    {
        $data = BankStatement::query()
            ->select(['ID', 'DESCRIPTION'])
            ->where("BANK_ACCOUNT_ID", '=', $BANK_ACCOUNT_ID)
            ->orderBy('ID')
            ->get();

        return $data;

    }
    public function store(string $DATE_FROM, string $DATE_TO, string $DESCRIPTION, int $BANK_ACCOUNT_ID, int $FILE_TYPE, string $NOTES): int
    {

        $ID = (int) $this->objectServices->ObjectNextID(TableName::BANK_STATEMENT->value);

        BankStatement::create([
            'ID'              => $ID,
            'DATE_FROM'       => $DATE_FROM,
            'DATE_TO'         => $DATE_TO,
            'RECORDED_ON'     => $this->dateServices->Now(),
            'DESCRIPTION'     => $DESCRIPTION,
            'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
            'FILE_TYPE'       => $FILE_TYPE,
            'NOTES'           => $NOTES,
        ]);

        return $ID;
    }
    public function update(int $ID, string $DATE_FROM, string $DATE_TO, string $DESCRIPTION, int $BANK_ACCOUNT_ID, int $FILE_TYPE, string $NOTES)
    {
        BankStatement::where('ID', '=', $ID)
            ->update([
                'DATE_FROM'       => $DATE_FROM,
                'DATE_TO'         => $DATE_TO,
                'DESCRIPTION'     => $DESCRIPTION,
                'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
                'FILE_TYPE'       => $FILE_TYPE,
                'NOTES'           => $NOTES,
            ]);
    }
    public function delete(int $ID)
    {
        BankStatementDetails::where('BANK_STATEMENT_ID', '=', $ID)->delete();
        BankStatement::where('ID', '=', $ID)->delete();
    }

    public function Search($search)
    {
        $result = BankStatement::query()
            ->select([
                'bank_statement.ID',
                'bank_statement.DATE_FROM',
                'bank_statement.DATE_TO',
                'bank_statement.RECORDED_ON',
                'bank_statement.DESCRIPTION',
                'bank_statement.NOTES',
                'b.NAME as BANK_NAME',
                'bank_statement.RECON_STATUS',
                'bank_statement.RECON_DATE',
                't.DESCRIPTION as FILE_TYPE',
            ])
            ->join('account as b', 'b.ID', '=', 'bank_statement.BANK_ACCOUNT_ID')
            ->join('file_type_map as t', 't.ID', '=', 'bank_statement.FILE_TYPE')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bank_statement.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('b.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('bank_statement.ID', 'desc')
            ->paginate(30);

        return $result;
    }

    public function storeDetails(int $BANK_STATEMENT_ID, string $DATE_TRANSACTION, string $REFERENCE, string $DESCRIPTION, string $CHECK_NUMBER, float $DEBIT, float $CREDIT, float $BALANCE)
    {

        $ID = (int) $this->objectServices->ObjectNextID(TableName::BANK_STATEMENT_DETAILS->value);

        BankStatementDetails::create([
            'ID'                => $ID,
            'BANK_STATEMENT_ID' => $BANK_STATEMENT_ID,
            'DATE_TRANSACTION'  => $this->dateServices->DateFormat($DATE_TRANSACTION),
            'REFERENCE'         => $REFERENCE,
            'DESCRIPTION'       => $DESCRIPTION,
            'CHECK_NUMBER'      => $CHECK_NUMBER,
            'DEBIT'             => $DEBIT,
            'CREDIT'            => $CREDIT,
            'BALANCE'           => $BALANCE,
        ]);
    }
    public function updateDetails(int $ID, int $BANK_STATEMENT_ID, string $DATE_TRANSACTION, string $REFERENCE, string $DESCRIPTION, string $CHECK_NUMBER, float $DEBIT, float $CREDIT, float $BALANCE)
    {
        BankStatementDetails::where('ID', '=', $ID)
            ->update([
                'BANK_STATEMENT_ID' => $BANK_STATEMENT_ID,
                'DATE_TRANSACTION'  => $DATE_TRANSACTION,
                'REFERENCE'         => $REFERENCE,
                'DESCRIPTION'       => $DESCRIPTION,
                'CHECK_NUMBER'      => $CHECK_NUMBER,
                'DEBIT'             => $DEBIT,
                'CREDIT'            => $CREDIT,
                'BALANCE'           => $BALANCE,
            ]);
    }
    public function deleteDetails(int $ID)
    {
        BankStatementDetails::where('ID', '=', $ID)->delete();
    }
    public function getDetails(string $OBJECT_DATE, int $OBJECT_TYPE, int $OBJECT_ID)
    {
        $result = BankStatementDetails::query()
            ->select(['ID'])
            ->whereDate('DATE_TRANSACTION', $OBJECT_DATE)
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();

        return $result;
    }
    public function listDetails(int $BANK_STATEMENT_ID): array | Collection
    {

        $result = BankStatementDetails::query()
            ->select([
                'ID',
                'BANK_STATEMENT_ID',
                'DATE_TRANSACTION',
                'REFERENCE',
                'DESCRIPTION',
                'CHECK_NUMBER',
                'DEBIT',
                'CREDIT',
                'BALANCE',
                'OBJECT_TYPE',
                'OBJECT_ID',
                'RECON_LOG',
            ])
            ->where('BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->orderBy('ID')
            ->get();

        return $result;
    }
    public function listDetailsDateResult(int $BANK_STATEMENT_ID): array | Collection
    {
        $result = BankStatementDetails::query()
            ->selectRaw('DATE(DATE_TRANSACTION) as DATE_TRANSACTION')
            ->where('BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->groupByRaw('DATE(DATE_TRANSACTION)')
            ->get();

        return $result;
    }
    private function GetBeginningBalance(int $BANK_STATEMENT_ID)
    {

        $data = BankStatementDetails::query()
            ->select(['DEBIT', 'CREDIT', 'BALANCE'])
            ->where('BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->first();
        if ($data) {

            $DEBIT   = $data->DEBIT ?? 0;
            $CREDIT  = $data->CREDIT ?? 0;
            $BALANCE = $data->BALANCE ?? 0;

            if ($DEBIT > 0) {
                return $BALANCE - $DEBIT;
            }

            if ($CREDIT > 0) {
                return $BALANCE + $CREDIT;
            }

        }

        return 0;
    }
    private function GetEndingBalance(int $BANK_STATEMENT_ID): float
    {

        $data = BankStatementDetails::query()->select(['BALANCE'])
            ->where('BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->orderBy('ID', 'desc')
            ->first();
        if ($data) {

            $BALANCE = $data->BALANCE ?? 0;

            return $BALANCE;

        }

        return 0;
    }

    public function UpdateField(int $BANK_STATEMENT_ID)
    {
        $BEGIN_BALANCE = $this->GetBeginningBalance($BANK_STATEMENT_ID);
        $END_BALANCE   = $this->GetEndingBalance($BANK_STATEMENT_ID);

        BankStatement::where('ID', '=', $BANK_STATEMENT_ID)
            ->update(['BEGINNING_BALANCE' => $BEGIN_BALANCE, 'ENDING_BALANCE' => $END_BALANCE]);

    }
    public function updateEntryBankStatement(int $ID, int $OBJECT_TYPE, int $OBJECT_ID)
    {
        BankStatementDetails::where('ID', '=', $ID)->update([
            'OBJECT_TYPE' => $OBJECT_TYPE,
            'OBJECT_ID'   => $OBJECT_ID,
            'RECON_LOG'   => $this->dateServices->NowDateTime(),
        ]);
    }
    public function updateNullBankStatement(int $ID)
    {
        BankStatementDetails::where('ID', '=', $ID)
            ->update([
                'OBJECT_TYPE' => null,
                'OBJECT_ID'   => null,
                'RECON_LOG'   => null,
            ]);
    }
    public function getbankStatement(int $BANK_STATEMENT_ID, $search): array | Collection
    {
        $result = BankStatementDetails::query()
            ->select([
                'bank_statement_details.ID',
                'bank_statement_details.DATE_TRANSACTION',
                'bank_statement_details.REFERENCE',
                'bank_statement_details.DESCRIPTION',
                'bank_statement_details.CHECK_NUMBER',
                'bank_statement_details.DEBIT',
                'bank_statement_details.CREDIT',
                'bank_statement_details.BALANCE',
                'bank_statement_details.OBJECT_TYPE',
                'bank_statement_details.OBJECT_ID',
                'bank_statement_details.RECON_LOG',

            ])
            ->join('bank_statement as bs', 'bs.ID', '=', 'bank_statement_details.BANK_STATEMENT_ID')
            ->leftJoin('account_journal as aj', function ($join) {
                $join->on('aj.OBJECT_ID', '=', 'bank_statement_details.OBJECT_ID');
                $join->on('aj.OBJECT_TYPE', '=', 'bank_statement_details.OBJECT_TYPE');
                $join->on(
                    'aj.OBJECT_DATE',
                    '=',
                    DB::raw('DATE(bank_statement_details.DATE_TRANSACTION)')
                );

            })
            ->where('bs.ID', '=', $BANK_STATEMENT_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bank_statement_details.REFERENCE', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.DEBIT', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.CREDIT', 'like', '%' . $search . '%');
                });
            })
            ->get();

        return $result;
    }

    public function getbankStatementRecon(int $BANK_STATEMENT_ID, int $BANK_ACCOUNT_ID, $search): array | Collection
    {
        $result = BankStatementDetails::query()
            ->select([
                'bank_statement_details.ID',
                'bank_statement_details.DATE_TRANSACTION',
                'bank_statement_details.REFERENCE',
                'bank_statement_details.DESCRIPTION',
                'bank_statement_details.CHECK_NUMBER',
                'bank_statement_details.DEBIT',
                'bank_statement_details.CREDIT',
                'bank_statement_details.BALANCE',
                DB::raw($this->accountJournalSerivces->TX_CODE),
                DB::raw($this->accountJournalSerivces->GetFullDescription()),
                'aj.AMOUNT',
                'l.NAME as LOCATION_NAME',
            ])
            ->leftJoin('account_journal as aj', function ($join) use (&$BANK_ACCOUNT_ID) {
                $join->on('aj.OBJECT_ID', '=', 'bank_statement_details.OBJECT_ID');
                $join->on('aj.OBJECT_TYPE', '=', 'bank_statement_details.OBJECT_TYPE');
                $join->on('aj.ACCOUNT_ID', '=', DB::raw($BANK_ACCOUNT_ID));
                $join->on('aj.AMOUNT', '!=', DB::raw(0));
            })
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'bank_statement_details.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('bank_statement_details.BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bank_statement_details.REFERENCE', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.DEBIT', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.CREDIT', 'like', '%' . $search . '%');
                });
            })

            ->get();

        return $result;
    }
    public function getbankStatementReconUncleared(int $BANK_STATEMENT_ID, int $BANK_ACCOUNT_ID, $search): array | Collection
    {

        $result = BankStatementDetails::query()
            ->select([
                'bank_statement_details.ID',
                'bank_statement_details.DATE_TRANSACTION',
                'bank_statement_details.REFERENCE',
                'bank_statement_details.DESCRIPTION',
                'bank_statement_details.CHECK_NUMBER',
                'bank_statement_details.DEBIT',
                'bank_statement_details.CREDIT',
                'bank_statement_details.BALANCE',
                DB::raw($this->accountJournalSerivces->TX_CODE),
                DB::raw($this->accountJournalSerivces->GetFullDescription()),
                'aj.AMOUNT',
                'l.NAME as LOCATION_NAME',
            ])
            ->leftJoin('account_journal as aj', function ($join) use (&$BANK_ACCOUNT_ID) {
                $join->on('aj.OBJECT_ID', '=', 'bank_statement_details.OBJECT_ID');
                $join->on('aj.OBJECT_TYPE', '=', 'bank_statement_details.OBJECT_TYPE');
                $join->on('aj.ACCOUNT_ID', '=', DB::raw($BANK_ACCOUNT_ID));
                $join->on('aj.AMOUNT', '!=', DB::raw(0));
            })
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'bank_statement_details.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('bank_statement_details.BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->whereNull('bank_statement_details.RECON_LOG')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bank_statement_details.REFERENCE', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.DEBIT', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement_details.CREDIT', 'like', '%' . $search . '%');
                });
            })

            ->get();

        return $result;

    }

    public function getSumDebitCredit(int $BANK_STATEMENT_ID, )
    {

        $result = BankStatementDetails::query()
            ->select([
                DB::raw('SUM(DEBIT) as TOTAL_DEBIT'),
                DB::raw('SUM(CREDIT) as TOTAL_CREDIT'),
            ])
            ->where('BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->first();

        if ($result) {
            return ['DEBIT' => $result->TOTAL_DEBIT ?? 0, 'CREDIT' => $result->TOTAL_CREDIT ?? 0];
        }

        return ['DEBIT' => 0, 'CREDIT' => 0];

    }
}
