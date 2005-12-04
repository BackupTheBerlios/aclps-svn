<?php

class BusinessLogic_User_User
{
    private $userInfo;
    private $permissions;
    
    private function __construct()
    {
        //Do nothing
    }

    static public function GetInstance()
    {
    	if (!isset($_SESSION['BusinessLogic_User_User']))
        {
            $_SESSION['BusinessLogic_User_User'] = serialize(new BusinessLogic_User_User());
        }
   	
        return unserialize($_SESSION['BusinessLogic_User_User']);
    }

    //**********************************
    //ACTION FUNCTIONS
    //**********************************

    public function EditUserData()
    {
        if ($this->CheckSignedIn())
        {
            return new Presentation_View_ViewEditUserDataView($_GET['blogID'], $this->userInfo['Email'], '');
        }
        else
        {
            throw new Exception('User is not signed in.');
        }
    }

    public function ProcessEditUserData($email, $oldPassword, $newPassword)
    {
        if ($this->CheckSignedIn())
        {
            $aDataAccess = DataAccess_DataAccessFactory::GetInstance();
            
            //Do we need to update the user's email info
            if ($email != $this->userInfo['Email'])
            {
                $query = "update [0] set Email='[1]' where UserID=[2]";
                $arguments = array('Users', $email, $this->GetUserID());
                $result = $aDataAccess->Update($query, $arguments);
                $this->userInfo['Email'] = $email;

                //Need to store all this information
                $_SESSION['BusinessLogic_User_User'] = serialize($this);
            }

            //Does the user want to change their password, order is important in checking intent
            if ($oldPassword != '' and $newPassword != '')
            {
                $query = "select * from [0] where UserID=[1] and Password=SHA1('[2]')";
                $arguments = array('Users', $this->GetUserID(), $oldPassword);
                $result = $aDataAccess->Select($query, $arguments);
                
                //Password match
                if (count($result) > 0)
                {
                    $query = "update [0] set Password = SHA1('[1]') where UserID=[2]";
                    $arguments = array('Users', $newPassword, $this->GetUserID());
                    $aDataAccess->Update($query, $arguments);
                }
                else
                {
                    return new Presentation_View_ViewEditUserDataView($_GET['blogID'], $this->userInfo['Email'], 'Old Password provided is invalid.');
                }
                
                $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewBlog&blogID=' . $_GET['blogID'];
                header("Location: $path");
                exit;
            }
            elseif ($oldPassword != '' or $newPassword != '')
            {
                    return new Presentation_View_ViewEditUserDataView($_GET['blogID'], $this->userInfo['Email'], 'The password fields were not filled in correctly.');
            }
            else
            {
                $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewBlog&blogID=' . $_GET['blogID'];
                header("Location: $path");
                exit;
            }
        }
        else
        {
            throw new Exception('User is not signed in.');
        }
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
          return new Presentation_View_ViewSignInView('Unknown Username/Password combination.');
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
    
    public function AcceptInvitation($invitingBlogID)
    {
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        
        $query = "select Rank from [0] where UserID=[1] and BlogID=[2]";
        $arguments = array('Invitations', $this->GetUserID(), $invitingBlogID);
        $result = $DataAccess->Select($query, $arguments);
        
        if (count($result) > 0)
        {
            $rank = $result[0]['Rank'];
            
            $query = "delete from [0] where UserID=[1] and BlogID=[2]";
            $arguments = array('Invitations', $this->GetUserID(), $invitingBlogID);
            $result = $DataAccess->Delete($query, $arguments);
            
            $query = "insert into [0](UserID, BlogID, Auth) VALUES('[1]', '[2]', '[3]')";
            $arguments = array('User_Auth', $this->GetUserID(), $invitingBlogID, $rank);
            $result = $DataAccess->Insert($query, $arguments);
            
            $this->UpdatePermissions();
            
            //Return to dashboard
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewDashboard&blogID=' . $_GET['blogID'];
            header("Location: $path");
            exit;
        }
        else
        {
            throw new Exception('Invalid invitation.');
        }

    }
    
    public function DeclineInvitation($invitingBlogID)
    {
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();

        $query = "select Rank from [0] where UserID=[1] and BlogID=[2]";
        $arguments = array('Invitations', $this->GetUserID(), $invitingBlogID);
        $result = $DataAccess->Select($query, $arguments);

        if (count($result) > 0)
        {
            $query = "delete from [0] where UserID=[1] and BlogID=[2]";
            $arguments = array('Invitations', $this->GetUserID(), $invitingBlogID);
            $result = $DataAccess->Delete($query, $arguments);

            //Return to dashboard
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewDashboard&blogID=' . $_GET['blogID'];
            header("Location: $path");
            exit;
        }
        else
        {
            throw new Exception('Invalid invitation.');
        }

    }
    
    public function ViewInvitation($blogID)
    {
        $permission = $this->GetPermissionForBlog($this->GetUserID());
        if ($permission = 'Owner' or 'Editor')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();
            
            $query = 'select UserID, Rank from [0] where BlogID=[1]';
            $arguments = array('Invitations', $blogID);
            $invitationsResult = $DataAccess->Select($query, $arguments);

            $invitations = array();

            if (count($invitationsResult) > 0)
            {
                foreach ($invitationsResult as $key=>$value)
                {
                    $query = 'select Username from [0] where UserID=[1]';
                    $arguments = array('Users', $value['UserID']);
                    $result = $DataAccess->Select($query, $arguments);

                    $username = $result[0]['Username'];
                    $rank = $value['Rank'];

                    $invitations[$username] = $rank;

                }
            }

            $aViewInvitationCollectionView = new Presentation_View_ViewInvitationCollectionView();

            foreach($invitations as $username=>$rank)
            {
                $aViewInvitationCollectionView->AddView(new Presentation_View_ViewInvitationView($username, $rank));
            }
            
            return $aViewInvitationCollectionView;
        }
        else
        {
            throw new Exception('Access Denied.');
        }

    }
    
