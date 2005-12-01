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
        if (count($associatedBlogs) > 0)
        {
            $ret = '<div id=associatedBlogs>'
                    . '<a href="index.php?Action=ViewBlog&blogID=' . $this->blogID . '">' . $this->blogTitle . '</a>'
                    . '</div>';

            return $ret;
        }
        else
        {
            $ret = '<div id=associatedBlogs>'
                    . 'You are not associated with any blogs.'
                    . '</div>';

            return $ret;
        }
    }
}

?>
