<?php

class BusinessLogic_Post_PostSecurity
{
    //Helper class which determines whether a user can use a certain function.

    private function __construct()
    {
	//Do Nothing
    }

    static public function GetInstance()
    {
	if (!isset($_SESSION['BusinessLogic_Post_PostSecurity']))
	{
	    $_SESSION['BusinessLogic_Post_PostSecurity'] = new BusinessLogic_Post_PostSecurity();
	}
	return $_SESSION['BusinessLogic_Post_PostSecurity'];
    }

    public function NewPost($blogID,$userID)
    {
	//Returns true if the user has privilege {Author, Editor, Owner}. Otherwise, false.
	$permission = BusinessLogic_User::GetInstance()->UserPermission($blogID,$userID);
	if ($permission != "nobody")
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    public function ProcessNewPost($blogID,$userID)
    {
	//Returns true if the user has privilege {Author, Editor, Owner}. Otherwise, false.
	$permission = BusinessLogic_User::GetInstance()->UserPermission($blogID,$userID);
	if ($permission != "nobody")
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    public function EditPost($blogID, $postID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	elseif ($permission == "author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($blogID,$postID))
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    public function ProcessEditPost($blogID, $postID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	elseif ($permission == "author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($blogID,$postID))
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    public function DeletePost($blogID, $postID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	elseif ($permission == "author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($blogID,$postID))
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    public function ProcessDeletePost($blogID, $postID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	elseif ($permission == "author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($blogID,$postID))
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    public function ViewPostsByID($blogID,$userID)
    {
	//Returns the privilege level of the user.
	return BusinessLogic_User::GetInstance()->UserPermission($blogID,$userID);
    }

    public function ViewPostsByRecentCount($blogID,$userID)
    {
	//Returns the privilege level of the user.
	return BusinessLogic_User::GetInstance()->UserPermission($blogID,$userID);
    }

    public function ViewPostsByDaysOld($blogID,$userID)
    {
	//Returns the privilege level of the user.
	return BusinessLogic_User::GetInstance()->UserPermission($blogID,$userID);
    }

    public function ViewPostsByMonth($blogID,$userID)
    {
	//Returns the privilege level of the user.
	return BusinessLogic_User::GetInstance()->UserPermission($blogID,$userID);
    }

    public function ViewPostsByDay($blogID,$userID)
    {
	//Returns the privilege level of the user.
	return BusinessLogic_User::GetInstance()->UserPermission($blogID,$userID);
    }
}

?>