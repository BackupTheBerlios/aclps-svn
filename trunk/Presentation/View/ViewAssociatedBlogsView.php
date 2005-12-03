<?php

class Presentation_View_ViewAssociatedBlogsView extends Presentation_View_View
{
    private $associatedBlogs;

    public function __construct($associatedBlogs)
    {
        $this->associatedBlogs = $associatedBlogs;
    }

    public function Display()
    {
        if (count($this->associatedBlogs) > 0)
        {
            $ret = '<div id=associatedBlogs>';
            
            foreach ($this->associatedBlogs as $blogID => $title)
            {
                $ret = $ret . '<a href="index.php?Action=ViewBlog&blogID=' . $blogID . '">' . $title . '</a><br />';
            }
            
            $ret = $ret . '</div>';
        }
        else
        {
            $ret = '<div id=associatedBlogs>'
                    . 'You are not associated with any blogs.'
                    . '</div>';
        }
        
        return $ret;
    }
}

?>
