<?php

namespace App\Services;

class NumberServices
{
    public static function AcctFormat(float $AMOUNT)
    {
        if ($AMOUNT >= 0) {
            return  (string) number_format($AMOUNT, 2);
        } else {
            $newText = str_replace("-", "", $AMOUNT);
            return  (string) "(" . number_format($newText, 2) . ")";
        }
    }
    public function Fixed(float $AMOUNT)
    {

        return sprintf('%.2f', $AMOUNT);
    }
    public static function Decimal2Only(string $formatted): float {
        $number = (float) str_replace(',', '', $formatted);
        
        return $number;
    
    }
    public function doubleNumber($Num)
    {
        return (float) str_replace(',', '', $Num);
    }
}
