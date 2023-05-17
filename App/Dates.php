<?php

namespace App;

class Dates
{  
    public static function getCurrentDate()
    {
        return date("Y-m-d");
    }
    
    public static function validateDate($period, $currentYear, $currentMonth)
    {
        $dateRange = [];

        if ($period == 'last_month'){
            $lastMonth = '';
            $year = '';

            if ($currentMonth != '01'){
                $lastMonth = (int) $currentMonth - 1;
                
                if (strlen($lastMonth) == 1){
                    $lastMonth = '0'.$lastMonth; 
                }
                $year = $currentYear;
            }
            else{
                $lastMonth = '12';
                $year =  (int) $currentYear - 1;
            }

            $endDay = static::findLastDayOfMonth($lastMonth, $year);

            $startDate = $year.'-'.$lastMonth.'-01';
            $endDate = $year.'-'.$lastMonth.'-'.$endDay;
        }

        else if ($period == 'current_year'){
            $startDate = $currentYear.'-01-01';
            $endDate = $currentYear.'-12-31'; 
        }

        else if ($period == 'custom_period'){
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date']; 
        }

        else{
            $endDay = static::findLastDayOfMonth($currentMonth, $currentYear);

            $startDate = $currentYear.'-'.$currentMonth.'-01';
            $endDate = $currentYear.'-'.$currentMonth.'-'.$endDay;
        }

        $dateRange = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        $_SESSION['start_date'] = $startDate;
        $_SESSION['end_date'] = $endDate;

        return $dateRange;
    }

    public static function findLastDayOfMonth($month, $year)
    {
        switch($month){
            case '01': case '03': case '05': case '07': case '08': case '10': case '12':
                return '31';
            case '04': case '06': case '09': case '11':
                return '30';
            case '02':
                return ($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0  ? '29' : '28';        
        }
    }
}