<?php

class Presentation_View_ViewTopBarView extends Presentation_View_View
{

    private $blogId;
    private $signedIn;

    public function __construct($blogID, $signedIn)
    {
        $this->blogID = $blogID;
        $this->signedIn = $signedIn;
    }
    public function Display()
    {
        if ($this->signedIn)
        {
            $ret =    '<a href=index.php?Action=ViewDashboard&blogID='    . $this->blogID . '>My Dashboard</a>'
                    . '&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=EditUserData&blogID='     . $this->blogID . '>Edit My Account</a>'
                    . '&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ViewSearch&blogID='       . $this->blogID . '>Search</a>'
                    . '&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ProcessSignOut&blogID=1>Sign Out</a>';
        }
        else
        {
            $ret =    '<a href=index.php?Action=ViewSignIn&blogID='       . $this->blogID . '>Sign In</a>'
                    . '&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ViewRegister&blogID='     . $this->blogID . '>Register An Account</a>'
                    . '&nbsp;|&nbsp;'
                    . '<a href=index.php?Action=ViewSearch&blogID='       . $this->blogID . '>Search</a>';
        }
        
        return $ret;
    }
}

?>
