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
    }

    public function EditAbout($blogID)
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

    public function ProcessEditAbout($blogID)
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

    public function EditBlogImages($blogID)
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

    public function ProcessEditBlogImages($blogID)
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

    public function EditLinks()
    {
	//TODO
    }

    public function ProcessEditLinks()
    {
	//TODO
    }

    public function EditMembers()
    {
	//TODO
    }

    public function ProcessEditMembers()
    {
	//TODO
    }

    public function NewBlog()
    {
	//TODO
    }

    public function ProcessNewBlog()
    {
	//TODO
    }
}

?>
