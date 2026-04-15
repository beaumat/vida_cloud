<?php

namespace App\Services;

use App\Models\Items;
use App\Models\ItemTreatment;
use App\Models\ItemTreatmentTrigger;
use Illuminate\Support\Facades\DB;

class ItemTreatmentServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Get(int $Id)
    {
        return ItemTreatment::where('ID', $Id)->first();
    }
    public function Store(
        int $LOCATION_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        int $NO_OF_USED,
        bool $IS_AUTO,
        bool $IS_REQUIRED,
        float $NEW_TREATMENT_QTY,
        bool $FIRST_TIME_AUTO_NEW,
        bool $IS_AUTO_SC,
        bool $FT_AUTO_SC
    ): int {
        $ID =  (int) $this->object->ObjectNextID('ITEM_TREATMENT');
        ItemTreatment::create([
            'ID'                    => $ID,
            'LOCATION_ID'           => $LOCATION_ID,
            'ITEM_ID'               => $ITEM_ID,
            'QUANTITY'              => $QUANTITY,
            'UNIT_ID'               => $UNIT_ID > 0 ? $UNIT_ID : null,
            'NO_OF_USED'            => $NO_OF_USED,
            'INACTIVE'              => false,
            'IS_AUTO'               => $IS_AUTO,
            'IS_REQUIRED'           => $IS_REQUIRED,
            'NEW_TREATMENT_QTY'     => $NEW_TREATMENT_QTY,
            'FIRST_TIME_AUTO_NEW'   => $FIRST_TIME_AUTO_NEW,
            'IS_AUTO_SC'            => $IS_AUTO_SC,
            'FT_AUTO_SC'            => $FT_AUTO_SC
        ]);

        return $ID;
    }

    public function Update(
        int $ID,
        int $LOCATION_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        int $NO_OF_USED,
        bool $INACTIVE,
        bool $IS_AUTO,
        bool $IS_REQUIRED,
        float $NEW_TREATMENT_QTY,
        bool $FIRST_TIME_AUTO_NEW,
        bool $IS_AUTO_SC,
        bool $FT_AUTO_SC
    ) {
        ItemTreatment::where('ID', $ID)
            ->update([
                'LOCATION_ID'           => $LOCATION_ID,
                'ITEM_ID'               => $ITEM_ID,
                'QUANTITY'              => $QUANTITY,
                'UNIT_ID'               => $UNIT_ID > 0 ? $UNIT_ID : null,
                'NO_OF_USED'            => $NO_OF_USED,
                'INACTIVE'              => $INACTIVE,
                'IS_AUTO'               => $IS_AUTO,
                'IS_REQUIRED'           => $IS_REQUIRED,
                'NEW_TREATMENT_QTY'     => $NEW_TREATMENT_QTY,
                'FIRST_TIME_AUTO_NEW'   => $FIRST_TIME_AUTO_NEW,
                'IS_AUTO_SC'            => $IS_AUTO_SC,
                'FT_AUTO_SC'            => $FT_AUTO_SC
            ]);
    }

    public function Delete(int $ID)
    {
        ItemTreatment::where('ID', $ID)->delete();
    }

    public function Search($search, $locationId)
    {
        $result = ItemTreatment::query()
            ->select([
                'item_treatment.ID',
                'l.NAME as LOCATION_NAME',
                'i.DESCRIPTION as ITEM_NAME',
                'u.SYMBOL',
                'item_treatment.NO_OF_USED',
                'item_treatment.INACTIVE',
                'item_treatment.QUANTITY',
                'item_treatment.IS_AUTO',
                'item_treatment.IS_REQUIRED',
                'item_treatment.NEW_TREATMENT_QTY',
                'item_treatment.FIRST_TIME_AUTO_NEW',
                'item_treatment.IS_AUTO_SC',
                'item_treatment.FT_AUTO_SC'
            ])
            ->join('location as l', 'l.ID', '=', 'item_treatment.LOCATION_ID')
            ->join('item as i', 'i.ID', '=', 'item_treatment.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', 'item_treatment.UNIT_ID')
            ->when($locationId, function ($query) use (&$locationId) {
                $query->where('item_treatment.LOCATION_ID', '=', $locationId);
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('l.NAME', 'like', '%' . $search . '%')
                    ->orWhere('i.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('u.NAME', 'like', '%' . $search . '%');
            })
            ->orderBy('item_treatment.ID', 'desc')
            ->get();

        return $result;
    }


    public function SearchHemo($search, int $locationId, int $hemoId)
    {
        $result = ItemTreatment::query()
            ->select([
                'item_treatment.ID',
                'l.NAME as LOCATION_NAME',
                'i.DESCRIPTION as ITEM_NAME',
                'u.SYMBOL',
                'item_treatment.NO_OF_USED',
                'item_treatment.INACTIVE'
            ])
            ->join('location as l', 'l.ID', '=', 'item_treatment.LOCATION_ID')
            ->join('item as i', 'i.ID', '=', 'item_treatment.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', 'item_treatment.UNIT_ID')
            ->when($locationId, function ($query) use (&$locationId) {
                $query->where('item_treatment.LOCATION_ID', '=', $locationId);
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('l.NAME', 'like', '%' . $search . '%')
                    ->orWhere('i.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('u.NAME', 'like', '%' . $search . '%');
            })
            ->whereNotExists(function ($query) use (&$hemoId) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis_items as hi')
                    ->whereRaw('hi.ITEM_ID = i.ID')
                    ->where('hi.HEMO_ID', $hemoId);
            })
            ->orderBy('item_treatment.ID', 'desc')
            ->get();

        return $result;
    }

    public function AutoItemList(int $locationId)
    {
        $result = ItemTreatment::query()
            ->select([
                'ID',
                'ITEM_ID',
                'QUANTITY',
                'UNIT_ID',
                'NO_OF_USED',
                'IS_AUTO_SC as IS_CASHIER'
            ])

            ->where('LOCATION_ID', $locationId)
            ->where('IS_AUTO', 1)
            ->where('QUANTITY', '>', 0)
            ->where('INACTIVE', 0)
            ->orderBy('ID', 'desc')
            ->get();

        return $result;
    }

    public function NewAutoItemList(int $locationId)
    {
        $result = ItemTreatment::query()
            ->select([
                'ID',
                'ITEM_ID',
                'NEW_TREATMENT_QTY as QUANTITY',
                'UNIT_ID',
                'NO_OF_USED',
                'FT_AUTO_SC as IS_CASHIER'
            ])
            ->where('LOCATION_ID', $locationId)
            ->where('FIRST_TIME_AUTO_NEW', 1)
            ->where('NEW_TREATMENT_QTY', '>', 0)
            ->where('INACTIVE', 0)
            ->orderBy('ID', 'desc')
            ->get();

        return $result;
    }

    public function getItemRequired(int $locationId, int $hemoId)
    {
        $result = ItemTreatment::query()
            ->select([
                'item_treatment.ID',
                'l.NAME as LOCATION_NAME',
                'i.DESCRIPTION as ITEM_NAME',
                'u.SYMBOL',
                'item_treatment.NO_OF_USED',
                'item_treatment.INACTIVE'
            ])
            ->join('location as l', 'l.ID', '=', 'item_treatment.LOCATION_ID')
            ->join('item as i', 'i.ID', '=', 'item_treatment.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', 'item_treatment.UNIT_ID')
            ->where('item_treatment.LOCATION_ID', '=', $locationId)
            ->whereNotExists(function ($query) use (&$hemoId) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis_items as hi')
                    ->whereRaw('hi.ITEM_ID = i.ID')
                    ->where('hi.HEMO_ID', $hemoId);
            })
            ->where('item_treatment.IS_REQUIRED', true)
            ->orderBy('item_treatment.ID', 'desc')
            ->get();

        return $result;
    }
    public function getRequiredSuccess(int $locationId, int $hemoId): bool
    {
        // $total_required = (int) ItemTreatment::where('IS_REQUIRED',true)->where('INACTIVE',false)->count();

        $total_exists = (int) ItemTreatment::query()
            ->join('item as i', 'i.ID', '=', 'item_treatment.ITEM_ID')
            ->where('item_treatment.LOCATION_ID', '=', $locationId)
            ->whereExists(function ($query) use (&$hemoId) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis_items as hi')
                    ->whereRaw('hi.ITEM_ID = i.ID')
                    ->where('hi.HEMO_ID', $hemoId);
            })
            ->where('item_treatment.IS_REQUIRED', true)
            ->count();


        if ($total_exists > 0) {
            return true;
        }
        return false;
    }
    public function getItemList(bool $isCode, int $locationId)
    {

        if ($isCode) {

            return Items::query()
                ->select(['item.ID', 'item.CODE'])
                ->join('item_treatment as t', 't.ITEM_ID', '=', 'item.ID')
                ->where('item.INACTIVE', '0')
                ->where('t.INACTIVE', '0')
                ->where('t.LOCATION_ID', $locationId)
                ->whereIn('item.TYPE', ['0', '1'])
                ->get();
        }

        return Items::query()
            ->select(['item.ID', 'item.DESCRIPTION'])
            ->join('item_treatment as t', 't.ITEM_ID', '=', 'item.ID')
            ->where('item.INACTIVE', '0')
            ->where('t.INACTIVE', '0')
            ->where('t.LOCATION_ID', $locationId)
            ->whereIn('item.TYPE', ['0', '1'])
            ->get();
    }

    public function storeItemTrigger(int $ITEM_TREATMENT_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID)
    {
        $ID =  (int) $this->object->ObjectNextID('ITEM_TREATMENT_TRIGGER');

        ItemTreatmentTrigger::create([
            'ID' => $ID,
            'ITEM_TREATMENT_ID' => $ITEM_TREATMENT_ID,
            'ITEM_ID' => $ITEM_ID,
            'QUANTITY' => $QUANTITY,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,

        ]);
    }

    public function deleteItemTrigger(int $ID)
    {
        ItemTreatmentTrigger::where('ID', $ID)->delete();
    }

    public function listItemTrigger(int $ITEM_TREATMENT_ID)
    {
        $result =  ItemTreatmentTrigger::query()
            ->select([
                'item_treatment_trigger.ID',
                'item_treatment_trigger.QUANTITY',
                'item_treatment_trigger.ITEM_ID',
                'item_treatment_trigger.UNIT_ID',
                'u.SYMBOL',
                'i.DESCRIPTION as ITEM_NAME'

            ])
            ->join('item as i', 'i.ID', '=', 'item_treatment_trigger.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'item_treatment_trigger.UNIT_ID')
            ->where('item_treatment_trigger.ITEM_TREATMENT_ID', $ITEM_TREATMENT_ID)
            ->get();

        return $result;
    }
    public function itemTriggerExists(int $ITEM_TREATMENT_ID): bool
    {
        return  ItemTreatmentTrigger::where('ITEM_TREATMENT_ID', $ITEM_TREATMENT_ID)->exists();
    }
    public function getItemTrigger(int $ITEM_ID, int $LOCATION_ID, int $UNIT_ID)
    {
        $result =  ItemTreatmentTrigger::query()
            ->select([
                'item_treatment_trigger.QUANTITY',
                'item_treatment_trigger.ITEM_ID',
                'item_treatment_trigger.UNIT_ID',
            ])
            ->join('item_treatment', 'item_treatment.ID', '=', 'item_treatment_trigger.ITEM_TREATMENT_ID')
            ->where('item_treatment.ITEM_ID', $ITEM_ID)
            ->where('item_treatment.LOCATION_ID', $LOCATION_ID)
            ->where('item_treatment.UNIT_ID', $UNIT_ID)
            ->get();

        return $result;
    }

    public function getItemTriggerQuantity(int $ITEM_ID, int $LOCATION_ID, int $UNIT_ID, int  $TRIGGER_ITEM_ID, int $TRIGGER_UNIT_ID): int
    {
        $result =  ItemTreatmentTrigger::query()
            ->select([
                'item_treatment_trigger.QUANTITY'
            ])
            ->join('item_treatment', 'item_treatment.ID', '=', 'item_treatment_trigger.ITEM_TREATMENT_ID')
            ->where('item_treatment.ITEM_ID', $ITEM_ID)
            ->where('item_treatment.LOCATION_ID', $LOCATION_ID)
            ->where('item_treatment.UNIT_ID', $UNIT_ID)
            ->where('item_treatment_trigger.ITEM_ID', $TRIGGER_ITEM_ID)
            ->where('item_treatment_trigger.UNIT_ID', $TRIGGER_UNIT_ID)
            ->first();




        return (int) $result->QUANTITY ?? 0;
    }

    public function getItemTreatmentID(int $ITEM_ID, int $LOCATION_ID, int $UNIT_ID): int
    {
        $result = ItemTreatment::where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('UNIT_ID', $UNIT_ID)
            ->first();

        if ($result) {
            return $result->ID;
        }

        return 0;
    }
}
