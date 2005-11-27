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

    public function NewPost($blogID)
    {
	//Returns true if the user has privilege {Author, Editor, Owner}. Otherwise, false.
	$permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	if ($permission != "Nobody")
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }
    public function ProcessNewPost($blogID)
    {
	return $this->NewPost($blogID);
    }

    public function EditPost($blogID, $postID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	if ($permission == "Nobody")
	{
	    return false;
	}
	elseif ($permission == "Author" and 
		BusinessLogic_User_User::GetInstance()->GetUserID() == 
		BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($postID))
	{
	    return true;
	}
	else
	{
	    return true;
	}
    }
    public function ProcessEditPost($blogID, $postID)
    {
	return $this->EditPost($blogID, $postID);
    }

    public function DeletePost($blogID, $postID)
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	$permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	if ($permission == "Nobody")
	{
	    return false;
	}
	elseif ($permission == "Author" and 
		BusinessLogic_User_User::GetInstance()->GetUserID() ==
		BusinessLogic_Post_PostDataAccess::GetInstance()->GetPostAuthorID($postID))
	{
	    return true;
	}
	else
	{
	    return true;
	}
    }
    public function ProcessDeletePost($blogID, $postID)
    {
	return $this->DeletePost($blogID, $postID);
    }

    private function ViewPost($blogID)
    {
	//Returns the user's permission level and whether hidden posts should be hidden (in an array of that order).
	$permissionlabel = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	return array($permissionlabel,($permissionlabel == "Nobody"));
    }
    public function ViewPostsByID($blogID)
    {
	return $this->ViewPost($blogID);
    }
    public function ViewPostsByRecentCount($blogID)
    {
	return $this->ViewPost($blogID);
    }
    public function ViewPostsByDaysOld($blogID)
    {
	return $this->ViewPost($blogID);
    }
    public function ViewPostsByMonth($blogID)
    {
	return $this->ViewPost($blogID);
    }
    public function ViewPostsByDay($blogID)
    {
	return $this->ViewPost($blogID);
    }

    public function ActivateControls($postCollectionView,$blogID,$permission) {
	//Depending on the user's permission level, adds controls to posts that should have them.
	if ($permission == "Nobody")
	{
	    return;
	}
	elseif ($permission == "Author")
	{
	    foreach($postCollectionView->GetPosts() as $key => $value)
	    {
		$value->SetControls($value->GetAuthorID() ==
				    BusinessLogic_User_User::GetInstance()->GetUserID());
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

    public function ActivateControlsSingle($postView,$blogID,$permission) {
	//Same as above, except for a single ViewPostView rather than a ViewPostCollectionView.
	if ($permission == "Nobody")
	{
	    return;
	}
	elseif ($permission == "Author")
	{
	    $postCollectionView->SetControls($postCollectionView->GetAuthorID() ==
					     BusinessLogic_User_User::GetInstance()->GetUserID());
	}
	else
	{
	    $postCollectionView->SetControls(true);
	}
    }
}

?>
