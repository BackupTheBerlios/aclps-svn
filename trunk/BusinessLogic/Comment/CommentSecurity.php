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
            $_SESSION['BusinessLogic_Comment_CommentSecurity'] = new BusinessLogic_Comment_CommentSecurity();
        }
        return $_SESSION['BusinessLogic_Comment_CommentSecurity'];
    }

    public function NewComment($blogID)
    {
        //Returns false if the user is not logged in. Otherwise, true.
        return BusinessLogic_User_User::GetInstance()->CheckSignedIn();
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

    public function ViewComments($blogID,$postID)
    {
        //Returns true.
        return true;
    }

    public function ActivateControls($commentCollectionView,$blogID)
    {
        $permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
        //Depending on the user's permission level, adds controls to posts that should have them.
        if ($permission == "Nobody" or $permission == "Author")
        {
            foreach($commentCollectionView->GetComments() as $key => $value)
            {
                $value->SetControls(false);
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