<?php

namespace App\Services;

class OtherServices
{


    public function formatSpecialDate($dateString)
    {
        // Convert the date string to a timestamp
        $timestamp = strtotime($dateString);

        // Extract the day, month, and year
        $day = date('j', $timestamp);
        $month = date('F', $timestamp);
        $year = date('Y', $timestamp);

        // Determine the ordinal suffix for the day
        $suffix = 'th';
        if (!in_array(($day % 100), [11, 12, 13])) {
            switch ($day % 10) {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
            }
        }

        // Format the final string
        return "Done this {$day}{$suffix} day of {$month} {$year}.";
    }
    public static function formatDays($dateString)
    {
        // Split the input string by comma and trim each date
        $dates = array_map('trim', explode(',', $dateString));

        // Extract the day from each date
        $days = array_map(function ($date) {
            return date('d', strtotime($date));
        }, $dates);

        // Join the days with a comma and space
        return implode(', ', $days);
    }
    public static function formatDates($dateString)
    {
        // Split the input string by comma and trim each date
        $dates = array_map('trim', explode(',', $dateString));

        // Parse the dates and group by month and year
        $formattedDates = [];
        foreach ($dates as $date) {
            $timestamp = strtotime($date);
            $year = date('Y', $timestamp);
            $month = date('M', $timestamp);
            $day = date('d', $timestamp);

            // Group days by month and year
            $formattedDates[$year][$month][] = $day;
        }

        // Construct the formatted string
        $result = [];
        foreach ($formattedDates as $year => $months) {
            foreach ($months as $month => $days) {
                $result[] = $month . ' ' . implode(', ', $days) . ' ' . $year;
            }
        }

        return implode(', ', $result);
    }

    public function PhilHlealthDigitFormat(string $input): string
    {
        // Format the string
        $formatted = substr($input, 0, 2) . '-' . substr($input, 2, 9) . '-' . substr($input, 11, 1);
        return $formatted; // This will return: 1202-0500922-3

    }

    public function numberToWordWeeks($num): string
    {
        switch ($num) {
            case 1:
                return "Once";
            case 2:
                return "Twice";
            case 3:
                return "Thrice";
            case 4:
                return "Four times";
            case 5:
                return "Five times";
            case 6:
                return "Six times";
            case 7:
                return "Seven times";
            default:
                return "Number out of range";
        }
    }
}
