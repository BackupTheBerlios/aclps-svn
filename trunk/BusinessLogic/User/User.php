<?php

class BusinessLogic_User_User
{
    private $userInfo;
    private $permissions;
    
    private function __construct()
    {
        //Do Nothing
    }

    static public function GetInstance()
    {
    	if (!isset($_SESSION['BusinessLogic_User_User']))
	    {
	        $_SESSION['BusinessLogic_User_User'] = new BusinessLogic_User_User();
   	    }
   	    
	    return $_SESSION['BusinessLogic_User_User'];
    }

    public function HandleRequest()
    {
        $request = $_GET['Action'];
        switch($request)
    	{
            case 'EditUserData':
                return $this->EditUserData();
                break;
            case 'ProcessEditUserData':
                return $this->ProcessEditUserData();
                break;
            case 'ViewRegister':
                return $this->ViewRegister();
                break;
            case 'ProcessRegister':
                return $this->ProcessRegister();
                break;
            case 'ViewSignIn':
                return $this->ViewSignIn();
                break;
            case 'ProcessSignIn':
                return $this->ProcessSignIn();
                break;
            case 'ProcessSignOut':
                return $this->ProcessSignOut();
                break;
        	default:
    	       return BusinessLogic_Post_Post::GetInstance()->HandleRequest();
    	}
    }
    
    //**********************************
    //ACTION FUNCTIONS
    //**********************************

    public function EditUserData()
    {
        if ($this->CheckSignedIn())
        {
            return new Presentation_View_EditUserDataView($this->userInfo);
        }
        else
        {
            return new Exception('User is not signed in.');
        }
    }

    public function ProcessEditUserData()
    {
	   //Processes the form data in EditUserDataView and modifies the Users table.
	   //TODO
    }
    
    public function ViewRegister()
    {
        if (BusinessLogic_User_UserSecurity::GetInstance()->ViewRegister())
        {
            return new Presentation_View_ViewRegisterView();
        }
        else
        {
            return new Exception('You are not allowed to register at this time.');
        }
    }

    public function ProcessRegister()
    {
	//Processes the form data in EditUserDataView and modifies the Users table.
	//TODO
    }

    public function ViewSignIn()
    {
        if (BusinessLogic_User_UserSecurity::GetInstance()->ViewSignIn())
        {
            return new Presentation_View_ViewSignInView();
        }
        else
        {
            return new Exception('You are not allowed to Sign In at this time.');
        }
    }

    public function ProcessSignIn()
    {
	//Processes the form data in ViewSignInView and authenticates the user. Signs In the user if the credentials are valid.
	//TODO
    }

    public function ProcessSignOut()
    {
        //Destroy session
        $_SESSION = array();
        session_destroy();
        
        //Return to index
        header("Location: http://cs411.beoba.net/ACLPS/");
        exit;
    }

    //**********************************
    //NON-ACTION FUNCTIONS
    //**********************************
    
    public function GetUserID()
    {
        if ($this->CheckSignedIn())
        {
            return $this->userInfo['UserID'];
        }
        else
        {
            return new Exception('User is not signed in.');
        }
    }

    public function GetUserName()
    {
        if ($this->CheckSignedIn())
        {
            return $this->userInfo['UserName'];
        }
        else
        {
            return new Exception('User is not signed in.');
        }
    }

    public function GetPermissionForBlog($blogID)
    {
        if (isset($this->permissions[$blogID]))
        {
            return $this->permissions[$blogID];
        }
        else
        {
            return 'Nobody';
        }
    }
    
    private function CheckSignedIn()
    {
      if (isset($this->userInfo['UserID']))
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    
    public function GetTopBar()
    {
        return new Presentation_View_ViewTopBarView($this->CheckSignedIn());
    }
}

?>
