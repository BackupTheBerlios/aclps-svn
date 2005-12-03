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
            . '<p>Please Login:</p>'
            . '<p><input type="text" name="username">'
            . '<label for="username">Username</label></p>'

            . '<p><input type="password" name="password">'
            . '<label for="password">Password</label></p>'

            . '<p><input type="submit" id="submit" value="Sign In"></p>'
            . '</fieldset>'
            . '</form>';
            
            return $form;
    }
}

?>
