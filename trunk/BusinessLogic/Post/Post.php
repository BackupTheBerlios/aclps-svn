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

    public function NewPost($blogID, $userID)
    {
	//Calls the PostSecurity class to determine if the user can create a new post. If so, a NewPostView is returned. Otherwise, an exception is thrown.
	//TODO
    }

    public function ProcessNewPost($postData, $userID)
    {
	//Calls PostSecurity to determine if the user can create a new post. If so, it will process the form data in NewPostView and call PostDataAccess.ProcessNewPost() to commit the new data to storage. Otherwise, an exception is thrown.
	//TODO
    }

    public function EditPost($blogID, $postID, $userID)
    {
	//Calls the PostSecurity class to determine if the user can edit a post. If so, PostDataAccess is called and an EditPostView is returned. Otherwise, an exception is thrown.
	//TODO
    }

    public function ProcessEditPost($postData, $userID)
    {
	//Calls PostSecurity to determine if the user can edit a post. If so, it will process the form data in EditPostView and call PostDataAccess.ProcessEditPost() to commit the new data to storage. Otherwise, an exception is thrown.
	//TODO
    }

    public function DeletePost($blogID, $postID, $userID)
    {
	//Calls the PostSecurity class to determine if the user can delete a post. If so, PostDataAccess is called and a DeletePostView is returned. Otherwise, an exception is thrown.
	//TODO
    }

    public function ProcessDeletePost($postData, $userID)
    {
	//Calls PostSecurity to determine if the user can delete a post. If so, it will process the form data in DeletePostView and call PostDataAccess.ProcessDeletePost() to commit the new data to storage. Otherwise, an exception is thrown.
	//TODO
    }

    public function ViewPostsByID($blogID, $postID, $userID)
    {
	//Calls the PostSecurity class to determine if the user can view a post. If so, PostDataAccess is called and a ViewPostCollectionView is returned. Otherwise, an exception is thrown.
	//TODO
    }

    public function ViewPostsByRecentCount($blogID, $count, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	//TODO
    }

    public function ViewPostsByDaysOld($blogID, $ageInDays, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	//TODO
    }

    public function ViewPostsByMonth($blogID, $year, $month, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	//TODO
    }

    public function ViewPostsByDay($blogID, $year, $month, $day, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	//TODO
    }

    public function HandleRequest()
    {
	//Checks $_GET['action'] to see if the action belongs to the Post class. If so, the appropriate function is called. Otherwise, Comment.HandleRequest() is called.
	$request = $_GET['Action'];
	switch($request)
	{
	    //TODO: add actions here, if any
	default:
	    BusinessLogic_Comment_Comment::GetInstance()->HandleRequest();
	}
    }
}
?>