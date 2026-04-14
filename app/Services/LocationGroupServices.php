<?php
namespace App\Services;
use App\Models\LocationGroup;

class LocationGroupServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function Store(string $NAME, bool $INACTIVE): int
    {
        $ID = $this->object->ObjectNextID('LOCATION_GROUP');
        LocationGroup::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'INACTIVE' => $INACTIVE
        ]);

        return $ID;
    }

    public function Update(int $ID,string $NAME, bool $INACTIVE): void
    {

        LocationGroup::where('ID', $ID)->update([
            'NAME' => $NAME,
            'INACTIVE' => $INACTIVE
        ]);
    }

    public function Delete(int $ID): void
    {
        LocationGroup::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        return LocationGroup::query()
            ->select(
                [
                    'ID',
                    'NAME',
                    'INACTIVE'
                ]
            )
            ->when($search, function ($query) use (&$search) {
                $query->where('NAME', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->get();
    }
}