<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\Check;
use App\Models\CheckBills;
use Illuminate\Support\Facades\DB;

class BillPaymentServices
{
    public int $CHECK_TYPE_ID           = 1;
    public int $object_type_check       = 57;
    public int $object_type_check_bills = 58;

    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;

    private $usersLogServices;
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                = $objectService;
        $this->compute               = $computeServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function Get(int $ID)
    {
        $result = Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->first();

        return $result;
    }
    public function Store(string $CODE, string $DATE, int $BANK_ACCOUNT_ID, int $PAY_TO_ID, int $LOCATION_ID, float $AMOUNT, string $NOTES, int $ACCOUNTS_PAYABLE_ID): int
    {

        $ID          = (int) $this->object->ObjectNextID('CHECK');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('CHECK');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Check::create([
            'ID'                  => $ID,
            'RECORDED_ON'         => $this->dateServices->Now(),
            'CODE'                => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                => $DATE,
            'TYPE'                => $this->CHECK_TYPE_ID,
            'BANK_ACCOUNT_ID'     => $BANK_ACCOUNT_ID,
            'PAY_TO_ID'           => $PAY_TO_ID,
            'LOCATION_ID'         => $LOCATION_ID,
            'AMOUNT'              => $AMOUNT,
            'NOTES'               => $NOTES,
            'PRINTED'             => false,
            'STATUS'              => 0,
            'STATUS_DATE'         => $this->dateServices->NowDate(),
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID ?? null,

        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CHECK, $ID);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Check::where('ID', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::CHECK, $ID);

    }
    public function UpdateAmount(int $ID, float $AMOUNT)
    {
        Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->update([
                'AMOUNT' => $AMOUNT,
            ]);
    }
    public function Update(int $ID, string $CODE, int $BANK_ACCOUNT_ID, int $PAY_TO_ID, int $LOCATION_ID, float $AMOUNT, string $NOTES)
    {
        Check::where('ID', '=', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->update([
                'ID'              => $ID,
                'CODE'            => $CODE,
                'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
                'PAY_TO_ID'       => $PAY_TO_ID,
                'LOCATION_ID'     => $LOCATION_ID,
                'AMOUNT'          => $AMOUNT,
                'NOTES'           => $NOTES,
                'PRINTED'         => false,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CHECK, $ID);

    }
    public function UpdateBillPaymentApplied(int $CHECK_ID): float
    {
        $pay = (float) CheckBills::query()
            ->select(DB::raw('IFNULL(SUM(check_bills.AMOUNT_PAID), 0) as pay'))
            ->where('check_bills.CHECK_ID', '=', $CHECK_ID)
            ->first()
            ->pay ?? 0;

        return $pay;
    }
    public function UpdatePF_PERIOD_ID(int $CHECK_ID, int $PF_PERIOD_ID)
    {

        Check::where('ID', '=', $CHECK_ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->update([
                'PF_PERIOD_ID' => $PF_PERIOD_ID,
            ]);
    }
    public function Delete(int $ID)
    {

        CheckBills::where('CHECK_ID', '=', $ID)
            ->delete();

        Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CHECK, $ID);
    }
    public function getDoctorPaidList(int $DOCTOR_ID, int $LOCATION_ID)
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
                'check.STATUS as STATUS_ID',
                'check.PF_PERIOD_ID',
                DB::raw("(select count(*) from check_bills where check_bills.CHECK_ID = check.ID) as TOTAL_COUNT"),
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
            ->where('check.TYPE', '=', $this->CHECK_TYPE_ID)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('doctor_batch_paid as s')
                    ->whereRaw('s.CHECK_ID = check.ID');

            })
            ->where('check.LOCATION_ID', '=', $LOCATION_ID)
            ->where('check.PAY_TO_ID', '=', $DOCTOR_ID)
            ->whereNotNull('check.PF_PERIOD_ID')
            ->orderBy('check.ID', 'desc')
            ->get();

        return $result;
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
                'check.STATUS as STATUS_ID',

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
    public function BillPaymentBillsExist(int $CHECK_ID, int $BILL_ID): int
    {
        $data = CheckBills::where('CHECK_ID', $CHECK_ID)->where('BILL_ID', $BILL_ID)->first();
        if ($data) {
            return (int) $data->ID;
        }
        return 0;
    }
    public function billPaymentBIllsPatientList($CHECK_ID): object
    {
        $result = CheckBills::query()
            ->select([
                'check_bills.ID',
                'check_bills.BILL_ID',
                'check_bills.DISCOUNT',
                'check_bills.AMOUNT_PAID',
                'bill.CODE',
                'bill.DATE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                'contact.NAME as PATIENT_NAME',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as NO_TREATMENT '),
                DB::raw("(SELECT SUM(withholding_tax_bills.AMOUNT_WITHHELD) from withholding_tax_bills where withholding_tax_bills.BILL_ID = check_bills.BILL_ID) as TAX_AMOUNT"),
            ])
            ->join('bill', 'bill.ID', '=', 'check_bills.BILL_ID')
            ->join('philhealth_prof_fee', 'philhealth_prof_fee.BILL_ID', '=', 'check_bills.BILL_ID')
            ->join('philhealth', 'philhealth.ID', '=', 'philhealth_prof_fee.PHIC_ID')
            ->join('contact', 'contact.ID', '=', 'philhealth.CONTACT_ID')
            ->where('check_bills.CHECK_ID', '=', $CHECK_ID)
            ->get();

        return $result;
    }
    public function getDoctorBatch(int $DOCTOR_BATCH_ID)
    {
        $result = CheckBills::query()
            ->select([
                'check_bills.ID',
                'check_bills.BILL_ID',
                'check_bills.DISCOUNT',
                'check_bills.AMOUNT_PAID',
                'bill.CODE',
                'bill.DATE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                'contact.NAME as PATIENT_NAME',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                'philhealth.AR_NO as LHIO_NO',
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as NO_TREATMENT '),
                DB::raw("(SELECT SUM(withholding_tax_bills.AMOUNT_WITHHELD) from withholding_tax_bills where withholding_tax_bills.BILL_ID = check_bills.BILL_ID) as TAX_AMOUNT"),
            ])
            ->join('doctor_batch_paid as dbp', 'dbp.CHECK_ID', '=', 'check_bills.CHECK_ID')
            ->join('bill', 'bill.ID', '=', 'check_bills.BILL_ID')
            ->join('philhealth_prof_fee', 'philhealth_prof_fee.BILL_ID', '=', 'check_bills.BILL_ID')
            ->join('philhealth', 'philhealth.ID', '=', 'philhealth_prof_fee.PHIC_ID')
            ->join('contact', 'contact.ID', '=', 'philhealth.CONTACT_ID')
            ->where('dbp.DOCTOR_BATCH_ID', '=', $DOCTOR_BATCH_ID)
            ->get();

        return $result;
    }

