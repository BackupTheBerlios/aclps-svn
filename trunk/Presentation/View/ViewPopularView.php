<?php

class Presentation_View_ViewPopularView extends Presentation_View_View
{
  private $blogs;
  private $linkprefix;
  
  public function __construct($blogs)
  {
    $this->blogs = $blogs;
    
    $this->linkprefix = explode('?',$_SERVER['REQUEST_URI'], 2);
    $this->linkprefix = $this->linkprefix[0];
  }
  
  public function Display()
  {
    if(is_array($this->blogs))
    {
      if(count($this->blogs) > 0)
      {
        $ret = '<br/>Popular Link:<br/><div id="popularcollection">';
        foreach($this->blogs as $value)
        {
        $ret .= '<a href="'.$this->linkprefix.'?Action=ViewBlog&blogID='.
            $value['BlogID'].'">'.$value['Title'].'</a><br/>';
        }
        $ret .= '</div>';
        return $ret;
      }
      else
      {
        return '<div id="rankcollection">No Rank.</div>';
      }
    }
    else
    {
        throw new Exception("Can't get Rank");
    }
  }
  
}
