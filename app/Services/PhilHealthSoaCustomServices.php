<?php

namespace App\Services;

use App\Models\PhilHealthSoaCustom;
use App\Models\PhilHealthSoaCustomRequired;
use Illuminate\Support\Facades\DB;

class PhilHealthSoaCustomServices
{

    private $objectServices;
    public function __construct(ObjectServices $objectServices)
    {
        $this->objectServices = $objectServices;
    }

    public function Get(int $ID, int $LOCATION_ID)
    {
        $result =  PhilHealthSoaCustom::where('ID', $ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->first();

        if ($result) {
            return $result;
        }
        return [];
    }
    public function GetFirst(int $LOCATION_ID)
    {
        $result =  PhilHealthSoaCustom::where('LOCATION_ID', '=', $LOCATION_ID)
            ->first();

        if ($result) {
            return $result;
        }
        return [];
    }
    public function List($search, int $LOCATION_ID)
    {
        $result = PhilHealthSoaCustom::query()
            ->select([
                'philhealth_soa_custom.ID',
                'philhealth_soa_custom.LOCATION_ID',
                'philhealth_soa_custom.DESCRIPTION',
                'philhealth_soa_custom.DRUG_MED',
                'philhealth_soa_custom.LAB_DIAG',
                'philhealth_soa_custom.OPERATING_ROOM_FEE',
                'philhealth_soa_custom.SUPPLIES',
                'philhealth_soa_custom.ADMIN_OTHER_FEE',
                'philhealth_soa_custom.DRUG_MED_PK',
                'philhealth_soa_custom.LAB_DIAG_PK',
                'philhealth_soa_custom.OPERATING_ROOM_FEE_PK',
                'philhealth_soa_custom.SUPPLIES_PK',
                'philhealth_soa_custom.ADMIN_OTHER_FEE_PK',
                'philhealth_soa_custom.INACTIVE',
                'philhealth_soa_custom.ACTUAL_FEE',
                'philhealth_soa_custom.HIDE_FEE',
                'l.NAME as LOCATION'

            ])
            ->join('location as l', 'l.ID', '=', 'LOCATION_ID')
            ->where('DESCRIPTION', 'like', '%' . $search . '%')
            ->where('LOCATION_ID', $LOCATION_ID)
            ->get();

        return $result;
    }
    public function Store(
        int $LOCATION_ID,
        string $DESCRIPTION,
        float $DRUG_MED,
        float $LAB_DIAG,
        float $OPERATING_ROOM_FEE,
        float $SUPPLIES,
        float $ADMIN_OTHER_FEE,
        float $DRUG_MED_PK,
        float $LAB_DIAG_PK,
        float $OPERATING_ROOM_FEE_PK,
        float $SUPPLIES_PK,
        float $ADMIN_OTHER_FEE_PK,
        bool $INACTIVE = false,
        float $ACTUAL_FEE,
        float $HIDE_FEE
    ): int {
        $ID = $this->objectServices->ObjectNextID('PHILHEALTH_SOA_CUSTOM');

        PhilHealthSoaCustom::create([
            'ID'                    => $ID,
            'LOCATION_ID'           => $LOCATION_ID,
            'DESCRIPTION'           => $DESCRIPTION,
            'DRUG_MED'              => $DRUG_MED,
            'LAB_DIAG'              => $LAB_DIAG,
            'OPERATING_ROOM_FEE'    => $OPERATING_ROOM_FEE,
            'SUPPLIES'              => $SUPPLIES,
            'ADMIN_OTHER_FEE'       => $ADMIN_OTHER_FEE,
            'DRUG_MED_PK'           => $DRUG_MED_PK,
            'LAB_DIAG_PK'           => $LAB_DIAG_PK,
            'OPERATING_ROOM_FEE_PK' => $OPERATING_ROOM_FEE_PK,
            'SUPPLIES_PK'           => $SUPPLIES_PK,
            'ADMIN_OTHER_FEE_PK'    => $ADMIN_OTHER_FEE_PK,
            'INACTIVE'              => $INACTIVE,
            'ACTUAL_FEE'            => $ACTUAL_FEE,
            'HIDE_FEE'              => $HIDE_FEE
        ]);

        return $ID;
    }
    public function Update(
        int $ID,
        int $LOCATION_ID,
        string $DESCRIPTION,
        float $DRUG_MED,
        float $LAB_DIAG,
        float $OPERATING_ROOM_FEE,
        float $SUPPLIES,
        float $ADMIN_OTHER_FEE,
        float $DRUG_MED_PK,
        float $LAB_DIAG_PK,
        float $OPERATING_ROOM_FEE_PK,
        float $SUPPLIES_PK,
        float $ADMIN_OTHER_FEE_PK,
        bool $INACTIVE = false,
        float $ACTUAL_FEE,
        float $HIDE_FEE
    ) {
        PhilHealthSoaCustom::where('ID', $ID)
            ->update([
                'LOCATION_ID'           => $LOCATION_ID,
                'DESCRIPTION'           => $DESCRIPTION,
                'DRUG_MED'              => $DRUG_MED,
                'LAB_DIAG'              => $LAB_DIAG,
                'OPERATING_ROOM_FEE'    => $OPERATING_ROOM_FEE,
                'SUPPLIES'              => $SUPPLIES,
                'ADMIN_OTHER_FEE'       => $ADMIN_OTHER_FEE,
                'DRUG_MED_PK'           => $DRUG_MED_PK,
                'LAB_DIAG_PK'           => $LAB_DIAG_PK,
                'OPERATING_ROOM_FEE_PK' => $OPERATING_ROOM_FEE_PK,
                'SUPPLIES_PK'           => $SUPPLIES_PK,
                'ADMIN_OTHER_FEE_PK'    => $ADMIN_OTHER_FEE_PK,
                'INACTIVE'              => $INACTIVE,
                'ACTUAL_FEE'            => $ACTUAL_FEE,
                'HIDE_FEE'              => $HIDE_FEE
            ]);
    }

    public function Delete(int $ID)
    {
        PhilHealthSoaCustomRequired::where('SOA_CUSTOM_ID', $ID)->delete();
        PhilHealthSoaCustom::where('ID', $ID)->delete();
    }
    public function ItemExist(int $SOA_CUSTOM_ID, int $ITEM_ID): bool
    {
        return   PhilHealthSoaCustomRequired::where('SOA_CUSTOM_ID', $SOA_CUSTOM_ID)
        ->where('ITEM_ID', $ITEM_ID)
        ->exists();
    }
    public function ItemStore(int $SOA_CUSTOM_ID, int $ITEM_ID)
    {


        $ID = $this->objectServices->ObjectNextID('PHILHEALTH_SOA_CUSTOM_REQUIRED');

        PhilHealthSoaCustomRequired::create([
            'ID'            => $ID,
            'SOA_CUSTOM_ID' => $SOA_CUSTOM_ID,
            'ITEM_ID'       => $ITEM_ID
        ]);
    }
    public function UpdateStore(int $ID, int $ITEM_ID)
    {
        PhilHealthSoaCustomRequired::where('ID', $ID)->update(['ITEM_ID' => $ITEM_ID]);
    }
    public function DeleteStore(int $ID)
    {
        PhilHealthSoaCustomRequired::where('ID', $ID)->delete();
    }

    public function GetList(int $SOA_CUSTOM_ID)
    {
        $result = PhilHealthSoaCustomRequired::query()
            ->select([
                'philhealth_soa_custom_required.ID',
                'philhealth_soa_custom_required.ITEM_ID',
                'i.DESCRIPTION'
            ])
            ->join('item as i', 'i.ID', '=', 'philhealth_soa_custom_required.ITEM_ID')
            ->where('philhealth_soa_custom_required.SOA_CUSTOM_ID', $SOA_CUSTOM_ID)
            ->get();

        return $result;
    }

    public function CollectionRequirements(int $LOCATION_ID, array $itemList = [])
    {

        $result = PhilHealthSoaCustom::query()
            ->select(['philhealth_soa_custom.ID'])
            ->leftJoin('philhealth_soa_custom_required', 'philhealth_soa_custom_required.SOA_CUSTOM_ID', '=', 'philhealth_soa_custom.ID')
            ->whereIn('philhealth_soa_custom_required.ITEM_ID', $itemList)
            ->where('philhealth_soa_custom.LOCATION_ID', $LOCATION_ID)
            ->where('philhealth_soa_custom.INACTIVE', false)
            ->orderBy(DB::raw('(philhealth_soa_custom.DRUG_MED + philhealth_soa_custom.LAB_DIAG + philhealth_soa_custom.OPERATING_ROOM_FEE + philhealth_soa_custom.SUPPLIES + philhealth_soa_custom.ADMIN_OTHER_FEE)'), 'desc')
            ->get();

        return $result;
    }
    public function CheckingRequirements(int $SOA_ID) {}
}
