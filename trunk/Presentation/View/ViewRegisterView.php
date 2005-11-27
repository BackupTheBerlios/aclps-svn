<?php

class Presentation_View_ViewRegisterView extends Presentation_View_View
{
    public function Display()
    {
      return '<form method="post" action="index.php?Action=ProcessRegister">'
            . '<fieldset>'
            . '<legend>&nbsp;Account Registration</legend>'
            . '<p>'
            . '<center>Please fill in the fields below.</center></p>'
            . '<label for="userName">Username:</label>'
            . '<input type="text" name="userName">'
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
    }
}

?>
