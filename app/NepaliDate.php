<?php

namespace App;

class NepaliDate
{
    public $year;
    public $month;
    public $day;
    public $session;
    public static $_bs = [
        0  => [2000, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        1  => [2001, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2  => [2002, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        3  => [2003, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        4  => [2004, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        5  => [2005, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        6  => [2006, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        7  => [2007, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        8  => [2008, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        9  => [2009, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        10 => [2010, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        11 => [2011, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        12 => [2012, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        13 => [2013, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        14 => [2014, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        15 => [2015, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        16 => [2016, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        17 => [2017, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        18 => [2018, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        19 => [2019, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        20 => [2020, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        21 => [2021, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        22 => [2022, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        23 => [2023, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        24 => [2024, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        25 => [2025, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        26 => [2026, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        27 => [2027, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        28 => [2028, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        29 => [2029, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
        30 => [2030, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        31 => [2031, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        32 => [2032, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        33 => [2033, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        34 => [2034, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        35 => [2035, 30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        36 => [2036, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        37 => [2037, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        38 => [2038, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        39 => [2039, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        40 => [2040, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        41 => [2041, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        42 => [2042, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        43 => [2043, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        44 => [2044, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        45 => [2045, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        46 => [2046, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        47 => [2047, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        48 => [2048, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        49 => [2049, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        50 => [2050, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        51 => [2051, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        52 => [2052, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        53 => [2053, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        54 => [2054, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        55 => [2055, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        56 => [2056, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
        57 => [2057, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        58 => [2058, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        59 => [2059, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        60 => [2060, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        61 => [2061, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        62 => [2062, 30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
        63 => [2063, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        64 => [2064, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        65 => [2065, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        66 => [2066, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        67 => [2067, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        68 => [2068, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        69 => [2069, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        70 => [2070, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        71 => [2071, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        72 => [2072, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        73 => [2073, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        74 => [2074, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        75 => [2075, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        76 => [2076, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        77 => [2077, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        78 => [2078, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        79 => [2079, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        80 => [2080, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        81 => [2081, 31, 31, 32, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        82 => [2082, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        83 => [2083, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30],
        84 => [2084, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30],
        85 => [2085, 31, 32, 31, 32, 30, 31, 30, 30, 29, 30, 30, 30],
        86 => [2086, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        87 => [2087, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30],
        88 => [2088, 30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30],
        89 => [2089, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        90 => [2090, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
    ];

    public static function calculateSalary($year,$month,$employee){
        if($employee->start==null && $employee->enddate==null){
            $salary= $employee->salary;;
            // dd($start,$end,$_start,$_end,$days,$extra,$salary);
            $tax=env('use_employeetax',false)?($salary*env('emp_tax',1)/100):0;
            return [$tax,$salary];
        }

        $start=$employee->start??0;

        $end=$employee->enddate??0;

        $days=self::$_bs[$year-2000][$month];
        $_start=$year*10000+$month*100+1;
        $_end=$year*10000+$month*100+$days;
        if($end==0){
            $end=$_end;
        }
        if($start==0){
            $start=$_start;
        }
        $extra=0;

        if($_end>$start){
            return [0,0]
        }

        if($_start>$end){
            // dd($start,$end,$_start,$_end,$days,$extra);

            return [0,0];
        }


        if($start>$_start){
            $extra+=$start-$_start;
        }
        if($end<$_end){
            $extra+=$_end-$end;
        }

        $salary= $extra>0?($employee->salary/$days*($days-$extra)):$employee->salary;
        // dd($start,$end,$_start,$_end,$days,$extra,$salary);
        $tax=env('use_employeetax',false)?($salary*env('emp_tax',1)/100):0;
        return [$tax,(int)$salary];
    }


    public function  getBS(){
        return self::$_bs;
    }
    public function __construct($date)
    {
        $this->year = (int)($date / 10000);
        $date = $date % 10000;
        $this->month = (int)($date / 100);
        $this->day = (int)($date % 100);
        if ($this->day < 16) {
            $this->session = 1;
        } else {
            $this->session = 2;
        }
    }

    public static function  getDateMonthLast($y, $m)
    {
        return ($y * 10000) + ($m * 100) + self::$_bs[$y - 2000][$m];
    }
    public static function getMonthDays($y, $m)
    {
        return self::$_bs[$y - 2000][$m];
    }
    public static function  getDateSessionLast($y, $m, $s)
    {
        if ($s == 1) {
            return ($y * 10000) + ($m * 100) + 15;
        } else {

            return ($y * 10000) + ($m * 100) + self::$_bs[$y - 2000][$m];
        }
    }
    public static function  getDateMonthFirst($y, $m)
    {
        return ($y * 10000) + ($m * 100) + 1;
    }

    public function prevMonth()
    {
        $m = $this->month - 1;
        $y = $this->year;
        if ($m < 1) {
            $m = 12;
            $y = $y - 1;
        }
        return [$y, $m];
    }
    public static function nextMonthStatic($y, $m)
    {
        $m = $m + 1;

        if ($m > 12) {
            $m = 1;
            $y = $y + 1;
        }
        return [$y, $m];
    }
    public static function getDate($year, $month, $session)
    {
        $data = [];
        $date = $year * 10000 + $month * 100;
        $data[1] = $date + ($session == 1 ? 1 : 16);
        $data[2] = $date + ($session == 1 ? 15 : 32);
        return $data;
    }

    public static function getDateWeek($year, $month, $week)
    {
        $data = [];
        $date = $year * 10000 + $month * 100;
        $data[1] = $date + (($week - 1) * 7) + 1;
        $data[2] = $date + ($week * 7);
        return $data;
    }

    public function prevSession()
    {
        $arr = [$this->year, $this->month, $this->session];
        $arr[2] = $this->session - 1;
        if ($arr[2] < 1) {
            $arr[1] = $this->month - 1;
            $arr[2] = 2;
            if ($arr[1] < 1) {
                $arr[0] = $this->year - 1;
                $arr[1] = 12;
            }
        }
        return $arr;
    }

    public function isPrevClosed($user_id)
    {
        if (!env('prev_check', false)) {
            return true;
        } else {
            $s = $this->prevSession();
            return \App\Models\FarmerReport::where([
                ['year', $s[0]],
                ['month', $s[1]],
                ['session', $s[2]],
                ['user_id', $user_id],
            ])->count() > 0;
        }
    }

    public static function getDateMonth($year, $month)
    {
        $data = [];
        $date = $year * 10000 + $month * 100;
        $data[1] = $date + 1;
        $data[2] = $date + self::$_bs[$year-2000][$month];
        return $data;
    }

    public static function getDateYear($year)
    {
        $data = [];
        $date = $year * 10000;
        $data[1] = $date + 101;
        $data[2] = $date + 1200+ self::$_bs[$year-2000][12];
        return $data;
    }

    public static function nextSession($year, $month, $session)
    {
        $data = [];

        $session += 1;
        if ($session > 2) {
            $session = 1;
            $month += 1;
        }

        if ($month > 12) {
            $month = 1;
            $year += 1;
        }
        return [
            'year' => $year,
            'month' => $month,
            'session' => $session,
        ];
    }

    public static function getNextDate($year, $month, $session)
    {
        $nsession = self::nextSession($year, $month, $session);
        return self::getDate(
            $nsession['year'],
            $nsession['month'],
            $nsession['session']
        )[1];
    }

    public static function isWrongDate($di)
    {
        $diarr = explode('-', $di);
        $hasErr = false;
        try {
            if (count($diarr) != 3) {
                $hasErr = true;
            } else {
                if ($diarr[0] > 2090 || $diarr[0] < 2000) {
                    $hasErr = true;
                } else {
                    if ($diarr[1] > 12 || $diarr[1] < 1) {
                        $hasErr = true;
                    } else {

                        if ($diarr[2] > NepaliDate::getMonthDays((int)$diarr[0], (int) $diarr[1]) || $diarr[2] < 1) {
                            $hasErr = true;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $hasErr=true;
        }

        return $hasErr;
    }

    public function get_nepali_month($m)
    {
        $n_month = false;
        switch ($m) {
            case 1:
                $n_month = 'बैशाख';
                break;
            case 2:
                $n_month = 'जेष्ठ';
                break;
            case 3:
                $n_month = 'असार';
                break;
            case 4:
                $n_month = 'श्रावण';
                break;
            case 5:
                $n_month = 'भाद्र';
                break;
            case 6:
                $n_month = 'आश्विन';
                break;
            case 7:
                $n_month = 'कार्तिक';
                break;
            case 8:
                $n_month = 'मंसिर';
                break;
            case 9:
                $n_month = 'पुष';
                break;
            case 10:
                $n_month = 'माघ';
                break;
            case 11:
                $n_month = 'फाल्गुन';
                break;
            case 12:
                $n_month = 'चैत्र';
                break;
        }

        return $n_month;
    }
}
