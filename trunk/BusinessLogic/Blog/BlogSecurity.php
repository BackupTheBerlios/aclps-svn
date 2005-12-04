<?php

class BusinessLogic_Blog_BlogSecurity
{
    private function __construct()
    {
	//Do Nothing
    }

    static public function GetInstance()
    {
	if (isset($_SESSION['BusinessLogic_Blog_BlogSecurity']))
	{
	    return $_SESSION['BusinessLogic_Blog_BlogSecurity'];
	}
	else
	{
	    $_SESSION['BusinessLogic_Blog_BlogSecurity'] = new BusinessLogic_Blog_BlogSecurity();
	    return $_SESSION['BusinessLogic_Blog_BlogSecurity'];
	}
        
    }
    
    public function ViewBlog($blogID)
    {
    	return BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
    }
    
    public function ViewArchive()
    {
	//TODO
        //See Post.ViewPostsByMonth($blogID, $year, $month) and Post.ViewPostsByDay($blogID, $year, $month, $date) --nick
    }
    
    public function EditBlogLayout($blogID)
    {
        $permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
        if($permission == 'Owner')
        {
            return true;
            
        }
        else
        {
            return false;
    	}
    }

    public function ProcessEditBlogLayout($blogID)
    {
        return $this->EditBlogLayout($blogID);
    }
    
    public function EditLinks($blogID)
    {
        $permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
        if($permission == 'Owner')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function ProcessEditLinks($blogID)
    {
	return $this->EditLinks($blogID);
    }

    public function EditMembership($blogID)
    {
        return BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
    }

    public function NewBlog()
    {
        //Returns true if the user has no blogs. Returns false otherwise.
        $userID = BusinessLogic_User_User::GetInstance()->GetUserID();
        $query = 'select Count(*) from [0] where UserID=[1] AND Auth="Owner"';
        $arguments = array('User_Auth', $userID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance(); 
        $result = $DataAccess->Select($query, $arguments);
        $numOfBlogs = $result[0]["Count(*)"];

        return ($numOfBlogs == 0);
    }

    public function ProcessNewBlog()
    {
        //Returns true if the user has no blogs. Returns false otherwise.
        return $this->NewBlog();
    }
}

?>
