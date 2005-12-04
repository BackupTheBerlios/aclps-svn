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
            . '<table id="formtable"><tr><td colspan="2">Change Email:</td></tr>'
            . '<tr><td><label for="email"> Email:</label></td><td><input type="text" name="email" value="' . $this->email . '"></td></tr>'
            . '<tr><td colspan="2">Change Password:</td></tr>'
            . '<tr><td><label for="oldPassword"> Current Password:</label></td><td><input type="password" name="oldPassword"></td></tr>'
            . '<tr><td><label for="newPassword"> New Password:</label></td><td><input type="password" name="newPassword"></td></tr>'
            . '<tr><td><label for="confirmNewPassword"> Confirm New Password:</label></td><td><input type="password" name="confirmNewPassword"></td></tr>'
            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Submit Changes"></td></tr></table>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