    public function AddInvitation($blogID, $errorMessage)
    {
        $permission = $this->GetPermissionForBlog($this->GetUserID());
        if ($permission = 'Owner' or 'Editor')
        {
            return new Presentation_View_ViewAddInvitationView($blogID, $this->GetRankList($blogID), $errorMessage);
        }
        else
        {
            throw new Exception('Access Denied');
        }

    }
    
    public function ProcessAddInvitation($blogID, $username, $rank)
    {
        $permission = $this->GetPermissionForBlog($this->GetUserID());
        if ($permission = 'Owner' or 'Editor')
        {
            //username exists?
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            $query = "select * from [0] where username='[1]'";
            $arguments = array('Users', $username);
            $result = $DataAccess->Select($query, $arguments);
            
            if (count($result) > 0)
            {
                $userID = $result[0]['UserID'];

                //Is there already an invitation
                $query = 'select * from [0] where UserID=[1]';
                $arguments = array('Invitations', $userID);
                $result = $DataAccess->Select($query, $arguments);
                
                if(count($result) < 1)
                {
                    //insert invitation
                    $query = "insert into [0] (UserID, BlogID, Rank) VALUES('[1]', '[2]', '[3]')";
                    $arguments = array('Invitations', $userID, $blogID, $rank);
                    $result = $DataAccess->Insert($query, $arguments);
                    
                    $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=EditMembership&blogID=' . $blogID;
                    header("Location: $path");
                    exit;
                }
                else
                {
                    return $this->AddInvitation($blogID, 'An invitation already exists for this user.');
                }
            }
            else
            {
                return $this->AddInvitation($blogID, 'Unknown user.');
            }
        }
        else
        {
            throw new Exception('Access Denied');
        }

    }
    
    public function DeleteInvitation($blogID)
    {
        $permission = $this->GetPermissionForBlog($this->GetUserID());
        if ($permission = 'Owner' or 'Editor')
        {
            //
        }
        else
        {
            throw new Exception('Access Denied');
        }

    }
    
    public function ProcessDeleteInvitation($blogID)
    {
        $permission = $this->GetPermissionForBlog($this->GetUserID());
        if ($permission = 'Owner' or 'Editor')
        {
            //
        }
        else
        {
            throw new Exception('Access Denied');
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
        return new Presentation_View_ViewTopBarView($_GET['blogID'], $this->CheckSignedIn());
    }
    
    public static function ConvertUserIDToName($userID)
    {
        if (!$userID)
        {
            throw new Exception('No UserID was provided!');
        }
        //check cache, return if its there
        if (isset($_SESSION['BusinessLogic_User_User_UserIDTable'][$userID]))
        {
            return $_SESSION['BusinessLogic_User_User_UserIDTable'][$userID];
        }
        //find in DB if not in cache:
        $query = "select Username from [0] where UserID=[1]";
        $arguments = array('Users', $userID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        
        if (isset($result[0]['Username']))
        {
            //store to cache
            $_SESSION['BusinessLogic_User_User_UserIDTable'][$userID] = $result[0]['Username'];
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
                    return true;
                }
            }
            return false;
        }
        else
        {
            throw new Exception('User is not logged in.');
        }
    }
    
