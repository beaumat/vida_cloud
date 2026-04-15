<?php

namespace App\Services;

use App\Models\HemodialysisMachines;

class HemodialysisMachineServices
{
    private $object;

    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function get(int $ID)
    {
        return HemodialysisMachines::where('ID', $ID)->first();
    }
    public function Store(string $CODE, int $TYPE, string $DESCRIPTION, int $LOCATION_ID, int $CAPACITY): int
    {
        $ID = $this->object->ObjectNextID('HEMODIALYSIS_MACHINE');

        HemodialysisMachines::create([
            'ID'            => $ID,
            'CODE'          => $CODE,
            'TYPE'          => $TYPE,
            'DESCRIPTION'   => $DESCRIPTION,
            'LOCATION_ID'   => $LOCATION_ID,
            'CAPACITY'      => $CAPACITY
        ]);

        return $ID;
    }
    public function Update(int $ID, string $CODE, int $TYPE, string $DESCRIPTION, int $LOCATION_ID, int $CAPACITY)
    {

        HemodialysisMachines::where('ID', $ID)
            ->update([
                'CODE'          => $CODE,
                'TYPE'          => $TYPE,
                'DESCRIPTION'   => $DESCRIPTION,
                'LOCATION_ID'   => $LOCATION_ID,
                'CAPACITY'      => $CAPACITY
            ]);
    }
    public function Delete(int $ID)
    {
        HemodialysisMachines::where('ID', $ID)->delete();
    }
    public function GetList(int $LOCATION_ID)
    {
        return HemodialysisMachines::where('LOCATION_ID', $LOCATION_ID)->limit(50)->get();
    }
    public function GetCapacity(int $HEMO_ID): int
    {
        return (int) HemodialysisMachines::where('ID', $HEMO_ID)->first()->CAPACITY;
    }
    public function Search($search)
    {
        $result = HemodialysisMachines::query()
            ->select([
                'hemodialysis_machine.ID',
                'hemodialysis_machine.CODE',
                'hemodialysis_machine.DESCRIPTION',
                'machine_type.DESCRIPTION as TYPE',
                'location.NAME as LOCATION',
                'hemodialysis_machine.CAPACITY'
            ])
            ->join('machine_type', 'machine_type.ID', '=', 'hemodialysis_machine.TYPE')
            ->join('location', 'location.ID', '=', 'hemodialysis_machine.LOCATION_ID')
            ->where('hemodialysis_machine.CODE', 'like', '%' . $search . '%')
            ->orWhere('hemodialysis_machine.DESCRIPTION', 'like', '%' . $search . '%')
            ->get();

        if ($result) {
            return $result;
        }

        return [];
    }
    public function getDefaultByLocation(int $LOCATION_ID)
    {
        $result = HemodialysisMachines::where('LOCATION_ID', $LOCATION_ID)->first();
        if ($result) {
            return  $result->ID;
        }
        return 0;
    }
}
