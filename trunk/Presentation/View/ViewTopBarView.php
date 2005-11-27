<?php

class Presentation_View_ViewRegisterView extends Presentation_View_View
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
            $ret = '<a href=index.php?Action=ViewDashBoard>My DashBoard</a>&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=EditUserData>Edit My Account</a>&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ProcessSignOut>Sign Out</a>';
        }
        else
        {
            $ret = '<a href=index.php?Action=ViewSignIn>Sign In</a>&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ViewRegister>Register An Account</a>';
        }
        
        return $ret;
    }
}

?>
