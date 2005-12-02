<?php

class Presentation_View_ViewPopularView extends Presentation_View_View
{

  public function __construct()
  {

  }
  
  public function Display()
  {
    return $this->ViewCalendar();
  }
  
  //Display the current month Calendar
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
    $cal = $today['month'].'&nbsp;&nbsp;'.$today['year'].'<br/>'
        .'<table border="0" id="table1" cellspacing="5"><tr>'
        .'<td>Sun</td><td>Mon</td><td>Tue</td><td>Wed</td><td>Thu</td>'
        .'<td>Fri</td><td>Sat</td></tr><tr>';
    //1st row
    for($count=0; $count<7; ++$count)
    {
        if($count >= $space)
            $temp = $day++;
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
            $cal .= "<td>$temp</td>";
        }
        $cal .= '</tr>';
    }
    
    return $cal.'</table>';
  }
  
}
