<?php

class Presentation_View_ViewRemoveMemberView extends Presentation_View_View
{
    private $userID;
    private $username;
    private $rank;

    public function __construct($userID, $username, $rank)
    {
        $this->userID = $userID;
        $this->username = $username;
        $this->rank = $rank;
    }

    public function Display()
    {
        $ret = '<p><input type="checkbox" name="'.$this->userID.'">'.$this->username.' as an '.$this->rank.'</p>';
        return $ret;
    }
}

?>
