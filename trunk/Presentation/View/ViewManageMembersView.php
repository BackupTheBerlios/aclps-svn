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
        $ret = '<fieldset><legend>Manage Current Members</legend>';
        if ($this->permission == 'Owner')
        {
            $ret .= '<p><a href=index.php?Action=ChangeMemberRank&blogID='   . $this->blogID . '>Change Member Ranks</a></p>';
        }

        $ret .= '<p><a href=index.php?Action=RemoveMember&blogID='       . $this->blogID . '>Remove Members</a></p>'
            . '</fieldset>';
        return $ret;
    }
}

?>
