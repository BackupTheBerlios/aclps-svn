<?php

class Presentation_View_ViewRemoveInvitationView extends Presentation_View_View
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
        $ret = '<div id=removeinvitation>'
                . '<input type=checkbox name=' . $this->userID . '>' . $this->username . ' invited as an ' . $this->rank
                . '</div>';
        return $ret;
    }
}

?>
