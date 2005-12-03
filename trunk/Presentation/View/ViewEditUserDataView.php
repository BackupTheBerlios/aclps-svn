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
            . '<legend>Edit Account</legend>';

      if ($this->errorMessage != '')
      {
        $form = $form.'<p>'.$this->errorMessage.'</p>';
      }

        $form = $form
            . '<p>Change Email</p>'
            . '<p><input type="text" name="email" value="' . $this->email . '"><label for="email">Email</label></p>'
            . '<p>Change Password:</p>'
            . '<p><input type="password" name="oldPassword"><label for="oldPassword">Old Password</label></p>'
            . '<p><input type="password" name="newPassword"><label for="newPassword">New Password</label></p>'
            . '<p><input type="password" name="confirmNewPassword"><label for="confirmNewPassword">Confirm New Password</label></p>'
            . '<p><input type="submit" id="submit" value="Submit Changes"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
