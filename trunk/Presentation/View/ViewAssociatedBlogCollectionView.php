<?php

class Presentation_View_ViewAssociatedBlogCollectionView extends Presentation_View_View
{

    private $blogID;
    private $showCreateBlogLink;

    public function __construct($showCreateBlogLink, $blogID)
    {
        $this->showCreateBlogLink = $showCreateBlogLink;
        $this->blogID = $blogID;
    }
    
    public function Display()
    {
        $ret = '<div id="dashboardcollection">';
        $ret .= '<div id="dashboardcollectionheader">Blogs</div>';
        
        if ($this->showCreateBlogLink)
        {
            $ret .= '<div id="dashboardelement">'
                .'<div id="dashboardelement_title"><a href="index.php?Action=NewBlog&blogID='.$this->blogID.'">[Make One Of Your Own]</a></div>'
                .'<div id="dashboardelement_subtitle">You are not currently the owner of a blog.<br />If you want, you can create one.';
                if (count($this->Views) < 1)
                {
                    $ret .= '<br />You can also join an existing blog by getting an Editor or Owner to invite you.';
                }
                $ret .= '</div><div id="dashboardelement_controls"><a href="index.php?Action=NewBlog&blogID=' . $this->blogID.'">Create My Blog</a></div></div>';
        }
        if (count($this->Views) > 0)
        {
            foreach($this->Views as $view)
            {
                $ret = $ret . $view->Display();
            }
        }

        $ret .= '</div>';

        return $ret;
    }
}

?>
