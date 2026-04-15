<?php
namespace App\Services;

use App\Models\LocationReference;
use Illuminate\Support\Str;

class LocationReferenceServices
{

    public function NextReference(int $LOCATION_ID, string $TABLE_NAME, string $SYMBOL_CODE): string
    {
        try {
            $result = LocationReference::query()
                ->select([
                    'NEXT_CODE',
                    'DIGIT_CODE'
                ])
                ->where('LOCATION_ID', $LOCATION_ID)
                ->where('TABLE_NAME', $TABLE_NAME)
                ->where('SYMBOL_CODE', $SYMBOL_CODE)
                ->first();


            if ($result) {
                $code = $this->codeFormat($LOCATION_ID, $result->NEXT_CODE, $result->DIGIT_CODE, $SYMBOL_CODE);
                $newNextCode = intval($result->NEXT_CODE) + 1;

                LocationReference::where('LOCATION_ID', $LOCATION_ID)
                    ->where('TABLE_NAME', $TABLE_NAME)
                    ->where('SYMBOL_CODE', $SYMBOL_CODE)
                    ->update(['NEXT_CODE' => $newNextCode]);

                return $code;

            } else {
                return $this->store($LOCATION_ID, $TABLE_NAME, 2, 5, $SYMBOL_CODE);
            }
        } catch (\Exception $e) {
            dd($e);
        }


    }

    public function store(int $LOCATION_ID, string $TABLE_NAME, int $NEXT_CODE, int $DIGIT_CODE, string $SYMBOL_CODE): string
    {
        LocationReference::create(
            [
                'LOCATION_ID' => $LOCATION_ID,
                'TABLE_NAME' => $TABLE_NAME,
                'NEXT_CODE' => $NEXT_CODE,
                'DIGIT_CODE' => $DIGIT_CODE,
                'SYMBOL_CODE' => $SYMBOL_CODE
            ]
        );

        return $this->codeFormat($LOCATION_ID, 1, $DIGIT_CODE, $SYMBOL_CODE);
    }

    public function codeFormat(string $LOCATION_ID, int $Number, int $digit, string $symbol): string
    {
        $loc_code = Str::padLeft($LOCATION_ID, 3, '0');

        return $loc_code . '-' . $symbol . Str::padLeft($Number, $digit, '0');
    }
}