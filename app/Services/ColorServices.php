<?php

namespace App\Services;

class ColorServices
{

    public function getColorClass(int $batch_id): string
    {

        switch ($batch_id) {
            case 1:
                return "bg-white text-dark";
            case 2:
                return "bg-yellow text-dark";
            case 3:
                return "bg-orange text-white";
            default:
                # code...
                return "";
        }
    }


    public function getPrintClass(int $batch_id): string
    {
        switch ($batch_id) {
            case 1:
                return "";
            case 2:
                return "bgYellow";
            case 3:
                return "bgOrange";
            default:
                # code...
                return "";
        }
    }

    
}
