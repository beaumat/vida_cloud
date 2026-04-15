<?php

namespace App\Services;

use App\Models\CostAdjustment;
use App\Models\CostAdjustmentItem;

class CostAdjustmentServices
{
    private $object;
    private $dateServices;
    private $systemSettingServices;

    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices)
    {
        $this->object = $objectServices;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function Get(int $ID)
    {
        $result = CostAdjustment::where('ID', '=', $ID)->first();
        if ($result) {

            return $result;
        }

        return null;

    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID)
    {
        $ID = $this->object->ObjectNextID("COST_ADJUSTMENT");
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('COST_ADJUSTMENT');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        CostAdjustment::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'LOCATION_ID' => $LOCATION_ID,
            'STATUS' => 0,
            'STATUS_DATE' => $this->dateServices->NowDate()
        ]);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        CostAdjustment::where('ID', $ID)
            ->update([
                'STATUS' => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate()
            ]);
    }
    public function Update(int $ID, string $CODE, string $DATE, int $LOCATION_ID)
    {
        CostAdjustment::where('ID', '=', $ID)
            ->update([
                'CODE' => $CODE,
                'DATE' => $DATE,
                'LOCATION_ID' => $LOCATION_ID,
            ]);
    }
    public function Delete(int $ID)
    {
        CostAdjustmentItem::where('COST_ADUSTMENT_ID', '=', $ID)->delete();
        CostAdjustment::where('ID', '=', $ID)->delete();
    }
    public function Search($search, int $locationId)
    {
        return CostAdjustment::query()
            ->select([
                'cost_adjustment.ID',
                'cost_adjustment.CODE',
                'cost_adjustment.DATE',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS'
            ])
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'cost_adjustment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'cost_adjustment.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('cost_adjustment.CODE', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('cost_adjustment.ID', 'desc')
            ->paginate(30);
    }
    private function getLine($Id): int
    {
        return (int) CostAdjustmentItem::where('COST_ADJUSTMENT_ID', $Id)->max('LINE_NO');
    }
    public function StoreItem(int $COST_ADJUSTMENT_ID, int $ITEM_ID, float $COST)
    {
        $ID = $this->object->ObjectNextID("COST_ADJUSTMENT_ITEM");
        $LINE_NO = (int) $this->getLine($COST_ADJUSTMENT_ID) + 1;

        CostAdjustmentItem::create([
            'ID' => $ID,
            'COST_ADJUSTMENT_ID' => $COST_ADJUSTMENT_ID,
            'LINE_NO' => $LINE_NO,
            'ITEM_ID' => $ITEM_ID,
            'COST' => $COST
        ]);
    }
    public function UpdateItem(int $ID, float $COST)
    {
        CostAdjustmentItem::where('ID', $ID)->update(['COST' => $COST]);
    }
    public function DeleteItem(int $ID)
    {
        CostAdjustmentItem::where('ID', $ID)->delete();
    }
    public function GetItem(int $ID)
    {
        $result = CostAdjustmentItem::where('ID', $ID)->first();
        if ($result) {
            return $result;
        }
        return null;
    }
    public function ItemList(int $COST_ADUSTMENT_ID)
    {
        $result = CostAdjustmentItem::query()
            ->select([
                'cost_adjustment_item.ID',
                'cost_adjustment_item.COST',
                'cost_adjustment_item.ITEM_ID',
                'i.CODE',
                'i.DESCRIPTION',
            ])
            ->join('item as i', 'i.ID', 'cost_adjustment_item.ITEM_ID')
            ->where('COST_ADJUSTMENT_ID', '=', $COST_ADUSTMENT_ID)
            ->orderBy('LINE_NO')
            ->get();

        return $result;
    }

}