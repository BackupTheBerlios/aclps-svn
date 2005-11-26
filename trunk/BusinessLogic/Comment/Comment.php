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
	$post = BusinessLogic_Comment_CommentDataAccess::GetInstance()->NewPost($blogID);
	//TODO: set comment author (derived from userid) and return comment
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

    public function EditComment($blogID, $postID, $commentID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can edit a comment. If so, CommentDataAccess is called and an EditCommentView is returned. Otherwise, an exception is thrown.
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->EditComment($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	return BusinessLogic_Comment_CommentDataAccess::GetInstance()->EditComment($blogID,$postID,$commentID);
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

    public function DeleteComment($blogID, $postID, $commentID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can delete a post. If so, CommentDataAccess is called and a DeleteCommentView is returned. Otherwise, an exception is thrown.
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->DeleteComment($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	return BusinessLogic_Comment_CommentDataAccess::GetInstance()->DeleteComment($blogID,$postID,$commentID);
    }

    public function ProcessDeleteComment($commentData, $userID)
    {
	//Calls CommentSecurity to determine if the user can delete a post. If so, it will process the form data in DeleteCommentView and call CommentDataAccess.ProcessDeleteComment() to commit the new data to storage. Otherwise, an exception is thrown.
	$blogID = $postView->GetBlogID();
	if (!BusinessLogic_Comment_CommentSecurity::GetInstance()->ProcessDeleteComment($blogID,$userID)) {
	    throw new Exception("Insufficient permissions.");
	}
	BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessDeleteComment($postView);
    }

    public function ViewComments($blogID, $postID)
    {
	//Calls the CommentDataAccess class and returns a ViewCommentsView.
	$permission = BusinessLogic_Comment_CommentSecurity::GetInstance()->ViewComments($blogID,$userID);
	$postView = BusinessLogic_Comment_CommentDataAccess::GetInstance()->ViewComments($blogID,$count);
	if ($permission == "nobody")
	{
	    $postView->RemovePrivatePosts();
	}
	return $postView;
    }

    public function ViewLinkToComments($blogID, $postID)
    {
	//Calls the CommentDataAccess class and returns a URL to the location of the comments.
	//TODO
    }

    public function HandleRequest()
    {
	//Checks $_GET['action'] to see if the action belongs to the Comment class. If so, the appropriate function is called. Otherwise, User.HandleRequest() is called.
	$request = $_GET['Action'];
	switch($request)
	{
	case 'ViewComment':
	    //TODO
	    break;
	case 'NewComment':
	    //TODO
	    break;
	case 'ProcessNewComment':
	    //TODO
	    break;
	case 'EditComment':
 	    //TODO
	    break;
	case 'ProcessEditComment':
	    //TODO
	    break;
	case 'DeleteComment':
	    //TODO
	    break;
	case 'ProcessDeleteComment':
	    //TODO
	    break;
	default:
	    BusinessLogic_User_User::GetInstance()->HandleRequest();
	}
    }
}
?>