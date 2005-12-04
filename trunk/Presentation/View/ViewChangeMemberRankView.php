<?php

class Presentation_View_ViewChangeMemberRankView extends Presentation_View_View
{
    private $userID;
    private $username;
    private $cRank;
    private $ranks;

    public function __construct($userID, $username, $cRank, $ranks)
    {
        $this->userID = $userID;
        $this->username = $username;
        $this->cRank = $cRank;
        $this->ranks = $ranks;
    }

    public function Display()
    {
        $form = '<div id=changememberrank>'
                . $this->username . ' as an '
                . '<select name=' . $this->userID . '>';

                foreach ($this->ranks as $key=>$value)
                {
                    if ($value == $this->cRank)
                    {
                        $form = $form . '<option value='. $value . ' SELECTED>' . $value . '</option>';
                    }
                    else
                    {
                        $form = $form . '<option value='. $value . '>' . $value . '</option>';
                    }
                }

        $form = $form
                . '</select>'
                . '</div>';
                
        return $form;
    }
}

?>
