<?php

class Presentation_View_ViewManageMembersView extends Presentation_View_View
{
    private $blogID;
    private $permission;

    public function __construct($blogID, $permission)
    {
        $this->blogID = $blogID;
        $this->permission = $permission;
    }

    public function Display()
    {
        $ret = '<div id=managemembers>'
                . '<div id=managemembers_title>Manage Members</div>';
        if ($this->permission == 'Owner')
        {
            $ret = $ret
                . '<div id=managemembers_change>'
                . '<a href=index.php?Action=ChangeMemberRank&blogID='   . $this->blogID . '>Change Members\' Ranks</a>'
                . '</div>';
        }

        $ret = $ret
                . '<div id=managemembers_remove>'
                . '<a href=index.php?Action=RemoveMember&blogID='       . $this->blogID . '>Remove Members</a>'
                . '</div>'
                . '</div>';
        return $ret;
    }
}

?>
