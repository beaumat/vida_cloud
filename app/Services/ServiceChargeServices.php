<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\PatientPaymentCharges;
use App\Models\ServiceCharges;
use App\Models\ServiceChargesItems;
use App\Models\Tax;
use function Laravel\Prompts\search;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ServiceChargeServices
{
    use WithPagination;
    private int $PRIMING_FEE_ID = 176;
    private $object;
    private $compute;
    private $locationReference;
    private $systemSettingServices;
    private $dateServices;
    private $usersLogServices;
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        LocationReferenceServices $locationReferenceServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        UsersLogServices $usersLogServices

    ) {
        $this->object                = $objectService;
        $this->compute               = $computeServices;
        $this->locationReference     = $locationReferenceServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices          = $dateServices;
        $this->usersLogServices      = $usersLogServices;

    }
    public function getItemBalance(int $SERVICE_CHARGES_ITEM_ID): float
    {
        $data = ServiceChargesItems::where('ID', $SERVICE_CHARGES_ITEM_ID)->first();
        if ($data) {
            return (float) ($data->AMOUNT - $data->PAID_AMOUNT);
        }
        return 0;
    }
    public function setItemPaidAmount(int $SERVICE_CHARGES_ITEM_ID, float $PAID_AMOUNT)
    {

        ServiceChargesItems::where('ID', '=', $SERVICE_CHARGES_ITEM_ID)
            ->update([
                'PAID_AMOUNT' => $PAID_AMOUNT,
            ]);
    }
    public function getServiceChargeList_NotPhilhealth(int $PATIENT_PAYMENT_ID, int $PATIENT_ID, int $LOCATION_ID): object
    {
        $result = ServiceChargesItems::query()
            ->select([
                'service_charges_items.ID',
                'service_charges_items.SERVICE_CHARGES_ID',
                'service_charges.DATE',
                'service_charges.CODE',
                'service_charges.AMOUNT',
                'service_charges.BALANCE_DUE',
                'item.DESCRIPTION as ITEM_NAME',
                'service_charges_items.AMOUNT as ITEM_AMOUNT',
                'service_charges_items.PAID_AMOUNT',
                'service_charges_items.QUANTITY',
                'unit_of_measure.SYMBOL',
            ])
            ->leftJoin('service_charges', 'service_charges.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->leftJoin('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->leftJoin('unit_of_measure', 'unit_of_measure.ID', '=', 'service_charges_items.UNIT_ID')
            ->where('service_charges.PATIENT_ID', $PATIENT_ID)
            ->where('service_charges.LOCATION_ID', $LOCATION_ID)
            ->whereRaw('( service_charges_items.AMOUNT -  service_charges_items.PAID_AMOUNT ) > 0')
            ->whereNotExists(function ($query) use (&$PATIENT_PAYMENT_ID) {
                $query->select(DB::raw(1))
                    ->from('patient_payment_charges as ppc')
                    ->where('ppc.PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
                    ->whereRaw('ppc.SERVICE_CHARGES_ITEM_ID = service_charges_items.ID');
            })
            ->where('service_charges_items.ITEM_ID', '<>', '2')
            ->get();

        return $result;
    }
    public function getServiceChargeList_PH(int $PATIENT_PAYMENT_ID, int $PATIENT_ID, int $LOCATION_ID, int $PHILHEALTH_ID = 0): object
    {
        $result = ServiceChargesItems::query()
            ->select([
                'service_charges_items.ID',
                'service_charges_items.SERVICE_CHARGES_ID',
                'service_charges.DATE',
                'service_charges.CODE',
                'service_charges.AMOUNT',
                'service_charges.BALANCE_DUE',
                'item.DESCRIPTION as ITEM_NAME',
                'service_charges_items.AMOUNT as ITEM_AMOUNT',
                'service_charges_items.PAID_AMOUNT',
                'service_charges_items.QUANTITY',
                'unit_of_measure.SYMBOL',
            ])
            ->leftJoin('service_charges', 'service_charges.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->leftJoin('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->leftJoin('unit_of_measure', 'unit_of_measure.ID', '=', 'service_charges_items.UNIT_ID')
            ->where('service_charges.PATIENT_ID', $PATIENT_ID)
            ->where('service_charges.LOCATION_ID', $LOCATION_ID)
            ->whereRaw('(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) > 0')
            ->whereNotExists(function ($query) use (&$PATIENT_PAYMENT_ID) {
                $query->select(DB::raw(1))
                    ->from('patient_payment_charges as ppc')
                    ->where('ppc.PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
                    ->whereRaw('ppc.SERVICE_CHARGES_ITEM_ID = service_charges_items.ID');
            })
            ->where('service_charges_items.ITEM_ID', '=', '2')
            ->get();

        return $result;
    }
    public function getServiceChargeList_PH_Date(int $PATIENT_PAYMENT_ID, int $PATIENT_ID, int $LOCATION_ID, string $DT_FROM, string $DT_TO): object
    {
        $result = ServiceChargesItems::query()
            ->select([
                'service_charges_items.ID',
                'service_charges_items.SERVICE_CHARGES_ID',
                'service_charges.DATE',
                'service_charges.CODE',
                'service_charges.AMOUNT',
                'service_charges.BALANCE_DUE',
                'item.DESCRIPTION as ITEM_NAME',
                'service_charges_items.AMOUNT as ITEM_AMOUNT',
                'service_charges_items.PAID_AMOUNT',
                'service_charges_items.QUANTITY',
                'unit_of_measure.SYMBOL',
            ])
            ->leftJoin('service_charges', 'service_charges.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->leftJoin('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->leftJoin('unit_of_measure', 'unit_of_measure.ID', '=', 'service_charges_items.UNIT_ID')
            ->where('service_charges.PATIENT_ID', $PATIENT_ID)
            ->where('service_charges.LOCATION_ID', $LOCATION_ID)
            ->whereRaw('(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) > 0')
            ->whereNotExists(function ($query) use (&$PATIENT_PAYMENT_ID) {
                $query->select(DB::raw(1))
                    ->from('patient_payment_charges as ppc')
                    ->where('ppc.PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
                    ->whereRaw('ppc.SERVICE_CHARGES_ITEM_ID = service_charges_items.ID');
            })
            ->where('service_charges_items.ITEM_ID', '=', '2')
            ->whereBetween('service_charges.DATE', [$DT_FROM, $DT_TO])
            ->get();

        return $result;
    }
    public function get(int $ID)
    {
        $result = ServiceCharges::where('ID', $ID)->first();
        if ($result) {
            return $result;
        }

        return [];
    }
    public function get2(int $PATIENT_ID, int $LOCATION_ID, string $DATE)
    {
        $result = ServiceCharges::where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', $DATE)
            ->first();

        if ($result) {
            return $result;
        }

        return [];
    }
    public function checkifSchavePAF(int $PAF_ID, int $SC_ID): bool
    {

        return (bool) DB::table('service_charges_items as sci')->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->where('i.PHIC_AGREEMENT_FORM_TITLE_ID', '=', $PAF_ID)
            ->where('sci.SERVICE_CHARGES_ID', '=', $SC_ID)
            ->exists();
    }
    public function getItemList(int $SERVICE_CHARGES_ID)
    {

        $result = ServiceChargesItems::where('SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)->get();
        return $result;
    }
    public function getItem(int $ID)
    {
        $result = ServiceChargesItems::where('ID', $ID)->first();
        if ($result) {
            return $result;
        }
        return [];
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $PATIENT_ID,
        int $LOCATION_ID,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $STATUS,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        int $OUTPUT_TAX_VAT_METHOD,
        int $OUTPUT_TAX_ACCOUNT_ID,
        bool $WALK_IN = false,
        bool $USE_PHIC = false

    ): int {

        $ID          = (int) $this->object->ObjectNextID('SERVICE_CHARGES');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('SERVICE_CHARGES');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        ServiceCharges::create([
            'ID'                     => $ID,
            'RECORDED_ON'            => $this->dateServices->Now(),
            'CODE'                   => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                   => $DATE,
            'PATIENT_ID'             => $PATIENT_ID,
            'LOCATION_ID'            => $LOCATION_ID,
            'NOTES'                  => $NOTES ?? null,
            'AMOUNT'                 => 0,
            'BALANCE_DUE'            => 0,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
            'STATUS'                 => $STATUS,
            'STATUS_DATE'            => $this->dateServices->NowDate(),
            'OUTPUT_TAX_ID'          => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
            'OUTPUT_TAX_RATE'        => $OUTPUT_TAX_RATE,
            'OUTPUT_TAX_VAT_METHOD'  => $OUTPUT_TAX_VAT_METHOD,
            'OUTPUT_TAX_ACCOUNT_ID'  => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,
            'WALK_IN'                => $WALK_IN,
            'USE_PHIC'               => $USE_PHIC,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::SERVICE_CHARGES, $ID);

        return $ID;
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        ServiceCharges::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);
    }
    public function Update(
        int $ID,
        string $CODE,
        string $DATE,
        int $PATIENT_ID,
        int $LOCATION_ID,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $STATUS,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        int $OUTPUT_TAX_VAT_METHOD,
        int $OUTPUT_TAX_ACCOUNT_ID
    ): void {

        ServiceCharges::where('ID', $ID)
            ->update([
                'CODE'                   => $CODE,
                'DATE'                   => $DATE,
                'PATIENT_ID'             => $PATIENT_ID,
                'LOCATION_ID'            => $LOCATION_ID,
                'NOTES'                  => $NOTES ?? null,
                'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
                'OUTPUT_TAX_ID'          => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
                'OUTPUT_TAX_RATE'        => $OUTPUT_TAX_RATE,
                'OUTPUT_TAX_VAT_METHOD'  => $OUTPUT_TAX_VAT_METHOD,
                'OUTPUT_TAX_ACCOUNT_ID'  => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::SERVICE_CHARGES, $ID);
    }

    public function Delete(int $ID): void
    {
        ServiceChargesItems::where('SERVICE_CHARGES_ID', $ID)->delete();
        ServiceCharges::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::SERVICE_CHARGES, $ID);
    }
    public function ServicesChargesExists(string $DATE, int $PATIENT_ID, int $LOCATION_ID): bool
    {
        return ServiceCharges::where('DATE', $DATE)->where('PATIENT_ID', $PATIENT_ID)->where('LOCATION_ID', $LOCATION_ID)->exists();
    }

    public function ServicesChargesGetFirst(string $DATE, int $PATIENT_ID, int $LOCATION_ID)
    {
        $data = ServiceCharges::where('DATE', $DATE)
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->first();

        if ($data) {
            return $data;
        }

        return [];
    }

    public function Search($search, int $locationId, int $perPage, string $dateEntry, bool $showNurseEntry = false)
    {
        $query = ServiceCharges::query()
            ->select([
                'service_charges.ID',
                'service_charges.CODE',
                'service_charges.DATE',
                'service_charges.AMOUNT',
                'service_charges.BALANCE_DUE',
                'service_charges.OUTPUT_TAX_RATE',
                'service_charges.NOTES',
                DB::raw("If(c.TYPE = 3, CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1), ' ', c.SALUTATION),c.NAME) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
                's.ID as STATUS_ID',
            ])
            ->when($showNurseEntry, function ($query) use ($showNurseEntry) {
                if ($showNurseEntry) {
                    $query->addSelect([
                        DB::raw('(SELECT s.DESCRIPTION FROM hemodialysis AS h JOIN hemo_status AS s ON s.ID = h.STATUS_ID WHERE h.CUSTOMER_ID = service_charges.PATIENT_ID AND h.DATE = service_charges.DATE AND h.LOCATION_ID = service_charges.LOCATION_ID LIMIT 1) as TR_STATUS'),
                        DB::raw('(SELECT IF(COUNT(*) > 0, TRUE, FALSE) FROM hemodialysis_items INNER JOIN service_charges_items ON service_charges_items.ID = hemodialysis_items.SC_ITEM_ID WHERE service_charges_items.SERVICE_CHARGES_ID = service_charges.ID AND hemodialysis_items.IS_CASHIER = 1 limit 1) as got_charge'),
                    ]);
                }
            })

            ->join('contact as c', 'c.ID', '=', 'service_charges.PATIENT_ID')
            ->join('location as l', 'l.ID', '=', 'service_charges.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'service_charges.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'service_charges.OUTPUT_TAX_ID')
            ->when($locationId > 0, fn($query) => $query->where('l.ID', $locationId))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('service_charges.CODE', 'like', '%' . $search . '%')
                        ->orWhere('service_charges.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('service_charges.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when(! $search, function ($query) use ($dateEntry) {
                $query->whereDate('service_charges.DATE', $dateEntry);
            })
            ->where('service_charges.USE_PHIC', '=', 0)
            ->orderByDesc('service_charges.DATE')
            ->limit(1000)
            ->paginate($perPage);

        return $query;
    }
    public function PatientRecord($search, int $contact_id, int $perPage, int $LOCK_LOCATION_ID)
    {
        $result = ServiceCharges::query()
            ->select([
                'service_charges.ID',
                'service_charges.CODE',
                'service_charges.DATE',
                'service_charges.AMOUNT',
                'service_charges.BALANCE_DUE',
                'service_charges.OUTPUT_TAX_RATE',
                'service_charges.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                's.ID as STATUS_ID',
            ])
            ->join('location as l', 'l.ID', '=', 'service_charges.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'service_charges.STATUS')
            ->where('service_charges.PATIENT_ID', $contact_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('service_charges.CODE', 'like', '%' . $search . '%')
                        ->orWhere('service_charges.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('service_charges.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->when($LOCK_LOCATION_ID > 0, function ($query) use (&$LOCK_LOCATION_ID) {
                $query->where('service_charges.LOCATION_ID', $LOCK_LOCATION_ID);
            })
            ->where('service_charges.USE_PHIC', '=', 0)
            ->orderBy('service_charges.ID', 'desc')
            ->paginate($perPage);
        return $result;
    }
    public function getItemDetails(int $id)
    {
        return ServiceChargesItems::where('id', $id)->first();
    }
    private function getLine($Id): int
    {
        return (int) ServiceChargesItems::where('SERVICE_CHARGES_ID', $Id)->max('LINE_NO');
    }
    public function AutoUpdateNextID()
    {
    }
    public function ItemStore(
        int $SERVICE_CHARGES_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $COGS_ACCOUNT_ID,
        int $ASSET_ACCOUNT_ID,
        int $INCOME_ACCOUNT_ID = 0,
        int $GROUP_LINE_ID,
        bool $PRINT_IN_FORMS,
        int $PRICE_LEVEL_ID
    ): int {

        $LINE_NO = (int) $this->getLine($SERVICE_CHARGES_ID) + 1;
        $ID      = (int) $this->object->ObjectNextID('SERVICE_CHARGES_ITEMS');
        ServiceChargesItems::create([
            'ID'                 => $ID,
            'SERVICE_CHARGES_ID' => $SERVICE_CHARGES_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
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
            'GROUP_LINE_ID'      => $GROUP_LINE_ID > 0,
            'PRINT_IN_FORMS'     => $PRINT_IN_FORMS,
            'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'DATE_LOG'           => $this->dateServices->NowDate(),
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::SERVICE_CHARGES_ITEMS, $SERVICE_CHARGES_ID);
        return $ID;
    }
    public function ItemUpdate(
        int $ID,
        int $SERVICE_CHARGES_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $PRICE_LEVEL_ID,
        int $INCOME_ACCOUNT_ID = 0
    ) {
        ServiceChargesItems::where('ID', $ID)
            ->where('SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)
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
                'PRICE_LEVEL_ID'     => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
                'INCOME_ACCOUNT_ID'  => $INCOME_ACCOUNT_ID > 0 ? $INCOME_ACCOUNT_ID : null,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::SERVICE_CHARGES_ITEMS, $SERVICE_CHARGES_ID);
    }
    public function ItemDelete(int $ID, int $SERVICE_CHARGES_ID)
    {
        ServiceChargesItems::where('ID', $ID)->where('SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::SERVICE_CHARGES_ITEMS, $SERVICE_CHARGES_ID);
    }
    public function GetCountByYear(int $ITEM_ID, int $YEAR, int $PATIENT_ID, int $LOCATION_ID): int
    {

        $count = ServiceCharges::join('service_charges_items', 'service_charges_items.SERVICE_CHARGES_ID', '=', 'service_charges.ID')
            ->where('service_charges.PATIENT_ID', $PATIENT_ID)
            ->whereYear('service_charges.DATE', $YEAR)
            ->where('service_charges.LOCATION_ID', $LOCATION_ID)
            ->where('service_charges_items.ITEM_ID', $ITEM_ID)
            ->where('service_charges.USE_PHIC', '=', 0)
            ->count();

        return $count;
    }
    public function getPhilHealthItem(string $DATE, int $LOCATION_ID, int $PATIENT_ID): float
    {

        $result = ServiceChargesItems::select('service_charges_items.RATE')
            ->join('service_charges as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->where('ITEM_ID', '=', '2')
            ->where('sc.DATE', '=', $DATE)
            ->where('sc.LOCATION_ID', '=', $LOCATION_ID)
            ->where('PATIENT_ID', '=', $PATIENT_ID)
            ->first();

        if ($result) {
            return $result->RATE ?? 0;
        }
        return 0;
    }
    public function getItemCount(int $SERVICE_CHARGES_ID): int
    {
        return ServiceChargesItems::where('SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)->count();
    }
    public function ItemView(int $SERVICE_CHARGES_ID, bool $isNotZero = false)
    {
        return ServiceChargesItems::query()
            ->select([
                'service_charges_items.ID',
                'service_charges_items.ITEM_ID',
                'service_charges_items.SERVICE_CHARGES_ID',
                'service_charges_items.QUANTITY',
                'service_charges_items.UNIT_ID',
                'service_charges_items.RATE',
                'service_charges_items.AMOUNT',
                'service_charges_items.TAXABLE',
                'service_charges_items.TAXABLE_AMOUNT',
                'service_charges_items.PAID_AMOUNT',
                DB::raw('(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) as BALANCE '),
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                'c.DESCRIPTION as CLASS_DESCRIPTION',
                DB::raw('(select count(*) from patient_payment_charges where SERVICE_CHARGES_ITEM_ID = service_charges_items.ID) as count_pay'),
                'service_charges_items.DATE_LOG',
                DB::raw('(select if(count(*) > 0, true, false)  from hemodialysis_items where hemodialysis_items.SC_ITEM_ID = service_charges_items.ID and hemodialysis_items.IS_CASHIER = 1) as other_charge '),
                'service_charges_items.INCOME_ACCOUNT_ID',
                'a.NAME as ACCOUNT_NAME',
                'service_charges_items.INVOICE_ID',

            ])
            ->leftJoin('item as i', 'i.ID', '=', 'service_charges_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'service_charges_items.UNIT_ID')
            ->leftJoin('item_sub_class as sl', 'sl.ID', '=', 'i.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 'sl.CLASS_ID')
            ->leftJoin('account as a', 'a.ID', '=', 'service_charges_items.INCOME_ACCOUNT_ID')
            ->where('service_charges_items.SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)
            ->when($isNotZero == true, function ($query) {
                $query->whereRaw('(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) > 0');
            })
            ->orderBy('service_charges_items.LINE_NO', 'asc')
            ->get();
    }
    public function getItemListSum($SERVICE_CHARGES_ID, $arrayID = []): float
    {
        if ($arrayID == []) {

            return 0.00;
        }

        $result = ServiceChargesItems::query()
            ->select([
                DB::raw('sum(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) as AMOUNT '),
            ])

            ->where('service_charges_items.SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)
            ->whereIn('service_charges_items.ID', $arrayID)
            ->first();

        if ($result) {
            return (float) $result->AMOUNT ?? 0.00;
        }

        return 0.00;
    }
    public function getItemSum(int $SERVICE_CHARGES_ID, int $ID)
    {

        $result = ServiceChargesItems::query()
            ->select([
                DB::raw('sum(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) as AMOUNT '),
            ])

            ->where('service_charges_items.SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)
            ->where('service_charges_items.ID', '=', $ID)
            ->first();

        if ($result) {
            return (float) $result->AMOUNT ?? 0.00;
        }

        return 0.00;
    }
    public function ReComputed(int $ID): array
    {
        $iCharges = ServiceCharges::where('ID', $ID)->first();
        if ($iCharges) {
            $TAX_ID = (int) $iCharges->OUTPUT_TAX_ID;

            $itemResult = ServiceChargesItems::query()
                ->select(
                    [
                        'service_charges_items.AMOUNT',
                        'service_charges_items.TAX_AMOUNT',
                        'service_charges_items.TAXABLE_AMOUNT',
                        'service_charges_items.TAXABLE',
                        'item.TYPE',
                    ]
                )
                ->join('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
                ->where('service_charges_items.SERVICE_CHARGES_ID', $ID)
                ->whereIn('item.TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
                ->orderBy('service_charges_items.LINE_NO', 'asc')
                ->get();

            $data = $this->compute->taxCompute($itemResult, $TAX_ID);

            foreach ($data as $list) {
                $originalAmount = (float) $list['AMOUNT'];
                $balance        = (float) $originalAmount - $this->GetPaymentAppliedViaServiceCharges($ID);
                ServiceCharges::where('ID', $ID)
                    ->update([
                        'AMOUNT'            => $originalAmount,
                        'BALANCE_DUE'       => $balance,
                        'OUTPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                        'TAXABLE_AMOUNT'    => $list['TAXABLE_AMOUNT'],
                        'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT'],
                    ]);

                $result = [
                    [
                        'AMOUNT'            => $originalAmount,
                        'BALANCE_DUE'       => $balance,
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
    public function GetPaymentAppliedViaServiceCharges(int $SERVICE_CHARGES_ID): float
    {
        $result = PatientPaymentCharges::query()
            ->select(DB::raw('IFNULL(SUM(patient_payment_charges.AMOUNT_APPLIED), 0) AS pay'))
            ->join('service_charges_items', 'service_charges_items.ID', '=', 'patient_payment_charges.SERVICE_CHARGES_ITEM_ID')
            ->where('service_charges_items.SERVICE_CHARGES_ID', '=', $SERVICE_CHARGES_ID)
            ->first();

        return $result->pay ?? 0;
    }
    public function updateServiceChargesItemPaid(int $SERVICE_CHARGES_ITEM_ID)
    {
        $data = ServiceChargesItems::where('ID', $SERVICE_CHARGES_ITEM_ID)->first();
        if ($data) {
            $ITEM_PAID = $this->getPaidItemCharge($SERVICE_CHARGES_ITEM_ID);

            ServiceChargesItems::where('ID', $SERVICE_CHARGES_ITEM_ID)
                ->update([
                    'PAID_AMOUNT' => $ITEM_PAID,
                ]);
            $this->updateServiceChargesBalance($data->SERVICE_CHARGES_ID);
        }
    }
    public function getPaidItemCharge(int $SERVICE_CHARGES_ITEM_ID): float
    {

        $result = PatientPaymentCharges::query()
            ->select(DB::raw('IFNULL(SUM(patient_payment_charges.AMOUNT_APPLIED), 0) AS pay'))
            ->where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->first();

        return $result->pay ?? 0;
    }
    public function updateServiceChargesBalance(int $SERVICE_CHARGES_ID)
    {
        $data = ServiceCharges::where('ID', $SERVICE_CHARGES_ID)->first();
        if ($data) {
            $AMOUNT  = (float) $data->AMOUNT;
            $PAYMENT = (float) $this->GetPaymentAppliedViaServiceCharges($SERVICE_CHARGES_ID);
            $BALANCE = $AMOUNT - $PAYMENT;

            $STATUS = 0;

            if ($PAYMENT == 0) {
                // poste    d
                $STATUS = 0;
            } elseif ($BALANCE <= 0) {
                //paid
                $STATUS = 11;
            } else {
                // Unpaid
                $STATUS = 2;
            }

            $this->setUpdate($SERVICE_CHARGES_ID, $BALANCE, $STATUS);
        }
    }
    private function setUpdate(int $SERVICE_CHARGES_ID, float $BALANCE, int $STATUS)
    {
        ServiceCharges::where('ID', $SERVICE_CHARGES_ID)
            ->update([
                'BALANCE_DUE' => $BALANCE,
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);
    }
    public function getUpdateTaxItem(int $SERVICE_CHARGES_ID, int $TAX_ID)
    {
        $items = ServiceChargesItems::query()
            ->select([
                'service_charges_items.ID',
                'service_charges_items.AMOUNT',
                'service_charges_items.TAXABLE',
            ])
            ->join('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->where('service_charges_items.SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)
            ->where('item.TYPE', 0)
            ->orderBy('service_charges_items.LINE_NO', 'asc')
            ->get();

        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;
        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                ServiceChargesItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'     => $tax_result['TAX_AMOUNT'],
                    ]);
            }
        }
    }
    public function getSum(string $fromDate, string $toDate, int $locationId = 0, int $patientId = 0): float
    {
        $result = (float) ServiceCharges::whereBetween('DATE', [$fromDate, $toDate])
            ->when($locationId > 0, function ($query) use (&$locationId) {
                $query->where('LOCATION_ID', $locationId);
            })
            ->when($patientId > 0, function ($query) use (&$patientId) {
                $query->where('PATIENT_ID', $patientId);
            })
            ->where('USE_PHIC', '=', 0)
            ->sum('AMOUNT');

        return $result;
    }
    public function getBalanceItem(int $PATIENT_ID, int $LOCK_LOCATION_ID)
    {
        $result = ServiceChargesItems::query()
            ->select([
                'sc.DATE',
                'sc.CODE',
                'service_charges_items.SERVICE_CHARGES_ID',
                'i.DESCRIPTION as ITEM_NAME',
                'service_charges_items.AMOUNT',
                'service_charges_items.PAID_AMOUNT',
                DB::raw('(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) as BALANCE'),
            ])
            ->join('service_charges as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->join('item as i', 'i.ID', '=', 'service_charges_items.ITEM_ID')
            ->where('sc.PATIENT_ID', $PATIENT_ID)
            ->whereNotIn('service_charges_items.ITEM_ID', [2])
            ->when($LOCK_LOCATION_ID > 0, function ($query) use (&$LOCK_LOCATION_ID) {
                $query->where('sc.LOCATION_ID', $LOCK_LOCATION_ID);
            })
            ->where('sc.USE_PHIC', '=', 0)
            ->having('BALANCE', '>', 0)
            ->orderBy('sc.DATE')
            ->get();

        return $result;
    }

    public function balanceList(int $PATIENT_ID, int $LOCATION_ID, string $DATE_FROM, string $DATE_TO)
    {
        $result = ServiceChargesItems::query()
            ->select([
                'sc.DATE',
                'sc.CODE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as CONTACT_NAME"),
                'service_charges_items.SERVICE_CHARGES_ID',
                'i.DESCRIPTION as ITEM_NAME',
                'service_charges_items.AMOUNT',
                'service_charges_items.PAID_AMOUNT',
                DB::raw('(service_charges_items.AMOUNT - service_charges_items.PAID_AMOUNT) as BALANCE'),
            ])
            ->join('service_charges as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->join('item as i', 'i.ID', '=', 'service_charges_items.ITEM_ID')
            ->whereBetween('sc.DATE', [$DATE_FROM, $DATE_TO])
            ->when($PATIENT_ID > 0, function ($query) use (&$PATIENT_ID) {
                $query->where('sc.PATIENT_ID', $PATIENT_ID);
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('sc.LOCATION_ID', $LOCATION_ID);
            })
            ->whereNotIn('service_charges_items.ITEM_ID', [2])
            ->where('sc.USE_PHIC', '=', 0)
            ->having('BALANCE', '>', 0)
            ->orderBy('sc.DATE')
            ->get();

        return $result;
    }

    public function GetWalkInServiceChargeTransaction(string $DATE)
    {
        // runs once a day.
        $result = ServiceChargesItems::query()
            ->select([
                'service_charges_items.ID',
                'service_charges_items.SERVICE_CHARGES_ID',
                'service_charges_items.ITEM_ID',
                'service_charges_items.QUANTITY',
                'service_charges_items.UNIT_BASE_QUANTITY',
                'item.COST',
                'service_charges.DATE',
                'service_charges.LOCATION_ID',
                'service_charges.PATIENT_ID',
            ])
            ->join('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->join('service_charges', 'service_charges.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('service_charges.DATE', '<=', $DATE)
            ->where('service_charges.WALK_IN', true)
            ->where('service_charges_items.IS_POSTED', false)
            ->where('service_charges.USE_PHIC', '=', 0)
            ->orderBy('service_charges.DATE', 'asc')
            ->get();

        return $result;
    }
    public function GetWalkInServiceChargePosted(string $DATE)
    {
        ServiceChargesItems::join('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->join('service_charges', 'service_charges.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('service_charges.DATE', '<=', $DATE)
            ->where('service_charges.WALK_IN', true)
            ->where('service_charges_items.IS_POSTED', false)
            ->where('service_charges.USE_PHIC', '=', 0)
            ->update([
                'service_charges_items.IS_POSTED' => true,
            ]);
    }
    public function ChangePatient(int $SERVICE_CHARGES_ID, int $TRANSFER_CONTACT_ID)
    {
        ServiceCharges::where('ID', '=', $SERVICE_CHARGES_ID)
            ->update([
                'PATIENT_ID' => $TRANSFER_CONTACT_ID,
            ]);
    }
    public function GetItemForCustomSoa(string $DATE, int $PATIENT_ID, int $LOCATION_ID)
    {
        $result = ServiceChargesItems::select(['service_charges_items.ITEM_ID'])
            ->join('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->join('service_charges', 'service_charges.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('service_charges.DATE', $DATE)
            ->where('service_charges.LOCATION_ID', $LOCATION_ID)
            ->where('service_charges.PATIENT_ID', $PATIENT_ID)
            ->where('service_charges.USE_PHIC', '=', 0)
            ->get();

        return $result->toArray();
    }
    public function getAvailmentTotal(int $PATIENT_ID, int $YEAR, int $LOCATION_ID): int
    {
        $result = ServiceCharges::query()
            ->join('service_charges_items', 'service_charges_items.SERVICE_CHARGES_ID', '=', 'service_charges.ID')
            ->where('service_charges_items.ITEM_ID', '=', 2)
            ->where('service_charges.LOCATION_ID', '=', $LOCATION_ID)
            ->where('service_charges.PATIENT_ID', '=', $PATIENT_ID)
            ->whereYear('service_charges.DATE', '=', $YEAR)
            ->count();

        return (int) $result;
    }

    public function getLastAvailment(int $PATIENT_ID, int $YEAR, int $LOCATION_ID)
    {
        $result = ServiceCharges::query()
            ->select(['service_charges.DATE'])
            ->join('service_charges_items', 'service_charges_items.SERVICE_CHARGES_ID', '=', 'service_charges.ID')
            ->where('service_charges_items.ITEM_ID', '=', 2)
            ->where('service_charges.LOCATION_ID', '=', $LOCATION_ID)
            ->where('service_charges.PATIENT_ID', '=', $PATIENT_ID)
            ->whereYear('service_charges.DATE', '=', $YEAR)
            ->orderBy('service_charges.DATE', 'desc')
            ->first();

        if ($result) {
            return $result;
        }

        return null;
    }

    private function getAvailmentsByMonth(int $PATIENT_ID, int $YEAR, int $LOCATION_ID)
    {
        $result = ServiceCharges::query()
            ->select([
                DB::raw('MONTH(service_charges.DATE) as month'),
                DB::raw("GROUP_CONCAT(service_charges.DATE ORDER BY service_charges.DATE ASC SEPARATOR ', ') as dates"),
            ])
            ->join('service_charges_items', 'service_charges_items.SERVICE_CHARGES_ID', '=', 'service_charges.ID')
            ->where('service_charges_items.ITEM_ID', 2)
            ->where('service_charges.LOCATION_ID', $LOCATION_ID)
            ->where('service_charges.PATIENT_ID', $PATIENT_ID)
            ->whereYear('service_charges.DATE', $YEAR)
            ->groupBy(DB::raw('MONTH(service_charges.DATE)'))
            ->pluck('dates', 'month');

        return $result;
    }

    public function getAvailList(int $PATIENT_ID, int $YEAR, int $LOCATION_ID): array
    {
        $months = [
            1  => 'JAN',
            2  => 'FEB',
            3  => 'MAR',
            4  => 'APR',
            5  => 'MAY',
            6  => 'JUN',
            7  => 'JUL',
            8  => 'AUG',
            9  => 'SEPT',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DEC',
        ];

        $availments = $this->getAvailmentsByMonth($PATIENT_ID, $YEAR, $LOCATION_ID);

        return array_map(function ($monthNumber, $monthName) use ($availments) {
            return [
                'ID'    => $monthName,
                'DATES' => $availments[$monthNumber] ?? '',
            ];
        }, array_keys($months), $months);
    }

    public function isHavePriming(string $DATE, int $LOCATION_ID, int $PATIENT_ID): bool
    {
        $result = (bool) ServiceCharges::query()
            ->join('service_charges_items', 'service_charges_items.SERVICE_CHARGES_ID', '=', 'service_charges.ID')
            ->where('service_charges_items.ITEM_ID', '=', $this->PRIMING_FEE_ID)
            ->where('service_charges.LOCATION_ID', '=', $LOCATION_ID)
            ->where('service_charges.PATIENT_ID', '=', $PATIENT_ID)
            ->where('service_charges.DATE', '=', $DATE)
            ->exists();

        return $result;
    }

    public function getListUsePhic(int $PATIENT_ID, int $LOCATION_ID, int $YEAR): object
    {
        $result = ServiceCharges::query()
            ->select([
                'ID',
                'DATE',
            ])
            ->where('service_charges.LOCATION_ID', '=', $LOCATION_ID)
            ->where('service_charges.PATIENT_ID', '=', $PATIENT_ID)
            ->whereYear('service_charges.DATE', '=', $YEAR)
            ->where('USE_PHIC', '=', true)
            ->get();

        return $result;
    }
    public function getDatePreviousTransaction(string $DATE, int $LOCATION_ID)
    {
        $result = ServiceCharges::query()
            ->select([
                'DATE',
            ])
            ->where('service_charges.LOCATION_ID', '=', $LOCATION_ID)
            ->where('service_charges.DATE', '<', $DATE)
            ->orderby('DATE', 'desc')
            ->groupBy('DATE')
            ->first();

        return $result ? $result->DATE : null;
    }
    public function getCountCharges(string $DATE, int $LOCATION_ID)
    {

        $result = ServiceCharges::query()
            ->where('service_charges.LOCATION_ID', '=', $LOCATION_ID)
            ->where('service_charges.DATE', '=', $DATE)
            ->where('service_charges.WALK_IN', '=', 0)
            ->count();

        return (int) $result;
    }

    public function GetItemPhic156UnInvoice(string $transDate, int $PHIL_HEALTH_ITEM_ID)
    {
        $result = ServiceChargesItems::query()
            ->select([
                'service_charges_items.ID',
                'service_charges_items.SERVICE_CHARGES_ID',
                'service_charges_items.ITEM_ID',
                'service_charges_items.AMOUNT',
                'sc.DATE',
                'sc.LOCATION_ID',
                'sc.PATIENT_ID',
            ])
            ->join('SERVICE_CHARGES as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->where('sc.DATE', '<=', $transDate)
            ->whereYear('sc.DATE', '>=', 2026)
            ->where('service_charges_items.ITEM_ID', $PHIL_HEALTH_ITEM_ID)
            ->where('service_charges_items.INVOICE_ID', '=', 0)
            ->whereNotNull('service_charges_items.AMOUNT')
            ->get();

        return $result;
    }
    public function UpdateInvoiceID(int $ID, int $INVOICE_ID)
    {
        ServiceChargesItems::where('ID', '=', $ID)
            ->where('INVOICE_ID', '=', 0)
            ->update([
                'INVOICE_ID' => $INVOICE_ID,
            ]);
    }

    public function RemovingUpdateInvoiceID(int $INVOICE_ID)
    {
        ServiceChargesItems::where('INVOICE_ID', '=', $INVOICE_ID)
            ->update([
                'INVOICE_ID' => 0,
            ]);
    }
}
