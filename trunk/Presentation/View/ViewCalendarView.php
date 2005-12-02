<?php

class Presentation_View_ViewCalendarView extends Presentation_View_View
{

  public function __construct()
  {

  }
  
  public function Display()
  {
    return $this->ViewCalendar();
  }
  
  //Display the current month Calendar, today's date will be red'
  public function ViewCalendar()
  {
    //get current time
    $today = getdate();
    //make 1st day timestamp
    $day_one = mktime(0,0,0,$today['mon'],1,$today['year']);
    //get weekday
    $space = idate('w', $day_one);
    $day = 1;

    //month and year
    $cal = '<div id="calendar_month_year">'.$today['month'].'&nbsp;&nbsp;'.$today['year']
        .'</div><table border="0" id="calendar_table" cellspacing="5"><tr>'
        .'<td><div id="calendar_week">Sun</div></td><td><div id="calendar_week">Mon</div></td><td><div id="calendar_week">Tue</div></td><td><div id="calendar_week">Wed</div></td><td><div id="calendar_week">Thu</div></td>'
        .'<td><div id="calendar_week">Fri</div></td><td><div id="calendar_week">Sat</div></td></tr><tr>';
    //1st row
    for($count=0; $count<7; ++$count)
    {
        if($count >= $space)
        {
            $temp = $day++;
            if($temp == $today['mday'])
                $temp = '<div id="calendar_today">'.$temp.'</div>';
            else
                $temp = '<div id="calendar_number">'.$temp.'</div>';
        }
        else
            $temp = '';

        $cal .= "<td>$temp</td>";
    }
    $cal .= '</tr>';
    //after 1sy row
    while(!$done)
    {
        $cal .= '<tr>';
        for($count=0; $count<7; ++$count)
        {
            if(!checkdate($today['mon'],($temp = $day++),$today['year']))
            {
                $temp = '';
                $done = true;
            }
            if($temp == $today['mday'])
                $temp = '<div id="calendar_today">'.$temp.'</div>';
            else
                $temp = '<div id="calendar_number">'.$temp.'</div>';
            $cal .= "<td>$temp</td>";
        }
        $cal .= '</tr>';
    }
    
    return $cal.'</table>';
  }
  
}
