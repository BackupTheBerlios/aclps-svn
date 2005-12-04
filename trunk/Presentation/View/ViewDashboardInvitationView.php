<?php

class Presentation_View_ViewDashboardInvitationView extends Presentation_View_View
{
    private $cBlogID;
    private $invitingBlogID;
    private $title;
    private $rank;

    public function __construct($cBlogID, $invitingBlogID, $title, $rank)
    {
        $this->cBlogID = $cBlogID;
        $this->invitingBlogID = $invitingBlogID;
        $this->title = $title;
        $this->rank = $rank;
    }

    public function Display()
    {
        $ret = '<div id=dashboardinvitation>'
                . '<a href=index.php?Action=ViewBlog&blogID=' . $this->invitingBlogID . '>' . $this->title . '</a>'
                . ' as an ' . $this->rank
                . ' <a href=index.php?Action=AcceptInvitation&blogID=' . $this->cBlogID
                . '&invitingBlogID=' . $this->invitingBlogID . '>Accept Invitation</a>'
                . ' <a href=index.php?Action=DeclineInvitation&blogID=' . $this->cBlogID
                . '&invitingBlogID=' . $this->invitingBlogID . '>Decline Invitation</a>'
                . '</div>';
        return $ret;
    }
}

?>
