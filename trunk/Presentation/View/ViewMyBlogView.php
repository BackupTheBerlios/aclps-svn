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
        if ($blogID != 0 and $blogTitle != '')
        {
            $ret = '<div id=myBlog>'
                    . '<a href="index.php?Action=ViewBlog&blogID=' . $this->blogID . '">' . $this->blogTitle . '</a>'
                    . '</div>';

            return $ret;
        }
        else
        {
            $ret = '<div id=myBlog>'
                    . '<a href="index.php?Action=NewBlog&blogID=1">Create my blog</a>'
                    . '</div>';

            return $ret;
        }
    }
}

?>
