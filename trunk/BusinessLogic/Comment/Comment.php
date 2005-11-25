<?php

class BusinessLogic_Post_Post
{
    //Business entity which encapsulates the functionality of a Post.

    static public function GetInstance()
    {
	if (!isset($_SESSION['BusinessLogic_Post_Post']))
	{
	    $_SESSION['BusinessLogic_Post_Post'] = new BusinessLogic_Post_Post();
	}
        return $_SESSION['BusinessLogic_Post_Post'];
    }

    public function NewComment($blogID, $postID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can create a new comment. If so, a NewCommentView is returned. Otherwise, an exception is thrown.
	//TODO
    }

    public function ProcessNewComment($commentData, $userID)
    {
	//Calls CommentSecurity to determine if the user can create a new comment. If so, it will process the form data in NewCommentView and call CommentDataAccess.ProcessNewComment() to commit the new data to storage. Otherwise, an exception is thrown.
	//TODO
    }

    public function EditComment($blogID, $postID, $commentID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can edit a comment. If so, CommentDataAccess is called and an EditCommentView is returned. Otherwise, an exception is thrown.
	//TODO
    }

    public function ProcessEditComment($commentData, $userID)
    {
	//Calls CommentSecurity to determine if the user can edit a comment. If so, it will process the form data in EditCommentView and call CommentDataAccess.ProcessEditComment() to commit the new data to storage. Otherwise, an exception is thrown.
	//TODO
    }

    public function DeleteComment($blogID, $postID, $commentID, $userID)
    {
	//Calls the CommentSecurity class to determine if the user can delete a post. If so, CommentDataAccess is called and a DeleteCommentView is returned. Otherwise, an exception is thrown.
	//TODO
    }

    public function ProcessDeleteComment($commentData, $userID)
    {
	//Calls CommentSecurity to determine if the user can delete a post. If so, it will process the form data in DeleteCommentView and call CommentDataAccess.ProcessDeleteComment() to commit the new data to storage. Otherwise, an exception is thrown.
	//TODO
    }

    public function ViewComments($blogID, $postID)
    {
	//Calls the CommentDataAccess class and returns a ViewCommentsView.
	//TODO
    }

    public function ViewLinkToComments($blogID, $postID)
    {
	//Calls the CommentDataAccess class and returns a URL to the location of the comments.
	//TODO
    }

    public function HandleRequest()
    {
	//Checks $_GET['action'] to see if the action belongs to the Post class. If so, the appropriate function is called. Otherwise, dies, as there is no further link in the CHAIN OF RESPONSIBILITY
	$request = $_GET['Action'];
	switch($request)
	{
	    //TODO: add actions here, if any
	default:
	    die('Unknown Request.');
	}

    }
}
?>