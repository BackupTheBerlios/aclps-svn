<?php

class BusinessLogic_Comment_CommentSecurity
{
    //Helper class which determines whether a user can use a certain function.

    private function __construct()
    {
	//Do Nothing
    }

    static public function GetInstance()
    {
	if (!isset($_SESSION['BusinessLogic_Comment_CommentSecurity']))
	{
	    $_SESSION['BusinessLogic_Post_PostSecurity'] = new BusinessLogic_Post_PostSecurity();
	}
	return $_SESSION['BusinessLogic_Post_PostSecurity'];
    }

    public function NewComment($blogID)
    {
	//Returns false if the user has privilege {Nobody}. Otherwise, true.
	$permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	if ($permission == "Nobody")
	{
	    return false;
	}
	else
	{
	    return true;
	}
    }
    public function ProcessNewComment($blogID)
    {
	return $this->NewComment($blogID);
    }

    public function EditComment($blogID)
    {
	//Returns true if the user has privilege {Editor, Owner}.
	$permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	if ($permission == "Nobody" or $permission == "Author")
	{
	    return false;
	}
	else
	{
	    return true;
	}
    }

    public function ProcessEditComment($blogID)
    {
	return $this->EditComment($blogID);
    }

    public function DeleteComment($blogID)
    {
	//Returns true if the user has privilege {Editor, Owner}.
	$permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	if ($permission == "Nobody" or $permission == "Author")
	{
	    return false;
	}
	else
	{
	    return true;
	}
    }
    public function ProcessDeleteComment($blogID)
    {
	return $this->DeleteComment($blogID);
    }

    public function ViewComments($blogID)
    {
	//Returns true.
	return true;
    }

    private function ActivateControls($commentCollectionView,$blogID)
    {
	$permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
	//Depending on the user's permission level, adds controls to posts that should have them.
	if ($permission == "Nobody")
	{
	    return;
	}
	elseif ($permission == "Author")
	{
	    foreach($commentCollectionView->GetComments() as $key => $value)
	    {
		$value->SetControls($value->GetAuthorID() == BusinessLogic_User_User::GetInstance()->GetUserID());
	    }
	}
	else
	{
	    foreach($commentCollectionView->GetComments() as $key => $value)
	    {
		$value->SetControls(true);
	    }
	}
    }
}

?>