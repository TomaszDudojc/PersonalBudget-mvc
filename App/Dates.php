<?php

namespace App;

class Dates
{
    public static function getCurrentDate()
    {
        return date("Y-m-d");
    }

    public static function getCurrentMonth()
    {
        return date('Y-m');
    }
}