<?php

class Presentation_View_ViewAssociatedBlogView extends Presentation_View_View
{
    private $title;
    private $blogID;
    private $rank;

    public function __construct($blogID, $title, $rank)
    {
        $this->blogID = $blogID;
        $this->title = $title;
        $this->rank = $rank;
    }

    public function Display()
    {
        return '<p><a href="index.php?Action=ViewBlog&blogID='.$this->blogID.'">'.$this->title .'</a>: '.$this->rank.'</p>';
    }
}

?>
