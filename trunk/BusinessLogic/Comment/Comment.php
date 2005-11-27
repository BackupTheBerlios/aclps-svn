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

    public function NewComment($blogID, $postID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can create a new comment. If so, a NewCommentView is returned. Otherwise, an exception is thrown.
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->NewComment($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	$comment = BusinessLogic_Comment_CommentDataAccess::GetInstance()->NewComment($blogID,$postID,$userID);
	return $comment;
    }

    public function ProcessNewComment($commentView, $userID)
    {
	//Calls CommentSecurity to determine if the user can create a new comment. If so, it will process the form data in NewCommentView and call CommentDataAccess.ProcessNewComment() to commit the new data to storage. Otherwise, an exception is thrown.
	$blogID = $commentView->GetBlogID();
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ProcessNewComment($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	BusinessLogic_Post_CommentDataAccess::GetInstance()->ProcessNewComment($commentView);
    }

    public function EditComment($blogID, $commentID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can edit a comment. If so, CommentDataAccess is called and an EditCommentView is returned. Otherwise, an exception is thrown.
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->EditComment($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	return BusinessLogic_Comment_CommentDataAccess::GetInstance()->EditComment($commentID);
    }

    public function ProcessEditComment($commentView, $userID)
    {
	//Calls CommentSecurity to determine if the user can edit a comment. If so, it will process the form data in EditCommentView and call CommentDataAccess.ProcessEditComment() to commit the new data to storage. Otherwise, an exception is thrown.
	$blogID = $commentView->GetBlogID();
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ProcessEditComment($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessEditComment($commentView);

    }

    public function DeleteComment($blogID, $commentID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can delete a post. If so, CommentDataAccess is called and a DeleteCommentView is returned. Otherwise, an exception is thrown.
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->DeleteComment($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	return BusinessLogic_Comment_CommentDataAccess::GetInstance()->DeleteComment($commentID);
    }

    public function ProcessDeleteComment($commentView, $userID)
    {
	//Calls CommentSecurity to determine if the user can delete a post. If so, it will process the form data in DeleteCommentView and call CommentDataAccess.ProcessDeleteComment() to commit the new data to storage. Otherwise, an exception is thrown.
	$blogID = $commentView->GetBlogID();
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ProcessDeleteComment($blogID,$userID)) {
	    throw new Exception("Insufficient permissions.");
	}
	BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessDeleteComment($commentCollectionView);
    }

    public function ViewComments($blogID, $postID, $userID)
    {
	//Calls the CommentDataAccess class and returns a ViewCommentsView.
	$permission = BusinessLogic_Comment_CommentSecurity::GetInstance()->ViewComments($blogID,$userID);
	if (!$permission) {
	    throw new Exception("Insufficient permissions.");
	}
	$commentCollectionView = BusinessLogic_Comment_CommentDataAccess::GetInstance()->ViewComments($blogID,$postID);
	BusinessLogic_Comment_CommentSecurity::GetInstance()->ActivateControls($commentCollectionView,$blogID,$userID);
	return $commentCollectionView;
    }

    public function ViewLinkToComments($blogID, $postID, $userID)
    {
	//Calls the CommentDataAccess class and returns a URL to the location of the comments.
	//TODO
	$permission = BusinessLogic_Comment_CommentSecurity::GetInstance()->ViewLinkToComments($blogID,$userID);
	if (!$permission) {
	    throw new Exception("Insufficient permissions.");
	}
	return "Link to comments here";
    }

    public function HandleRequest()
    {
	//Checks $_GET['action'] to see if the action belongs to the Comment class. If so, the appropriate function is called. Otherwise, User.HandleRequest() is called.
	$request = $_GET['Action'];
	$blogID = $_GET['BlogID'];
	$userID = BusinessLogic_User_User::GetInstance()->GetUserID();
	switch($request)
	{
	case 'NewComment':
	    $postID = $_GET['PostID'];
	    return $this->NewComment($postID,$blogID,$userID);
	    break;
	case 'ProcessNewComment':
	    //TODO ???
	    break;
	case 'EditComment':
	    $commentID = $_GET['CommentID'];
 	    return $this->EditComment($blogID,$commentID,$userID);
	    break;
	case 'ProcessEditComment':
	    //TODO ???
	    break;
	case 'DeleteComment':
	    $commentID = $_GET['CommentID'];
	    return $this->DeleteComment($blogID,$commentID,$userID);
	    break;
	case 'ProcessDeleteComment':
	    //TODO ???
	    break;
	default:
	    die('Unknown Request.');
	}
    }
}
?>