<?php
namespace App\Services;

use Carbon\Carbon;
use DateTime;

class DateServices
{
    public function isValidDateFormat($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function GetFirstDay_Year(string $BASE_DATE): string
    {
        $date = new DateTime($BASE_DATE);
        $y    = $date->format('Y');
        return $this->getFirstDayViaYear($y);
    }
    public function dateToYear($date): int
    {
        if (empty($date)) {
            return 0;
        }
        try {
            return (int) date('Y', strtotime($date));
        } catch (\Exception $e) {
            return 0;
        }
    }
    public static function getFirstDayViaYear(int $YEAR)
    {
        return "$YEAR-01-01";
    }
    public static function getLastDayViaYear(int $YEAR)
    {
        return "$YEAR-12-31";
    }
    public function GetFirstDay_ByMonthYear(int $YEAR, int $MONTH): string
    {
        $date = new DateTime("$YEAR-$MONTH-1");
        $y    = $date->format('Y');
        $m    = $date->format('m');
        return "$y-$m-01";
    }
    public function GetFirstDay_Month(string $BASE_DATE): string
    {
        $date = new DateTime($BASE_DATE);
        $y    = $date->format('Y');
        $m    = $date->format('m');
        return "$y-$m-01";
    }
    public function GetLastDay_ByMonthYear(int $YEAR, int $MONTH): string
    {
        $date = new DateTime("$YEAR-$MONTH-1");
        $date->modify('last day of this month');
        return $date->format('Y-m-d');

    }
    public function GetLastDay_Month(string $BASE_DATE): string
    {
        $date = new DateTime($BASE_DATE);
        $date->modify('last day of this month');
        return $date->format('Y-m-d');
    }
    public function NowDate()
    {
        // '2024-10-29'
        return Carbon::now()->format('Y-m-d');
    }
    public function NowDateTime()
    {

        return Carbon::now()->format('Y-m-d H:i:s');
    }
    public function DateFormat(string $BASE_DATE)
    {
        $date = new DateTime($BASE_DATE);
        return $date->format('Y-m-d H:i:s');
    }
    public function DateFormatOnly(string $BASE_DATE)
    {
        $date = new DateTime($BASE_DATE);
        return $date->format('Y-m-d');
    }
    public function NextDate()
    {
        return Carbon::now()->addDay()->format('Y-m-d'); // Tomorrow's date
    }
    public function BackDate()
    {
        return Carbon::now()->subDay()->format('Y-m-d');
    }
    public function Now()
    {
        return Carbon::now();
    }
    public function NowMonth(): int
    {
        return Carbon::now()->month;
    }
    public function NowYear(): int
    {
        return Carbon::now()->year;
    }
    public function WeeklyList(): array
    {
        return [
            ['ID' => 1, 'NAME' => 'Monday'],
            ['ID' => 2, 'NAME' => 'Tuesday'],
            ['ID' => 3, 'NAME' => 'Wednesday'],
            ['ID' => 4, 'NAME' => 'Thursday'],
            ['ID' => 5, 'NAME' => 'Friday'],
            ['ID' => 6, 'NAME' => 'Saturday'],
            ['ID' => 7, 'NAME' => 'Sunday'],
        ];
    }

    public function WeeklyLevel(): array
    {
        return [
            ['ID' => 1, 'DESCRIPTION' => '1st week'],
            ['ID' => 2, 'DESCRIPTION' => '2nd week'],
            ['ID' => 3, 'DESCRIPTION' => '3rd week'],
            ['ID' => 4, 'DESCRIPTION' => '4th week'],
            ['ID' => 5, 'DESCRIPTION' => '5th week'],

        ];
    }
    public function Get7Days(int $yr, int $m, int $wk_selected)
    {
        $month          = $m;
        $year           = $yr;
        $selectedWeek   = $wk_selected;
        $firstDayOfWeek = date('N', strtotime("$year-$month-01"));
        $startOfWeek    = 1 - $firstDayOfWeek + 1;
        $daysInMonth    = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if ($startOfWeek > 1) {
            $month--;
            if ($month == 0) {
                $month = 12;
                $year--;
            }
            $firstDayOfWeek = date('N', strtotime("$year-$month-01"));
            $startOfWeek    = 1 - $firstDayOfWeek + 1;
        }

        $startDate = $startOfWeek + ($selectedWeek - 1) * 7;
        $endDate   = min($startDate + 6, $daysInMonth);

        $selectedDates = [];

        // Generate dates for the selected week
        for ($day = $startDate; $day <= $endDate; $day++) {
            if ($day < 1) {
                // If the day is from the previous month, calculate the day accordingly
                $prevMonth = $month - 1;
                $prevYear  = $year;
                if ($prevMonth == 0) {
                    $prevMonth = 12;
                    $prevYear--;
                }
                $selectedDates[] = sprintf('%04d-%02d-%02d', $prevYear, $prevMonth, $day + cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear));
            } elseif ($day > $daysInMonth) {
                // If the day is from the next month, calculate the day accordingly
                $nextMonth = $month + 1;
                $nextYear  = $year;
                if ($nextMonth == 13) {
                    $nextMonth = 1;
                    $nextYear++;
                }
                $selectedDates[] = sprintf('%04d-%02d-%02d', $nextYear, $nextMonth, $day - $daysInMonth);
            } else {
                $selectedDates[] = sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }

        if (count($selectedDates) < 6) {
            return [];
        }
        return $selectedDates;
    }
    public function SemiMonthly(): array
    {
        return [
            ['ID' => 1, 'NAME' => '1/16 th'],
            ['ID' => 2, 'NAME' => '2/17 th'],
            ['ID' => 3, 'NAME' => '3/18 th'],
            ['ID' => 4, 'NAME' => '4/19 th'],
            ['ID' => 5, 'NAME' => '5/20 th'],
            ['ID' => 6, 'NAME' => '6/21 th'],
            ['ID' => 7, 'NAME' => '7/22 th'],
            ['ID' => 8, 'NAME' => '8/23 th'],
            ['ID' => 9, 'NAME' => '9/24 th'],
            ['ID' => 10, 'NAME' => '10/25 th'],
            ['ID' => 11, 'NAME' => '11/26 th'],
            ['ID' => 12, 'NAME' => '12/27 th'],
            ['ID' => 13, 'NAME' => '13/28 th'],
            ['ID' => 14, 'NAME' => '14/29 th'],
            ['ID' => 15, 'NAME' => '15/30 th'],
        ];
    }

    public function DayList(): array
    {
        return [
            ['ID' => 1, 'NAME' => '1st'],
            ['ID' => 2, 'NAME' => '2nd'],
            ['ID' => 3, 'NAME' => '3rd'],
            ['ID' => 4, 'NAME' => '4th'],
            ['ID' => 5, 'NAME' => '5th'],
            ['ID' => 6, 'NAME' => '6th'],
            ['ID' => 7, 'NAME' => '7th'],
            ['ID' => 8, 'NAME' => '8th'],
            ['ID' => 9, 'NAME' => '9th'],
            ['ID' => 10, 'NAME' => '10th'],
            ['ID' => 11, 'NAME' => '11th'],
            ['ID' => 12, 'NAME' => '12th'],
            ['ID' => 13, 'NAME' => '13th'],
            ['ID' => 14, 'NAME' => '14th'],
            ['ID' => 15, 'NAME' => '15th'],
            ['ID' => 16, 'NAME' => '16th'],
            ['ID' => 17, 'NAME' => '17th'],
            ['ID' => 18, 'NAME' => '18th'],
            ['ID' => 19, 'NAME' => '19th'],
            ['ID' => 20, 'NAME' => '20th'],
            ['ID' => 21, 'NAME' => '21th'],
            ['ID' => 22, 'NAME' => '22th'],
            ['ID' => 23, 'NAME' => '23th'],
            ['ID' => 24, 'NAME' => '24th'],
            ['ID' => 25, 'NAME' => '25th'],
            ['ID' => 26, 'NAME' => '26th'],
            ['ID' => 27, 'NAME' => '27th'],
            ['ID' => 28, 'NAME' => '28th'],
            ['ID' => 29, 'NAME' => '29th'],
            ['ID' => 30, 'NAME' => '30th'],

        ];
    }
    public function SemiAnnual(): array
    {
        return [
            ['ID' => 1, 'NAME' => 'Jan/Jul'],
            ['ID' => 2, 'NAME' => 'Feb/Aug'],
            ['ID' => 3, 'NAME' => 'Mar/Sep'],
            ['ID' => 4, 'NAME' => 'Apr/Oct'],
            ['ID' => 5, 'NAME' => 'May/Nov'],
            ['ID' => 6, 'NAME' => 'Jun/Dec'],

        ];
    }
    public function MonthList(): array
    {
        return [
            ['ID' => 1, 'NAME' => 'Jan'],
            ['ID' => 2, 'NAME' => 'Feb'],
            ['ID' => 3, 'NAME' => 'Mar'],
            ['ID' => 4, 'NAME' => 'Apr'],
            ['ID' => 5, 'NAME' => 'May'],
            ['ID' => 6, 'NAME' => 'Jun'],
            ['ID' => 7, 'NAME' => 'Jul'],
            ['ID' => 8, 'NAME' => 'Aug'],
            ['ID' => 9, 'NAME' => 'Sep'],
            ['ID' => 10, 'NAME' => 'Oct'],
            ['ID' => 11, 'NAME' => 'Nov'],
            ['ID' => 12, 'NAME' => 'Dec'],

        ];
    }

    public function FullMonthList(): array
    {
        return [
            ['ID' => 1, 'NAME' => 'January'],
            ['ID' => 2, 'NAME' => 'Febuary'],
            ['ID' => 3, 'NAME' => 'March'],
            ['ID' => 4, 'NAME' => 'April'],
            ['ID' => 5, 'NAME' => 'May'],
            ['ID' => 6, 'NAME' => 'June'],
            ['ID' => 7, 'NAME' => 'July'],
            ['ID' => 8, 'NAME' => 'Auguest'],
            ['ID' => 9, 'NAME' => 'September'],
            ['ID' => 10, 'NAME' => 'October'],
            ['ID' => 11, 'NAME' => 'November'],
            ['ID' => 12, 'NAME' => 'December'],

        ];
    }
    public function YearList(): array
    {
        $currentYear = (int) date('Y');
        $years       = [];
        $years[]     = ['ID' => 0, 'NAME' => ''];
        for ($year = 2024; $year <= $currentYear; $year++) {
            $years[] = ['ID' => $year, 'NAME' => (string) $year];
        }

        return $years;
    }
    public function isFirstDayOfMonth()
    {
        $today = date('d');
        if ($today == 1) {
            return true;
        }
        return false;
    }
    public function isWholeMonth(string $startDate, string $endDate): bool
    {
        // Convert strings to DateTime objects
        $start = new DateTime($startDate);
        $end   = new DateTime($endDate);

        // Check if the start date is the first day of the month
        if ($start->format('d') != '01') {
            return false;
        }

                                        // Get the last day of the month for the start date
        $lastDay = $start->format('t'); // 't' gives the number of days in the month

        // Check if the end date matches the last day of the month
        return $end->format('Y-m-d') === $start->format("Y-m-") . $lastDay;
    }
}
