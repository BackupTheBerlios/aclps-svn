<?php

class Presentation_View_ViewRegisterView extends Presentation_View_View
{
    private $errorMessage;
    private $username;
    private $email;
    
    public function __construct($username, $email, $errorMessage)
    {
        $this->username = $username;
        $this->email = $email;
        $this->errorMessage = $errorMessage;
    }

    public function Display()
    {
        $form = '<fieldset><legend><img src="UI/Themes/Images/Controls/register.png" id="controlbarimg" /> Account Registration</legend>'
            . '<form method="post" action="index.php?Action=ProcessRegister&blogID=1">';

      if ($this->errorMessage != '')
      {
        $form = $form.'<p>'.$this->errorMessage.'</p>';
      }
      
        $form = $form
            . '<table id="formtable"><tr><td><label for="username">Username:</label></td>'
            . '<td><input type="text" name="username" value="'.$this->username.'"></td></tr>'

            . '<tr><td><label for="email">Email:</label></td>'
            . '<td><input type="text" name="email" value="'.$this->email.'"></td></tr>'

            . '<tr><td><label for="password">Password:</label></td>'
            . '<td><input type="password" name="password"></td></tr>'

            . '<tr><td><label for="confirmPassword">Confirm Password</label></td>'
            . '<td><input type="password" name="confirmPassword"></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Register"></td></tr></table>'
            . '</form></fieldset>';

        return $form;
    }
}

?>