    public function billPaymentBills(int $CHECK_ID): object
    {
        $result = CheckBills::query()
            ->select([
                'check_bills.ID',
                'check_bills.BILL_ID',
                'check_bills.DISCOUNT',
                'check_bills.AMOUNT_PAID',
                'bill.CODE',
                'bill.DATE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                'bill.ACCOUNTS_PAYABLE_ID',
                DB::raw("(SELECT SUM(withholding_tax_bills.AMOUNT_WITHHELD) from withholding_tax_bills where withholding_tax_bills.BILL_ID = check_bills.BILL_ID) as TAX_AMOUNT"),
            ])
            ->leftJoin('bill', 'bill.ID', '=', 'check_bills.BILL_ID')
            ->where('check_bills.CHECK_ID', '=', $CHECK_ID)
            ->get();

        return $result;
    }
    public function getTotalApplied(int $CHECK_ID): float
    {
        if ($CHECK_ID == 0) {
            return 0;
        }

        $result = CheckBills::query()
            ->select([
                DB::raw('IFNULL(SUM(check_bills.AMOUNT_PAID),0) as PAID'),
            ])
            ->where('check_bills.CHECK_ID', $CHECK_ID)
            ->first();

        return (float) $result->PAID;
    }
    public function billPaymentBills_Delete(int $ID, int $CHECK_ID, int $BILL_ID)
    {
        CheckBills::where('ID', $ID)
            ->where('CHECK_ID', $CHECK_ID)
            ->where('BILL_ID', $BILL_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CHECK_BILLS, $CHECK_ID);
    }
    public function billPaymentBills_Get(int $ID, int $CHECK_ID, int $BILL_ID): object
    {
        return CheckBills::where('ID', $ID)
            ->where('CHECK_ID', $CHECK_ID)
            ->where('BILL_ID', $BILL_ID)
            ->first();
    }
    public function billPaymentBills_Store(int $CHECK_ID, int $BILL_ID, float $DISCOUNT, float $AMOUNT_PAID, int $DISCOUNT_ACCOUNT_ID, int $ACCOUNTS_PAYABLE_ID)
    {

        $ID = (int) $this->object->ObjectNextID('CHECK_BILLS');

        CheckBills::create([
            'ID'                  => $ID,
            'CHECK_ID'            => $CHECK_ID,
            'BILL_ID'             => $BILL_ID > 0 ? $BILL_ID : null,
            'DISCOUNT'            => $DISCOUNT,
            'AMOUNT_PAID'         => $AMOUNT_PAID,
            'DISCOUNT_ACCOUNT_ID' => $DISCOUNT_ACCOUNT_ID > 0 ? $DISCOUNT_ACCOUNT_ID : null,
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID > 0 ? $ACCOUNTS_PAYABLE_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CHECK_BILLS, $CHECK_ID);

        return $ID;
    }
    public function getTotalPay(int $BILL_ID, int $EXECPT_CHECK_ID): float
    {
        $data = CheckBills::query()
            ->selectRaw('ifnull(sum(AMOUNT_PAID),0) as total')
            ->where('BILL_ID', $BILL_ID)
            ->where('CHECK_ID', '<>', $EXECPT_CHECK_ID)
            ->first();

        if ($data) {
            return $data->total ?? 0;
        }

        return 0;
    }
    public function billPaymentBills_Update(int $ID, int $CHECK_ID, int $BILL_ID, float $DISCOUNT, float $AMOUNT_PAID)
    {
        CheckBills::where('ID', $ID)
            ->where('CHECK_ID', $CHECK_ID)
            ->where('BILL_ID', $BILL_ID)
            ->update([
                'DISCOUNT'    => $DISCOUNT,
                'AMOUNT_PAID' => $AMOUNT_PAID,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CHECK_BILLS, $CHECK_ID);
    }

    public function billPaymentJournal(int $CHECK_ID): object
    {
        $result = Check::query()
            ->select([
                'ID',
                'BANK_ACCOUNT_ID as ACCOUNT_ID',
                'PAY_TO_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 1 as ENTRY_TYPE'),
            ])
            ->where('ID', $CHECK_ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->get();

        return $result;
    }
    public function billPaymentJournalRemaining(int $CHECK_ID)
    {
        $result = Check::query()
            ->select([
                'check.ID',
                'check.BANK_ACCOUNT_ID as ACCOUNT_ID',
                'check.PAY_TO_ID as SUBSIDIARY_ID',
                DB::raw("(check.AMOUNT - (select IFNULL(sum(check_bills.AMOUNT_PAID),0)  from check_bills where check_bills.CHECK_ID = check.ID limit 1)) as AMOUNT"),
                DB::raw(' 0 as ENTRY_TYPE'),
            ])
            ->where('check.ID', $CHECK_ID)
            ->where('check.TYPE', '=', $this->CHECK_TYPE_ID)
            ->get();

        return $result;
    }
    public function billPaymentBillsjournal(int $CHECK_ID)
    {
        $result = CheckBills::query()
            ->select([
                'CHECK_BILLS.ID',
                'CHECK_BILLS.ACCOUNTS_PAYABLE_ID as ACCOUNT_ID',
                'CHECK.PAY_TO_ID as SUBSIDIARY_ID',
                'CHECK_BILLS.AMOUNT_PAID as AMOUNT',
                DB::raw(' 0 as ENTRY_TYPE'),
            ])->join('CHECK', 'CHECK.ID', '=', 'CHECK_BILLS.CHECK_ID')
            ->where('CHECK_BILLS.CHECK_ID', '=', $CHECK_ID)
            ->get();

        return $result;
    }

    public function getContactRecord(int $PAY_TO_ID): object
    {

        $result = Check::query()
            ->select([
                'check.ID',
                'check.DATE',
                'check.CODE',
                'check.NOTES',
                'check.AMOUNT',
                'ct.NAME as TYPE',
                'a.NAME as BANK_NAME',
                'l.NAME as LOCATION_NAME',
            ])
            ->join('check_type_map as ct', '=', 'check.TYPE')
            ->join('account as a', 'a.ID', '=', 'check.BANK_ACCOUNT_ID')
            ->join('location as l', '=', 'check.LOCATION_ID')
            ->where('PAY_TO_ID', '=', $PAY_TO_ID)
            ->get();

        return $result;
    }

    public function updateXero(int $ID, bool $IS_XERO, float $AMOUNT)
    {
        Check::where('ID', $ID)
            ->update([
                'IS_XERO' => $IS_XERO,
                'AMOUNT'  => $AMOUNT,
            ]);
    }
    public function listViaContact(int $CONTACT_ID)
    {
        $result = Check::query()
            ->select([
                'check.ID',
                'check.CODE',
                'check.DATE',
                'check.AMOUNT',
                'check.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', 'l.ID', '=', 'check.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'check.STATUS')
            ->where('check.TYPE', '=', $this->CHECK_TYPE_ID)
            ->where('check.PAY_TO_ID', '=', $CONTACT_ID)
            ->orderBy('check.DATE', 'desc')
            ->get();

        return $result;
    }
}
