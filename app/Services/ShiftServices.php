<?php
namespace App\Services;

use App\Models\Shift;

class ShiftServices
{

    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function get($id)
    {
        return Shift::where('ID', $id)->first();
    }
    public function List()
    {
        return Shift::all();
    }
    public function Store(string $NAME, int $LINE_NO): int
    {
        $ID = $this->object->ObjectNextID('SHIFT');

        Shift::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'LINE_NO' => $LINE_NO
        ]);

        return $ID;
    }

    public function Update(int $ID, string $NAME, int $LINE_NO): void
    {
        Shift::where('ID', $ID)->update([
            'NAME' => $NAME,
            'LINE_NO' => $LINE_NO
        ]);
    }

    public function Delete(int $ID): void
    {
        Shift::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        return Shift::query()
            ->select(['ID', 'NAME', 'LINE_NO'])
            ->when($search, function ($query) use (&$search) {
                $query->where('NAME', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->get();
    }
}