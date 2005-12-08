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
       $form = '<fieldset>'
           . '<legend><img src="UI/Themes/Images/Controls/edituser.png" id="controlbarimg" />  Edit My Account</legend>'
           . '<form method="post" action=index.php?Action=ProcessEditUserData&blogID='. $this->blogID . '>';

      if ($this->errorMessage != '')
      {
        $form .= '<p>'.$this->errorMessage.'</p>';
      }

        $form .= '<table id="formtable"><tr><td colspan="2">Change Email:</td></tr>'
            . '<tr><td><label for="email"> Email:</label></td><td><input type="text" name="email" value="' . $this->email . '"></td></tr>'
            . '<tr><td colspan="2">Change Password:</td></tr>'
            . '<tr><td><label for="oldPassword"> Current Password:</label></td><td><input type="password" name="oldPassword"></td></tr>'
            . '<tr><td><label for="newPassword"> New Password:</label></td><td><input type="password" name="newPassword"></td></tr>'
            . '<tr><td><label for="confirmNewPassword"> Confirm New Password:</label></td><td><input type="password" name="confirmNewPassword"></td></tr>'
            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Submit Changes"></td></tr></table>'
            . '</form></fieldset>';

        return $form;
    }
}

?>
