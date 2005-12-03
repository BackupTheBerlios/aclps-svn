<?php

class Presentation_View_ViewCalendarView extends Presentation_View_View
{
  private $posts;
  private $year;
  private $blogID;
  private $month;
  private $today;
  private $linkprefix;
  private $aLink;
  private $action;

  //$posts should be an object of BusinessLogic_Post_PostDataAccess
  //can modify and get the current calendar month and year in order
  //to change the display month and year of calendar
  public function __construct($action, $blogID, $posts, $year, $month)
  {
    $this->action = $action;
    $this->today = getdate();
    $this->blogID = $blogID;
    $this->linkprefix = explode('?',$_SERVER['REQUEST_URI'], 2);
    $this->linkprefix = $this->linkprefix[0];
    
    if($year == '')
        $this->year = $this->today['year'];
    else
        $this->year = $year;
        
    if($month == '')
        $this->month = $this->today['mon'];
    else
        $this->month = $month;

    if(is_object($posts)){
        $this->posts = $posts;
    }
    else{
        throw new Exception("Incorrect used of Calendar.");
    }
  }
  
  public function Display()
  {
    $this->posts = $this->posts->GetDatesWithPostsForMonth($this->blogID, $this->year, $this->month);

    $display = $this->ViewCalendar();
    $display .= $this->ViewMonth();
    
    return $display;
  }
  
  //Display the Calendar
  public function ViewCalendar()
  {
    $day_one = mktime(0,0,0,$this->month,1,$this->year);
    $space = idate('w', $day_one);
    $total_day = idate('t', $day_one);
    $wantday = getdate($day_one);
    $day = 1;
    $this->aLink = '<a href="'.$this->linkprefix.'?Action=ViewPost&blogID='
        .$this->blogID.'&year='.$this->year.'&month='.$this->month.'&date=';
    
    if(($this->month != $this->today['mon'])||($this->year != $this->today['year']))
      $set = true;
    
    //month and year
    $cal .= '<div id="calendar_month_year">'.$wantday['month'].'&nbsp;&nbsp;'.$this->year
        .'</div><table border="0" id="calendar_table" cellspacing="5"><tr id="calendar_week_row">'
        .'<td><div id="calendar_week">Sun</div></td><td><div id="calendar_week">Mon</div></td><td><div id="calendar_week">Tue</div></td><td><div id="calendar_week">Wed</div></td><td><div id="calendar_week">Thu</div></td>'
        .'<td><div id="calendar_week">Fri</div></td><td><div id="calendar_week">Sat</div></td></tr><tr>';
        
    //1st row
    for($count=0; $count<7; ++$count)
    {
        if($count >= $space)
        {
            $temp = $day++;
            $temp = $this->testnumber($found, $set, $temp);
        }
        else
            $temp = '';

        $cal .= "<td>$temp</td>";
    }
    $cal .= '</tr>';
    //after 1st row
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
            else
                $temp = $this->testnumber($found, $set, $temp);
                
            $cal .= "<td>$temp</td>";
        }
        $cal .= '</tr>';
    }

    return $cal.'</table>';
  }
  
  public function ViewMonth()
  {
    $day_one = mktime(0,0,0,$this->month-1,1,$this->year);
    
    if(($this->month-1) == 0)
        $show = '12&year='.($this->year-1);
    else
        $show = ($this->month-1).'&year='.$this->year;
    
    $display = '<div id="calendar_last_mon"><a href="'
        .$this->linkprefix.'?Action='.$this->action.'&blogID='
        .$this->blogID.'&month='.$show.'">'
        .strftime('%b', $day_one).'</a></div>';
    
    if(($this->today['mon'] > $this->month)||($this->today['year'] > $this->year))
    {
        $day_one = mktime(0,0,0,$this->month+1,1,$this->year);
        
        if(($this->month+1) == 13)
            $show = '1&year='.($this->year+1);
        else
            $show = ($this->month+1).'&year='.$this->year;
        
        $display .= '<div id="calendar_last_mon"><a href="'
            .$this->linkprefix.'?Action='.$this->action.'&blogID='
            .$this->blogID.'&month='.$show.'">'
            .strftime('%b', $day_one).'</a></div>';
    }

    return $display;
  }
  
  private function testnumber($found, $set, $temp)
  {
    if(!$found && !$set && ($temp == $this->today['mday']))
    {
        if($this->posts[$temp])
            $temp = $this->aLink.$temp.'"><div id="calendar_today">'.$temp.'</div></a>';
        else
            $temp = '<div id="calendar_today">'.$temp.'</div>';

        $found = true;
    }
    elseif((!$found || $set) && $this->posts[$temp])
        $temp = $this->aLink.$temp.'"><div id="calendar_posts">'.$temp.'</div></a>';
    else
        $temp = '<div id="calendar_number">'.$temp.'</div>';
        
    return $temp;
  }
  
  public function getmonth()
  {
    return $this->month;
  }
  
  public function getyear()
  {
    return $this->month;
  }
  
  public function setmonth($month)
  {
    $this->month = $month;
  }
  
  public function setyear($year)
  {
    $this->year = $year;
  }

}
