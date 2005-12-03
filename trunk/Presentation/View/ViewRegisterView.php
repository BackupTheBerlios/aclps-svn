<?php

class Presentation_View_ViewRegisterView extends Presentation_View_View
{
    private $errorMessage;
    private $username;
    private $email;
    
    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        $this->username = '';
        $this->email = '';
    }

    public function SetFields($username,$email)
    {
        //Instead of returning a newly blank form when the user fills registration incorrectly, we can have it return what they typed in just before:
        $this->username = $username;
        $this->email = $email;
    }
    
    public function Display()
    {
        $form = '<form method="post" action="index.php?Action=ProcessRegister&blogID=1">'
            . '<fieldset>'
            . '<legend>Account Registration</legend>';

      if ($this->errorMessage != '')
      {
        $form = $form.'<p>'.$this->errorMessage.'</p>';
      }
      
        $form = $form
            . '<p><input type="text" name="username" value="'.$this->username.'">'
            . '<label for="username">Username</label></p>'

            . '<p><input type="text" name="email" value="'.$this->email.'">'
            . '<label for="email">Email</label></p>'

            . '<p><input type="password" name="password">'
            . '<label for="password">Password</label></p>'

            . '<p><input type="password" name="confirmPassword">'
            . '<label for="confirmPassword">Confirm Password</label></p>'

            . '<p><input type="submit" id="submit" value="Register"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
