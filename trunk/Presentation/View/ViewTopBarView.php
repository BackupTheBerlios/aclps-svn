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
            $ret = ''
                .'<a href="index.php?Action=ViewBlog&blogID=1"><img src="UI/Themes/Images/Controls/home.png" id="controlbarimg" />ACLPS Home</a> '
                .'<a href="index.php?Action=ViewDashboard&blogID='.$this->blogID.'"><img src="UI/Themes/Images/Controls/dashboard.png" id="controlbarimg" />Dashboard</a> '
                .'<a href="index.php?Action=EditUserData&blogID='.$this->blogID.'"><img src="UI/Themes/Images/Controls/edituser.png" id="controlbarimg" />Edit My Account</a> '
                .'<a href="index.php?Action=ProcessSignOut&blogID=1"><img src="UI/Themes/Images/Controls/signout.png" id="controlbarimg" />Sign Out</a> '
                .'<a href="index.php?Action=ViewSearch&blogID='.$this->blogID.'"><img src="UI/Themes/Images/Controls/search.png" id="controlbarimg" />Search</a> ';
        }
        else
        {
            $ret = '<a href="index.php?Action=ViewBlog&blogID=1"><img src="UI/Themes/Images/Controls/home.png" id="controlbarimg" />ACLPS Home</a> '
                .'<a href="index.php?Action=ViewRegister&blogID='. $this->blogID.'"><img src="UI/Themes/Images/Controls/register.png" id="controlbarimg" />Register An Account</a> '
                .'<a href="index.php?Action=ViewSignIn&blogID='.$this->blogID.'"><img src="UI/Themes/Images/Controls/signin.png" id="controlbarimg" />Sign In</a> '
                .'<a href="index.php?Action=ViewSearch&blogID='. $this->blogID.'"><img src="UI/Themes/Images/Controls/search.png" id="controlbarimg" />Search</a> ';
        }
        
        return $ret;
    }
}

?>
