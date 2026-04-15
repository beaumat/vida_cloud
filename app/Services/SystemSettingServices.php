<?php

namespace App\Services;

use App\Models\SystemSetting;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class SystemSettingServices
{
    public function GetList()
    {
        return DB::table('system_settings')->select(['NAME', 'VALUE'])->get();

    }
    public function SetValue(string $NAME, string $VALUE)
    {
        return SystemSetting::where('NAME', $NAME)->update(['VALUE' => $VALUE]);
    }
    public function NewValue(string $NAME)
    {
        SystemSetting::create([
            'NAME' => $NAME,
            'VALUE' => ''
        ]);
    }
    public function GetValue(string $NAME): string
    {
        $result = SystemSetting::query()->select('VALUE')->where('NAME', $NAME)->limit(1);

        if ($result) {
            return $result->first()->VALUE ?? '';
        }
        return '';
    }
    public function IsCloseDate(string $selectDate): bool
    {
        try {
            // Fetch the first matching record
            $result = SystemSetting::query()
                ->select('VALUE')
                ->where('NAME', '=', 'ClosingDate')
                ->first();

            if ($result && $result->VALUE) {
                // Convert both values to Carbon dates for comparison
                $closingDate = Carbon::parse($result->VALUE);
                $inputDate = Carbon::parse($selectDate);

                if ($inputDate->lte($closingDate)) {
                    return true;
                }
            }
        } catch (\Throwable $th) {
            // You can log the error if needed
            // \Log::error($th->getMessage());
        }
        return false;
    }
    public function CloseDate(): string
    {
        $result = SystemSetting::query()
            ->select('VALUE')
            ->where('NAME', '=', 'ClosingDate')
            ->first();
            
        return $result->VALUE ?? '';
    }


}