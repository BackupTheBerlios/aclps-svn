<?php

class Presentation_View_ViewTopBarView extends Presentation_View_View
{
    private $signedIn;

    public function __construct($signedIn)
    {
        $this->signedIn = $signedIn;
    }
    public function Display()
    {
        if ($this->signedIn)
        {
            $ret = '<a href=index.php?Action=ViewDashBoard&blogID=1>My DashBoard</a>&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=EditUserData&blogID=1>Edit My Account</a>&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ProcessSignOut&blogID=1>Sign Out</a>';
        }
        else
        {
            $ret = '<a href=index.php?Action=ViewSignIn&blogID=1>Sign In</a>&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ViewRegister&blogID=1>Register An Account</a>';
        }
        
        return $ret;
    }
}

?>
