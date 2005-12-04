<?php

class Presentation_View_ViewInvitationView extends Presentation_View_View
{
    private $username;
    private $rank;

    public function __construct($username, $rank)
    {
        $this->username = $username;
        $this->rank = $rank;
    }

    public function Display()
    {
        $ret = '<div id=invitation>'
                . $this->username . ' invited as an ' . $this->rank
                . '</div>';
        return $ret;
    }
}

?>
