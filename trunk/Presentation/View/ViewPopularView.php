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
      $ret = '<fieldset><legend>Most Visited Blogs</legend>';
      if(count($this->blogs) > 0)
      {
        $ret .= '<div id="popularcollection">';
        foreach($this->blogs as $value)
        {
        $ret .= '<p><a href="'.$this->linkprefix.'?Action=ViewBlog&blogID='.
            $value['BlogID'].'">'.$value['Title'].'</a></p>';
        }
        $ret .= '</div></fieldset>';
        return $ret;
      }
      else
      {
        return $ret.'<div id="popularcollection">There are currently no rankings available.</div></fieldset>';
      }
    }
    else
    {
        throw new Exception("Popularity information unavailable.");
    }
  }
  
}
