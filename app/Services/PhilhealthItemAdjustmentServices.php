<?php
namespace App\Services;

use App\Models\PhilhealthItemAdjustment;

class PhilhealthItemAdjustmentServices
{
    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }

    public function ItemAdjustStore(int $PATIENT_ID, int $LOCATION_ID, int $NO_OF_USED, int $YEAR, string $NOTES, int $NO_OF_ITEM): int
    {

        $ID = (int) $this->object->ObjectNextID('PHILHEALTH_ITEM_ADJUSTMENT');

        PhilhealthItemAdjustment::create(
            [
                'ID'          => $ID,
                'PATIENT_ID'  => $PATIENT_ID,
                'LOCATION_ID' => $LOCATION_ID,
                'NO_OF_USED'  => $NO_OF_USED,
                'YEAR'        => $YEAR,
                'NOTES'       => $NOTES,
                'NO_OF_ITEM'  => $NO_OF_ITEM,
            ]
        );

        return $ID;
    }
    public function UpdateFile(int $ID, string $FILE_NAME, string $FILE_PATH)
    {

        PhilhealthItemAdjustment::where('ID', '=', $ID)
            ->update([
                'FILE_NAME' => $FILE_NAME,
                'FILE_PATH' => $FILE_PATH,
            ]);
    }
    public function ItemAdjustUpdate(int $ID, int $YEAR, int $NO_OF_USED, string $NOTES, int $NO_OF_ITEM)
    {

        PhilhealthItemAdjustment::where('ID', '=', $ID)
            ->update(
                [
                    'YEAR'       => $YEAR,
                    'NO_OF_USED' => $NO_OF_USED,
                    'NOTES'      => $NOTES,
                    'NO_OF_ITEM' => $NO_OF_ITEM,
                ]
            );
    }
    public function ItemAdjustDelete(int $ID)
    {

        PhilhealthItemAdjustment::where('ID', '=', $ID)->delete();
    }
    public function GetItemAdjust(int $ID)
    {

        $result = PhilhealthItemAdjustment::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }

        return null;
    }
    public function ItemAdjustList(int $PATIENT_ID, int $LOCATION_ID)
    {

        $result = PhilhealthItemAdjustment::query()
            ->select(['ID', 'NO_OF_USED', 'NO_OF_ITEM', 'YEAR', 'NOTES', 'FILE_NAME', 'FILE_PATH'])
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->get();

        return $result;
    }

    public function ItemAdjustGet(int $PATIENT_ID, int $LOCATION_ID, int $YEAR): int
    {
        $result = PhilhealthItemAdjustment::query()
            ->select(['NO_OF_USED'])
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('YEAR', $YEAR)
            ->first();

        if ($result) {

            return $result->NO_OF_USED ?? 0;
        }

        return 0;
    }
    public function ItemAdjustGet1(int $PATIENT_ID, int $LOCATION_ID, int $YEAR): int
    {
        $result = PhilhealthItemAdjustment::query()
            ->select(['NO_OF_ITEM'])
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('YEAR', $YEAR)
            ->first();

        if ($result) {

            return $result->NO_OF_ITEM ?? 0;
        }

        return 0;
    }
    public function ItemTotalOther(int $PATIENT_ID, int $LOCATION_ID, int $YEAR): int
    {
        $result = PhilhealthItemAdjustment::query()
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('YEAR', $YEAR)
            ->sum('NO_OF_USED');

        if ($result) {
            return (int) $result;
        }

        return 0;
    }
    public function ItemTotalOther1(int $PATIENT_ID, int $LOCATION_ID, int $YEAR): int
    {
        $result = PhilhealthItemAdjustment::query()
            ->where('PATIENT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('YEAR', $YEAR)
            ->sum('NO_OF_ITEM');

        if ($result) {
            return (int) $result;
        }

        return 0;
    }
}
