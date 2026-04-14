<?php
namespace App\Services;

use App\Models\ItemSoa;
use App\Models\ItemSoaItemized;
use App\Models\ServiceChargesItems;
use Illuminate\Support\Facades\DB;

class ItemSoaItemizedServices
{

    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function itemExist(int $ITEM_ID, int $SOA_ITEM_ID): bool
    {
        return ItemSoaItemized::where('ITEM_ID', '=', $ITEM_ID)->where('SOA_ITEM_ID', '=', $SOA_ITEM_ID)->exists();
    }
    public function Store(int $ITEM_ID, int $SOA_ITEM_ID)
    {

        $ID = (int) $this->object->ObjectNextID('ITEM_SOA_ITEMIZED');

        ItemSoaItemized::create(
            [
                'ID' => $ID,
                'ITEM_ID' => $ITEM_ID,
                'SOA_ITEM_ID' => $SOA_ITEM_ID,
                'INACTIVE' => false,
            ]
        );

        return $ID;
    }
    public function Update(int $ID, int $ITEM_ID, int $SOA_ITEM_ID, bool $INACTIVE)
    {

        ItemSoaItemized::where('ID', '=', $ID)->update(
            [
                'ITEM_ID' => $ITEM_ID,
                'SOA_ITEM_ID' => $SOA_ITEM_ID,
                'INACTIVE' => $INACTIVE,
            ]
        );
    }
    public function Delete(int $ID)
    {
        ItemSoaItemized::where('ID', '=', $ID)->delete();
    }
    public function Get(int $ID)
    {
        $result = ItemSoaItemized::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }
        return null;
    }
    public function list(int $SOA_ITEM_ID)
    {

        $result = ItemSoaItemized::query()
            ->select([
                'item_soa_itemized.ID',
                'item.CODE',
                'item.DESCRIPTION',
                'item_soa_itemized.INACTIVE',
            ])->join('item', 'item.ID', '=', 'item_soa_itemized.ITEM_ID')
            ->where('item_soa_itemized.SOA_ITEM_ID', '=', $SOA_ITEM_ID)
            ->get();

        return $result;
    }
    public function getList(int $SOA_ITEM_ID)
    {
        $result = ItemSoaItemized::query()->where('SOA_ITEM_ID', '=', $SOA_ITEM_ID)->get();
        return $result;
    }
    public static function getQuantityActual($dates = [], int $locationid, int $patientid, int $SOA_ITEM_ID): int
    {

        $result = (int) ServiceChargesItems::join('item_soa_itemized as i', 'i.ITEM_ID', '=', 'service_charges_items.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->where('i.SOA_ITEM_ID', '=', $SOA_ITEM_ID)
            ->whereIn('sc.DATE', $dates)
            ->where('sc.LOCATION_ID', '=', $locationid)
            ->where('sc.PATIENT_ID', '=', $patientid)
            ->sum('service_charges_items.QUANTITY') ?? 0;

        return $result;
    }
    public function isExistThatDay($date, int $locationid, int $patientid, int $SOA_ITEM_ID): bool
    {

        $result = (bool) ServiceChargesItems::join('item_soa_itemized as i', 'i.ITEM_ID', '=', 'service_charges_items.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->where('i.SOA_ITEM_ID', '=', $SOA_ITEM_ID)
            ->where('sc.DATE', '=', $date)
            ->where('sc.LOCATION_ID', '=', $locationid)
            ->where('sc.PATIENT_ID', '=', $patientid)
            ->exists();

        return $result;
    }
    public static function getItemActual($dates = [], int $locationid, int $patientid, int $SOA_ITEM_ID)
    {

        $result = ServiceChargesItems::query()
            ->select([
                DB::raw('SUM(service_charges_items.QUANTITY) as QUANTITY'),
                'service_charges_items.ITEM_ID',
                'item.DESCRIPTION as ITEM_DESCRIPTION',
            ])
            ->join('item_soa_itemized as i', 'i.ITEM_ID', '=', 'service_charges_items.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->join('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->where('i.SOA_ITEM_ID', '=', $SOA_ITEM_ID)
            ->whereIn('sc.DATE', $dates)
            ->where('sc.LOCATION_ID', '=', $locationid)
            ->where('sc.PATIENT_ID', '=', $patientid)
            ->groupBy(['service_charges_items.ITEM_ID', 'item.DESCRIPTION'])
            ->first();

        if ($result) {
            return $result;
        } else {
            return null;
        }

    }
    private function getQty(string $DATE_ADMITTED, string $DATE_DISCHARGED, int $locationid, int $patientid, int $SOA_ITEM_ID)
    {
        $result = (int) ServiceChargesItems::join('item_soa_itemized as i', 'i.ITEM_ID', '=', 'service_charges_items.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'service_charges_items.SERVICE_CHARGES_ID')
            ->where('i.SOA_ITEM_ID', '=', $SOA_ITEM_ID)
            ->whereBetween('sc.DATE', [$DATE_ADMITTED, $DATE_DISCHARGED])
            ->where('sc.LOCATION_ID', '=', $locationid)
            ->where('sc.PATIENT_ID', '=', $patientid)
            ->sum('service_charges_items.QUANTITY') ?? 0;

        return $result;
    }
    public function getSumByOnActualQty(int $locationid, int $patientid, int $SOA_TYPE, string $DATE_ADMITTED, string $DATE_DISCHARGED)
    {
        $TOTAL = 0;

        $dataList = ItemSoa::where('TYPE', '=', $SOA_TYPE)
            ->where('LOCATION_ID', '=', $locationid)
            ->where('ACTUAL_BASE', '=', true)
            ->where('INACTIVE', '=', false)
            ->get();

        foreach ($dataList as $list) {

            $QTY = $this->getQty($DATE_ADMITTED, $DATE_DISCHARGED, $locationid, $patientid, $list->ID);
            $AMOUNT = $QTY * $list->RATE ?? 0;
            $TOTAL = $TOTAL + $AMOUNT;
        }

        return $TOTAL;
    }

    public function getSumByOnActualQtyA(int $locationid, int $patientid, int $SOA_TYPE, string $DATE_ADMITTED, string $DATE_DISCHARGED, int $QTY_SET)
    {
        $TOTAL = 0;

        $dataList = ItemSoa::where('TYPE', '=', $SOA_TYPE)
            ->where('LOCATION_ID', '=', $locationid)
            ->where('ITEM_CONTROL_A', '=', true)
            ->where('INACTIVE', '=', false)
            ->get();

        $QTY = 0;

        foreach ($dataList as $list) {

            $QTY = $this->getQty($DATE_ADMITTED, $DATE_DISCHARGED, $locationid, $patientid, $list->ID);
           
            for ($i = 1; $i <= $QTY_SET; $i++) {
                if ($i <= $QTY) {
                    // nothing
                } else {
                    $AMOUNT = 1 * $list->RATE ?? 0;
                    $TOTAL = $TOTAL - $AMOUNT;
                }
            }
        }

        return $TOTAL;
    }
    public function getIsHaveItemControlB(int $locationid, int $SOA_TYPE, float $A_AMOUNT)
    {


        $result = (bool) ItemSoa::where('TYPE', '=', $SOA_TYPE)
            ->where('LOCATION_ID', '=', $locationid)
            ->where('ITEM_CONTROL_B', '=', true)
            ->where('INACTIVE', '=', false)
            ->exists();

        if ($result) {

            if ($A_AMOUNT < 0) {
                return $A_AMOUNT * -1;
            }
        } else {
            0;
        }

    }
}
