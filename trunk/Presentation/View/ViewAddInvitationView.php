<?php

class Presentation_View_ViewAddInvitationView extends Presentation_View_View
{
    private $blogID;

    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?&Action=ProcessAddInvitation&blogID=' . $this->blogID . '">'
                    . '<fieldset>'
                    . '<legend>Create New Invitation</legend>'
                    . '<table id="formtable"><tr><td colspan="2"><label for="username">Username:</label> '
                    . '<input type="text" name="username"></td></tr>'
                    . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Create Invitation">'
                    . '</td></tr></table>'
                    . '</fieldset>'
                    . '</form>';
        return $form;
    }
}

?>
