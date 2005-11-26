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
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID,$userID);
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
	return $this->NewPost($blogID,$userID)
    }

    public function EditPost($blogID, $postID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	elseif ($permission == "author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($postID))
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
	return $this->EditPost($blogID, $postID, $userID);
    }

    public function DeletePost($blogID, $postID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	elseif ($permission == "author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($postID))
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
	return $this->DeletePost($blogID, $postID, $userID);
    }

    public function ViewPostsByID($blogID,$userID)
    {
	return $this->ViewPost($blogID,$userID);
    }
    public function ViewPostsByRecentCount($blogID,$userID)
    {
	return $this->ViewPost($blogID,$userID);
    }
    public function ViewPostsByDaysOld($blogID,$userID)
    {
	return $this->ViewPost($blogID,$userID);
    }
    public function ViewPostsByMonth($blogID,$userID)
    {
	return $this->ViewPost($blogID,$userID);
    }
    public function ViewPostsByDay($blogID,$userID)
    {
	return $this->ViewPost($blogID,$userID);
    }

    private function ViewPost($blogID,$userID)
    {
	//Returns the privilege level of the user.
	return BusinessLogic_User_User::GetInstance()->UserPermission($blogID,$userID);
    }
}

?>