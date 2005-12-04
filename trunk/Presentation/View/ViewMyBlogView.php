<?php

class Presentation_View_ViewMyBlogView extends Presentation_View_View
{
    private $blogID;
    private $blogTitle;
    
    public function __construct($blogID, $blogTitle)
    {
        $this->blogID = $blogID;
        $this->blogTitle = $blogTitle;
    }
    
    public function Display()
    {
        $ret = '<div id=myblog><div id=myblog_title>My Blog</div>';
        
        if ($this->blogID != 0 and $this->blogTitle != '')
        {
            $ret = $ret
                    . '<a href="index.php?Action=ViewBlog&blogID=' . $this->blogID . '">' . $this->blogTitle . '</a>';
        }
        else
        {
            $ret = $ret
                    . '<a href="index.php?Action=NewBlog&blogID=1">Create my blog</a>';
        }
        
        $ret = $ret . '</div>';
        
        return $ret;
    }
}

?>
