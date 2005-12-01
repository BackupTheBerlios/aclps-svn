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
            $_SESSION['BusinessLogic_User_User'] = serialize(new BusinessLogic_User_User());
        }
   	
        return unserialize($_SESSION['BusinessLogic_User_User']);
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
            if ($_POST['username'] != '' and $_POST['email'] != ''
                and $_POST['password'] != '' and $_POST['confirmPassword'] != '')
            {
                if ($_POST['password'] == $_POST['confirmPassword'])
                {
                    return $this->ProcessRegister($_POST['username'], $_POST['email'],
                                                  $_POST['password']);
                }
                else
                {
                    $tryagain = new Presentation_View_ViewRegisterView('Password and Confirmation Password do not match.');
                    $tryagain->SetFields($_POST['username'],$_POST['email']);
                    return $tryagain;
                }
            }
            else
            {
                return new Presentation_View_ViewRegisterView('You must fill in the entire form.');
            }
            
            break;
            
        case 'ViewSignIn':
            return $this->ViewSignIn();
            break;
        case 'ProcessSignIn':
            if ($_POST['username'] != '' and $_POST['password'] != '')
            {
                return $this->ProcessSignIn($_POST['username'], $_POST['password']);
            }
            else
            {
                return new Presentation_View_ViewSignInView('You must fill in the entire form.');
            }
            
            break;
        case 'ProcessSignOut':
            return $this->ProcessSignOut();
            break;
        case 'ViewSearch':
            return $this->ViewSearch();
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
        //if (BusinessLogic_User_UserSecurity::GetInstance()->ViewRegister())
        if (true)
        {
            return new Presentation_View_ViewRegisterView('');
        }
        else
        {
            return new Exception('You are not allowed to register at this time.');
        }
    }

    public function ProcessRegister($username, $email, $password)
    {
        $query = "select * from [0] where Username='[1]'";
        $arguments = array('Users', $username);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);

        //there is no matching record in the DB
        if (count($result) == 0)
        {
            $query = "insert into [0] (Username, Email, Password) VALUES ('[1]','[2]',sha1('[3]'))";
            $arguments = array('Users', $username, $email, $password);
            $result = $DataAccess->Insert($query, $arguments);
            
            $this->ProcessSignIn($username, $password);
        }
        else
        {
          return new Presentation_View_ViewRegisterView("The username $username is already in use.");
        }
    }

    public function ViewSignIn()
    {
        //if (BusinessLogic_User_UserSecurity::GetInstance()->ViewSignIn())
        if (true)
        {
            //'' is passed to indicate that there is no error message
            return new Presentation_View_ViewSignInView('');
        }
        else
        {
            return new Exception('You are not allowed to Sign In at this time.');
        }
    }

    public function ProcessSignIn($username, $password)
    {
        $query = "select UserID,Username,Email from [0] where Username='[1]' and Password=sha1('[2]')";
        $arguments = array('Users', $username, $password);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        


        //there is a matching record in the DB
        if (count($result) > 0)
        {
            //DataAccess wraps the rows into a new table. We need to get the first row, i.e. [0]
            $this->userInfo = $result[0];

            //Need to fill in permissions table
            $query = 'select BlogID, Auth from [0] where UserID=[1]';
            $arguments = array('User_Auth', $result[0]['UserID']);
            $result = $DataAccess->Select($query, $arguments);
            
            foreach ($result as $key=>$value)
            {
                $this->permissions[$value['BlogID']] = $value['Auth'];
            }

            //Need to store all this information
            $_SESSION['BusinessLogic_User_User'] = serialize($this);

            //We need to redirect since we cannot directly change the $_GET['Action']
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewDashboard&blogID=1';
            header("Location: $path");
            exit;
        }
        else
        {
          return new Presentation_View_ViewSignInView('There is no one who matches the supplied credentials.');
        }
        

    }

    public function ProcessSignOut()
    {
        //Destroy session
        $_SESSION = array();
        session_destroy();
        
        //Return to index
        $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php';
        header("Location: $path");
        exit;
    }

    //**********************************
    //Search-Action Functions
    //**********************************
    public function ViewSearch()
    {
        if (true)
        {
            //'' is passed to indicate that there is no error message
            return new Presentation_View_ViewSearchView('');
        }
        else
        {
            return new Exception('You are not allowed to Sign In at this time.');
        }
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
            return $this->userInfo['Username'];
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
        print_r($this->userInfo);
        print '<br />';
        print_r($this->permissions);
        print '<br />';
        return new Presentation_View_ViewTopBarView($this->CheckSignedIn());
    }
    
    public static function ConvertUserIDToName($userID)
    {
        if (!$userID)
        {
            throw new Exception('No UserID was provided!');
        }
        $query = "select Username from [0] where UserID=[1]";
        $arguments = array('Users', $userID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        
        if (isset($result[0]['Username']))
        {
            return $result[0]['Username'];
        }
        else
        {
            throw new Exception('Username does not exist for specified UserID');
        }
    }
    
    public function IsUserBlogOwner()
    {
        if ($this->CheckSignedIn())
        {
            $hasBlog = false;
            
            foreach($this->permissions as $key=>$value)
            {
                if ($value == 'Owner')
                {
                  $hasBlog = true;
                }
            }
            
            return $hasBlog;
        }
        else
        {
          throw new Exception('User is not logged in.');
        }
    }
    
    public function GetUserBlogID()
    {
      if ($this->IsUserBlogOwner)
      {
            $blogID = -1;
            
            foreach($this->permissions as $key=>$value)
            {
                if ($value == 'Owner')
                {
                  $blogID = $key;
                }
            }

            return $blogID;
      }
      else
      {
        throw new Exception('User does not own a blog.');
      }
    }

}

?>
