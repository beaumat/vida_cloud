<?php
namespace App\Services;

use App\Models\Locations;

class LocationServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getList()
    {
        return Locations::query()
            ->select(['ID', 'NAME'])
            ->where('INACTIVE', '0')
            ->get();
    }
    public function getCenterList()
    {
        return Locations::query()
            ->select(['ID', 'NAME'])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();
    }
    public function SOA_FORMAT(int $LOCATION_ID)
    {
        $data = Locations::where('ID', '=', $LOCATION_ID)->first();

        if ($data->PHIC_SOA_FORMAT) {
            return $data->PHIC_SOA_FORMAT ?? 'PrintSoa';
        }

        return "PrintSoa";
    }
    public function getListExcept($ID)
    {
        return Locations::query()
            ->select(['ID', 'NAME'])
            ->where('INACTIVE', '0')
            ->where('ID', '<>', $ID)
            ->get();
    }
    public function getPesonel(int $ID)
    {
        $data = Locations::query()
            ->select([
                'location.ID',
                'm.PRINT_NAME_AS as MANAGER_NAME',
                'm.NICKNAME as MANAGER_POSITION',
                'p.PRINT_NAME_AS as PHIC_NAME',
                'p.NICKNAME as PHIC_POSITION',
            ])
            ->leftJoin('contact as m', 'm.ID', '=', 'location.HCI_MANAGER_ID')
            ->leftJoin('contact as p', 'p.ID', '=', 'location.PHIC_INCHARGE_ID')
            ->where('location.ID', $ID)
            ->first();

        return $data;
    }
    public function get(int $ID)
    {
        $result = Locations::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }

        return [];
    }
    public function AgreementFormQtyAllowed(int $LOCATION_ID): bool
    {

        switch ($LOCATION_ID) {
            case 32: // San Franz
                return false;
            case 33: // Butuan City
                return true;
            default:
                return false;
        }

    }
    public function IsExist(int $ID): bool
    {
        return (bool) Locations::where('ID', '=', $ID)->exists();
    }
    public function isITEMIZED(int $ID): bool
    {
        return (bool) Locations::where('ID', '=', $ID)->where('ITEMIZED_BASE', '=', true)->exists();
    }
    public function Store(
        string $NAME,
        bool $INACTIVE,
        int $PRICE_LEVEL_ID,
        int $GROUP_ID,
        int $HCI_MANAGER_ID,
        int $PHIC_INCHARGE_ID,
        string $NAME_OF_BUSINESS,
        string $ACCREDITATION_NO,
        string $BLDG_NAME_LOT_BLOCK,
        string $STREET_SUB_VALL,
        string $BRGY_CITY_MUNI,
        string $PROVINCE,
        string $ZIP_CODE,
        string $REPORT_HEADER_1,
        string $REPORT_HEADER_2,
        string $REPORT_HEADER_3,
        string $PHIC_SOA_FORMAT = 'PrintSoa',
        bool $PHIC_FORM_MODIFY = false,
        bool $IS_DAILY = false,
        string $LOGO_FILE,
        bool $USED_DRY_WEIGHT,
        string $DOCTOR_ORDER_DEFAULT,
        bool $OTHER_SIGN,
        int $PREPARED_BY_ID,
        int $HD_FACILITY_REP_ID,
        bool $ITEMIZED_BASE,
        int $PHIC_INCHARGE2_ID,
        bool $LEAVE_BLANK_AG_ADMIN_OFFICE_FEE,
        int $PF_TAX_ID,
        int $HCI_MANAGER_TREATMENT_ID

    ): int {
        $ID = $this->object->ObjectNextID('LOCATION');
        Locations::create([
            'ID'                              => $ID,
            'NAME'                            => $NAME,
            'INACTIVE'                        => $INACTIVE,
            'PRICE_LEVEL_ID'                  => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'GROUP_ID'                        => $GROUP_ID > 0 ? $GROUP_ID : null,
            'HCI_MANAGER_ID'                  => $HCI_MANAGER_ID > 0 ? $HCI_MANAGER_ID : null,
            'PHIC_INCHARGE_ID'                => $PHIC_INCHARGE_ID > 0 ? $PHIC_INCHARGE_ID : null,
            'NAME_OF_BUSINESS'                => strtoupper($NAME_OF_BUSINESS),
            'ACCREDITATION_NO'                => $ACCREDITATION_NO,
            'BLDG_NAME_LOT_BLOCK'             => strtoupper($BLDG_NAME_LOT_BLOCK),
            'STREET_SUB_VALL'                 => strtoupper($STREET_SUB_VALL),
            'BRGY_CITY_MUNI'                  => strtoupper($BRGY_CITY_MUNI),
            'PROVINCE'                        => strtoupper($PROVINCE),
            'ZIP_CODE'                        => $ZIP_CODE,
            'REPORT_HEADER_1'                 => $REPORT_HEADER_1,
            'REPORT_HEADER_2'                 => $REPORT_HEADER_2,
            'REPORT_HEADER_3'                 => $REPORT_HEADER_3,
            'PHIC_SOA_FORMAT'                 => $PHIC_SOA_FORMAT,
            'PHIC_FORM_MODIFY'                => $PHIC_FORM_MODIFY,
            'IS_DAILY'                        => $IS_DAILY,
            'LOGO_FILE'                       => $LOGO_FILE,
            'USED_DRY_WEIGHT'                 => $USED_DRY_WEIGHT,
            'DOCTOR_ORDER_DEFAULT'            => $DOCTOR_ORDER_DEFAULT,
            'OTHER_SIGN'                      => $OTHER_SIGN,
            'PREPARED_BY_ID'                  => $PREPARED_BY_ID > 0 ? $HD_FACILITY_REP_ID : null,
            'HD_FACILITY_REP_ID'              => $HD_FACILITY_REP_ID > 0 ? $HD_FACILITY_REP_ID : null,
            'ITEMIZED_BASE'                   => $ITEMIZED_BASE,
            'PHIC_INCHARGE2_ID'               => $PHIC_INCHARGE2_ID > 0 ? $PHIC_INCHARGE2_ID : null,
            'LEAVE_BLANK_AG_ADMIN_OFFICE_FEE' => $LEAVE_BLANK_AG_ADMIN_OFFICE_FEE,
            'PF_TAX_ID'                       => $PF_TAX_ID,
            'HCI_MANAGER_TREATMENT_ID'        => $HCI_MANAGER_TREATMENT_ID > 0 ? $HCI_MANAGER_TREATMENT_ID : null,
        ]);

        return $ID;
    }

    public function Update(
        int $ID,
        string $NAME,
        bool $INACTIVE,
        int $PRICE_LEVEL_ID,
        int $GROUP_ID,
        int $HCI_MANAGER_ID,
        int $PHIC_INCHARGE_ID,
        string $NAME_OF_BUSINESS,
        string $ACCREDITATION_NO,
        string $BLDG_NAME_LOT_BLOCK,
        string $STREET_SUB_VALL,
        string $BRGY_CITY_MUNI,
        string $PROVINCE,
        string $ZIP_CODE,
        string $REPORT_HEADER_1,
        string $REPORT_HEADER_2,
        string $REPORT_HEADER_3,
        string $PHIC_SOA_FORMAT,
        bool $PHIC_FORM_MODIFY = false,
        bool $IS_DAILY = false,
        string $LOGO_FILE,
        bool $USED_DRY_WEIGHT,
        string $DOCTOR_ORDER_DEFAULT,
        bool $OTHER_SIGN,
        int $PREPARED_BY_ID,
        int $HD_FACILITY_REP_ID,
        bool $ITEMIZED_BASE,
        int $PHIC_INCHARGE2_ID,
        bool $LEAVE_BLANK_AG_ADMIN_OFFICE_FEE,
        int $PF_TAX_ID,
        int $HCI_MANAGER_TREATMENT_ID

    ): void {

        Locations::where('ID', $ID)
            ->update([
                'NAME'                            => $NAME,
                'INACTIVE'                        => $INACTIVE,
                'PRICE_LEVEL_ID'                  => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
                'GROUP_ID'                        => $GROUP_ID > 0 ? $GROUP_ID : null,
                'HCI_MANAGER_ID'                  => $HCI_MANAGER_ID > 0 ? $HCI_MANAGER_ID : null,
                'PHIC_INCHARGE_ID'                => $PHIC_INCHARGE_ID > 0 ? $PHIC_INCHARGE_ID : null,
                'NAME_OF_BUSINESS'                => strtoupper($NAME_OF_BUSINESS),
                'ACCREDITATION_NO'                => $ACCREDITATION_NO,
                'BLDG_NAME_LOT_BLOCK'             => strtoupper($BLDG_NAME_LOT_BLOCK),
                'STREET_SUB_VALL'                 => strtoupper($STREET_SUB_VALL),
                'BRGY_CITY_MUNI'                  => strtoupper($BRGY_CITY_MUNI),
                'PROVINCE'                        => strtoupper($PROVINCE),
                'ZIP_CODE'                        => $ZIP_CODE,
                'REPORT_HEADER_1'                 => $REPORT_HEADER_1,
                'REPORT_HEADER_2'                 => $REPORT_HEADER_2,
                'REPORT_HEADER_3'                 => $REPORT_HEADER_3,
                'PHIC_SOA_FORMAT'                 => $PHIC_SOA_FORMAT,
                'PHIC_FORM_MODIFY'                => $PHIC_FORM_MODIFY,
                'IS_DAILY'                        => $IS_DAILY,
                'LOGO_FILE'                       => $LOGO_FILE,
                'USED_DRY_WEIGHT'                 => $USED_DRY_WEIGHT,
                'DOCTOR_ORDER_DEFAULT'            => $DOCTOR_ORDER_DEFAULT,
                'OTHER_SIGN'                      => $OTHER_SIGN,
                'PREPARED_BY_ID'                  => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
                'HD_FACILITY_REP_ID'              => $HD_FACILITY_REP_ID > 0 ? $HD_FACILITY_REP_ID : null,
                'ITEMIZED_BASE'                   => $ITEMIZED_BASE,
                'PHIC_INCHARGE2_ID'               => $PHIC_INCHARGE2_ID,
                'LEAVE_BLANK_AG_ADMIN_OFFICE_FEE' => $LEAVE_BLANK_AG_ADMIN_OFFICE_FEE,
                'PF_TAX_ID'                       => $PF_TAX_ID,
                'HCI_MANAGER_TREATMENT_ID'        => $HCI_MANAGER_TREATMENT_ID > 0 ? $HCI_MANAGER_TREATMENT_ID : null,

            ]);
    }

    public function Delete(int $LOCATION_ID): void
    {
        Locations::where('ID', $LOCATION_ID)->delete();
    }
    public function Search($search)
    {
        $result = Locations::query()
            ->select(
                [
                    'location.ID',
                    'location.NAME',
                    'location.INACTIVE',
                    'location.PRICE_LEVEL_ID',
                    'location.GROUP_ID',
                    'price_level.DESCRIPTION as PRICE_LEVEL',
                    'item_group.DESCRIPTION as ITEM_GROUP',
                ]
            )
            ->leftJoin('price_level', 'price_level.ID', '=', 'location.PRICE_LEVEL_ID')
            ->leftJoin('item_group', 'item_group.ID', '=', 'location.GROUP_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('location.NAME', 'like', '%' . $search . '%');
            })
            ->orderBy('location.ID', 'desc')
            ->get();

        return $result;
    }
    public function UpdateHDFacifilityRep(int $ID, int $HD_FACILITY_REP_ID)
    {
        Locations::where('ID', '=', $ID)
            ->update([
                'HD_FACILITY_REP_ID' => $HD_FACILITY_REP_ID > 0 ? $HD_FACILITY_REP_ID : null,
            ]);
    }
    public function UpdatePhicIncharge(int $ID, int $PHIC_INCHARGE_ID)
    {
        Locations::where('ID', '=', $ID)
            ->update([
                'PHIC_INCHARGE2_ID' => $PHIC_INCHARGE_ID > 0 ? $PHIC_INCHARGE_ID : null,
            ]);
    }
    public function AllowedFixLocation(int $LOCATION_ID, array $ALLOWED_LOCATIONS = []): bool
    {
        if (in_array((int) $LOCATION_ID, $ALLOWED_LOCATIONS)) {
            // These locations are allowed to be fixed
            return true;
        }
        // how will allowed --- IGNORE ---
        return false;
    }
}
