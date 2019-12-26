<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2019-11-28
 * Time: 17:31
 */

class DatesManager
{
    const EVEN_DAYS = 30;
    const ODD_DAYS = 31;
    const FEB_COMMON = 28;
    const FEB_LEAP = 29;
    const FEB_MEAN = 28.25;
    const YEAR_DAYS_MEAN = 365.25;
    const DAYS_IN_WEEK = 7;
    const WORKING_DAYS = 5;
    const FEB_MONTH = 2;
    const LEAP_YEAR = 4;
    const BONUS_DAY_AFTER = 10;
    const BONUS_WEEK_DAY = 3;
    const NEXT_MONTHS_QUANTITY = 12;
    const MONTHS_IN_YEAR = 12;

    private static $days = [
        self::ODD_DAYS,
        self::FEB_MEAN,
        self::ODD_DAYS,
        self::EVEN_DAYS,
        self::ODD_DAYS,
        self::EVEN_DAYS,
        self::ODD_DAYS,
        self::ODD_DAYS,
        self::EVEN_DAYS,
        self::ODD_DAYS,
        self::EVEN_DAYS,
        self::ODD_DAYS,
    ];

    private static $months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    private static $sumDays = [];
    private static $dates = [];

    public function createDates(): DatesManager
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        self::$dates[] = ['Month Name', 'Salary Date', 'Bonus Date'];
        for ($i = 0; $i < self::NEXT_MONTHS_QUANTITY; $i++) {
            $currentMonth += 1;
            if ($currentMonth > self::MONTHS_IN_YEAR) {
                $currentMonth = 1;
                $currentYear += 1;
            }

            self::$dates[] = [
                self::$months[$currentMonth - 1],
                $currentYear.'-'.self::formatMonth($currentMonth).'-'.self::getSalaryDay($currentMonth, $currentYear),
                $currentYear.'-'.self::formatMonth($currentMonth).'-'.self::getBonusDay($currentMonth, $currentYear),
            ];
        }

        return $this;
    }

    public static function getSalaryDay(int $month, int $year): int
    {
        if (empty(self::$sumDays)) {
            self::populateSumDays();
        }

        $salaryDay = floor((self::YEAR_DAYS_MEAN * ($year - 1) + self::$sumDays[$month - 1]) - 1) % self::DAYS_IN_WEEK;
        if ($salaryDay == 0) {
            $salaryDay = self::DAYS_IN_WEEK;
        }

        if ($salaryDay > self::WORKING_DAYS) {
            return self::getLastDay($month, $year) - $salaryDay + self::WORKING_DAYS;
        }

        return self::getLastDay($month, $year);
    }

    public static function getBonusDay(int $month, int $year): int
    {
        if (empty(self::$sumDays)) {
            self::populateSumDays();
        }

        $bonusDay = self::YEAR_DAYS_MEAN * ($year - 1);
        if ($month > 1) {
            $bonusDay += self::$sumDays[$month - 2];
        }
        $bonusDay += self::BONUS_DAY_AFTER;
        $bonusDay %= self::DAYS_IN_WEEK;
        if ($bonusDay == 0) {
            $bonusDay = self::DAYS_IN_WEEK;
        }

        if ($bonusDay <= self::BONUS_WEEK_DAY) {
            return 2 * self::DAYS_IN_WEEK - $bonusDay;
        }

        return 3 * self::DAYS_IN_WEEK - $bonusDay;
    }

    public function writeToCSV(): void
    {
        $counter = 0;
        $fileBaseName = 'dates_'.date('Ymd');
        $formattedName = $fileBaseName.'.csv';
        while (file_exists($formattedName)) {
            $formattedName = $fileBaseName.'('.++$counter.').csv';
        }

        $fp = fopen($formattedName, 'w');
        foreach (self::$dates as $item) {
            fputcsv($fp, $item);
        }
        fclose($fp);
    }

    private static function populateSumDays(): void
    {
        if (empty(self::$sumDays)) {
            $sum = 0;
            foreach (self::$days as $item) {
                $sum += $item;
                self::$sumDays[] = $sum;
            }
        }
    }

    private static function getLastDay(int $month, int $year): int
    {
        if ($month == self::FEB_MONTH) {
            if ($year % self::LEAP_YEAR == 0) {
                return self::FEB_LEAP;
            }
            return self::FEB_COMMON;
        }
        return self::$days[$month - 1];
    }

    private static function formatMonth(int $month): string
    {
        if ($month < 10) {
            return '0'.$month;
        }
        return $month;
    }
}