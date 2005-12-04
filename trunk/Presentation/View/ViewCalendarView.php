<?php

class Presentation_View_ViewCalendarView extends Presentation_View_View
{
    private $posts;
    private $year;
    private $blogID;
    private $month;
    private $today;
    private $linkurl;
    
    //$posts should be the array returned by GetDatesWithPostsForMonth
    public function __construct($blogID, $posts, $year, $month)
    {
        $this->today = getdate();
        $this->blogID = $blogID;
        $this->year = $year;
        $this->month = $month;
        $this->posts = $posts;
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
        $space = idate('w', $day_one);
        $total_day = idate('t', $day_one);
        $wantday = getdate($day_one);
        $day = 1;
        $this->linkurl = 'index.php?Action=ViewPost&blogID='
            .$this->blogID.'&year='.$this->year.'&month='.$this->month.'&date=';
        
        if(($this->month != $this->today['mon'])||($this->year != $this->today['year']))
            $set = true;
        
        //top row - static
        $cal .= "\n".'<table id="calendar_table">'."\n"
            .'<tr id="calendar_week_row">'."\n"
            .'<td><div id="calendar_week">Sun</div></td>'."\n"
            .'<td><div id="calendar_week">Mon</div></td>'."\n"
            .'<td><div id="calendar_week">Tue</div></td>'."\n"
            .'<td><div id="calendar_week">Wed</div></td>'."\n"
            .'<td><div id="calendar_week">Thu</div></td>'."\n"
            .'<td><div id="calendar_week">Fri</div></td>'."\n"
            .'<td><div id="calendar_week">Sat</div></td>'."\n"
            .'</tr><tr>'."\n";
        
        //1st row, skipping slots until we hit the 1st
        for($count=0; $count<7; ++$count)
        {
            if($count >= $space)
            {
                $daystr = $this->testnumber($found, $set, $daystr+1);
            }
            else
                $daystr = '';
            
            $cal .= "<td>$daystr</td>\n";
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
                if(!(($daystr = $day++) <= $total_day))
                {
                    $daystr = '';
                    $done = true;
                }
                else
                {
                    $daystr = $this->testnumber($found, $set, $daystr);
                }
                $cal .= "<td>$daystr</td>\n";
            }
            $cal .= "</tr>\n";
        }
        
        return $cal."</table>\n";
    }
    
    private function ViewMonth()
    {
        $day_one = mktime(0,0,0,$this->month-1,1,$this->year);
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
        $display = '<div id="calendar_last_mon"><a href="'
            .'index.php?Action=ViewPost&blogID='.$this->blogID
            .'&month='.$prevmonth.'&year='.$prevyear.'">&laquo;</a></div>'."\n";
        
        //current month text:
        $display .= '<div id="calendar_month_year">'.$wantday['month'].' '.$this->year.'</div>'."\n";
        
        //next month link:
        if(($this->today['mon'] > $this->month)||($this->today['year'] > $this->year))
        {
            $day_one = mktime(0,0,0,$this->month+1,1,$this->year);
            
            $nextmonth = ($this->month%12)+1;
            if ($nextmonth == 1)
                $nextyear = $this->year+1;
            else
                $nextyear = $this->year;
            
            $display .= '<div id="calendar_next_mon"><a href="'
                .'index.php?Action=ViewPost&blogID='.$this->blogID
                .'&month='.$nextmonth.'&year='.$nextyear.'">&raquo;</a></div>'."\n";
        }
        return $display;
    }
    
    //tests whether a given date is today or whether there are posts on that day
    private function testnumber($found, $set, $temp)
    {
        if(!$found && !$set && ($temp == $this->today['mday']))
        {
            if($this->posts[$temp])
                $temp = '<a href="'.$this->linkurl.$temp.'"><div id="calendar_today">'.$temp.'</div></a>';
            else
                $temp = '<div id="calendar_today">'.$temp.'</div>';
            
            $found = true;
        }
        elseif((!$found || $set) && $this->posts[$temp])
        {
            $temp = '<a href="'.$this->linkurl.$temp.'"><div id="calendar_posts">'.$temp.'</div></a>';
        }
        else
        {
            $temp = '<div id="calendar_number">'.$temp.'</div>';
        }
        
        return $temp;
    }
}
