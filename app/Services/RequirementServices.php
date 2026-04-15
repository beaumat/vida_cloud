<?php
namespace App\Services;

use App\Models\Requirements;

class RequirementServices
{

    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function get($ID)
    {
        return Requirements::where('ID', $ID)->first();
    }
    public function Store(string $DESCRIPTION, bool $INACTIVE): int
    {
        $ID = (int) $this->object->ObjectNextID('REQUIREMENT');
        Requirements::create([
            'ID'          => $ID,
            'DESCRIPTION' => $DESCRIPTION,
            'INACTIVE'    => $INACTIVE,
        ]);

        return $ID;
    }
    public function Update(int $ID, string $DESCRIPTION, bool $INACTIVE)
    {
        Requirements::where('ID', $ID)->update([
            'DESCRIPTION' => $DESCRIPTION,
            'INACTIVE'    => $INACTIVE,
        ]);
    }
    public function Delete(int $ID)
    {
        Requirements::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        return Requirements::query()
            ->select([
                'ID',
                'DESCRIPTION',
                'INACTIVE',
            ])
            ->when($search, function ($query) use (&$search) {
                $query->where('DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->paginate(10);

    }

}
