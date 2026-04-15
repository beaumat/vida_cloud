<?php
namespace App\Services;

use App\Enums\DocStatus;
use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\UsersLog;

class UsersLogServices
{
    public $userServices;
    public $dateServices;
    public function __construct(UserServices $userServices, DateServices $dateServices)
    {
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function AddLogs(TransType $TRANS_TYPE, LogEntity $LOG_TYPE, int $LOG_ID)
    {

        try {

            $USERNAME = $this->userServices->GetUsername();

            if ($USERNAME != "") { // USERNAME MUST BE REQUIRED

                if ($this->userServices->LogsDisabled()) {
                    return; // if true make no log entry
                }

                UsersLog::create([
                    'USERNAME'     => $USERNAME,
                    'TRANS_TYPE'   => $TRANS_TYPE,
                    'LOG_DATETIME' => $this->dateServices->NowDateTime(),
                    'LOG_TYPE'     => $LOG_TYPE,
                    'LOG_ID'       => $LOG_ID,
                ]);
            }

        } catch (\Throwable $th) {

        }
    }
    public function StatusLog(int $STATUS, LogEntity $LOG_TYPE, int $LOG_ID)
    {
        $POSTED_ID = DocStatus::POSTED->value;

        $UNPOSTED_ID = DocStatus::UNPOSTED->value;

        if ($POSTED_ID === $STATUS) {

            $this->AddLogs(TransType::POST, $LOG_TYPE, $LOG_ID);

        } elseif ($UNPOSTED_ID === $STATUS) {

            $this->AddLogs(TransType::UNPOST, $LOG_TYPE, $LOG_ID);
        }

    }

}
