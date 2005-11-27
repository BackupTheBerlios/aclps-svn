<?php

class Presentation_View_ViewSignInView extends Presentation_View_View
{
    public function Display()
    {
      echo 'form method="post" action="index.php?Action=ProcessSignIn">'
            . '<fieldset>'
            . '<legend>&nbsp;Sign In</legend>'
            . '<p>'
            . '<center>Please Login:</center></p>'
            . '<label for="email">Email:</label>'
            . '<input type="text" name="email">'
            . '<br />'
            . '<label for="password">Password:</label>'
            . '<input type="password" name="password">'
            . '<br />'
            . '<input type="submit" class="submit-login" value="Sign In">'
            . '</fieldset>'
            . '</form>';
    }
}

?>
