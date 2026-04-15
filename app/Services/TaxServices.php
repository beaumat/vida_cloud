<?php

namespace App\Services;

use App\Models\Tax;

class TaxServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getRate(int $ID): float
    {
        $result =  Tax::where('ID', $ID)->first();
        if($result ) {
            return (float) $result->RATE;
        }
        return 0;
    }
    public function get(int $ID)
    {

        $tax = Tax::where('ID', $ID)->first();
        if ($tax) {
            return $tax;
        }

        return null;
    }
    public function getByType(int $ID): int
    {
        return Tax::where('TAX_TYPE', $ID)->first()->ID;
    }
    public function getWTax()
    {
        $result = Tax::query()->select(['ID', 'NAME'])->where('TAX_TYPE', 2)->where('INACTIVE', '=', false)->get();
        return  $result;
    }
    public function getList()
    {
        $result = Tax::query()->select(['ID', 'NAME'])->where('TAX_TYPE', 3)->get();
        return  $result;
    }
    public function Store(string $NAME, int $TAX_TYPE, float $RATE, int $VAT_METHOD, int $TAX_ACCOUNT_ID, int $ASSET_ACCOUNT_ID, bool $INACTIVE): int
    {
        $ID = $this->object->ObjectNextID('TAX');
        Tax::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'TAX_TYPE' => $TAX_TYPE,
            'RATE' => $RATE,
            'VAT_METHOD' => $VAT_METHOD > 0 ? $VAT_METHOD : 0,
            'TAX_ACCOUNT_ID' => $TAX_ACCOUNT_ID > 0 ? $TAX_ACCOUNT_ID : null,
            'ASSET_ACCOUNT_ID' => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'INACTIVE' => $INACTIVE
        ]);
        return $ID;
    }
    public function Update(int $ID, string $NAME, int $TAX_TYPE, float $RATE, int $VAT_METHOD, int $TAX_ACCOUNT_ID, int $ASSET_ACCOUNT_ID, bool $INACTIVE): void
    {
        Tax::where('ID', $ID)->update([
            'NAME' => $NAME,
            'TAX_TYPE' => $TAX_TYPE,
            'RATE' => $RATE,
            'VAT_METHOD' => $VAT_METHOD > 0 ? $VAT_METHOD : 0,
            'TAX_ACCOUNT_ID' => $TAX_ACCOUNT_ID > 0 ? $TAX_ACCOUNT_ID : null,
            'ASSET_ACCOUNT_ID' => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'INACTIVE' => $INACTIVE
        ]);
    }
    public function Delete(int $ID): void
    {
        Tax::where('ID', $ID)->delete();
    }
    public function Search($search)
    {

        return Tax::query()
            ->select([
                'tax.ID',
                'tax.NAME',
                't.DESCRIPTION as TYPE',
                'tax.RATE'
            ])
            ->join('tax_type_map as t', 't.ID', '=', 'tax.TAX_TYPE')
            ->when($search, function ($query) use (&$search) {
                $query->where('NAME', 'like', '%' . $search . '%')
                    ->orWhere('DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->orderBy('tax.ID', 'desc')
            ->get();
    }

}
