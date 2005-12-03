<?php

class Presentation_View_ViewCalendarView extends Presentation_View_View
{
  private $posts;
  private $year;
  private $month;
  private $today;
  private $day_one;

  public function __construct($blogID, $posts, $year, $month)
  {
    $this->today = getdate();
    
    if($year == '')
        $this->year = $this->today['year'];
    else
        $this->year = $year;
        
    if($month == '')
        $this->month = 11;//$this->today['mon'];
    else
        $this->month = $month;
        
    $this->day_one = mktime(0,0,0,$this->month,1,$this->year);

    if(is_object($posts)){
        $this->posts = $posts->GetDatesWithPostsForMonth($blogID, $this->year, $this->month);
    }
    else{
        throw new Exception("Incorrect used of Calendar.");
    }
  }
  
  public function Display()
  {
    return $this->ViewCalendar();
  }
  
  //Display the Calendar according to input month and year
  public function ViewCalendar()
  {
    $space = idate('w', $this->day_one);
    $total_day = idate('t', $this->day_one);
    $wantday = getdate($this->day_one);
    $day = 1;
    
    if(($this->month != $this->today['mon'])||($this->year != $this->today['year']))
    {
      $tag = '<div id="calendar_number">';
      $set = true;
    }
    else
      $tag = '<div id="calendar_today">';

    //month and year
    $cal .= '<div id="calendar_month_year">'.$wantday['month'].'&nbsp;&nbsp;'.$this->year
        .'</div><table border="0" id="calendar_table" cellspacing="5"><tr>'
        .'<td><div id="calendar_week">Sun</div></td><td><div id="calendar_week">Mon</div></td><td><div id="calendar_week">Tue</div></td><td><div id="calendar_week">Wed</div></td><td><div id="calendar_week">Thu</div></td>'
        .'<td><div id="calendar_week">Fri</div></td><td><div id="calendar_week">Sat</div></td></tr><tr>';
        
    //1st row
    for($count=0; $count<7; ++$count)
    {
        if($count >= $space)
        {
            $temp = $day++;
            if(!$found && ($temp == $this->today['mday']))
            {
                $temp = $tag.$temp.'</div>';
                $found = true;
            }
            elseif((!$found || $set) && $this->posts[$temp])
            {
                $temp = '<div id="calendar_posts">'.$temp.'</div>';
            }
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
            if(!(($temp = $day++) <= $total_day))
            {
                $temp = '';
                $done = true;
            }
            if(!$found && ($temp == $this->today['mday']))
            {
                $temp = $tag.$temp.'</div>';
                $found = true;
            }
            elseif((!$found || $set) && $this->posts[$temp])
            {
                $temp = '<div id="calendar_posts">'.$temp.'</div>';
            }
            else
                $temp = '<div id="calendar_number">'.$temp.'</div>';
            $cal .= "<td>$temp</td>";
        }
        $cal .= '</tr>';
    }
    
    return $cal.'</table>';
  }
  
}
