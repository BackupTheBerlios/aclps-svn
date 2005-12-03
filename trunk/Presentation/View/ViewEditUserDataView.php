<?php

class Presentation_View_ViewEditUserDataView extends Presentation_View_View
{

    private $blogID;
    private $email;
    private $errorMessage;

    public function __construct($blogID, $email, $errorMessage)
    {
        $this->blogID = $blogID;
        $this->email = $email;
        $this->errorMessage = $errorMessage;
    }

    public function Display()
    {
       $form = '<form method="post" action=index.php?Action=ProcessEditUserData&blogID='. $this->blogID . '>'
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
            . '</p>'
            . '<label for="email">Email:</label>'
            . '<input type="text" name="email" value="' . $this->email . '">'
            . '<br />'
            . '<br />'
            . '<label for="oldpassword">Old Password:</label>'
            . '<input type="password" name="oldpassword">'
            . '<br />'
            . '<label for="newPassword">New Password:</label>'
            . '<input type="password" name="newPassword">'
            . '<br />'
            . '<label for="confirmNewPassword">Confirm:</label>'
            . '<input type="password" name="confirmNewPassword">'
            . '<br />'
            . '<input type="submit" class="submit-register" value="Submit Changes">'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
