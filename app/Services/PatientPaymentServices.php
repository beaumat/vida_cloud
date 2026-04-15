<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\PatientPaymentCharges;
use App\Models\PatientPayments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class PatientPaymentServices
{

    use WithPagination;

    public int $GL_ITEM              = 242;
    public int $PHILHEALTH_ITEM      = 2;
    public int $PHILHEALTH_METHOD_ID = 91;
    public int $SALES_ON_CASH        = 37;
    private $object;
    private $dateServices;
    private $systemSettingServices;
    private $philHealthServices;
    private $accountServices;
    private $paymentMethodServices;

    private $usersLogServices;

    public function __construct(
        ObjectServices $objectService,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        PhilHealthServices $philHealthServices,
        AccountServices $accountServices,
        PaymentMethodServices $paymentMethodServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                = $objectService;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->philHealthServices    = $philHealthServices;
        $this->accountServices       = $accountServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->usersLogServices      = $usersLogServices;
    }
    public function gotHaveItemBalance($dataList = [], int $ID, int $Init_AMOUNT)
    {
        foreach ($dataList as $list) {
            if ($list->ID == $ID) {

                $bal = $list->AMOUNT - $list->PAID_AMOUNT;

                if ($Init_AMOUNT > $bal) {
                    return true;
                }
            }
        }
        return false;
    }
    public function get($ID)
    {
        return PatientPayments::where('ID', $ID)->first();
    }
    public function getPhilhealthPayment($ID)
    {
        return PatientPayments::where('ID', '=', $ID)
            ->where('PAYMENT_METHOD_ID', '=', $this->paymentMethodServices->PHIL_HEALTH_ID)
            ->first();
    }
    public function getPatientPayment($ID): object
    {
        $result = PatientPayments::where('patient_payment.ID', '=', $ID)->first();
        return $result;
    }
    public function PaymentHaveAvailable(int $PATIENT_ID)
    {
        $data = PatientPayments::where('PATIENT_ID', $PATIENT_ID)
            ->whereRaw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED) > 0')
            ->first();

        return $data;
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $PATIENT_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        float $AMOUNT_APPLIED,
        int $PAYMENT_METHOD_ID,
        string $CARD_NO,
        $CARD_EXPIRY_DATE,
        string $RECEIPT_REF_NO,
        $RECEIPT_DATE,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OVERPAYMENT_ACCOUNT_ID,
        bool $DEPOSITED,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $PHILHEALTH_ID = 0,
        int $WTAX_AMOUNT = 0,
        int $WTAX_ACCOUNT_ID = 0,
        float $LESS_AMOUNT = 0
    ): int {
        $ID          = (int) $this->object->ObjectNextID('PATIENT_PAYMENT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PATIENT_PAYMENT');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        PatientPayments::create([
            'ID'                           => $ID,
            'RECORDED_ON'                  => $this->dateServices->Now(),
            'CODE'                         => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                         => $DATE,
            'PATIENT_ID'                   => $PATIENT_ID,
            'LOCATION_ID'                  => $LOCATION_ID,
            'AMOUNT'                       => $AMOUNT,
            'AMOUNT_APPLIED'               => $AMOUNT_APPLIED,
            'PAYMENT_METHOD_ID'            => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
            'CARD_NO'                      => $CARD_NO,
            'CARD_EXPIRY_DATE'             => $CARD_EXPIRY_DATE ?? null,
            'RECEIPT_REF_NO'               => $RECEIPT_REF_NO,
            'RECEIPT_DATE'                 => $RECEIPT_DATE ?? null,
            'NOTES'                        => $NOTES,
            'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID > 0 ? $UNDEPOSITED_FUNDS_ACCOUNT_ID : null,
            'OVERPAYMENT_ACCOUNT_ID'       => $OVERPAYMENT_ACCOUNT_ID > 0 ? $OVERPAYMENT_ACCOUNT_ID : null,
            'STATUS'                       => 2,
            'STATUS_DATE'                  => $this->dateServices->NowDate(),
            'DEPOSITED'                    => $DEPOSITED,
            'ACCOUNTS_RECEIVABLE_ID'       => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
            'PHILHEALTH_ID'                => $PHILHEALTH_ID,
            'WTAX_AMOUNT'                  => $WTAX_AMOUNT > 0 ? $WTAX_AMOUNT : null,
            'WTAX_ACCOUNT_ID'              => $WTAX_ACCOUNT_ID > 0 ? $WTAX_ACCOUNT_ID : null,
            'LESS_AMOUNT'                  => $LESS_AMOUNT,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PATIENT_PAYMENT, $ID);
        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        string $DATE,
        int $PATIENT_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        int $PAYMENT_METHOD_ID,
        string $CARD_NO,
        $CARD_EXPIRY_DATE,
        string $RECEIPT_REF_NO,
        $RECEIPT_DATE,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OVERPAYMENT_ACCOUNT_ID,
        bool $DEPOSITED,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $WTAX_AMOUNT = 0,
        int $WTAX_ACCOUNT_ID = 0,
        float $LESS_AMOUNT = 0
    ) {

        PatientPayments::where('ID', '=', $ID)
            ->update([
                'DATE'                         => $DATE,
                'CODE'                         => $CODE,
                'PATIENT_ID'                   => $PATIENT_ID,
                'LOCATION_ID'                  => $LOCATION_ID,
                'AMOUNT'                       => $AMOUNT,
                'PAYMENT_METHOD_ID'            => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
                'CARD_NO'                      => $CARD_NO,
                'CARD_EXPIRY_DATE'             => $CARD_EXPIRY_DATE ?? null,
                'RECEIPT_REF_NO'               => $RECEIPT_REF_NO,
                'RECEIPT_DATE'                 => $RECEIPT_DATE ?? null,
                'NOTES'                        => $NOTES,
                'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID > 0 ? $UNDEPOSITED_FUNDS_ACCOUNT_ID : null,
                'OVERPAYMENT_ACCOUNT_ID'       => $OVERPAYMENT_ACCOUNT_ID > 0 ? $OVERPAYMENT_ACCOUNT_ID : null,
                'DEPOSITED'                    => $DEPOSITED,
                'ACCOUNTS_RECEIVABLE_ID'       => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
                'WTAX_AMOUNT'                  => $WTAX_AMOUNT > 0 ? $WTAX_AMOUNT : null,
                'WTAX_ACCOUNT_ID'              => $WTAX_ACCOUNT_ID > 0 ? $WTAX_ACCOUNT_ID : null,
                'LESS_AMOUNT'                  => $LESS_AMOUNT,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::PATIENT_PAYMENT, $ID);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        PatientPayments::where('ID', $ID)->update([
            'STATUS'      => $STATUS,
            'STATUS_DATE' => $this->dateServices->NowDate(),
        ]);
    }
    public function ConfirmProccess(int $ID)
    {
        PatientPayments::where('ID', $ID)->update([
            'IS_CONFIRM'   => true,
            'DATE_CONFIRM' => $this->dateServices->NowDate(),
        ]);
    }
    public function UnConfirmProccess(int $ID)
    {
        PatientPayments::where('ID', $ID)->update([
            'IS_CONFIRM'   => false,
            'DATE_CONFIRM' => null,
        ]);
    }
    public function ChargesAreAlreadyExists(int $ID): bool
    {
        $r = (bool) PatientPaymentCharges::where('PATIENT_PAYMENT_ID', $ID)->exists();

        return $r;
    }
    public function Delete(int $ID)
    {
        PatientPaymentCharges::where('PATIENT_PAYMENT_ID', '=', $ID)->delete();
        PatientPayments::where('ID', '=', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PATIENT_PAYMENT, $ID);
    }
    public function getSumOnPhilHealth(int $PATIENT_ID, float $LOCATION_ID, int $PHILHEALTH_ID): float
    {
        return (float) PatientPayments::where("PHILHEALTH_ID", '=', $PHILHEALTH_ID)
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->sum('AMOUNT') ?? 0;
    }
    public function SearchPhilheatlh($search, int $locatioNId, int $perPage, string $sortby, bool $isDesc)
    {
        $result = PatientPayments::query()
            ->select([
                'patient_payment.ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'patient_payment.AMOUNT',
                'patient_payment.AMOUNT_APPLIED',
                DB::raw(" IFNULL(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED,0) AS BALANCE"),
                'patient_payment.NOTES',
                'c.LAST_NAME',
                'c.FIRST_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'pm.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment.FILE_PATH',
                'patient_payment.IS_CONFIRM',
                'patient_payment.RECEIPT_REF_NO',
                'patient_payment.RECEIPT_DATE',
                'patient_payment.PATIENT_ID',
                'patient_payment.WTAX_AMOUNT',
                'patient_payment.LESS_AMOUNT',
                'patient_payment.PHILHEALTH_ID',
            ])
            ->join('contact as c', 'c.ID', '=', 'patient_payment.PATIENT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'patient_payment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'patient_payment.STATUS')
            ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->join('philhealth as p', 'p.ID', '=', 'patient_payment.PHILHEALTH_ID')
            ->where('patient_payment.PAYMENT_METHOD_ID', '=', $this->paymentMethodServices->PHIL_HEALTH_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('patient_payment.CODE', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.AMOUNT_APPLIED', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('c.LAST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.FIRST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.MIDDLE_NAME', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.RECEIPT_REF_NO', 'like', '%' . $search . '%');
                });
            })

            ->orderBy($sortby, $isDesc ? 'desc' : 'asc')
            ->paginate($perPage);

        return $result;
    }
    public function Search(
        $search,
        int $locationId,
        int $perPage,
        string $sortby,
        bool $isDesc,
        int $paymentMethodId,
        bool $itemized = true,
        string $DT_FROM,
        string $DT_TO
    ) {

        if (! $itemized) {
            $result = PatientPayments::query()
                ->select([
                    'patient_payment.ID',
                    'patient_payment.CODE',
                    'patient_payment.DATE',
                    'patient_payment.AMOUNT',
                    'patient_payment.AMOUNT_APPLIED',
                    DB::raw(" IFNULL(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED,0) AS BALANCE"),
                    'patient_payment.NOTES',
                    'c.LAST_NAME',
                    'c.FIRST_NAME',
                    'l.NAME as LOCATION_NAME',
                    's.DESCRIPTION as STATUS',
                    'pm.DESCRIPTION as PAYMENT_METHOD',
                    'patient_payment.FILE_PATH',
                    'patient_payment.IS_CONFIRM',
                    'patient_payment.RECEIPT_REF_NO',
                    'patient_payment.RECEIPT_DATE',
                    'patient_payment.PATIENT_ID',
                    'patient_payment.IS_INVOICE',
                    'patient_payment.REF_ID',
                ])
                ->join('contact as c', 'c.ID', '=', 'patient_payment.PATIENT_ID')
                ->join('location as l', function ($join) use (&$locationId) {
                    $join->on('l.ID', '=', 'patient_payment.LOCATION_ID');
                    if ($locationId > 0) {
                        $join->where('l.ID', $locationId);
                    }
                })
                ->join('document_status_map as s', 's.ID', '=', 'patient_payment.STATUS')
                ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
                ->whereIn('pm.PAYMENT_TYPE', $this->paymentMethodServices->CASH_N_GL)
                ->when($search, function ($query) use (&$search) {
                    $query->where(function ($q) use (&$search) {
                        $q->where('patient_payment.CODE', 'like', '%' . $search . '%')
                            ->orWhere('patient_payment.AMOUNT_APPLIED', 'like', '%' . $search . '%')
                            ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                            ->orWhere('c.NAME', 'like', '%' . $search . '%')
                            ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                            ->orWhere('c.LAST_NAME', 'like', '%' . $search . '%')
                            ->orWhere('c.FIRST_NAME', 'like', '%' . $search . '%')
                            ->orWhere('c.MIDDLE_NAME', 'like', '%' . $search . '%')
                            ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                            ->orWhere('patient_payment.RECEIPT_REF_NO', 'like', '%' . $search . '%');
                    });
                })
                ->when($paymentMethodId > 0, function ($query) use (&$paymentMethodId) {
                    $query->where('patient_payment.PAYMENT_METHOD_ID', '=', $paymentMethodId);
                })
                ->orderBy($sortby, $isDesc ? 'desc' : 'asc')
                ->paginate($perPage);

            return $result;
        }

        //  itemized
        $result = PatientPayments::query()
            ->select([
                'patient_payment.ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'patient_payment.AMOUNT',
                'patient_payment.AMOUNT_APPLIED',
                DB::raw(" IFNULL(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED,0) AS BALANCE"),
                'patient_payment.NOTES',
                'c.LAST_NAME',
                'c.FIRST_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'pm.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment.FILE_PATH',
                'patient_payment.IS_CONFIRM',
                'patient_payment.RECEIPT_REF_NO',
                'patient_payment.RECEIPT_DATE',
                'patient_payment.PATIENT_ID',
                'i.DESCRIPTION as ITEM_NAME',
                'ppc.AMOUNT_APPLIED as ITEM_PAID',
                'sci.AMOUNT as ITEM_AMOUNT',
            ])
            ->join('contact as c', 'c.ID', '=', 'patient_payment.PATIENT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'patient_payment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'patient_payment.STATUS')
            ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.PATIENT_PAYMENT_ID', '=', 'patient_payment.ID')
            ->leftJoin('service_charges_items as sci', 'sci.ID', '=', 'ppc.SERVICE_CHARGES_ITEM_ID')
            ->leftJoin('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->leftJoin('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->whereBetween('sc.DATE', [$DT_FROM, $DT_TO])
            ->whereIn('pm.PAYMENT_TYPE', $this->paymentMethodServices->CASH_N_GL)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('patient_payment.CODE', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.AMOUNT_APPLIED', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('c.LAST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.FIRST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.MIDDLE_NAME', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('patient_payment.RECEIPT_REF_NO', 'like', '%' . $search . '%');
                });
            })
            ->when($paymentMethodId > 0, function ($query) use (&$paymentMethodId) {
                $query->where('patient_payment.PAYMENT_METHOD_ID', '=', $paymentMethodId);
            })
            ->orderBy($sortby, $isDesc ? 'desc' : 'asc')
            ->paginate($perPage);

        return $result;
    }
    public function GetSUM($search, int $locationId)
    {
        $result = PatientPayments::query()
            ->select([
                DB::raw(' IFNULL(SUM(patient_payment.AMOUNT),0) as TOTAL_DEPOSIT'),
                DB::raw(' IFNULL(SUM(patient_payment.AMOUNT_APPLIED),0) as TOTAL_APPLIED'),
            ])
            ->join('contact as c', 'c.ID', '=', 'patient_payment.PATIENT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'patient_payment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'patient_payment.STATUS')
            ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('patient_payment.CODE', 'like', '%' . $search . '%')
                    ->orWhere('patient_payment.AMOUNT_APPLIED', 'like', '%' . $search . '%')
                    ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->first();

        if ($result) {

            return [
                'TOTAL_DEPOSIT' => $result->TOTAL_DEPOSIT,
                'TOTAL_APPLIED' => $result->TOTAL_APPLIED,
            ];
        }

        return [
            'TOTAL_DEPOSIT' => 0,
            'TOTAL_APPLIED' => 0,
        ];
    }
    public function PaymentChargeStore(
        int $PATIENT_PAYMENT_ID,
        int $SERVICE_CHARGES_ITEM_ID,
        float $DISCOUNT,
        float $AMOUNT_APPLIED,
        int $DISCOUNT_ACCOUNT_ID,
        int $ACCOUNTS_RECEIVABLE_ID
    ): int {

        $ID = $this->object->ObjectNextID('PATIENT_PAYMENT_CHARGES');

        PatientPaymentCharges::create([
            'ID'                      => $ID,
            'PATIENT_PAYMENT_ID'      => $PATIENT_PAYMENT_ID,
            'SERVICE_CHARGES_ITEM_ID' => $SERVICE_CHARGES_ITEM_ID,
            'DISCOUNT'                => $DISCOUNT > 0 ? $DISCOUNT : null,
            'AMOUNT_APPLIED'          => $AMOUNT_APPLIED,
            'DISCOUNT_ACCOUNT_ID'     => $DISCOUNT_ACCOUNT_ID > 0 ? $DISCOUNT_ACCOUNT_ID : null,
            'ACCOUNTS_RECEIVABLE_ID'  => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::PATIENT_PAYMENT_CHARGES, $PATIENT_PAYMENT_ID);

        return $ID;
    }

    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        PatientPayments::where('ID', $ID)
            ->update([
                'FILE_NAME' => $FILE_NAME,
                'FILE_PATH' => $FILE_PATH,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPLOAD, LogEntity::PATIENT_PAYMENT, $ID);
    }
    public function CustomerRef(int $PATIENT_PAY_ID, bool $IS_INVOICE, int $REF_ID = 0)
    {

        PatientPayments::where('ID', '=', $PATIENT_PAY_ID)
            ->update([
                'IS_INVOICE' => $IS_INVOICE,
                'REF_ID'     => $REF_ID > 0 ? $REF_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::PATIENT_PAYMENT, $PATIENT_PAY_ID);
    }
    public function GetCustomerRef(bool $IS_INVOICE, int $REF_ID)
    {
        $data = PatientPayments::where('IS_INVOICE', '=', $IS_INVOICE)
            ->where('REF_ID', '=', $REF_ID)
            ->first();

        if ($data) {
            return (int) $data->ID;
        }
        return 0;
    }
    public function PaymentChargesExist(int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID): int
    {
        $data = PatientPaymentCharges::where('PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
            ->where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->first();
        if ($data) {
            return $data->ID;
        }
        return 0;
    }

    public function PaymentChargesUpdate(int $ID, int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID, float $DISCOUNT, float $AMOUNT_APPLIED)
    {
        PatientPaymentCharges::where('ID', $ID)
            ->where('PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
            ->where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->update([
                'DISCOUNT'       => $DISCOUNT,
                'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
            ]);

    }
    public function PaymentChargesDelete(int $ID, int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID)
    {

        PatientPaymentCharges::where('ID', $ID)
            ->where('PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
            ->where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->delete();

        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::PATIENT_PAYMENT_CHARGES, $PATIENT_PAYMENT_ID);
    }
    public function PaymentChargesList(int $PATIENT_PAYMENT_ID, int $PHILHEALTH_ID = 0): object
    {

        $PH_ITEM_ID = $this->philHealthServices->PHIL_HEALTH_ITEM_ID;

        $result = PatientPaymentCharges::query()
            ->select([
                'patient_payment_charges.ID',
                'patient_payment_charges.SERVICE_CHARGES_ITEM_ID',
                'sc.ID as SERVICE_CHARGES_ID',
                'sc.DATE',
                'sc.CODE',
                'sc.AMOUNT',
                'sc.BALANCE_DUE',
                'i.DESCRIPTION as ITEM_NAME',
                'sci.ITEM_ID',
                'sci.QUANTITY',
                'sci.UNIT_BASE_QUANTITY',
                'sci.RATE',
                'sci.RATE_TYPE',
                'sci.AMOUNT as ITEM_AMOUNT',
                'sci.UNIT_ID',
                'sci.TAXABLE',
                'patient_payment_charges.AMOUNT_APPLIED as TAXABLE_AMOUNT',
                'sci.TAX_AMOUNT',
                'sci.INCOME_ACCOUNT_ID',
                'sci.COGS_ACCOUNT_ID',
                'sci.ASSET_ACCOUNT_ID',
                'a.NAME as ACCOUNT_NAME',
                'unit_of_measure.SYMBOL',
                'patient_payment_charges.AMOUNT_APPLIED',
            ])
            ->leftJoin('service_charges_items as sci', 'sci.ID', '=', 'patient_payment_charges.SERVICE_CHARGES_ITEM_ID')
            ->leftJoin('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->leftJoin('account as a', 'a.ID', '=', 'sci.INCOME_ACCOUNT_ID')
            ->leftJoin('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->leftJoin('unit_of_measure', 'unit_of_measure.ID', '=', 'sci.UNIT_ID')
            ->where('patient_payment_charges.PATIENT_PAYMENT_ID', '=', $PATIENT_PAYMENT_ID)
            ->when($PHILHEALTH_ID > 0, function ($query) use (&$PH_ITEM_ID) {
                $query->where('sci.ITEM_ID', '=', $PH_ITEM_ID);
            })
            ->get();

        return $result;
    }

    public function UpdatePaymentChargesApplied(int $PATIENT_PAYMENT_ID): float
    {
        $pay = (float) PatientPaymentCharges::query()
            ->select(DB::raw('IFNULL(SUM(patient_payment_charges.AMOUNT_APPLIED), 0) as pay'))
            ->where('patient_payment_charges.PATIENT_PAYMENT_ID', '=', $PATIENT_PAYMENT_ID)
            ->first()
            ->pay;

        PatientPayments::where('ID', $PATIENT_PAYMENT_ID)
            ->update(['AMOUNT_APPLIED' => $pay]);

        return $pay;
    }

    public function ServiceChargesPaymentList(int $SERVICE_CHARGES_ID, int $PATIENT_PAYMENT_ID)
    {
        $result = PatientPayments::query()
            ->select([
                'patient_payment_charges.ID',
                'patient_payment_charges.PATIENT_PAYMENT_ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'patient_payment.AMOUNT',
                'patient_payment.PAYMENT_METHOD_ID',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment_charges.AMOUNT_APPLIED',
                'patient_payment.FILE_PATH',
                'patient_payment.IS_CONFIRM',
                'item.DESCRIPTION as ITEM_NAME',
                'service_charges_items.QUANTITY',
                'service_charges_items.AMOUNT as ITEM_AMOUNT',
                'service_charges_items.ID as SERVICE_CHARGES_ITEM_ID',
            ])
            ->join('payment_method', 'payment_method.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->join('patient_payment_charges', 'patient_payment_charges.PATIENT_PAYMENT_ID', '=', 'patient_payment.ID')
            ->join('service_charges_items', 'service_charges_items.ID', '=', 'patient_payment_charges.SERVICE_CHARGES_ITEM_ID')
            ->leftJoin('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->where('patient_payment.PATIENT_ID', $PATIENT_PAYMENT_ID)
            ->where('service_charges_items.SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)
            ->get();

        return $result;
    }
    public function PaymentAvailableList(int $PATIENT_ID, int $LOCATION_ID)
    {
        $result = PatientPayments::query()
            ->select([
                'patient_payment.ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment.AMOUNT',
                'patient_payment.AMOUNT_APPLIED',
            ])
            ->leftJoin('payment_method', 'payment_method.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->where('patient_payment.PATIENT_ID', $PATIENT_ID)
            ->where('patient_payment.LOCATION_ID', $LOCATION_ID)
            ->whereRaw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED) > 0')
            ->get();

        return $result;
    }
    public function PaymentAvailableList_SC(int $PATIENT_ID, int $LOCATION_ID, int $serviceCharges_Item_Id)
    {

        $result = PatientPayments::query()
            ->select([
                'patient_payment.ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment.AMOUNT',
                'patient_payment.AMOUNT_APPLIED',
                DB::raw("(select count( *) from patient_payment_charges as d where d.PATIENT_PAYMENT_ID = patient_payment.ID and d.service_charges_item_id =  " . $serviceCharges_Item_Id . "  ) as IS_COUNT "),
            ])
            ->leftJoin('payment_method', 'payment_method.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->where('patient_payment.PATIENT_ID', $PATIENT_ID)
            ->where('patient_payment.LOCATION_ID', $LOCATION_ID)
            ->where('payment_method.ID', '!=', 91)
            ->whereRaw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED) > 0')
            ->orderBy('patient_payment.DATE')
            ->get();

        return $result;
    }
    public function GetPaymentRemaining(int $PATIENT_PAYMENT_ID): float
    {
        $result = PatientPayments::where('ID', $PATIENT_PAYMENT_ID)->first();

        return (float) $result->AMOUNT - (float) $result->AMOUNT_APPLIED;
    }
    public function GetPaymentRemainingItem(int $SERVICE_CHARGES_ITEM_ID): float
    {
        $data = PatientPaymentCharges::where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->select(DB::raw(' IFNULL( SUM(AMOUNT_APPLIED),0) as TOTAL'))
            ->first();

        return (float) $data->TOTAL;
    }

    public function HaveRemainingPaymentBalance(int $PATIENT_PAYMENT_ID, int $LOCATION_ID): bool
    {
        $total = (float) PatientPayments::query()
            ->select(DB::raw('IFNULL(SUM(AMOUNT-AMOUNT_APPLIED), 0) AS TOTAL'))
            ->where('PATIENT_ID', $PATIENT_PAYMENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('ID', 'asc')
            ->first()
            ->TOTAL;

        if ($total > 0) {
            return true;
        }
        return false;
    }

    public function getSumApplied(string $fromDate, string $toDate, int $locationId = 0, int $patientId = 0): float
    {
        $result = (float) PatientPayments::whereBetween('DATE', [$fromDate, $toDate])
            ->when($locationId > 0, function ($query) use (&$locationId) {
                $query->where('LOCATION_ID', $locationId);
            })
            ->when($patientId > 0, function ($query) use (&$patientId) {
                $query->where('PATIENT_ID', $patientId);
            })
            ->sum('AMOUNT_APPLIED');

        return $result;
    }
    public function PH_exists($PHILHEALTH_ID)
    {
        $result = PatientPayments::where('PHILHEALTH_ID', '=', $PHILHEALTH_ID)->first();
        if ($result) {
            return (int) $result->ID;
        }
        return 0;
    }
    public function PH_Store(int $PHILHEALTH_ID, float $AMOUNT, string $RECEIPT_REF_NO, string $RECEIPT_DATE, string $NOTES)
    {
        $METHOD_ID                    = $this->PHILHEALTH_METHOD_ID; // Philhealth
        $DATE                         = $RECEIPT_DATE;
        $phData                       = $this->philHealthServices->get($PHILHEALTH_ID);
        $UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
        $ACCOUNTS_RECEIVABLE_ID       = (int) $this->accountServices->getByName('Accounts Receivables');

        $ID = $this->Store(
            "",
            $DATE,
            $phData->CONTACT_ID,
            $phData->LOCATION_ID,
            $AMOUNT,
            0,
            $METHOD_ID,
            "",
            null,
            $RECEIPT_REF_NO,
            $RECEIPT_DATE,
            $NOTES,
            $UNDEPOSITED_FUNDS_ACCOUNT_ID,
            0,
            0,
            $ACCOUNTS_RECEIVABLE_ID,
            $PHILHEALTH_ID
        );

        return $ID;
    }
    public function PH_Update(int $ID, int $PHILHEALTH_ID, float $AMOUNT, string $RECEIPT_REF_NO, string $RECEIPT_DATE, string $NOTES)
    {
        $METHOD_ID = 91;

        PatientPayments::where('ID', $ID)
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->where('PAYMENT_METHOD_ID', $METHOD_ID)
            ->where('AMOUNT_APPLIED', 0)
            ->update([
                'AMOUNT'         => $AMOUNT,
                'RECEIPT_REF_NO' => $RECEIPT_REF_NO,
                'RECEIPT_DATE'   => $RECEIPT_DATE,
                'NOTES'          => $NOTES,
            ]);
    }
    public function PH_Delete(int $ID, int $PHILHEALTH_ID): bool
    {
        try {
            $METHOD_ID = 91;
            $data      = PatientPayments::where('ID', $ID)
                ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
                ->where('PAYMENT_METHOD_ID', $METHOD_ID)
                ->where('AMOUNT_APPLIED', 0);

            if ($data->exists()) {
                $data->delete();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting record: ' . $e->getMessage());
            return false;
        }
    }
    public function PH_List(int $PHILHEALTH_ID, int $PATIENT_ID, int $LOCATION_ID)
    {
        $result = PatientPayments::query()
            ->select([
                'ID',
                'CODE',
                'DATE',
                'RECEIPT_REF_NO',
                'RECEIPT_DATE',
                'AMOUNT',
                'WTAX_AMOUNT',
                'LESS_AMOUNT',
                'AMOUNT_APPLIED',
                'DEPOSITED',
                'NOTES',
            ])
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->get();

        return $result;
    }
    public function getPH_TotalPay(int $PHILHEALTH_ID): float
    {
        $pay = PatientPayments::query()
            ->select([DB::raw('SUM(AMOUNT) as TOTAL')])
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->first();

        if ($pay) {
            return $pay->TOTAL ?? 0;
        }

        return 0;
    }
    public function AssistanceAll(int $PATIENT_ID, int $LOCK_LOCATION_ID)
    {
        $result = PatientPayments::select([
            DB::raw('IF(ISNULL(RECEIPT_DATE), patient_payment.DATE, RECEIPT_DATE) AS TRANS_DATE'),
            DB::raw('IF(ISNULL(RECEIPT_REF_NO), patient_payment.CODE, RECEIPT_REF_NO) AS TRANS_CODE'),
            'patient_payment.AMOUNT',
            'patient_payment.AMOUNT_APPLIED',
            DB::raw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED)  as BALANCE'),
            'pm.description AS METHOD',
            'patient_payment.PAYMENT_METHOD_ID',
        ])
            ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->where('PATIENT_ID', $PATIENT_ID)
            ->whereIn('PAYMENT_METHOD_ID', [92, 93, 94, 96, 97, 98])
            ->when($LOCK_LOCATION_ID > 0, function ($query) use (&$LOCK_LOCATION_ID) {
                $query->where('patient_payment.LOCATION_ID', $LOCK_LOCATION_ID);
            })
            ->orderBy('TRANS_DATE', 'asc')
            ->get();

        return $result;
    }

    public function GetAssistanceAll(int $PATIENT_ID, int $LOCATION_ID, bool $includeZeroBalance = false)
    {
        $result = PatientPayments::select([
            'c.NAME as PATIENT_NAME',
            DB::raw('IF(ISNULL(RECEIPT_DATE), patient_payment.DATE, RECEIPT_DATE) AS TRANS_DATE'),
            DB::raw('IF(ISNULL(RECEIPT_REF_NO), patient_payment.CODE, RECEIPT_REF_NO) AS TRANS_CODE'),
            'patient_payment.AMOUNT',
            'patient_payment.AMOUNT_APPLIED',
            DB::raw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED)  as BALANCE'),
            'pm.description AS METHOD',
            'patient_payment.PAYMENT_METHOD_ID',
        ])
            ->join('contact as c', 'c.ID', '=', 'patient_payment.PATIENT_ID')
            ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->when($PATIENT_ID > 0, function ($query) use ($PATIENT_ID) {
                $query->where('PATIENT_ID', '=', $PATIENT_ID);
            })
            ->when(! $includeZeroBalance, function ($query) {
                $query->whereRaw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED) > 0');
            })
            ->whereIn('PAYMENT_METHOD_ID', [92, 93, 94, 96, 97, 98])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('patient_payment.LOCATION_ID', $LOCATION_ID);
            })
            ->orderBy('c.NAME')
            ->orderBy('TRANS_DATE', 'asc')
            ->get();

        return $result;
    }
    public function AssistanceByType(int $PATIENT_ID, int $METHOD_ID = 0, int $LOCK_LOCATION_ID)
    {
        // First query
        $query1 = PatientPayments::select([
            DB::raw('patient_payment.ID AS TRANS_ID'),
            DB::raw('patient_payment.DATE AS TRANS_DATE'),
            DB::raw('patient_payment.RECEIPT_REF_NO AS TRANS_CODE'),
            DB::raw("patient_payment.CODE as P_CODE"),
            DB::raw("patient_payment.DATE as P_DATE"),
            'patient_payment.AMOUNT',
            'patient_payment.AMOUNT_APPLIED',
            DB::raw('patient_payment.AMOUNT_APPLIED AS CREDIT_AMOUNT'),
            DB::raw('0 AS DEPOSIT_AMOUNT'),
            DB::raw('"" AS ITEM_CODE'),
            DB::raw('"" AS ITEM_NAME'),
            'pm.description AS METHOD',
            'patient_payment.PAYMENT_METHOD_ID',
        ])
            ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->where('PATIENT_ID', $PATIENT_ID)
            ->when($LOCK_LOCATION_ID > 0, function ($query) use (&$LOCK_LOCATION_ID) {
                $query->where('patient_payment.LOCATION_ID', $LOCK_LOCATION_ID);
            })
            ->when($METHOD_ID > 0, function ($query) use (&$METHOD_ID) {
                $query->where('patient_payment.PAYMENT_METHOD_ID', '=', $METHOD_ID);
            });

        // Second query
        $query2 = PatientPaymentCharges::select([
            DB::raw('sc.ID AS TRANS_ID'),
            'sc.DATE AS TRANS_DATE',
            'sc.CODE AS TRANS_CODE',
            DB::raw("'' as P_CODE"),
            DB::raw("'' as P_DATE"),
            'sci.AMOUNT',
            'patient_payment_charges.AMOUNT_APPLIED',
            DB::raw('0 AS CREDIT_AMOUNT'),
            DB::raw('(patient_payment_charges.AMOUNT_APPLIED ) AS DEPOSIT_AMOUNT'),
            'i.CODE AS ITEM_CODE',
            'i.DESCRIPTION AS ITEM_NAME',
            'pm.description AS METHOD',
            'pp.PAYMENT_METHOD_ID',
        ])
            ->join('patient_payment as pp', 'pp.ID', '=', 'patient_payment_charges.PATIENT_PAYMENT_ID')
            ->join('service_charges_items as sci', 'sci.ID', '=', 'patient_payment_charges.SERVICE_CHARGES_ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->leftJoin('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('payment_method as pm', 'pm.id', '=', 'pp.payment_method_id')
            ->where('pp.PATIENT_ID', $PATIENT_ID)
            ->when($METHOD_ID > 0, function ($query) use (&$METHOD_ID) {
                $query->where('pp.PAYMENT_METHOD_ID', '=', $METHOD_ID);
            });

        // Combine both queries
        $result = $query2->unionAll($query1)->orderBy('TRANS_DATE', 'asc')->get();

        return $result;
    }
}
