<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\Check;
use App\Models\DoctorBatch;
use App\Models\DoctorBatchPaid;
use Illuminate\Support\Facades\DB;

class DoctorBatchServices
{
    private $objectServices;
    private $systemSettingServices;
    private $billPaymentServices;

    private $usersLogServices;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, BillPaymentServices $billPaymentServices, UsersLogServices $usersLogServices)
    {
        $this->objectServices        = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->billPaymentServices   = $billPaymentServices;
        $this->usersLogServices      = $usersLogServices;
    }

    public function Get(int $ID)
    {
        $result = DoctorBatch::where('ID', '=', $ID)->first();

        return $result;
    }
    public function Store(int $DOCTOR_ID, int $LOCATION_ID): int
    {
        $ID = $this->objectServices->ObjectNextID("DOCTOR_BATCH");

        $OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('DOCTOR_BATCH');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));
        DoctorBatch::create([
            'ID'          => $ID,
            'CODE'        => $this->objectServices->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DOCTOR_ID'   => $DOCTOR_ID,
            'LOCATION_ID' => $LOCATION_ID,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::DOCTOR_BATCH, $ID);
        return (int) $ID;
    }
    public function Update(int $ID, int $DOCTOR_ID)
    {
        DoctorBatch::where('ID', '=', $ID)
            ->update(['DOCTOR_ID' => $DOCTOR_ID]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::DOCTOR_BATCH, $ID);
    }
    public function Delete(int $ID)
    {
        DoctorBatchPaid::where('DOCTOR_BATCH_ID', '=', $ID)->delete();
        DoctorBatch::where('ID', '=', $ID)->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::DOCTOR_BATCH, $ID);
    }
    public function Search($search, int $locationId)
    {
        $result = DoctorBatch::query()
            ->select([
                'doctor_batch.ID',
                'doctor_batch.CODE',
                'c.NAME',
                'l.NAME as LOCATION_NAME',
                DB::raw("(select count(*) from doctor_batch_paid where doctor_batch_paid.doctor_batch_id = doctor_batch.ID ) as TOTAL_COUNT"),
                DB::raw("(select SUM(p.AMOUNT) from doctor_batch_paid inner join `check` as p on p.ID = doctor_batch_paid.CHECK_ID where doctor_batch_paid.doctor_batch_id = doctor_batch.ID) as TOTAL_AMOUNT"),
            ])
            ->join('contact as c', 'c.ID', 'doctor_batch.DOCTOR_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'doctor_batch.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->where('doctor_batch.LOCATION_ID', '=', $locationId)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('c.NAME', 'like', '%' . $search . '%');
                    $q->orWhere('doctor_batch.CODE', 'like', '%' . $search . '%');
                });
            })->orderBy('doctor_batch.ID', 'desc')
            ->paginate(30);

        return $result;
    }

    public function StorePaid(int $DOCTOR_BATCH_ID, int $PAYMENT_PERIOD_ID, int $CHECK_ID)
    {
        $ID = $this->objectServices->ObjectNextID("DOCTOR_BATCH_PAID");
        DoctorBatchPaid::create([
            'ID'                => $ID,
            'PAYMENT_PERIOD_ID' => $PAYMENT_PERIOD_ID,
            'CHECK_ID'          => $CHECK_ID,
            'DOCTOR_BATCH_ID'   => $DOCTOR_BATCH_ID,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::DOCTOR_BATCH_PAID, $DOCTOR_BATCH_ID);
    }
    public function DeletePaid(int $ID, int $DOCTOR_BATCH_ID)
    {
        DoctorBatchPaid::where('ID', '=', $ID)->where('DOCTOR_BATCH_ID', '=', $DOCTOR_BATCH_ID)->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::DOCTOR_BATCH_PAID, $DOCTOR_BATCH_ID);
    }
    public function PaidList(int $DOCTOR_BATCH_ID)
    {
        $result = Check::query()
            ->select([
                'dbp.ID',
                'check.ID as CHECK_ID',
                'check.CODE',
                'check.DATE',
                'check.AMOUNT',
                'check.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'a.NAME as BANK_ACCOUNT_NAME',
                'check.STATUS as STATUS_ID',
                'check.PF_PERIOD_ID',
                DB::raw("(select count(*) from check_bills where check_bills.CHECK_ID = check.ID) as TOTAL_COUNT"),
                DB::raw("(select sum(t.AMOUNT_WITHHELD) from withholding_tax_bills  as t inner join check_bills as cb on cb.BILL_ID = t.BILL_ID  where   cb.CHECK_ID = check.ID  LIMIT 1) as TAX_AMOUNT"),
                'pp.RECEIPT_NO as OR_NO',
                'pp.DATE as OR_DATE',
                'pp.DATE_FROM',
                'pp.DATE_TO',

            ])
            ->join('contact as c', 'c.ID', '=', 'check.PAY_TO_ID')
            ->join('account as a', 'a.ID', '=', 'check.BANK_ACCOUNT_ID')
            ->join('location as l', 'l.ID', '=', 'check.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'check.STATUS')
            ->join('payment_period as pp', 'pp.ID', '=', 'check.PF_PERIOD_ID')
            ->join('doctor_batch_paid as dbp', 'dbp.CHECK_ID', '=', 'check.ID')
            ->join('doctor_batch as db', 'db.ID', '=', 'dbp.DOCTOR_BATCH_ID')
            ->where('check.TYPE', '=', $this->billPaymentServices->CHECK_TYPE_ID)
            ->where('dbp.DOCTOR_BATCH_ID', '=', $DOCTOR_BATCH_ID)
            ->whereNotNull('check.PF_PERIOD_ID')
            ->orderBy('check.ID', 'desc')
            ->get();

        return $result;
    }
}
