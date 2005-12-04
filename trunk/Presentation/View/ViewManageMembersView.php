<?php

class Presentation_View_ViewManageMembersView extends Presentation_View_View
{
    private $blogID;

    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $ret = '<div id=managemembers>'
                . '<div id=managemembers_title>Manage Invitations</div>'
                . '<div id=managemembers_change>'
                . '<a href=index.php?Action=ChangeMemberRank&blogID='   . $this->blogID . '>Change Members\' Rank</a>'
                . '</div>'
                . '<div id=managemembers_delete>'
                . '<a href=index.php?Action=DeleteMember&blogID='       . $this->blogID . '>Delete Members</a>'
                . '</div>'
                . '</div>';
        return $ret;
    }
}

?>
