<?php

class Presentation_View_ViewAssociatedBlogView extends Presentation_View_View
{
    private $cBlogID;
    private $title;
    private $blogID;
    private $rank;

    public function __construct($cBlogID, $blogID, $title, $rank)
    {
        $this->cBlogID = $cBlogID;
        $this->blogID = $blogID;
        $this->title = $title;
        $this->rank = $rank;
    }

    public function Display()
    {
        $ret = '<div id="dashboardelement"><div id="dashboardelement_title"><a href="index.php?Action=ViewBlog&blogID='
            . $this->blogID . '">' . $this->title . '</a></div>'
            . '<div id="dashboardelement_subtitle">' . $this->rank . '</div>'
            . '<div id="dashboardelement_controls"><a href ="index.php?Action=ViewBlog&blogID='
            . $this->blogID . '">View Blog</a>';
        
        
        if ($this->rank == 'Owner')
        {
            $ret .= ' - <a href ="index.php?Action=DeleteBlog&blogID=' . $this->cBlogID
                . '&deleteBlogID=' . $this->blogID . '">Delete Blog</a></div>';
        }
        else
        {
            $ret .= ' - <a href ="index.php?Action=LeaveBlog&blogID=' . $this->cBlogID
                . '&leaveBlogID=' . $this->blogID . '">Leave Blog</a></div>';
        }
        
        $ret .= '</div>';
        
        return $ret;
    }
}

?>
