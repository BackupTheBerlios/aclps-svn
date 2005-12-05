<?php

class Presentation_View_ViewSignInView extends Presentation_View_View
{
    private $errorMessage;

    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
    
    public function Display()
    {
        $form = '<form method="post" action="index.php?Action=ProcessSignIn&blogID=1">'
            . '<fieldset>'
            . '<legend>Sign In</legend>';

      if($this->errorMessage != '')
      {
        $form = $form
                . '<p>'.$this->errorMessage.'</p>';
      }

        $form = $form
            . '<table id="formtable"><tr><td><label for="username">Username:</label></td>'
            . '<td><input type="text" name="username"></td></tr>'

            . '<tr><td><label for="password">Password:</label></td>'
            . '<td><input type="password" name="password"></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Sign In"></td></tr></table>'
            . '</fieldset>'
            . '</form>';
            
            return $form;
    }
}

?>
