<?php

class Presentation_View_ViewMyBlogView extends Presentation_View_View
{
    private $blogID;
    private $blogTitle;
    
    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }
    
    public function Display()
    {
        $ret = '<div id=myblog><a href="index.php?Action=NewBlog&blogID=' . $this->blogID . '">Create My Blog</a></div>';
        
        return $ret;
    }
}

?>
