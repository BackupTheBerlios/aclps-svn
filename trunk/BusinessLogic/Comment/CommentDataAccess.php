<?php

class BusinessLogic_Comment_CommentDataAccess
{
    //Helper class which interacts with the Data Access layer.
    //This class is also responsible for converting data into a View structure.

    private function __construct()
    {
        //do nothing
    }

    static public function GetInstance()
    {
        if (!isset($_SESSION['BusinessLogic_Comment_CommentDataAccess']))
        {
            $_SESSION['BusinessLogic_Comment_CommentDataAccess'] = new BusinessLogic_Comment_CommentDataAccess();
        }
        return $_SESSION['BusinessLogic_Comment_CommentDataAccess'];
    }

    public function NewComment($blogID, $postID)
    {
        //Creates a new empty comment and returns it.
        //$blogID, $postID, $commentID, $authorID, $title, $timestamp, $content
        $userID = BusinessLogic_User_User::GetInstance()->GetUserID();
        $newCommentData = new Presentation_View_ViewCommentView($blogID, $postID, 0, $userID, '', 0, '');
        return new Presentation_View_NewCommentView($newCommentData);
    }

    public function ProcessNewComment($commentView)
    {
        //Inserts data into the Comments table.
        $query = 'insert into Comments (PostID,BlogID,UserID,Title,Timestamp,Content) VALUES ("[0]","[1]","[2]","[3]",NOW(),"[4]")';
        $arguments = array($commentView->GetPostID(), $commentView->GetBlogID(),
			   $commentView->GetAuthorID(), $commentView->GetTitle(),
                           $commentView->GetContent());
        $response = $DataAccess->Insert($query, $arguments);
    }

    public function EditComment($commentID)
    {
        //Returns an EditCommentView with data from the Comments table.
        return new Presentation_View_EditCommentView($this->GetSingleComment($commentID));
    }

    public function ProcessEditComment($commentView)
    {
        //Updates the Comments table with the new data.
        $query = 'update Comments set Title="[0]", Content="[1]" where CommentID=[2]';
        $arguments = array($commentView->GetTitle(), $commentView->GetContent(), $commentView->GetCommentID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
    }

    public function DeleteComment($commentID)
    {
        //Returns a DeleteCommentView with data from the Comments table.
        return new Presentation_View_DeleteCommentView($this->GetSingleComment($commentID));
    }

    public function ProcessDeleteComment($commentID)
    {
        //Updates the Comments table with the new data.
        $query = 'delete from Comments where CommentID=[0]';
        $arguments = array($commentID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    public function ProcessDeleteAllComments($postID)
    {
        //Deletes all comments associated with this post.
        $query = 'delete from Comments where PostID=[0]';
        $arguments = array($postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query,$arguments);
    }

    public function ViewComments($blogID, $postID)
    {
        //Returns a ViewCommentCollectionView with data from the Comments table.
        $query = 'select * from Comments where BlogID=[0] and PostID=[1] order by Timestamp asc';
        $arguments = array($blogID, $postID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $comments = $this->SQLResultsToViewCommentViews($response);
        return new Presentation_View_ViewCommentCollectionView($comments);
    }

    private function GetSingleComment($commentID)
    {
        //Returns a single comment's viewcommentview.
        $query = 'select * from Comments where CommentID=[0]';
        $arguments = array($commentID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $comments = $this->SQLResultsToViewCommentViews($response);
        return $comments[0];
    }

    public function ViewCommentsByID($commentID)
    {
        //Returns a ViewCommentCollectionView with data from the Comments table.
        $query = 'select * from Comments where CommentID=[0] order by Timestamp desc';
        $arguments = array($commentID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $comments = $this->SQLResultsToViewCommentViews($response);
        return new Presentation_View_ViewCommentCollectionView($comments);
    }

    public function GetCommentCounts($postIDs)
    {
        //Given an array of postIDs to look at, returns an array of comment counts in the same array indices.
        if (count($postIDs) < 1)
        {
            return array();
        }

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        
        $arguments = array();
        $insertme = '0';//just to stick into OR statement
        foreach($postIDs as $key=>$value)
        {
            $insertme = $insertme.' or PostID=['.$key.']';
            array_push($arguments, $value);
        }
        $query = 'select count(CommentID),PostID from Comments where '.$insertme.' group by PostID';
        $response = $DataAccess->Select($query, $arguments);

        $returnme = array();
        //extract contents of response:
        foreach($postIDs as $key=>$postID)
        {
            foreach($response as $responsekey=>$responsedata)
            {
                if ($responsedata['PostID'] == $postID)
                {
                    $returnme[$key] = $responsedata['count(CommentID)'];
                    unset($response[$responsekey]);
                    break;
                }
            }
        }
        //fill in 0's for slots that aren't currently filled:
        foreach($postIDs as $key=>$value)
        {
            if (!isset($returnme[$key]))
            {
                $returnme[$key] = 0;
            }
        }

        return $returnme;
    }

    private function SQLResultsToViewCommentViews($results)
    {
        if (count($results) < 1)
        {
            return array();
        }

        //go through each row and make a postview from it:
        foreach ($results as $key=>$value)
        {
            $returnme[$key] = new Presentation_View_ViewCommentView($value['BlogID'], $value['PostID'],
                                                                    $value['CommentID'], $value['UserID'],
                                                                    $value['Title'], $value['Timestamp'],
                                                                    $value['Content']);
        }
        return $returnme;
    }
}

?>