    public function GetUserBlogID()
    {
      if ($this->IsUserBlogOwner())
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
    
    public function NewBlog($blogID)
    {
        if (!$this->IsUserBlogOwner())
        {
            //TODO: Should also check that the blogID does't already have an owner...
            $query = "insert into [0] (UserID, BlogID, Auth) values('[1]', '[2]', '[3]')";
            $arguments = array('User_Auth', $this->GetUserID(), $blogID, 'Owner');

            $DataAccess = DataAccess_DataAccessFactory::GetInstance();
            $result = $DataAccess->Insert($query, $arguments);
            
            $this->UpdatePermissions();
            
            return true;
        }
        else
        {
            throw new Exception('User already owns a blog.');
        }
    }

    //Deletes all members of a blog
    public function DeleteBlog($blogID)
    {
        if ($this->IsUserBlogOwner())
        {
            if ($this->GetUserBlogID() == $blogID)
            {
                $query = "delete from [0] where BlogID=[1]";
                $arguments = array('User_Auth', $blogID);

                $DataAccess = DataAccess_DataAccessFactory::GetInstance();
                $result = $DataAccess->Delete($query, $arguments);

                $this->UpdatePermissions();

                return true;
            }
            else
            {
                throw new Exception('User does not own the specified blog.');
            }
        }
        else
        {
            throw new Exception("User doesn't own a blog.");
        }
    }

    public function AddPermission($userID, $blogID, $auth)
    {
        //should use NewBlog for this case
        if ($auth != 'Owner')
        {
            $query = "insert into [0] (UserID, BlogID, Auth) values('[1]', '[2]', '[3]')";
            $arguments = array('User_Auth', $this->GetUserID(), $blogID, $auth);

            $DataAccess = DataAccess_DataAccessFactory::GetInstance();
            $result = $DataAccess->Insert($query, $arguments);

            if ($userID == $this->GetUserID())
            {
                $this->UpdatePermissions();
            }
            
            return true;
        }
        else
        {
          throw new Exception('Illegal function call: Owners cannot be added using this function.');
        }
    }

    public function RemovePermission($userID, $blogID)
    {
        $query = "select Auth from [0] where UserID=[1] and BlogID=[2]";
        $arguments = array('User_Auth', $userID, $blogID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        $row = $result[0];
        
        if ($row['Auth'] != 'Owner')
        {
            $query = "delete from [0] where UserID=[1] and BlogID=[2]";
            $arguments = array('User_Auth', $userID, $blogID);
            
            if ($userID == $this->GetUserID())
            {
                $this->UpdatePermissions();
            }
            
            return true;
        }
        else
        {
            throw new Exception('Illegal function call: Owners cannot be deleted using this function.');
        }
    }
    
    private function UpdatePermissions()
    {
        $this->permissions = array();
        
        $query = 'select BlogID, Auth from [0] where UserID=[1]';
        $arguments = array('User_Auth', $this->GetUserID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        
        foreach ($result as $key=>$value)
        {
            $this->permissions[$value['BlogID']] = $value['Auth'];
        }
        
        //Need to store all this information
        $_SESSION['BusinessLogic_User_User'] = serialize($this);
    }
    
    public function ChangeOwnerShip($blogID, $currentOwner, $newOwner)
    {
        //TODO
    }
    
    public function GetRankList($blogID)
    {
        switch($this->GetPermissionForBlog($blogID))
        {
            case 'Owner':
                return array('Author', 'Editor');
            case 'Editor':
                return array('Author');
            default:
                throw new Exception('Access Denied.');
        }
    }
    
    //****************************************
    //          The Handler
    //****************************************
    
    public function HandleRequest()
    {
        $request = $_GET['Action'];
        switch($request)
    	{
        case 'EditUserData':
            return $this->EditUserData();
            break;
        case 'ProcessEditUserData':
            if ($_POST['newPassword'] == $_POST['confirmNewPassword'])
            {
                if ($_POST['email'] != '')
                {
                    return $this->ProcessEditUserData($_POST['email'], $_POST['oldPassword'], $_POST['newPassword']);
                }
                else
                {
                    return new Presentation_View_ViewEditUserDataView($_GET['blogID'], $this->userInfo['Email'], 'Email cannot be blank.');
                }
            }
            else
            {
                return new Presentation_View_ViewEditUserDataView($_GET['blogID'], $this->userInfo['Email'], 'New Password and Confirmation Password do not match.');
            }
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

        case 'AcceptInvitation':
            if (isset($_GET['invitingBlogID']))
            {
                return $this->AcceptInvitation($_GET['invitingBlogID']);
            }
            else
            {
                throw new Exception('Malformed Action Request.');
            }
            break;

        case 'DeclineInvitation':
            if (isset($_GET['invitingBlogID']))
            {
                return $this->DeclineInvitation($_GET['invitingBlogID']);
            }
            else
            {
                throw new Exception('Malformed Action Request.');
            }
            break;

        case 'ViewInvitation':
            return $this->ViewInvitation($_GET['blogID']);
            break;

        case 'AddInvitation':
            return $this->AddInvitation($_GET['blogID'], '');
            break;

        case 'ProcessAddInvitation':
            if (isset($_POST['username']) and $_POST['rank'])
            {
                return $this->ProcessAddInvitation($_GET['blogID'], $_POST['username'], $_POST['rank']);
            }
            else
            {
                return $this->AddInvitation($_GET['blogID'], 'Form not filled in completely.');
            }

            break;

        case 'RemoveInvitation':
            return $this->RemoveInvitation($_GET['blogID']);
            break;

        default:
            return BusinessLogic_Post_Post::GetInstance()->HandleRequest();
    	}
    }

}

?>
