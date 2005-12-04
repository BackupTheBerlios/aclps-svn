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
        $form = '<form method="post" action="index.php?&Action=ProcessAddInvitation&blogID=' . $this->blogID . '">'
                    . '<fieldset>'
                    . '<legend>Create New Invitation</legend>'
                    . '<table id="formtable">';

        if (isset($this->errorMessage))
        {
            $form = $form . '<tr><td colspan="2">' . $this->errorMessage . '</td></tr>';
        }

        $form = $form
                    . '<tr><td colspan="2"><label for="username">Username:</label> '
                    . '<input type="text" name="username"></td></tr>'
                    . '<tr><td colspan="2"><label for="rank">Rank:</label> '
                    . '<select name="rank">';

        foreach ($this->ranks as $key=>$value)
        {
            $form = $form . '<option value='. $value . '>' . $value . '</option>';
        }

        $form = $form
                    . '</select></td></tr>'
                    . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Create Invitation">'
                    . '</td></tr></table>'
                    . '</fieldset>'
                    . '</form>';
                    
        return $form;
    }
}

?>
