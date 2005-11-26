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
	if ($permission != "nobody")
	{
	    return true;
	}
	else
	{
	    return false;
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
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	else
	{
	    return false;
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
	if ($permission == "owner" or $permission == "editor")
	{
	    return true;
	}
	else
	{
	    return false;
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
}

?>