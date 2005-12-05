<?php

class Presentation_View_ViewAddInvitationView extends Presentation_View_View
{
    private $blogID;
    private $ranks;
    private $errorMessage;

    public function __construct($blogID, $ranks, $errorMessage)
    {
        $this->blogID = $blogID;
        $this->ranks = $ranks;
        $this->errorMessage = $errorMessage;
    }

    public function Display()
    {
        $form = '<fieldset><legend>Create New Invitation</legend>'
            . '<form method="post" action="index.php?&Action=ProcessAddInvitation&blogID=' . $this->blogID . '">'
            . '<table id="formtable">';

        if (isset($this->errorMessage))
        {
            $form .= '<tr><td>' . $this->errorMessage . '</td></tr>';
        }

        $form .= '<tr><td><label for="username">Username:</label> '
            . '<input type="text" name="username"></td></tr>'
            . '<tr><td><label for="rank">Rank:</label> '
            . '<select name="rank">';

        foreach ($this->ranks as $key=>$value)
        {
            $form .= '<option value='. $value . '>' . $value . '</option>';
        }

        $form .= '</select></td></tr>'
                    . '<tr><td align="center"><input type="submit" id="submit" value="Create Invitation">'
                    . '</td></tr></table>'
                    . '</form></fieldset>';
                    
        return $form;
    }
}

?>
