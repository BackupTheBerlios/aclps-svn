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
	if ($permission != "Nobody")
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
	return $this->NewPost($blogID,$userID);
    }

    public function EditPost($blogID, $postID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "Nobody")
	{
	    return false;
	}
	elseif ($permission == "Author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($postID))
	{
	    return true;
	}
	else
	{
	    return true;
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
	if ($permission == "Nobody")
	{
	    return false;
	}
	elseif ($permission == "Author" and 
		$userID == BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($postID))
	{
	    return true;
	}
	else
	{
	    return true;
	}
    }
    public function ProcessDeletePost($blogID, $postID, $userID)
    {
	return $this->DeletePost($blogID, $postID, $userID);
    }

    private function ViewPost($blogID,$userID)
    {
	//Returns the user's permission level and whether hidden posts should be hidden (in an array of that order).
	$permissionlabel = BusinessLogic_User_User::GetInstance()->UserPermission($blogID,$userID);
	return array($permissionlabel,($permissionlabel == "Nobody"));
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

    private function ActivateControls($postCollectionView,$blogID,$userID,$permission) {
	//Depending on the user's permission level, adds controls to posts that should have them.
	if ($permission == "Nobody")
	{
	    return;
	}
	elseif ($permission == "Author")
	{
	    foreach($postCollectionView->GetPosts() as $key => $value)
	    {
		if ($value->GetAuthorID() == $userID)
		{
		    $value->SetControls(true);
		}
		else
		{
		    $value->SetControls(false);
		}
	    }
	}
	else
	{
	    foreach($postCollectionView->GetPosts() as $key => $value)
	    {
		$value->SetControls(true);
	    }
	}
    }
}

?>