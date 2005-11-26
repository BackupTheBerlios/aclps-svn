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
	if (!BusinessLogic_Post_PostSecurity::GetInstance()->NewPost($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	$post = BusinessLogic_Post_PostDataAccess::GetInstance()->NewPost($blogID,$userID);
	return $post;
    }

    public function ProcessNewPost($postView, $userID)
    {
	//Calls PostSecurity to determine if the user can create a new post. If so, it will process the form data in NewPostView and call PostDataAccess.ProcessNewPost() to commit the new data to storage. Otherwise, an exception is thrown.
	$blogID = $postView->GetBlogID();
	if (!BusinessLogic_Post_PostSecurity::GetInstance()->ProcessNewPost($blogID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	BusinessLogic_Post_PostDataAccess::GetInstance()->ProcessNewPost($postView);
    }

    public function EditPost($blogID, $postID, $userID)
    {
	//Calls the PostSecurity class to determine if the user can edit a post. If so, PostDataAccess is called and an EditPostView is returned. Otherwise, an exception is thrown.
	if (!BusinessLogic_Post_PostSecurity::GetInstance()->EditPost($blogID,$postID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	return BusinessLogic_Post_PostDataAccess::GetInstance()->EditPost($postID);
    }

    public function ProcessEditPost($postView, $userID)
    {
	//Calls PostSecurity to determine if the user can edit a post. If so, it will process the form data in EditPostView and call PostDataAccess.ProcessEditPost() to commit the new data to storage. Otherwise, an exception is thrown.
	$blogID = $postView->GetBlogID();
	$postID = $postView->GetPostID();
	if (!BusinessLogic_Post_PostSecurity::GetInstance()->ProcessEditPost($blogID,$postID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	BusinessLogic_Post_PostDataAccess::GetInstance()->ProcessEditPost($postView);
    }

    public function DeletePost($blogID, $postID, $userID)
    {
	//Calls the PostSecurity class to determine if the user can delete a post. If so, PostDataAccess is called and a DeletePostView is returned. Otherwise, an exception is thrown.
	if (!BusinessLogic_Post_PostSecurity::GetInstance()->DeletePost($blogID,$postID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	return BusinessLogic_Post_PostDataAccess::GetInstance()->DeletePost($postID);
    }

    public function ProcessDeletePost($postView, $userID)
    {
	//Calls PostSecurity to determine if the user can delete a post. If so, it will call PostDataAccess.ProcessDeletePost() to delete the post. Otherwise, an exception is thrown.
	$blogID = $postView->GetBlogID();
	$postID = $postView->GetPostID();
	if (!BusinessLogic_Post_PostSecurity::GetInstance()->ProcessDeletePost($blogID,$postID,$userID))
	{
	    throw new Exception("Insufficient permissions.");
	}
	BusinessLogic_Post_PostDataAccess::GetInstance()->ProcessDeletePost($postView);
	BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessDeleteAllComments($postView);
    }

    public function ViewPostsByID($blogID, $postID, $userID)
    {
	//Calls the PostSecurity class to determine if the user can view a post. If so, PostDataAccess is called and a ViewPostCollectionView is returned. Otherwise, an exception is thrown.
	$permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByID($blogID,$userID);
	$postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByID($postID);
	BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$userID,$permission);
	return $postCollectionView;
    }

    public function ViewPostsByRecentCount($blogID, $count, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	$permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByRecentCount($blogID,$userID);
	$postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByRecentCount($blogID,$count);
	BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$userID,$permission);
	return $postCollectionView;
    }

    public function ViewPostsByDaysOld($blogID, $daysOld, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	$permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByDaysOld($blogID,$userID);
	$postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByDaysOld($blogID,$daysOld);
	BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$userID,$permission);
	return $postCollectionView;
    }

    public function ViewPostsByMonth($blogID, $year, $month, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	$permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByMonth($blogID,$userID);
	$postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByMonth($blogID,$year,$month);
	BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$userID,$permission);
	return $postCollectionView;
    }

    public function ViewPostsByDay($blogID, $year, $month, $date, $userID)
    {
	//Calls the PostSecurity class to determine the user's privilege level. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
	$permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByDay($blogID,$userID);
	$postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByDay($blogID,$year,$month,$date);
	BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$userID,$permission);
	return $postCollectionView;
    }

    public function HandleRequest()
    {
	//Checks $_GET['action'] to see if the action belongs to the Post class. If so, the appropriate function is called. Otherwise, Comment.HandleRequest() is called.
	$request = $_GET['Action'];
	switch($request)
	{
	case 'ViewPost':
	    //TODO
	    break;
	case 'NewPost':
	    //TODO
	    break;
	case 'ProcessNewPost':
	    //TODO
	    break;
	case 'EditPost':
	    //TODO
	    break;
	case 'ProcessEditPost':
	    //TODO
	    break;
	case 'DeletePost':
	    //TODO
	    break;
	case 'ProcessDeletePost':
	    //TODO
	    break;
	default:
	    BusinessLogic_Comment_Comment::GetInstance()->HandleRequest();
	}
    }
}
?>