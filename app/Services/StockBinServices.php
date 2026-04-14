<?php

namespace App\Services;

use App\Models\StockBin;

class StockBinServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getList()
    {
        return StockBin::query()->select(['ID', 'DESCRIPTION'])->get();
    }
    public function Store(string $CODE, string $DESCRIPTION): int
    {
        $ID = $this->object->ObjectNextID('STOCK_BIN');

        StockBin::create([
            'ID' => $ID,
            'CODE' => $CODE,
            'DESCRIPTION' => $DESCRIPTION
        ]);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION): void
    {

        StockBin::where('ID', $ID)->update([
            'CODE' => $CODE,
            'DESCRIPTION' => $DESCRIPTION
        ]);
    }

    public function Delete(int $ID): void
    {
        StockBin::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        if (!$search) {
            return StockBin::orderBy('ID', 'desc')->get();
        } else {
            return StockBin::where('CODE', 'like', '%' . $search . '%')
                ->orWhere('DESCRIPTION', 'like', '%' . $search . '%')
                ->orderBy('ID', 'desc')
                ->get();
        }
    }
}
