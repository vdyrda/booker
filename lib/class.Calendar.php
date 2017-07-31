<?php
class Calendar {
    public $year;
    public $month;
    public $days_in_month;
    public $prev_year;
    public $prev_month;
    public $next_year;
    public $next_month;
    public $this_year;
    public $this_month;
    public $month_start; // the first day of the working period 
    public $week_days = [];
    
    function __construct($year, $month) {
       // get the current month and year 
        $this->this_year = (int) strftime("%Y");
        $this->this_month  = (int) strftime("%m");
        $this->set_period($year, $month);
        if (empty($this->week_days)) {
            $m = '2017-01-'; 
            $d = WEEK_FIRST_DAY == "Sunday" ? 1 : 2;  // 2017-01-01 is Sunday
            for ($i=0; $i<7; $i++) {
                 $this->week_days[] = strftime('%A', strtotime($m.$d++));
            }
        }
    }
    
    /**
     *  Set working time period parameters
     * @param int $y
     * @param int $m
     */
    public function set_period($y, $m) {
        // 3. fix wrong values 
        if ( ($y < $this-year) || ($m < 1) || ($m > 12)) {
            $this->year= $this->this_year;
            $this->month= $this->this_month;
        } else {
            $this->year = $y;
            $this->month = $m;
        }
        // Get  the previous period
        if ($this->month == 1) {
            $this->prev_month = 12;
            $this->prev_year = $this->year - 1;
        } else {
            $this->prev_month = $this->month - 1;
            $this->prev_year = $this->year;
        }
        // Get  the next period
        if ($this->month == 12) {
            $this->next_month = 1;
            $this->next_year = $this->year + 1;
        } else {
            $this->next_month = $this->month + 1;
            $this->next_year = $this->year;
        }
        $this->month_start = strtotime($this->year."-".$this->month."-01 00:00:00"); 
        $this->days_in_month = strftime('%d', strtotime($this->next_year."-".$this->next_month."-01 00:00:00")-1);
    }
    
    /**
     *  Get a calendar for working period 
     * @return array : array of strings (days of week), array of rows (days of month), string of the ready html code with the table
     */
    public function getTable() {
        $td = [];
        
        $td_row = 0;
        $days = (int) strftime('%w', $this->month_start) - (WEEK_FIRST_DAY == "Sunday" ? 0 : 1);
        for ($i=0; $i<$days; $i++) {
            $td[$td_row] [] = '';
        }
        for ($day=1; $day <= $this->days_in_month; $day++) {
            if (++$days > 7) {
                $days = 1;
                $td_row++;
            }
            $td[$td_row] [] = $day;
        }
        return [$this->week_days, $td];
    }

    /**
     *  Get array of  months
     * @return array
     */
    public static function getMonths() {
        $output = [];
        for ($i=1; $i<=12; $i++) {
            $output[$i] = strftime("%B", strtotime("2017-{$i}-01"));
        }
        return $output;
    }
    
    /**
     * Get array of all the dates of repeated event
     * @param int $start
     * @param int $end
     * @param int $frequency
     * @param int $duration
     * @return array
     */
    public static function get_repeated($start, $end, $frequency, $duration) {
        $dates = [];
        if ($frequency == REPEAT_WEEKLY || $frequency == REPEAT_BIWEEKLY) {
            $period = 60*60*24*7*$frequency;
            for ($i=0; $i<$duration; $i++) {
                $dates[] = ['start'=>$start+$i*$period, 'end'=>$end+$i*$period];
            }
        } elseif ($frequency == REPEAT_MONTHLY) {
            $weeks4 = 60*60*24*7*4;
            $weeks5 = 60*60*24*7*5;
            $start_day = (int) strftime("%d", $start);
            $i = 0;            
            do {
                $dates[] = ["start"=>$start, "end"=>$end];
                $diff5 = abs($start_day - (int) strftime('%d', $start+$weeks5));
                if ($diff5<7) {
                    $start += $weeks5;
                    $end += $weeks5;
                } else {
                    $start += $weeks4;
                    $end += $weeks4;
                }
            } while (++$i < $duration);
        }
        return $dates;
    }
    
}
