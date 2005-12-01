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
            . '<legend>&nbsp;Sign In</legend>'
            . '<p>';

      if ($this->errorMessage != '')
      {
        $form = $form
                . $this->errorMessage
                . '<br />';
      }

        $form = $form
            . '<center>Please Login:</center>'
            . '</p>'
            . '<label for="username">Username:</label>'
            . '<input type="text" name="username">'
            . '<br />'
            . '<label for="password">Password:</label>'
            . '<input type="password" name="password">'
            . '<br />'
            . '<input type="submit" class="submit-login" value="Sign In">'
            . '</fieldset>'
            . '</form>';
            
            return $form;
    }
}

?>
