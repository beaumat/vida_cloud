<?php
namespace App\Services;

use App\Models\ShipVia;

class ShipViaServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getFirst()
    {
        try {
            return ShipVia::first()->ID;
        } catch (\Throwable $th) {
            return 0;
        }


    }
    public function getList(): object
    {
        return ShipVia::all();
    }
    public function Store(string $CODE, string $DESCRIPTION): int
    {
        $ID = $this->object->ObjectNextID('SHIP_VIA');

        ShipVia::create([
            'ID' => $ID,
            'CODE' => $CODE,
            'DESCRIPTION' => $DESCRIPTION
        ]);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION): void
    {

        ShipVia::where('ID', $ID)->update([
            'CODE' => $CODE,
            'DESCRIPTION' => $DESCRIPTION
        ]);
    }

    public function Delete(int $ID): void
    {
        ShipVia::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        if (!$search) {
            return ShipVia::orderBy('ID', 'desc')->get();
        } else {
            return ShipVia::where('CODE', 'like', '%' . $search . '%')
                ->orWhere('DESCRIPTION', 'like', '%' . $search . '%')
                ->orderBy('ID', 'desc')
                ->get();
        }
    }
}