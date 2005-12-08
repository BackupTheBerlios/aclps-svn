<?php

class Presentation_View_ViewDashboardInvitationView extends Presentation_View_View
{
    private $cBlogID;
    private $blogID;
    private $title;
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
        $ret = '<div id=dashboardelement><div id=dashboardelement_title><a href="index.php?Action=ViewBlog&blogID='
                    . $this->blogID . '">' . $this->title . '</a></div>'
                    . '<div id=dashboardelement_rank>' . $this->rank . '</div>'
                    . '<div id=dashboardelement_controls>'
                    . '<a href ="index.php?Action=ViewBlog&blogID=' . $this->blogID . '">View Blog</a>'
                    . ' - <a href ="index.php?Action=AcceptInvitation'
                    . '&blogID=' . $this->cBlogID . '&invitingBlogID=' . $this->blogID . '">Accept Invitation</a>'
                    . ' - <a href ="index.php?Action=DeclineInvitation'
                    . '&blogID=' . $this->cBlogID . '&invitingBlogID=' . $this->blogID . '">Decline Invitation</a>'
                    . '</div>'
                    . '</div>';

        return $ret;
    }
}

?>
