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
        $ret = '<div id=manageinvitations>'
                . '<div id=manageinvitations_title>Manage Invitations</div>'
                . '<div id=manageinvitations_view>'
                . '<a href=index.php?Action=ViewInvitation&blogID='     . $this->blogID . '>View Outstanding Invitations</a>'
                . '</div>'
                . '<div id=manageinvitations_new>'
                . '<a href=index.php?Action=AddInvitation&blogID='      . $this->blogID . '>Create New Invitation</a>'
                . '</div>'
                . '<div id=manageinvitations_delete>'
                . '<a href=index.php?Action=RemoveInvitation&blogID='   . $this->blogID . '>Remove Invitations</a>'
                . '</div>'
                . '</div>';
        return $ret;
    }
}

?>
