<?php

class Presentation_View_ViewAssociatedBlogView extends Presentation_View_View
{
    private $title;
    private $blogID;
    private $rank;

    public function __construct($cBlogID, $invitingBlogID, $title, $rank)
    {
        $this->blogID = $blogID;
        $this->title = $title;
        $this->rank = $rank;
    }

    public function Display()
    {
        $ret = '<div id=invitation>'
                . '<a href=index.php?Action=ViewBlog&blogID=' . $this->invitingBlogID . '>' . $this->title . '</a>'
                . ' as an ' . $this->rank
                . ' <a href=index.php?Action=AcceptInvitation&blogID' . $this->cBlogID .
                . '&invitingBlogID=' . $this->invitingBlogID . '>'
                . '</div>';
        return $ret;
    }
}

?>
