<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\Contacts;
use App\Models\DoctorLocation;
use App\Models\ServiceChargesItems;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ContactServices
{
    use WithPagination;
    private $objectService;
    private $itemServices;
    private $dateServices;
    private $usersLogServices;
    public function __construct(ObjectServices $objectService, ItemServices $itemServices, DateServices $dateServices, UsersLogServices $usersLogServices)
    {
        $this->objectService    = $objectService;
        $this->itemServices     = $itemServices;
        $this->dateServices     = $dateServices;
        $this->usersLogServices = $usersLogServices;
    }
    public function is12CharRequired(string $value): bool
    {
        if ($value == "" || $value == null) {
            return false;
        }
        if (strlen($value) == 12) {
            return false;
        }

        return true;
    }
    public function updateParameterBool(int $ID, string $paraName, bool $value)
    {
        Contacts::where('ID', '=', $ID)
            ->update([
                $paraName => $value,
            ]);
    }
    public function isActive(int $ID): bool
    {
        return Contacts::where('ID', '=', $ID)->where('INACTIVE', '=', false)->exists();
    }
    public function isDoctor(int $CONTACT_ID): bool
    {
        return (bool) Contacts::where('ID', '=', $CONTACT_ID)
            ->where('TYPE', '=', 4)
            ->exists();
    }
    public function getName($ID): string
    {
        $result = contacts::query()->select([
            DB::raw("CONCAT( contact.FIRST_NAME, ' ', LEFT(contact.MIDDLE_NAME, 1),'. ', contact.LAST_NAME, '  ', IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as NAME, contact.EMAIL"),

        ])->where('ID', '=', $ID)->first();
        if ($result) {
            return $result->NAME ?? '';
        }
        return '';
    }
    public function getName2($ID)
    {
        $result = contacts::query()->select([
            "NAME",
            "NICKNAME",

        ])->where('ID', '=', $ID)->first();
        if ($result) {
            $name = $result->NAME ?? '';
            $pos  = $result->NICKNAME ?? '';
            if ($pos) {
                return $name . '/' . $pos;
            }
            return $name ?? '';
        }
        return '';
    }
    public function getPatientByMed($ID)
    {
        $result = contacts::query()
            ->select([
                'contact.MED_CERT_SCHED_ID',
                'contact.MED_CERT_NURSE_ID',
                DB::raw("CONCAT( contact.FIRST_NAME, ' ', LEFT(contact.MIDDLE_NAME, 1),'. ', contact.LAST_NAME, '  ', IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as NAME"),
                'contact.LAST_NAME',
                'contact.EMAIL',
                'contact.DATE_OF_BIRTH',
                'contact.ADDRESS_UNIT_ROOM_FLOOR',
                'contact.ADDRESS_BUILDING_NAME',
                'contact.ADDRESS_LOT_BLK_HOUSE_BLDG',
                'contact.ADDRESS_STREET',
                'contact.ADDRESS_SUB_VALL',
                'contact.ADDRESS_BRGY',
                'contact.ADDRESS_CITY_MUNI',
                'contact.ADDRESS_PROVINCE',
                'gender_map.DESCRIPTION as GENDER',
                'contact.FINAL_DIAGNOSIS',
                'ms.DESCRIPTION as FULL_DESCRIPTION',
                'ms.SHORT_DESCRIPTION',
                'nurse.NAME as NURSE_NAME',
                'nurse.TAXPAYER_ID as LIC_NUMBER',
                'contact.LOCATION_ID',
                'contact.FIX_MON',
                'contact.FIX_TUE',
                'contact.FIX_WEN',
                'contact.FIX_THU',
                'contact.FIX_FRI',
                'contact.FIX_SAT',
                'contact.FIX_SUN',
                'contact.MOBILE_NO',
            ])
            ->leftJoin('contact as nurse', 'nurse.ID', '=', 'contact.MED_CERT_NURSE_ID')
            ->leftJoin('medcert_sched as ms', 'ms.ID', '=', 'contact.MED_CERT_SCHED_ID')
            ->leftJoin('gender_map', 'gender_map.ID', '=', 'contact.GENDER')
            ->where('contact.ID', '=', $ID)
            ->where('contact.TYPE', '=', 3)
            ->first();

        return $result;
    }

    public function get(int $ID, int $TYPE)
    {

        $result = contacts::where('ID', '=', $ID)
            ->where('TYPE', '=', $TYPE)
            ->first();

        return $result;
    }
    public function get2(int $ID)
    {

        $result = contacts::where('ID', '=', $ID)
            ->first();

        return $result;
    }
    public function updateaWitNessOnly(int $ID, int $WITNESS_ID)
    {
        contacts::where('ID', '=', $ID)
            ->update(['WITNESS_ID' => $WITNESS_ID]);
    }
    public function getSingleData(int $ID)
    {
        $result = contacts::where('ID', '=', $ID)
            ->first();

        return $result;
    }
    public function IsNotPatient(int $ID): bool
    {
        $result = contacts::where('ID', '=', $ID)
            ->where('TYPE', '=', 3)
            ->first();

        if ($result) {
            return false;
        }

        return true;
    }
    public function pinLogin(string $PIN): int
    {
        $data = Contacts::where('PIN', '=', $PIN)
            ->where('TYPE', '=', 2)
            ->first();

        if ($data) {
            return (int) $data->ID;
        }
        return 0;
    }
    public function getFirstFromListByID(int $TYPE): int
    {
        // Temporary
        $data = contacts::where('TYPE', '=', $TYPE)->first();
        if ($data) {
            return (int) $data->ID ?? 0;
        }
        return 0;
    }
    public function getList(int $Type): object
    {
        if ($Type == 3) {
            $result = Contacts::query()
                ->select([
                    'ID',
                    DB::raw("CONCAT(LAST_NAME, ', ', FIRST_NAME, ', ', LEFT(MIDDLE_NAME, 1)) as NAME"),
                ])
                ->where('TYPE', '=', $Type)
                ->where('INACTIVE', '=', '0')
                ->orderBy('LAST_NAME', 'asc')
                ->get();

            return $result;
        }

        $result = Contacts::query()
            ->select([
                'contact.ID',
                'contact.NAME',
            ])
            ->join('contact_type_map as t', 't.ID', '=', 'contact.TYPE')
            ->where('contact.TYPE', '=', $Type)
            ->where('contact.INACTIVE', '=', false)->get();

        return $result;
    }
    public function getVendorDoc()
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                'contact.NAME',
                't.DESCRIPTION as TYPE',
            ])
            ->join('contact_type_map as t', 't.ID', '=', 'contact.TYPE')
            ->whereIn('contact.TYPE', [0, 4])
            ->where('contact.INACTIVE', '=', false)
            ->get();

        return $result;
    }
    public function getListAllType()
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                'contact.NAME',
                't.DESCRIPTION as TYPE',
            ])
            ->join('contact_type_map as t', 't.ID', '=', 'contact.TYPE')
            ->where('contact.INACTIVE', '=', false)
            ->get();

        return $result;
    }
    public function getCustoPatientList(): object
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                DB::raw("contact.PRINT_NAME_AS as NAME"),
                'contact_type_map.DESCRIPTION as TYPE',
            ])
            ->join('contact_type_map', 'contact_type_map.ID', '=', 'contact.TYPE')
            ->whereIn('contact.TYPE', [1, 3])
            ->orderBy('contact.LAST_NAME', 'asc')
            ->get();

        return $result;
    }
    public function getEmployeeList()
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                'contact.NAME',
                't.DESCRIPTION as TYPE',
            ])
            ->join('contact_type_map as t', 't.ID', '=', 'contact.TYPE')
            ->whereIn('contact.TYPE', [2])
            ->where('contact.INACTIVE', '=', false)
            ->get();

        return $result;
    }
    public function getPatientList(int $LOCATION_ID): object
    {

        $result = Contacts::query()
            ->select([
                'ID',
                DB::raw("CONCAT(LAST_NAME, ', ', FIRST_NAME, ', ', LEFT(MIDDLE_NAME, 1),' ', SALUTATION) as NAME"),
            ])
            ->where('TYPE', '=', 3)
            ->where('INACTIVE', '=', '0')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('LOCATION_ID', $LOCATION_ID);
            })
            ->orderBy('LAST_NAME', 'asc')
            ->limit(200)
            ->get();

        return $result;
    }
    public function getPatientList2(int $LOCATION_ID): object
    {

        $result = Contacts::query()
            ->select([
                'ID',
                DB::raw("IF(TYPE =3, CONCAT(LAST_NAME, ', ', FIRST_NAME, ', ', LEFT(MIDDLE_NAME, 1),' ', SALUTATION), NAME ) as NAME"),
            ])
            ->where('INACTIVE', '=', '0')
            ->where('TYPE', 3)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('LOCATION_ID', '=', $LOCATION_ID);
            })->orWhere('TYPE', '=', 1)
            ->orderBy('LAST_NAME', 'asc')
            ->get();

        return $result;
    }
    public function getPatientAvailmentList($search, int $LOCATION_ID, int $YEAR): object
    {

        $groupList = $this->itemServices->GetAllItemByGroup(1);
        $list      = '';
        foreach ($groupList as $item) {
            if ($list == '') {
                $list = "'" . $item['ID'] . "'";
            } else {
                $list .= ",'" . $item['ID'] . "'";
            }
        }

        $result = Contacts::query()
            ->select([
                'ID',
                DB::raw("CONCAT(LAST_NAME, ', ', FIRST_NAME, ', ', LEFT(MIDDLE_NAME, 1),' ', SALUTATION) as NAME"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 1) as TOTAL_JAN"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 2) as TOTAL_FEB"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 3) as TOTAL_MAR"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 4) as TOTAL_APR"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 5) as TOTAL_MAY"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 6) as TOTAL_JUN"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 7) as TOTAL_JUL"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 8) as TOTAL_AUG"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 9) as TOTAL_SEP"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 10) as TOTAL_OCT"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 11) as TOTAL_NOV"),
                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and MONTH(service_charges.DATE) = 12) as TOTAL_DEC"),

                DB::raw("(select count(*) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID = '2'  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR) as TOTAL_DAYS"),
                DB::raw("(select sum(service_charges_items.QUANTITY) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID IN ($list)  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR) as TOTAL_ITEMS"),
                DB::raw("(select IfNULL(sum(philhealth_item_adjustment.NO_OF_ITEM),0) from philhealth_item_adjustment WHERE philhealth_item_adjustment.PATIENT_ID = contact.ID and  philhealth_item_adjustment.LOCATION_ID = $LOCATION_ID and philhealth_item_adjustment.YEAR = $YEAR ) as TOTAL_OTHER_ITEM"),
                DB::raw("(select IfNULL(sum(philhealth_item_adjustment.NO_OF_USED),0) from philhealth_item_adjustment WHERE philhealth_item_adjustment.PATIENT_ID = contact.ID and  philhealth_item_adjustment.LOCATION_ID = $LOCATION_ID and philhealth_item_adjustment.YEAR = $YEAR ) as TOTAL_OTHER"),
            ])->where('TYPE', 3)
            ->where('INACTIVE', '0')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('LOCATION_ID', $LOCATION_ID);
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('LAST_NAME', 'like', "%" . $search . "%")
                        ->orWhere('FIRST_NAME', 'like', "%" . $search . "%")
                        ->orWhere('MIDDLE_NAME', 'like', "%" . $search . "%");
                });
            })

            ->orderBy('LAST_NAME', 'asc')
            ->get();

        return $result;
    }
    public function getPatientAvailmentListDialyzerQty(int $PATIENT_ID, int $LOCATION_ID, string $DATE): int
    {

        $YEAR      = $this->dateServices->dateToYear($DATE);
        $groupList = $this->itemServices->GetAllItemByGroup(1);
        $list      = '';
        foreach ($groupList as $item) {
            if ($list == '') {
                $list = "'" . $item['ID'] . "'";
            } else {
                $list .= ",'" . $item['ID'] . "'";
            }
        }

        $result = Contacts::query()
            ->select([
                DB::raw("(select IFNULL(sum(service_charges_items.QUANTITY),0) from service_charges join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID  where service_charges.PATIENT_ID = contact.ID and service_charges_items.ITEM_ID IN ($list)  and service_charges.LOCATION_ID = $LOCATION_ID  and YEAR(service_charges.DATE) = $YEAR and service_charges.DATE <= '$DATE' ) as TOTAL_ITEMS"),
            ])->where('TYPE', 3)
            ->where('INACTIVE', '0')
            ->where('ID', '=', $PATIENT_ID)
            ->first();

        if ($result) {
            return (int) $result->TOTAL_ITEMS ?? 0;
        }

        return 0;

    }
    public function getPatientAvailmentListDialyzerQtyDetails(int $PATIENT_ID, int $LOCATION_ID, string $DATE)
    {

        $YEAR      = $this->dateServices->dateToYear($DATE);
        $groupList = $this->itemServices->GetAllItemByGroup(1);
        $list      = '';
        foreach ($groupList as $item) {
            if ($list == '') {
                $list = "'" . $item['ID'] . "'";
            } else {
                $list .= ",'" . $item['ID'] . "'";
            }
        }

        $result = ServiceChargesItems::query()
            ->select([
                's.DATE',
                's.CODE',
                'service_charges_items.SERVICE_CHARGES_ID',
                'service_charges_items.ITEM_ID',
                'service_charges_items.QUANTITY',
                'i.DESCRIPTION',
            ])
            ->join('service_charges as s', 's.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->join('item as i', 'i.ID', '=', 'service_charges_items.ITEM_ID')
            ->where('s.PATIENT_ID', '=', $PATIENT_ID)
            ->whereIn('service_charges_items.ITEM_ID', explode(',', str_replace("'", '', $list)))
            ->where('s.LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('s.DATE', '=', $YEAR)
            ->whereDate('s.DATE', '<=', $DATE)
            ->orderBy('s.DATE', 'asc')
            ->get();

        return $result;
    }
    public function getPatientListViaReport(int $LOCATION_ID, string $DATE_FROM, string $DATE_TO)
    {

        $result = Contacts::query()
            ->select(
                [
                    'ID',
                    DB::raw("CONCAT(LAST_NAME, ', ', FIRST_NAME, ', ', LEFT(MIDDLE_NAME, 1)) as NAME"),
                ]
            )->where('TYPE', '=', 3)
            ->whereExists(function ($query) use (&$LOCATION_ID, &$DATE_FROM, &$DATE_TO) {
                $query->select(DB::raw(1))
                    ->from('service_charges as s')
                    ->whereRaw('s.PATIENT_ID = contact.ID')
                    ->where('s.LOCATION_ID', '=', $LOCATION_ID)
                    ->whereBetween('s.DATE', [$DATE_FROM, $DATE_TO]);
            })
            ->orderBy('LAST_NAME', 'asc')
            ->get();

        return $result;
    }
    public function getDoctorListByLocation(int $LOCATION_ID)
    {
        $result = DoctorLocation::query()
            ->select(['c.ID', 'c.NAME'])
            ->join('contact as c', 'c.ID', '=', 'doctor_location.DOCTOR_ID')
            ->where('doctor_location.LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('c.NAME', 'asc')
            ->get();

        return $result;
    }
    public function calculateUserAge($dateString)
    {
        try {
            $date = Carbon::parse($dateString);
            return (int) $date->age;
        } catch (\Exception $e) {
            // Handle invalid date string or other errors
            return 0;
        }
    }
    public function Store(
        int $TYPE,
        string $NAME,
        string $COMPANY_NAME,
        string $SALUTATION,
        string $FIRST_NAME,
        string $MIDDLE_NAME,
        string $LAST_NAME,
        string $PRINT_NAME_AS,
        string $POSTAL_ADDRESS,
        string $CONTACT_PERSON,
        string $TELEPHONE_NO,
        string $FAX_NO,
        string $MOBILE_NO,
        string $ALT_TELEPHONE_NO,
        string $ALT_CONTACT_PERSON,
        string $EMAIL,
        string $ACCOUNT_NO,
        bool $INACTIVE,
        int $GROUP_ID,
        int $PAYMENT_TERMS_ID,
        float $CREDIT_LIMIT,
        int $PREF_PAYMENT_METHOD_ID,
        string $CREDIT_CARD_NO,
        string $CREDIT_CARD_EXPIRY_DATE,
        int $SALES_REP_ID,
        int $PRICE_LEVEL_ID,
        string $TAXPAYER_ID,
        int $TAX_ID,
        int $EW_TAX_ID,
        string $SSS_NO,
        int $GENDER,
        string $DATE_OF_BIRTH,
        string $NICKNAME,
        string $HIRE_DATE,
        $CUSTOM_FIELD1 = null,
        $CUSTOM_FIELD2 = null,
        $CUSTOM_FIELD3 = null,
        $CUSTOM_FIELD4 = null,
        $CUSTOM_FIELD5 = null
    ): int {
        $OBJECT_TYPE = 0;
        switch ($TYPE) {
            case 0:
                $OBJECT_TYPE = (int) $this->objectService->ObjectTypeIdByName('Vendor');
                break;
            case 1:
                $OBJECT_TYPE = (int) $this->objectService->ObjectTypeIdByName('Customer');
                break;
            case 2:
                $OBJECT_TYPE = (int) $this->objectService->ObjectTypeIdByName('Employee');
                break;
            case 3:
                $OBJECT_TYPE = (int) $this->objectService->ObjectTypeIdByName('Tax Agency');
                break;
            case 4:
                $OBJECT_TYPE = (int) $this->objectService->ObjectTypeIdByName('Other Contact');
                break;
            case 5:
                $OBJECT_TYPE = (int) $this->objectService->ObjectTypeIdByName('Patient');
                break;
            default:
                # code...
                dd("type not found");
                break;
        }

        $ID = $this->objectService->ObjectNextIdByName('Contact');

        Contacts::create([
            "ID"                      => $ID,
            "TYPE"                    => $TYPE,
            "NAME"                    => strtoupper($NAME),
            "COMPANY_NAME"            => strtoupper($COMPANY_NAME),
            "SALUTATION"              => $SALUTATION,
            "FIRST_NAME"              => strtoupper($FIRST_NAME),
            "MIDDLE_NAME"             => strtoupper($MIDDLE_NAME),
            "LAST_NAME"               => strtoupper($LAST_NAME),
            "PRINT_NAME_AS"           => strtoupper($PRINT_NAME_AS),
            "POSTAL_ADDRESS"          => $POSTAL_ADDRESS,
            "CONTACT_PERSON"          => strtoupper($CONTACT_PERSON),
            "TELEPHONE_NO"            => $TELEPHONE_NO,
            "FAX_NO"                  => $FAX_NO,
            "MOBILE_NO"               => $MOBILE_NO,
            "ALT_TELEPHONE_NO"        => $ALT_TELEPHONE_NO,
            "ALT_CONTACT_PERSON"      => $ALT_CONTACT_PERSON,
            "EMAIL"                   => $EMAIL,
            "ACCOUNT_NO"              => $ACCOUNT_NO != '' ? $ACCOUNT_NO : $this->objectService->GetSequence($OBJECT_TYPE, $CUSTOM_FIELD1),
            "INACTIVE"                => $INACTIVE,
            "GROUP_ID"                => $GROUP_ID > 0 ? $GROUP_ID : null,
            "PAYMENT_TERMS_ID"        => $PAYMENT_TERMS_ID > 0 ? $PAYMENT_TERMS_ID : null,
            "CREDIT_LIMIT"            => $CREDIT_LIMIT,
            "PREF_PAYMENT_METHOD_ID"  => $PREF_PAYMENT_METHOD_ID > 0 ? $PREF_PAYMENT_METHOD_ID : null,
            "CREDIT_CARD_NO"          => $CREDIT_CARD_NO,
            "CREDIT_CARD_EXPIRY_DATE" => $CREDIT_CARD_EXPIRY_DATE ? $CREDIT_CARD_EXPIRY_DATE : null,
            "SALES_REP_ID"            => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
            "PRICE_LEVEL_ID"          => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            "APPLY_FINANCE_CHARGE"    => null,
            "TAXPAYER_ID"             => $TAXPAYER_ID,
            "TAX_ID"                  => $TAX_ID > 0 ? $TAX_ID : null,
            "EW_TAX_ID"               => $EW_TAX_ID > 0 ? $EW_TAX_ID : null,
            "SSS_NO"                  => $SSS_NO,
            "GENDER"                  => $GENDER > -1 ? $GENDER : null,
            "DATE_OF_BIRTH"           => $DATE_OF_BIRTH ? $DATE_OF_BIRTH : null,
            "NICKNAME"                => $NICKNAME,
            "HIRE_DATE"               => $HIRE_DATE ? $HIRE_DATE : null,
            "CUSTOM_FIELD1"           => $CUSTOM_FIELD1,
            "CUSTOM_FIELD2"           => $CUSTOM_FIELD2,
            "CUSTOM_FIELD3"           => $CUSTOM_FIELD3,
            "CUSTOM_FIELD4"           => $CUSTOM_FIELD4,
            "CUSTOM_FIELD5"           => $CUSTOM_FIELD5,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::CONTACT, $ID);

        return $ID;
    }

    public function Update(
        int $ID,
        int $TYPE,
        string $NAME,
        string $COMPANY_NAME,
        string $SALUTATION,
        string $FIRST_NAME,
        string $MIDDLE_NAME,
        string $LAST_NAME,
        string $PRINT_NAME_AS,
        string $POSTAL_ADDRESS,
        string $CONTACT_PERSON,
        string $TELEPHONE_NO,
        string $FAX_NO,
        string $MOBILE_NO,
        string $ALT_TELEPHONE_NO,
        string $ALT_CONTACT_PERSON,
        string $EMAIL,
        string $ACCOUNT_NO,
        bool $INACTIVE,
        int $GROUP_ID,
        int $PAYMENT_TERMS_ID,
        float $CREDIT_LIMIT,
        int $PREF_PAYMENT_METHOD_ID,
        string $CREDIT_CARD_NO,
        string $CREDIT_CARD_EXPIRY_DATE,
        int $SALES_REP_ID,
        int $PRICE_LEVEL_ID,
        string $TAXPAYER_ID,
        int $TAX_ID,
        int $EW_TAX_ID,
        string $SSS_NO,
        int $GENDER,
        string $DATE_OF_BIRTH,
        string $NICKNAME,
        string $HIRE_DATE,
        $CUSTOM_FIELD1 = null,
        $CUSTOM_FIELD2 = null,
        $CUSTOM_FIELD3 = null,
        $CUSTOM_FIELD4 = null,
        $CUSTOM_FIELD5 = null
    ): void {
        Contacts::where('ID', $ID)
            ->where('TYPE', $TYPE)
            ->update([
                "NAME"                    => strtoupper($NAME),
                "COMPANY_NAME"            => strtoupper($COMPANY_NAME),
                "SALUTATION"              => $SALUTATION,
                "FIRST_NAME"              => strtoupper($FIRST_NAME),
                "MIDDLE_NAME"             => strtoupper($MIDDLE_NAME),
                "LAST_NAME"               => strtoupper($LAST_NAME),
                "PRINT_NAME_AS"           => strtoupper($PRINT_NAME_AS),
                "POSTAL_ADDRESS"          => $POSTAL_ADDRESS,
                "CONTACT_PERSON"          => strtoupper($CONTACT_PERSON),
                "TELEPHONE_NO"            => $TELEPHONE_NO,
                "FAX_NO"                  => $FAX_NO,
                "MOBILE_NO"               => $MOBILE_NO,
                "ALT_TELEPHONE_NO"        => $ALT_TELEPHONE_NO,
                "ALT_CONTACT_PERSON"      => $ALT_CONTACT_PERSON,
                "EMAIL"                   => $EMAIL,
                "ACCOUNT_NO"              => $ACCOUNT_NO,
                "INACTIVE"                => $INACTIVE,
                "GROUP_ID"                => $GROUP_ID > 0 ? $GROUP_ID : null,
                "PAYMENT_TERMS_ID"        => $PAYMENT_TERMS_ID > 0 ? $PAYMENT_TERMS_ID : null,
                "CREDIT_LIMIT"            => $CREDIT_LIMIT,
                "PREF_PAYMENT_METHOD_ID"  => $PREF_PAYMENT_METHOD_ID > 0 ? $PREF_PAYMENT_METHOD_ID : null,
                "CREDIT_CARD_NO"          => $CREDIT_CARD_NO,
                "CREDIT_CARD_EXPIRY_DATE" => $CREDIT_CARD_EXPIRY_DATE ? $CREDIT_CARD_EXPIRY_DATE : null,
                "SALES_REP_ID"            => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
                "PRICE_LEVEL_ID"          => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
                "APPLY_FINANCE_CHARGE"    => null,
                "TAXPAYER_ID"             => $TAXPAYER_ID,
                "TAX_ID"                  => $TAX_ID > 0 ? $TAX_ID : null,
                "EW_TAX_ID"               => $EW_TAX_ID > 0 ? $EW_TAX_ID : null,
                "SSS_NO"                  => $SSS_NO,
                "GENDER"                  => $GENDER > -1 ? $GENDER : null,
                "DATE_OF_BIRTH"           => $DATE_OF_BIRTH ? $DATE_OF_BIRTH : null,
                "NICKNAME"                => $NICKNAME,
                "HIRE_DATE"               => $HIRE_DATE ? $HIRE_DATE : null,
                "CUSTOM_FIELD1"           => $CUSTOM_FIELD1,
                "CUSTOM_FIELD2"           => $CUSTOM_FIELD2,
                "CUSTOM_FIELD3"           => $CUSTOM_FIELD3,
                "CUSTOM_FIELD4"           => $CUSTOM_FIELD4,
                "CUSTOM_FIELD5"           => $CUSTOM_FIELD5,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CONTACT, $ID);
    }

    public function Delete(int $ID): void
    {
        Contacts::where('ID', '=', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::CONTACT, $ID);
    }
    public function UpdatePatientType(int $ID, int $TYPE)
    {
        Contacts::where('ID', $ID)->update(['PATIENT_TYPE_ID' => $TYPE]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CONTACT, $ID);
    }
    public function Search($search, int $TYPE, int $perPage, int $locationId = 0)
    {
        $result = Contacts::query()
            ->select(
                [
                    "contact.ID",
                    "contact.NAME",
                    "contact.COMPANY_NAME",
                    "contact.FIRST_NAME",
                    "contact.LAST_NAME",
                    "contact.PRINT_NAME_AS",
                    "contact.MOBILE_NO",
                    "contact.EMAIL",
                    "contact.ACCOUNT_NO",
                    "contact.POSTAL_ADDRESS",
                    "contact.CONTACT_PERSON",
                    "contact.INACTIVE",
                    'contact.PIN',
                    'gender_map.DESCRIPTION as GENDER',
                    'l.NAME as LOCATION',
                ]
            )
            ->join('contact_type_map as t', function ($join) use (&$TYPE) {
                $join->on('t.ID', '=', 'contact.TYPE')
                    ->where('t.ID', '=', $TYPE);
            })
            ->leftJoin('gender_map', 'gender_map.ID', '=', 'contact.GENDER')
            ->leftJoin('location as l', 'l.ID', '=', 'contact.LOCATION_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('contact.NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.ACCOUNT_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.COMPANY_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.FIRST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.LAST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('contact.MOBILE_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.EMAIL', 'like', '%' . $search . '%')
                        ->orWhere('contact.PIN', 'like', '%' . $search . '%');
                });
            })
            ->when($locationId > 0, function ($query) use (&$locationId) {
                $query->where('contact.LOCATION_ID', '=', $locationId);
            })
            ->orderBy('contact.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function SearchPatient2($search, int $perPage, int $locationId, string $sortBy, bool $isDesc, int $doctorId = 0)
    {
        $TYPE = 3;

        $result = Contacts::query()
            ->select(
                [
                    "contact.ID",
                    "contact.NAME",
                    "contact.COMPANY_NAME",
                    "contact.FIRST_NAME",
                    "contact.LAST_NAME",
                    "contact.MIDDLE_NAME",
                    "contact.SALUTATION",
                    "contact.PRINT_NAME_AS",
                    "contact.MOBILE_NO",
                    "contact.EMAIL",
                    "contact.ACCOUNT_NO",
                    "contact.POSTAL_ADDRESS",
                    "contact.CONTACT_PERSON",
                    "contact.INACTIVE",
                    'contact.PIN',
                    'contact.IS_COMPLETE',
                    'gender_map.DESCRIPTION as GENDER',
                    'contact.DATE_OF_BIRTH',
                    'contact.DATE_EXPIRED',
                    'contact.DATE_ADMISSION',
                    DB::raw('TIMESTAMPDIFF(YEAR, contact.DATE_OF_BIRTH ,if( isnull(contact.DATE_EXPIRED) = false, contact.DATE_EXPIRED, CURDATE() )) AS AGE'),
                    'l.NAME as LOCATION_NAME',
                    'd.PRINT_NAME_AS as DOCTOR_NAME',
                    'pc.DESCRIPTION as CLASS',
                    'contact.SECOND_CASE_RATE as PDP',
                ]
            )
            ->join('contact_type_map as t', function ($join) use (&$TYPE) {
                $join->on('t.ID', '=', 'contact.TYPE')
                    ->where('t.ID', '=', $TYPE);
            })
            ->leftJoin('gender_map', 'gender_map.ID', '=', 'contact.GENDER')
            ->leftJoin('location as l', 'l.ID', '=', 'contact.LOCATION_ID')
            ->leftJoin('patient_doctor as pd', 'pd.PATIENT_ID', '=', 'contact.ID')
            ->leftJoin('contact as d', 'd.ID', '=', 'pd.DOCTOR_ID')
            ->leftJoin('doctor_location as dl', 'dl.DOCTOR_ID', 'd.ID')
            ->leftJoin('patient_class as pc', 'pc.ID', '=', 'contact.CLASS_ID')
            ->when($doctorId > 0, function ($query) use (&$doctorId) {
                $query->where('pd.DOCTOR_ID', $doctorId);
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('contact.NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.ACCOUNT_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.PIN', 'like', '%' . $search . '%')
                        ->orWhere('contact.COMPANY_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.FIRST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.LAST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('contact.MOBILE_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.EMAIL', 'like', '%' . $search . '%')
                        ->orWhere('d.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when($locationId > 0, function ($query) use (&$locationId, &$search, &$doctorId) {
                $query->where('contact.LOCATION_ID', '=', $locationId)
                    ->where('dl.LOCATION_ID', '=', $locationId);

            })
            ->orderBy($sortBy, $isDesc ? 'desc' : 'asc');

        $result2 = Contacts::query()
            ->select(
                [
                    "contact.ID",
                    "contact.NAME",
                    "contact.COMPANY_NAME",
                    "contact.FIRST_NAME",
                    "contact.LAST_NAME",
                    "contact.MIDDLE_NAME",
                    "contact.SALUTATION",
                    "contact.PRINT_NAME_AS",
                    "contact.MOBILE_NO",
                    "contact.EMAIL",
                    "contact.ACCOUNT_NO",
                    "contact.POSTAL_ADDRESS",
                    "contact.CONTACT_PERSON",
                    "contact.INACTIVE",
                    'contact.PIN',
                    'contact.IS_COMPLETE',
                    'gender_map.DESCRIPTION as GENDER',
                    'contact.DATE_OF_BIRTH',
                    'contact.DATE_EXPIRED',
                    'contact.DATE_ADMISSION',
                    DB::raw('TIMESTAMPDIFF(YEAR, contact.DATE_OF_BIRTH ,if( isnull(contact.DATE_EXPIRED) = false, contact.DATE_EXPIRED, CURDATE() )) AS AGE'),
                    'l.NAME as LOCATION_NAME',
                    'd.PRINT_NAME_AS as DOCTOR_NAME',
                    'pc.DESCRIPTION as CLASS',
                    'contact.SECOND_CASE_RATE as PDP',
                ]
            )
            ->join('contact_type_map as t', function ($join) use (&$TYPE) {
                $join->on('t.ID', '=', 'contact.TYPE')
                    ->where('t.ID', '=', $TYPE);
            })
            ->leftJoin('gender_map', 'gender_map.ID', '=', 'contact.GENDER')
            ->leftJoin('location as l', 'l.ID', '=', 'contact.LOCATION_ID')
            ->leftJoin('patient_doctor as pd', 'pd.PATIENT_ID', '=', 'contact.ID')
            ->leftJoin('contact as d', 'd.ID', '=', 'pd.DOCTOR_ID')
            ->leftJoin('doctor_location as dl', 'dl.DOCTOR_ID', 'd.ID')
            ->leftJoin('patient_class as pc', 'pc.ID', '=', 'contact.CLASS_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('contact.NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.ACCOUNT_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.PIN', 'like', '%' . $search . '%')
                        ->orWhere('contact.COMPANY_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.FIRST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.LAST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('contact.MOBILE_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.EMAIL', 'like', '%' . $search . '%')
                        ->orWhere('d.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when($locationId > 0, function ($query) use (&$locationId, &$search, &$doctorId) {
                $query->where('contact.LOCATION_ID', '=', $locationId);
            })
            ->whereNull('pd.PATIENT_ID')
            ->orderBy($sortBy, $isDesc ? 'desc' : 'asc');

        $finalResult = $result
            ->union($result2) // use unionAll() if duplicates are OK
            ->paginate($perPage);

        return $finalResult;
    }
    public function SearchPatient($search, int $perPage, int $locationId, string $sortBy, bool $isDesc, int $doctorId = 0)
    {
        $TYPE = 3;

        $result = Contacts::query()
            ->select(
                [
                    "contact.ID",
                    "contact.NAME",
                    "contact.COMPANY_NAME",
                    "contact.FIRST_NAME",
                    "contact.LAST_NAME",
                    "contact.MIDDLE_NAME",
                    "contact.SALUTATION",
                    "contact.PRINT_NAME_AS",
                    "contact.MOBILE_NO",
                    "contact.EMAIL",
                    "contact.ACCOUNT_NO",
                    "contact.POSTAL_ADDRESS",
                    "contact.CONTACT_PERSON",
                    "contact.INACTIVE",
                    'contact.PIN',
                    'contact.IS_COMPLETE',
                    'gender_map.DESCRIPTION as GENDER',
                    'contact.DATE_OF_BIRTH',
                    'contact.DATE_EXPIRED',
                    'contact.DATE_ADMISSION',
                    DB::raw('TIMESTAMPDIFF(YEAR, contact.DATE_OF_BIRTH ,if( isnull(contact.DATE_EXPIRED) = false, contact.DATE_EXPIRED, CURDATE() )) AS AGE'),
                    'l.NAME as LOCATION_NAME',
                    'd.PRINT_NAME_AS as DOCTOR_NAME',
                    'pc.DESCRIPTION as CLASS',
                    'contact.SECOND_CASE_RATE as PDP',
                ]
            )
            ->join('contact_type_map as t', function ($join) use (&$TYPE) {
                $join->on('t.ID', '=', 'contact.TYPE')
                    ->where('t.ID', '=', $TYPE);
            })
            ->leftJoin('gender_map', 'gender_map.ID', '=', 'contact.GENDER')
            ->leftJoin('location as l', 'l.ID', '=', 'contact.LOCATION_ID')
            ->leftJoin('patient_doctor as pd', 'pd.PATIENT_ID', '=', 'contact.ID')
            ->leftJoin('contact as d', 'd.ID', '=', 'pd.DOCTOR_ID')
            ->leftJoin('doctor_location as dl', 'dl.DOCTOR_ID', 'd.ID')
            ->leftJoin('patient_class as pc', 'pc.ID', '=', 'contact.CLASS_ID')

            ->when($doctorId > 0, function ($query) use (&$doctorId) {
                $query->where('pd.DOCTOR_ID', $doctorId);
            })
            ->when($search, function ($query) use (&$search) {

                $query->where(function ($q) use ($search) {
                    $q->where('contact.NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.ACCOUNT_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.PIN', 'like', '%' . $search . '%')
                        ->orWhere('contact.COMPANY_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.FIRST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.LAST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('contact.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('contact.MOBILE_NO', 'like', '%' . $search . '%')
                        ->orWhere('contact.EMAIL', 'like', '%' . $search . '%')
                        ->orWhere('d.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when($locationId > 0, function ($query) use (&$locationId, &$search, &$doctorId) {
                $query->where('contact.LOCATION_ID', '=', $locationId)
                //  ->where('dl.LOCATION_ID','=', $locationId)
                    ->orWhereExists(function ($q) use (&$locationId, &$search, &$doctorId) {
                        $q->select(DB::raw(1))
                            ->from('service_charges as sc')
                            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 'sc.ID')
                            ->whereColumn('sc.PATIENT_ID', 'contact.ID')
                            ->where('sc.LOCATION_ID', $locationId)
                            ->when($doctorId > 0, function ($query) use (&$doctorId) {
                                $query->where('pd.DOCTOR_ID', $doctorId);
                            })
                            ->where('dl.LOCATION_ID', '=', $locationId)
                            ->where(function ($sql) use ($search) {
                                $sql->where('contact.NAME', 'like', '%' . $search . '%')
                                    ->orWhere('contact.ACCOUNT_NO', 'like', '%' . $search . '%')
                                    ->orWhere('contact.PIN', 'like', '%' . $search . '%')
                                    ->orWhere('contact.COMPANY_NAME', 'like', '%' . $search . '%')
                                    ->orWhere('contact.FIRST_NAME', 'like', '%' . $search . '%')
                                    ->orWhere('contact.LAST_NAME', 'like', '%' . $search . '%')
                                    ->orWhere('contact.PRINT_NAME_AS', 'like', '%' . $search . '%')
                                    ->orWhere('contact.MOBILE_NO', 'like', '%' . $search . '%')
                                    ->orWhere('contact.EMAIL', 'like', '%' . $search . '%')
                                    ->orWhere('d.PRINT_NAME_AS', 'like', '%' . $search . '%');
                            });
                    });
            })
            ->orderBy($sortBy, $isDesc ? 'desc' : 'asc')
            ->groupBy([
                "contact.ID",
                "contact.NAME",
                "contact.COMPANY_NAME",
                "contact.FIRST_NAME",
                "contact.LAST_NAME",
                "contact.MIDDLE_NAME",
                "contact.SALUTATION",
                "contact.PRINT_NAME_AS",
                "contact.MOBILE_NO",
                "contact.EMAIL",
                "contact.ACCOUNT_NO",
                "contact.POSTAL_ADDRESS",
                "contact.CONTACT_PERSON",
                "contact.INACTIVE",
                'contact.PIN',
                'contact.IS_COMPLETE',
                'gender_map.DESCRIPTION',
                'contact.DATE_OF_BIRTH',
                'contact.DATE_EXPIRED',
                'contact.DATE_ADMISSION',
                'contact.DATE_OF_BIRTH',
                'l.NAME',
                'd.PRINT_NAME_AS',
                'pc.DESCRIPTION',
                'contact.SECOND_CASE_RATE',

            ])->limit(100)
            ->paginate($perPage);

        return $result;
    }
    public function UpdatePin(int $ID, string $PIN)
    {
        Contacts::where('ID', '=', $ID)
            ->update(
                [
                    'PIN' => $PIN,
                ]
            );

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::CONTACT, $ID);

    }
    public function UpdateIsCompleted(int $CONTACT_ID, bool $VALUE)
    {
        Contacts::where('ID', '=', $CONTACT_ID)
            ->update(
                [
                    'IS_COMPLETE' => $VALUE,
                ]
            );
    }

    public function PatientIsRestricted(int $CONTACT_ID): bool
    {

        $result = Contacts::query()
            ->select([
                'contact.ADMITTED',
            ])
            ->where('contact.ID', '=', $CONTACT_ID)
            ->where('contact.TYPE', '=', 3)
            ->join('contact_requirement as cr', 'cr.CONTACT_ID', '=', 'contact.ID')

            ->first();

        if ($result) {

            return true;
        }

        return false;
    }
    public function ContactType(int $ID)
    {
        return DB::table('contact_type_map')->where('ID', $ID)->first();
    }
}
