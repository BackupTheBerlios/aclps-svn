<?php

class Presentation_View_ViewRegisterView extends Presentation_View_View
{
    private $errorMessage;
    
    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
    
    public function Display()
    {
        $form = '<form method="post" action="index.php?Action=ProcessRegister&blogID=1">'
            . '<fieldset>'
            . '<legend>&nbsp;Account Registration</legend>'
            . '<p>';

      if ($this->errorMessage != '')
      {
        $form = $form
            . $this->errorMessage
            . '<br />';
      }
      
        $form = $form
            . '<center>Please fill in the fields below.</center>'
            . '</p>'
            . '<label for="username">Username:</label>'
            . '<input type="text" name="username">'
            . '<br />'
            . '<label for="email">Email:</label>'
            . '<input type="text" name="email">'
            . '<br />'
            . '<label for="password">Password:</label>'
            . '<input type="password" name="password">'
            . '<br />'
            . '<label for="confirmPassword">Confirm Password:</label>'
            . '<input type="password" name="confirmPassword">'
            . '<br />'
            . '<input type="submit" class="submit-register" value="Register">'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
