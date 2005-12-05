<?php

class Presentation_View_ViewManageInvitationsView extends Presentation_View_View
{
    private $blogID;

    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $ret = '<fieldset><legend>Manage Invitations</legend>'
                . '<p><a href=index.php?Action=ViewInvitation&blogID='     . $this->blogID . '>View Pending Invitations</a></p>'
                . '<p><a href=index.php?Action=AddInvitation&blogID='      . $this->blogID . '>Create New Invitation</a></p>'
                . '<p><a href=index.php?Action=RemoveInvitation&blogID='   . $this->blogID . '>Remove Pending Invitations</a></p>'
                . '</fieldset>';
        return $ret;
    }
}

?>
