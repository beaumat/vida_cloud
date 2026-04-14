<?php
namespace App\Services;

use App\Models\AccountJournal;
use App\Models\AccountJournalEnding;

class AccountJournalEndingServices
{

    public function Recount(int $JOURNAL_ACCOUNT_ID)
    {
        $isExist = AccountJournalEnding::where('AJ_ID', '=', $JOURNAL_ACCOUNT_ID)->exists();
        if (! $isExist) {
            AccountJournalEnding::create(['AJ_ID' => $JOURNAL_ACCOUNT_ID]);
        }
    }

    public function ResetFirstEntryAccount(int $ACCOUNT_ID, int $LOCATION_ID)
    {
        $result = AccountJournal::where('ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('OBJECT_DATE', 'asc')
            ->orderBy('ID', 'asc')
            ->first();

        if ($result) {
            $this->SetZeroBalance($result->ID);
            $this->Recount($result->ID);
        }

    }
    public function SetZeroBalance(int $JOURNAL_ACCOUNT_ID)
    {
        AccountJournal::where('ID', '=', $JOURNAL_ACCOUNT_ID)
            ->update(['ENDING_BALANCE' => '0.00']);
    }

}
