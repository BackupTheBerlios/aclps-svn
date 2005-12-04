<?php

class Presentation_View_ViewCalendarView extends Presentation_View_View
{
    private $dateswithposts;
    private $year;
    private $blogID;
    private $month;
    private $today;
    private $linkurl;
    
    //$posts should be the array returned by GetDatesWithPostsForMonth
    public function __construct($blogID, $dateswithposts, $year, $month)
    {
        $this->today = getdate();
        $this->blogID = $blogID;
        $this->year = $year;
        $this->month = $month;
        $this->dateswithposts = $dateswithposts;
    }
    
    public function Display()
    {
        $display = $this->ViewMonth();
        $display .= $this->ViewCalendar();
        
        return $display;
    }
    
    //Display the Calendar
    private function ViewCalendar()
    {
        $day_one = mktime(0,0,0,$this->month,1,$this->year);
        //number of spaces on the first line of the calendar:
        $space = idate('w', $day_one);
        //total number of days in the month:
        $total_day = idate('t', $day_one);
        //other info about the month:
        $wantday = getdate($day_one);
        $dayint = 0;
        $this->linkurl = 'index.php?Action=ViewPost&blogID='
            .$this->blogID.'&year='.$this->year.'&month='.$this->month.'&date=';
        
        if ( ($this->month != $this->today['mon']) || ($this->year != $this->today['year']) )
            $lookingAtOtherMonth = true;
        
        //top row - static
        $cal .= "\n".'<table border="0" cellpadding="0" cellspacing="0" id="calendar_table">'."\n"
            .'<tr id="calendar_week_row">'."\n"
            .'<td id="calendar_week">Sun</td>'."\n"
            .'<td id="calendar_week">Mon</td>'."\n"
            .'<td id="calendar_week">Tue</td>'."\n"
            .'<td id="calendar_week">Wed</td>'."\n"
            .'<td id="calendar_week">Thu</td>'."\n"
            .'<td id="calendar_week">Fri</td>'."\n"
            .'<td id="calendar_week">Sat</td>'."\n"
            .'</tr><tr>'."\n";
        
        //1st row, skipping spaces until we hit the 1st of the month
        for($count=0; $count<7; ++$count)
        {
            if($count >= $space)
            {
                $dayint++;
                $daystr = $this->getDayString($lookingAtOtherMonth, $dayint);
            }
            else
                $daystr = '<td></td>';
            
            $cal .= $daystr."\n";
        }
        $cal .= "</tr>\n";
        
        //after 1st row
        //for each row:
        while(!$done)
        {
            $cal .= "<tr>\n";
            //for each day in a row:
            for($count=0; $count<7; ++$count)
            {
                $dayint++;
                if (!($dayint <= $total_day))
                {
                    $daystr = '';
                    $done = true;
                }
                else
                {
                    $daystr = $this->getDayString($lookingAtOtherMonth, $dayint);
                }
                $cal .= $daystr."\n";
            }
            $cal .= "</tr>\n";
        }
        
        return $cal."</table>\n";
    }
    
    private function ViewMonth()
    {
        $day_one = mktime(0,0,0,$this->month,1,$this->year);
        $wantday = getdate($day_one);
        
        //previous month link:
        if(($this->month-1) == 0)
        {
            $prevmonth = 12;
            $prevyear = $this->year-1;
        }
        else
        {
            $prevmonth = $this->month-1;
            $prevyear = $this->year;
        }
        $display = '<div id="calendar_month_year"><a href="'
            .'index.php?Action=ViewPost&blogID='.$this->blogID
            .'&month='.$prevmonth.'&year='.$prevyear.'">&laquo;</a>';
        
        //current month text:
        $display .= $wantday['month'].' '.$this->year;
        
        //next month link:
        if(($this->today['mon'] > $this->month)||($this->today['year'] > $this->year))
        {
            $day_one = mktime(0,0,0,$this->month+1,1,$this->year);
            
            $nextmonth = ($this->month%12)+1;
            if ($nextmonth == 1)
                $nextyear = $this->year+1;
            else
                $nextyear = $this->year;
            
            $display .= '<a href="'
                .'index.php?Action=ViewPost&blogID='.$this->blogID
                .'&month='.$nextmonth.'&year='.$nextyear.'">&raquo;</a>';
        }
        return $display."</div>\n";
    }
    
    //tests whether a given date is today and whether there are posts on that date
    //returns the string for that day depending on these conditions
    private function getDayString($lookingAtOtherMonth, $dayint)
    {
        if (!$lookingAtOtherMonth && ($dayint == $this->today['mday']))
        {
            if($this->dateswithposts[$dayint])
                $returnme = '<td id="calendar_today"><a href="'.$this->linkurl.$dayint.'">'.$dayint.'</a></td>';
            else
                $returnme = '<td id="calendar_today">'.$dayint.'</td>';
        }
        elseif ($this->dateswithposts[$dayint])
        {
            $returnme = '<td id="calendar_posts"><a href="'.$this->linkurl.$dayint.'">'.$dayint.'</a></td>';
        }
        else
        {
            $returnme = '<td id="calendar_number">'.$dayint.'</td>';
        }
        
        return $returnme;
    }
}
