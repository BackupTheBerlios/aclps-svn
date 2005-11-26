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

    public function NewComment($blogID, $userID)
    {
	//Returns false if the user has privilege {Nobody}. Otherwise, true.
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "Nobody")
	{
	    return false;
	}
	else
	{
	    return true;
	}
    }
    public function ProcessNewComment($blogID, $userID)
    {
	return $this->NewComment($blogID, $userID);
    }

    public function EditComment($blogID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}.
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "Nobody" or $permission == "Author")
	{
	    return false;
	}
	else
	{
	    return true;
	}
    }

    public function ProcessEditComment($blogID, $userID)
    {
	return $this->EditComment($blogID, $userID);
    }

    public function DeleteComment($blogID, $userID)
    {
	//Returns true if the user has privilege {Editor, Owner}.
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID, $userID);
	if ($permission == "Nobody" or $permission == "Author")
	{
	    return false;
	}
	else
	{
	    return true;
	}
    }
    public function ProcessDeleteComment($blogID, $userID)
    {
	return $this->DeleteComment($blogID, $userID);
    }

    public function ViewComments($blogID, $userID)
    {
	//Returns true.
	return true;
    }

    public function ViewLinkToComments($blogID, $userID)
    {
	//Returns true.
	return true;
    }

    private function ActivateControls($commentCollectionView,$blogID,$userID) {
	$permission = BusinessLogic_User_User::GetInstance()->UserPermission($blogID,$userID);
	//Depending on the user's permission level, adds controls to posts that should have them.
	if ($permission == "Nobody")
	{
	    return;
	}
	elseif ($permission == "Author")
	{
	    foreach($commentCollectionView->GetComments() as $key => $value)
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
	    foreach($commentCollectionView->GetComments() as $key => $value)
	    {
		$value->SetControls(true);
	    }
	}
    }
}

?>