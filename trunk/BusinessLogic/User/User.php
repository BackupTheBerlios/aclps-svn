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
                $query = 'update [0] set Email="[1]" where UserID="[2]"';
                $arguments = array('Users', $email, $this->GetUserID());
                $result = $aDataAccess->Update($query, $arguments);
                $this->userInfo['Email'] = $email;

                //Need to store all this information
                $_SESSION['BusinessLogic_User_User'] = serialize($this);
            }

            //Does the user want to change their password, order is important in checking intent
            if ($oldPassword != '' and $newPassword != '')
            {
                $query = 'select * from [0] where UserID="[1]" and Password=SHA1("[2]")';
                $arguments = array('Users', $this->GetUserID(), $oldPassword);
                $result = $aDataAccess->Select($query, $arguments);
                
                //Password match
                if (count($result) > 0)
                {
                    $query = 'update [0] set Password = SHA1("[1]") where UserID="[2]"';
                    $arguments = array('Users', $newPassword, $this->GetUserID());
                    $aDataAccess->Update($query, $arguments);
                }
                else
                {
                    return new Presentation_View_ViewEditUserDataView($_GET['blogID'], $this->userInfo['Email'], 'Old Password provided is invalid.');
                }
                //after change, forward user to blog frontpage:
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
                //forward user to blog frontpage:
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
        $query = 'select * from [0] where Username="[1]"';
        $arguments = array('Users', $username);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);

        //there is no matching record in the DB
        if (count($result) == 0)
        {
            $query = 'insert into [0] (Username, Email, Password) VALUES ("[1]","[2]",sha1("[3]"))';
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
            return new Exception('You are not allowed to sign in at this time.');
        }
    }

    public function ProcessSignIn($username, $password)
    {
        $query = 'select UserID,Username,Email from [0] where Username="[1]" and Password=sha1("[2]")';
        $arguments = array('Users', $username, $password);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        


        //there is a matching record in the DB
        if (count($result) > 0)
        {
            //DataAccess wraps the rows into a new table. We need to get the first row, i.e. [0]
            $this->userInfo = $result[0];

            //Need to fill in permissions table
            $query = 'select BlogID, Auth from [0] where UserID="[1]"';
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
        
        $query = 'select Rank from [0] where UserID="[1]" and BlogID="[2]"';
        $arguments = array('Invitations', $this->GetUserID(), $invitingBlogID);
        $result = $DataAccess->Select($query, $arguments);
        
        if (count($result) > 0)
        {
            $rank = $result[0]['Rank'];
            
            $query = 'delete from [0] where UserID="[1]" and BlogID="[2]"';
            $arguments = array('Invitations', $this->GetUserID(), $invitingBlogID);
            $result = $DataAccess->Delete($query, $arguments);
            
            $query = 'insert into [0] (UserID, BlogID, Auth) VALUES("[1]", "[2]", "[3]")';
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

        $query = 'select Rank from [0] where UserID="[1]" and BlogID="[2]"';
        $arguments = array('Invitations', $this->GetUserID(), $invitingBlogID);
        $result = $DataAccess->Select($query, $arguments);

        if (count($result) > 0)
        {
            $query = 'delete from [0] where UserID="[1]" and BlogID="[2]"';
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
        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();
            
            $query = 'select UserID, Rank from [0] where BlogID="[1]"';
            $arguments = array('Invitations', $blogID);
            $invitationsResult = $DataAccess->Select($query, $arguments);

            $invitations = array();

            if (count($invitationsResult) > 0)
            {
                foreach ($invitationsResult as $key=>$value)
                {
                    $query = 'select Username from [0] where UserID="[1]"';
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
        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner' or $permission == 'Editor')
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
        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            //username exists?
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            $query = 'select * from [0] where username="[1]"';
            $arguments = array('Users', $username);
            $userResult = $DataAccess->Select($query, $arguments);
            
            //username exists
            if (count($userResult) > 0)
            {
                $userID = $userResult[0]['UserID'];
                
                //Is user already part of your blog
                $query = 'select * from [0] where UserID="[1]" and BlogID="[2]"';
                $arguments = array('User_Auth', $userID, $blogID);
                $authResult = $DataAccess->Select($query, $arguments);
                
                //the user is not part of your blog
                if (count($authResult) < 1)
                {
                    //Is there already an invitation?
                    $query = 'select * from [0] where UserID="[1]"';
                    $arguments = array('Invitations', $userID);
                    $result = $DataAccess->Select($query, $arguments);

                    //there isn't a pre-existing invitation
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
                    return $this->AddInvitation($blogID, 'This user is already a member of your blog.');
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
    
    public function RemoveInvitation($blogID)
    {
        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            $query = 'select * from [0] where blogID="[1]"';
            $arguments = array('Invitations', $blogID);
            $invitationResult = $DataAccess->Select($query, $arguments);
            
            $aViewRemoveInvitationCollectionView = new Presentation_View_ViewRemoveInvitationCollectionView($blogID);

            foreach($invitationResult as $key=>$value)
            {
                $userID = $value['UserID'];
                $rank = $value['Rank'];
                
                $query = 'select Username from [0] where UserID="[1]"';
                $arguments = array('Users', $userID);
                $result = $DataAccess->Select($query, $arguments);
                
                $username = $result[0]['Username'];
                
                $aViewRemoveInvitationCollectionView->AddView(new Presentation_View_ViewRemoveInvitationView($userID, $username, $rank));
            }
            
            return $aViewRemoveInvitationCollectionView;
        }
        else
        {
            throw new Exception('Access Denied');
        }

    }
    
    public function ProcessRemoveInvitation($blogID)
    {
        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            foreach ($_POST as $userID=>$value)
            {
                $query = 'select * from [0] where UserID="[1]" and blogID="[2]"';
                $arguments = array('Invitations', $userID, $blogID);
                $result = $DataAccess->Select($query, $arguments);
                
                if (count($result) > 0)
                {
                    $query = 'delete from [0] where UserID="[1]" and blogID="[2]"';
                    $arguments = array('Invitations', $userID, $blogID);
                    $result = $DataAccess->Delete($query, $arguments);
                }
            }
            
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=EditMembership&blogID=' . $blogID;
            header("Location: $path");
            exit;
        }
        else
        {
            throw new Exception('Access Denied');
        }

    }
    
    public function ChangeMemberRank($blogID)
    {
        $permission = $this->GetPermissionForBlog($blogID);
        
        if ($permission == 'Owner')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            $query = 'select * from [0] where blogID="[1]" and Auth!="Owner"';
            $arguments = array('User_Auth', $blogID);
            $memberResult = $DataAccess->Select($query, $arguments);
            
            $aViewChangeMemberRankCollectionView = new Presentation_View_ViewChangeMemberRankCollectionView($blogID);

            foreach($memberResult as $key=>$value)
            {
                $userID = $value['UserID'];
                $rank = $value['Auth'];

                $query = 'select Username from [0] where UserID="[1]"';
                $arguments = array('Users', $userID);
                $result = $DataAccess->Select($query, $arguments);

                $username = $result[0]['Username'];

                $aViewChangeMemberRankCollectionView->AddView(new Presentation_View_ViewChangeMemberRankView($userID, $username, $rank, $this->GetRankList($blogID)));
            }
            return $aViewChangeMemberRankCollectionView;
        }
        else
        {
            throw new Exception('Access Denied');
        }
            
    }
    
    public function ProcessChangeMemberRank($blogID)
    {
        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            foreach ($_POST as $userID=>$auth)
            {
                $query = 'select * from [0] where UserID="[1]" and blogID="[2]"';
                $arguments = array('User_Auth', $userID, $blogID);
                $result = $DataAccess->Select($query, $arguments);

                if (count($result) > 0)
                {
                    //these are the only accaptable ranks
                    if ($auth == 'Author' or $auth == 'Editor')
                    {
                        $query = 'update [0] set Auth="[1]" where UserID="[2]" and blogID="[3]"';
                        $arguments = array('User_Auth', $auth, $userID, $blogID);
                        $result = $DataAccess->Update($query, $arguments);
                    }
                    else
                    {
                        throw new Exception('Invalid rank selection');
                    }
                }
            }

            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=EditMembership&blogID=' . $blogID;
            header("Location: $path");
            exit;
        }
        else
        {
            throw new Exception('Access Denied');
        }

    }
    
    public function RemoveMember($blogID)
    {
        $permission = $this->GetPermissionForBlog($blogID);
        
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            //The owner can delete anyone (except himself), editors can delete authors
            if ($permission == 'Owner')
            {
                $query = 'select * from [0] where blogID="[1]" and Auth!="Owner"';
            }
            else //$permission == Editor
            {
                $query = 'select * from [0] where blogID="[1]" and Auth="Author"';
            }

            $arguments = array('User_Auth', $blogID);
            $memberResult = $DataAccess->Select($query, $arguments);

            $aViewRemoveMemberCollectionView = new Presentation_View_ViewRemoveMemberCollectionView($blogID);

            foreach($memberResult as $key=>$value)
            {
                $userID = $value['UserID'];
                $rank = $value['Auth'];

                $query = 'select Username from [0] where UserID="[1]"';
                $arguments = array('Users', $userID);
                $result = $DataAccess->Select($query, $arguments);

                $username = $result[0]['Username'];

                $aViewRemoveMemberCollectionView->AddView(new Presentation_View_ViewRemoveMemberView($userID, $username, $rank));
            }

            return $aViewRemoveMemberCollectionView;
        }
        else
        {
            throw new Exception('Access Denied');
        }

    }
    
    public function ProcessRemoveMember($blogID)
    {
        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            foreach ($_POST as $userID=>$value)
            {
                //Is user in the system?
                $query = 'select * from [0] where UserID="[1]" and blogID="[2]"';
                $arguments = array('User_Auth', $userID, $blogID);
                $result = $DataAccess->Select($query, $arguments);

                if (count($result) > 0)
                {
                    if ($this->CanDeleteUser($blogID, $userID))
                    {
                        $query = 'delete from [0] where UserID="[1]" and blogID="[2]"';
                        $arguments = array('User_Auth', $userID, $blogID);
                        $result = $DataAccess->Delete($query, $arguments);
                    }
                    else
                    {
                        throw new Exception('You are not authorized to delete this user.');
                    }
                }
                else
                {
                    //We are not throwing an exception since the there are other things in $_POST besides userids
                }
            }

            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=EditMembership&blogID=' . $blogID;
            header("Location: $path");
            exit;
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
    
    public function CheckSignedIn()
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
        $query = 'select Username from [0] where UserID="[1]"';
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
            $query = 'insert into [0] (UserID, BlogID, Auth) values("[1]", "[2]", "[3]")';
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
                $query = 'delete from [0] where BlogID="[1]"';
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
            $query = 'insert into [0] (UserID, BlogID, Auth) values("[1]", "[2]", "[3]")';
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
        $query = 'select Auth from [0] where UserID="[1]" and BlogID="[2]"';
        $arguments = array('User_Auth', $userID, $blogID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        $row = $result[0];
        
        if ($row['Auth'] != 'Owner')
        {
            $query = 'delete from [0] where UserID="[1]" and BlogID="[2]"';
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
        
        $query = 'select BlogID, Auth from [0] where UserID="[1]"';
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
    
    public function CanDeleteUser($blogID, $userID)
    {

        $permission = $this->GetPermissionForBlog($blogID);
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            $DataAccess = DataAccess_DataAccessFactory::GetInstance();

            //get rank of user in blog
            $query = 'select Auth from [0] where UserID="[1]" and BlogID="[2]"';
            $arguments = array('User_Auth', $userID, $blogID);
            $result = $DataAccess->Select($query, $arguments);

            if (count($result) > 0)
            {
                $userAuth = $result[0]['Auth'];
                
                switch($userAuth)
                {
                    case 'Editor':
                        if ($permission == 'Owner')
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                        break;
                    
                    case 'Author':
                        if ($permission == 'Owner' or $permission == 'Editor')
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                        break;
                    
                    default:
                        return false;
                        break;
                }
            }
            else
            {
                throw new Exception('User is not part of blog.');
            }
        }
        else
        {
            throw new Exception('Access Denied.');
        }
    }
    
    private function VerifyUsername($name)
    {
        //remove all whitespace
        $name = str_replace(' ', '', $name);
        
        //name is in length range [5,15]
        if (strlen($name) > 4 and strlen($name) < 16)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    private function VerifyPassword($password)
    {
        //remove all whitespace
        $password = str_replace(' ', '', $password);

        //name is in length range [6,20]
        if (strlen($password) > 5 and strlen($password) < 21)
        {
            return true;
        }
        else
        {
            return false;
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
        
            $newPasswordsSame = $_POST['newPassword'] == $_POST['confirmNewPassword'];

            if ($_POST['email'] != '')
            {
                if ($_POST['oldPassword'] == '')
                {
                    //want to change email but not password
                    if ($_POST['newPassword'] == '' and $newPasswordSame)
                    {
                        return $this->ProcessEditUserData($_POST['email'], '', '');
                    }
                    //see message
                    elseif ($_POST['newPassword'] != '' and $newPasswordsSame)
                    {
                        return new Presentation_View_ViewEditUserDataView($_GET['blogID'],
                                $this->userInfo['Email'], 'Old Password cannot be blank.');
                    }
                    //see message
                    else
                    {
                        return new Presentation_View_ViewEditUserDataView($_GET['blogID'],
                                $this->userInfo['Email'], 'New Password and Confirmation Password do not match.');
                    }
                }
                //want to change email and change password
                elseif ($_POST['newPassword'] != '' and $newPasswordsSame)
                {
                    if ($this->VerifyPassword($_POST['newPassword']))
                    {
                        return $this->ProcessEditUserData($_POST['email'], $_POST['oldPassword'], $_POST['newPassword']);
                    }
                    else
                    {
                        return new Presentation_View_ViewEditUserDataView($_GET['blogID'],
                                $this->userInfo['Email'], 'New Password must be between 6 and 20 characters.');
                    }
                }
                //old password entered but nothing entered in the new password fields
                elseif ($_POST['newPassword'] == $_POST['confirmNewPassword'])
                {
                    return new Presentation_View_ViewEditUserDataView($_GET['blogID'],
                                $this->userInfo['Email'], 'All password fields must be entered in.');
                }
                //new password and confirmation do not match
                else
                {
                    return new Presentation_View_ViewEditUserDataView($_GET['blogID'],
                                $this->userInfo['Email'], 'New Password and Confirmation Password do not match.');
                }
            }
            //see message
            else
            {
                return new Presentation_View_ViewEditUserDataView($_GET['blogID'],
                            $this->userInfo['Email'], 'Email cannot be blank');
            }
            
            break;

        case 'ViewRegister':
            return $this->ViewRegister();
            break;

        case 'ProcessRegister':
            //Form filled in
            if (    $_POST['username']          != ''
                and $_POST['email']             != ''
                and $_POST['password']          != ''
                and $_POST['confirmPassword']   != '')
            {
                if ($_POST['password'] == $_POST['confirmPassword'])
                {
                    //Enforce length requirements
                    if ($this->VerifyUsername($_POST['username']))
                    {
                        if ($this->VerifyPassword($_POST['password']))
                        {
                            return $this->ProcessRegister($_POST['username'], $_POST['email'],
                              $_POST['password']);
                        }
                        else
                        {
                            return new Presentation_View_ViewRegisterView($_POST['username'],
                                     $_POST['email'], 'Password must be between 6 and 20 characters.');
                        }
                    }
                    else
                    {
                        return new Presentation_View_ViewRegisterView($_POST['username'],
                                     $_POST['email'], 'Username must be between 5 and 15 characters.');
                    }

                }
                else
                {
                    return new Presentation_View_ViewRegisterView($_POST['username'],
                                     $_POST['email'], 'Password and Confirmation Password do not match.');
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
            
        case 'ProcessRemoveInvitation':
            return $this->ProcessRemoveInvitation($_GET['blogID']);
            break;
            
        case 'ChangeMemberRank':
            return $this->ChangeMemberRank($_GET['blogID']);
            break;

        case 'ProcessChangeMemberRank':
            return $this->ProcessChangeMemberRank($_GET['blogID']);
            break;
            
        case 'RemoveMember':
            return $this->RemoveMember($_GET['blogID']);
            break;

        case 'ProcessRemoveMember':
            return $this->ProcessRemoveMember($_GET['blogID']);
            break;

        default:
            return BusinessLogic_Post_Post::GetInstance()->HandleRequest();
    	}
    }

}

?>
