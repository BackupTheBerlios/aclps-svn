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
            $ret =    '<img src="UI/Themes/Images/Controls/home.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ViewBlog&blogID=1">ACLPS Home</a> | '
                .'<img src="UI/Themes/Images/Controls/dashboard.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ViewDashboard&blogID='.$this->blogID.'">My Dashboard</a> | '
                .'<img src="UI/Themes/Images/Controls/edituser.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=EditUserData&blogID='.$this->blogID.'">Edit My Account</a> | '
                .'<img src="UI/Themes/Images/Controls/signout.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ProcessSignOut&blogID=1">Sign Out</a> |'
                .'<img src="UI/Themes/Images/Controls/search.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ViewSearch&blogID='.$this->blogID.'">Search</a>';
        }
        else
        {
            $ret =    '<img src="UI/Themes/Images/Controls/home.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ViewBlog&blogID=1">ACLPS Home</a> | '
                .'<img src="UI/Themes/Images/Controls/dashboard.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ViewRegister&blogID='. $this->blogID.'">Register An Account</a> | '
                .'<img src="UI/Themes/Images/Controls/signin.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ViewSignIn&blogID='.$this->blogID.'">Sign In</a> | '
                .'<img src="UI/Themes/Images/Controls/search.png" id="controlbarimg" /> '
                .'<a href="index.php?Action=ViewSearch&blogID='. $this->blogID.'">Search</a>';
        }
        
        return $ret;
    }
}

?>
