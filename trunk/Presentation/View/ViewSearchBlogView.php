<?php

  //this class represents a single blog's data, and can display it.
class Presentation_View_ViewSearchBlogView extends Presentation_View_View
{
    private $blogID;

    private $title;
    private $about;
    
    private $linkprefix;

    public function __construct($blogID, $title, $about)
    {
        $this->blogID = $blogID;
//        $this->authorName = BusinessLogic_User_User::ConvertUserIDToName($authorID);
        $this->title = $title;
        $this->about = $about;
        
        $this->linkprefix = explode('?',$_SERVER['REQUEST_URI'], 2);
        $this->linkprefix = $this->linkprefix[0];
    }
    
    public function Display()
    {
        $name = '<div id="blogtitle">'.$this->title.'</div>';

        $item = '<div id="search">'.$this->GetLink($name).
            '<div id="blogabout">'.$this->about.'</div>';
        
        $item .= '</div>';
        return $item;
    }
    
    public function GetLink($content)
    {
        return ('<a href="'.$this->linkprefix.'?Action=ViewBlog&blogID='.
        $this->blogID.'">'.$content.'</a>');
    }
    
    //blogID cannot be changed, is always set in constructor
    public function GetBlogID()
    {
        return $this->blogID;
    }
    
    public function SetTitle($aTitle)
    {
        $this->title = $aTitle;
    }
    
    public function GetTitle()
    {
            return $this->title;
    }
    
        public function SetAbout($aAbout)
    {
        $this->about = $aAbout;
    }

    public function GetAbout()
    {
            return $this->about;
    }
    
}
?>
