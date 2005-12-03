<?php

class Presentation_View_ViewEditUserDataView extends Presentation_View_View
{

    private $blogID;
    private $email;
    private $errorMessage;

    public function __construct($blogID, $email, $errorMessage)
    {
        $this->email = $email;
        $this->errorMessage = $errorMessage;
    }

    public function Display()
    {
       $form = '<form method="post" action="index.php?Action=ProcessEditUserData&blogID="'. $this->blogID . '">'
            . '<fieldset>'
            . '<legend>&nbsp;Edit Account</legend>'
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
            . '<label for="email">Email:</label>'
            . '<input type="text" name="email" value="'.$this->email.'">'
            . '</fieldset>'
            . '<br />'
            . '<fieldset>'
            . '<legend>&nbsp;Change Passowrd</legend>'
            . '<label for="oldpassword">Old Password:</label>'
            . '<input type="password" name="oldpassword">'
            . '<br />'
            . '<label for="newPassword">New Password:</label>'
            . '<input type="password" name="newPassword">'
            . '<br />'
            . '<label for="confirmNewPassword">Confirm New Password:</label>'
            . '<input type="password" name="confirmNewPassword">'
            . '<br />'
            . '<input type="submit" class="submit-register" value="Register">'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
