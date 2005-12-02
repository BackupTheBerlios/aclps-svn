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

    public function NewPost($blogID)
    {
        //Calls the PostSecurity class to determine if the user can create a new post. If so, a NewPostView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Post_PostSecurity::GetInstance()->NewPost($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        //Create a new empty post form and return it.
        //$blogID, $postID, $authorID, $title, $public, $timestamp, $content
        return new Presentation_View_NewPostView($blogID);
    }

    public function ProcessNewPost($postView)
    {
        //Calls PostSecurity to determine if the user can create a new post. If so, it will process the form data in NewPostView and call PostDataAccess.ProcessNewPost() to commit the new data to storage. Otherwise, an exception is thrown. Returns the post ID of the new post.
        $blogID = $postView->GetBlogID();
        if (!BusinessLogic_Post_PostSecurity::GetInstance()->ProcessNewPost($blogID))
        {
            throw new Exception('Authentication failed.');
        }
        return BusinessLogic_Post_PostDataAccess::GetInstance()->ProcessNewPost($postView);
    }

    public function EditPost($blogID, $postID)
    {
        //Calls the PostSecurity class to determine if the user can edit a post. If so, PostDataAccess is called and an EditPostView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Post_PostSecurity::GetInstance()->EditPost($blogID,$postID))
        {
            throw new Exception('Authentication failed.');
        }
        return BusinessLogic_Post_PostDataAccess::GetInstance()->EditPost($postID);
    }

    public function ProcessEditPost($postView,$useNowForTimestamp)
    {
        //Calls PostSecurity to determine if the user can edit a post. If so, it will process the form data in EditPostView and call PostDataAccess.ProcessEditPost() to commit the new data to storage. Otherwise, an exception is thrown.
        $blogID = $postView->GetBlogID();
        $postID = $postView->GetPostID();
        if (!BusinessLogic_Post_PostSecurity::GetInstance()->ProcessEditPost($blogID,$postID))
        {
            throw new Exception('Authentication failed.');
        }
        BusinessLogic_Post_PostDataAccess::GetInstance()->ProcessEditPost($postView,$useNowForTimestamp);
    }

    public function DeletePost($blogID, $postID)
    {
        //Calls the PostSecurity class to determine if the user can delete a post. If so, PostDataAccess is called and a DeletePostView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Post_PostSecurity::GetInstance()->DeletePost($blogID,$postID))
        {
            throw new Exception('Authentication failed.');
        }
        return BusinessLogic_Post_PostDataAccess::GetInstance()->DeletePost($postID);
    }

    public function ProcessDeletePost($blogID,$postID)
    {
        //Calls PostSecurity to determine if the user can delete a post. If so, it will call PostDataAccess.ProcessDeletePost() to delete the post. Otherwise, an exception is thrown.
        if (!BusinessLogic_Post_PostSecurity::GetInstance()->ProcessDeletePost($blogID,$postID))
        {
            throw new Exception('Authentication failed.');
            throw new Exception("Insufficient permissions.");
        }
        BusinessLogic_Post_PostDataAccess::GetInstance()->ProcessDeletePost($postID);
        BusinessLogic_Comment_CommentDataAccess::GetInstance()->ProcessDeleteAllComments($postID);
    }

    public function ViewPostsByID($blogID, $postID)
    {
        //Calls the PostSecurity class to determine the user's permissions. If so, PostDataAccess is called and a ViewPostCollectionView is returned. Otherwise, an exception is thrown.
        $permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByID($blogID);
        $postCommentView = BusinessLogic_Comment_Comment::GetInstance()->ViewComments($blogID,$postID);
        $postView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByID($blogID,$postID,$postCommentView,$permission[1]);
        BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControlsSingle($postView,$blogID,$permission[0]);
        return $postView;
    }

    public function ViewPostsByRecentCount($blogID, $count)
    {
        //Calls the PostSecurity class to determine the user's permissions. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
        $permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByRecentCount($blogID);
        $postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByRecentCount($blogID,$count,$permission[1]);
        BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$permission[0]);
        return $postCollectionView;
    }

    public function ViewPostsByDaysOld($blogID, $daysOld)
    {
        //Calls the PostSecurity class to determine the user's permissions. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
        $permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByDaysOld($blogID);
        $postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByDaysOld($blogID,$daysOld,$permission[1]);
        BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$permission[0]);
        return $postCollectionView;
    }

    public function GetDatesWithPostsForMonth($blogID, $year, $month)
    {
        //Returns an array mapping of DateOfMonth->true (true=posts on that date, nothing=no posts)
        $permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByMonth($blogID);
        return BusinessLogic_Post_PostDataAccess::GetInstance()->GetDatesWithPostsForMonth($blogID,$year,$month,$permission[1]);
    }

    public function ViewPostsByMonth($blogID, $year, $month)
    {
        //Calls the PostSecurity class to determine the user's permissions. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
        $permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByMonth($blogID);
        $postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByMonth($blogID,$year,$month,$permission[1]);
        BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$permission[0]);
        return $postCollectionView;
    }

    public function ViewPostsByDay($blogID, $year, $month, $date)
    {
        //Calls the PostSecurity class to determine the user's permissions. The PostDataAccess class is then called and a ViewPostCollectionView is returned.
        $permission = BusinessLogic_Post_PostSecurity::GetInstance()->ViewPostsByDay($blogID);
        $postCollectionView = BusinessLogic_Post_PostDataAccess::GetInstance()->ViewPostsByDay($blogID,$year,$month,$date,$permission[1]);
        BusinessLogic_Post_PostSecurity::GetInstance()->ActivateControls($postCollectionView,$blogID,$permission[0]);
        return $postCollectionView;
    }

    public function HandleRequest()
    {
        //Checks $_GET['Action'] to see if the action belongs to the Post class. If so, the appropriate function is called. Otherwise, Comment.HandleRequest() is called.
        $request = $_GET['Action'];
        $blogID = $_GET['blogID'];
        switch($request)
        {
        case 'ViewPost':
            if (isset($_GET['postID']))
            {
                return $this->ViewPostsByID($blogID,$_GET['postID']);
            }
            elseif (isset($_GET['date']))
            {
                return $this->ViewPostsByDay($blogID,$_GET['year'],$_GET['month'],$_GET['date']);
            }
            elseif (isset($_GET['month']))
            {
                return $this->ViewPostsByMonth($blogID,$_GET['year'],$_GET['month']);
            }
            elseif (isset($_GET['age']))
            {
                $age = $_GET['age'];
                if ($age > 100)
                {
                    $age = 100;
                }
                return $this->ViewPostsByDaysOld($blogID,$age);
            }
            elseif (isset($_GET['count']))
            {
                $count = $_GET['count'];
                if ($count > 100)
                {
                    $count = 100;
                }
                return $this->ViewPostsByRecentCount($blogID,$count);
            }
            else
            {
                //FALLBACK: default return count 10
                return $this->ViewPostsByRecentCount($blogID,10);
            }
            break;
        case 'NewPost':
            return $this->NewPost($blogID);
            break;
        case 'ProcessNewPost':
            $authorID = BusinessLogic_User_User::GetInstance()->GetUserID();
            $title = substr($_POST['title'],0,30);
            $view = new Presentation_View_ViewPostView($blogID,0,$authorID,$title,$_POST['public'],0,$_POST['content']);
            $this->ProcessNewPost($view);
            //forward user to viewing the blog that the post was just made in:
            return $this->ViewPostsByRecentCount($blogID,10);
            break;
        case 'EditPost':
            $postID = $_GET['postID'];
            return $this->EditPost($blogID,$postID);
            break;
        case 'ProcessEditPost':
            $authorID = BusinessLogic_User_User::GetInstance()->GetUserID();
            $title = substr($_POST['title'],0,30);
            $public = ($_POST['public'] == 'on');
            $view = new Presentation_View_ViewPostView($blogID,$_POST['postID'],$authorID,$title,$public, 0, $_POST['content']);
            $updateTimestamp = ($_POST['timestamp'] == 'now');
            $this->ProcessEditPost($view,$updateTimestamp);
            //forward user to viewing newly edited post:
            return $this->ViewPostsByID($blogID,$_POST['postID']);
            break;
        case 'DeletePost':
            $postID = $_GET['postID'];
            return $this->DeletePost($blogID,$postID);
            break;
        case 'ProcessDeletePost':
            $postID = $_POST['postID'];
            $this->ProcessDeletePost($blogID,$postID);
            //forward user to viewing posts in blog:
            return $this->ViewPostsByRecentCount($blogID,10);
            break;
        default:
            return BusinessLogic_Comment_Comment::GetInstance()->HandleRequest();
        }
    }
}
?>