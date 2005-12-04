<?php

class BusinessLogic_Comment_Comment
{
    //Business entity which encapsulates the functionality of a Post.

    static public function GetInstance()
    {
        if (!isset($_SESSION['BusinessLogic_Comment_Comment']))
        {
            $_SESSION['BusinessLogic_Comment_Comment'] = new BusinessLogic_Comment_Comment();
        }
        return $_SESSION['BusinessLogic_Comment_Comment'];
    }

    public function NewComment($blogID, $postID)
    {
        //Calls the CommentSecurity class to determine if the user can create a new comment. If so, a NewCommentView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->NewComment($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        return BusinessLogic_Comment_CommentDataAccess::GetInstance()->NewComment($blogID,$postID);
    }

    public function ProcessNewComment($commentView)
    {
        //Calls CommentSecurity to determine if the user can create a new comment. If so, it will process the form data in NewCommentView and call CommentDataAccess.ProcessNewComment() to commit the new data to storage. Otherwise, an exception is thrown.
        $blogID = $commentView->GetBlogID();
        if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ProcessNewComment($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessNewComment($commentView);
    }

    public function EditComment($blogID, $commentID)
    {
        //Calls the CommentSecurity class to determine if the user can edit a comment. If so, CommentDataAccess is called and an EditCommentView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->EditComment($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        return BusinessLogic_Comment_CommentDataAccess::GetInstance()->EditComment($commentID);
    }

    public function ProcessEditComment($commentView)
    {
        //Calls CommentSecurity to determine if the user can edit a comment. If so, it will process the form data in EditCommentView and call CommentDataAccess.ProcessEditComment() to commit the new data to storage. Otherwise, an exception is thrown.
        $blogID = $commentView->GetBlogID();
        if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ProcessEditComment($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessEditComment($commentView);
    }

    public function DeleteComment($blogID, $commentID)
    {
        //Calls the CommentSecurity class to determine if the user can delete a post. If so, CommentDataAccess is called and a DeleteCommentView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->DeleteComment($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        return BusinessLogic_Comment_CommentDataAccess::GetInstance()->DeleteComment($commentID);
    }

    public function ProcessDeleteComment($blogID, $commentID)
    {
        //Calls CommentSecurity to determine if the user can delete a post. If so, it will process the form data in DeleteCommentView and call CommentDataAccess.ProcessDeleteComment() to commit the new data to storage. Otherwise, an exception is thrown.
        if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ProcessDeleteComment($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessDeleteComment($commentID);
    }

    public function ViewComments($blogID, $postID)
    {
        //Calls the CommentDataAccess class and returns a ViewCommentsView.
        if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ViewComments($blogID,$postID))
        {
            throw new Exception('Authentication failed.');
        }
        $commentCollectionView = BusinessLogic_Comment_CommentDataAccess::GetInstance()->ViewComments($blogID,$postID);
        BusinessLogic_Comment_CommentSecurity::GetInstance()->ActivateControls($commentCollectionView,$blogID);
        return $commentCollectionView;
    }

    public function HandleRequest()
    {
        //Checks $_GET['action'] to see if the action belongs to the Comment class. If so, the appropriate function is called. Otherwise, User.HandleRequest() is called.
        $request = $_GET['Action'];
        $blogID = $_GET['blogID'];
        switch($request)
        {
        case 'NewComment':
            $postID = $_GET['postID'];
            return $this->NewComment($postID,$blogID);
            break;
        case 'ProcessNewComment':
            $authorID = BusinessLogic_User_User::GetInstance()->GetUserID();
            $title = substr($_POST['title'],0,30);
            //__construct($blogID, $postID, $commentID, $authorID, $title, $timestamp, $content)
            $view = new Presentation_View_ViewCommentView($blogID,$_POST['postID'],0,$authorID,$title,0,$_POST['content']);
            $this->ProcessNewComment($view);
            //forward user to viewing the post:
            return BusinessLogic_Post_Post::GetInstance()->ViewPostsByID($blogID,$_POST['postID']);
            break;
        case 'EditComment':
            $commentID = $_GET['commentID'];
            return $this->EditComment($blogID,$commentID);
            break;
        case 'ProcessEditComment':
            $authorID = BusinessLogic_User_User::GetInstance()->GetUserID();
            $title = substr($_POST['title'],0,30);
            $view = new Presentation_View_ViewCommentView($blogID,0,$_POST['commentID'],$authorID,$title, 0, $_POST['content']);
            $this->ProcessEditComment($view);
            //forward user to viewing the post:
            return BusinessLogic_Post_Post::GetInstance()->ViewPostsByID($blogID,$_POST['postID']);
            break;
        case 'DeleteComment':
            $commentID = $_GET['commentID'];
            $comment = $this->DeleteComment($blogID,$commentID);
            return $comment;
            break;
        case 'ProcessDeleteComment':
            $commentID = $_POST['commentID'];
            $this->ProcessDeleteComment($blogID,$commentID);
            //forward user to viewing the post:
            return BusinessLogic_Post_Post::GetInstance()->ViewPostsByID($blogID,$_POST['postID']);
            break;
        default:
            throw new Exception('Unknown Request.');
	}
    }
}
?>
