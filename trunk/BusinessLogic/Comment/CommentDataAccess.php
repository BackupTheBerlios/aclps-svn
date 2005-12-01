<?php

class BusinessLogic_Comment_CommentDataAccess
{
    //Helper class which interacts with the Data Access layer.
    //This class is also responsible for converting data into a View structure.

    private $TABLE;

    private function __construct()
    {
        $this->TABLE = 'Comments';
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
        $query = 'insert into [0] (PostID,BlogID,UserID,Title,Timestamp,Content) VALUES ([1],[2],[3],[4],NOW(),[5])';
        $arguments = array($this->TABLE, $commentView->GetPostID(), $commentView->GetBlogID(),
			   $commentView->GetAuthorID(), $commentView->GetTitle(),
                           $commentView->GetContent());
        $response = $DataAccess->Insert($query, $arguments);
    }

    public function EditComment($commentID)
    {
        //Returns an EditCommentView with data from the Comments table.
        $commentarray = $this->ViewCommentsByID($commentID);
        return new Presentation_View_EditCommentView($commentarray[0]);
    }

    public function ProcessEditComment($commentView)
    {
        //Updates the Comments table with the new data.
        $query = 'update [0] set Title=[1], Content=[2] where CommentID=[3]';
        $arguments = array($this->TABLE, $postView->GetTitle(), $postView->GetContent(), $postView->GetCommentID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
    }

    public function DeleteComment($commentID)
    {
        //Returns a DeleteCommentView with data from the Comments table.
        $commentarray = $this->ViewCommentsByID($commentID);
        return new Presentation_View_DeleteCommentView($commentarray[0]);
    }

    public function ProcessDeleteComment($commentID)
    {
        //Updates the Comments table with the new data.
        $query = 'delete from [0] where CommentID=[1]';
        $arguments = array($this->TABLE, $commentID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    public function ProcessDeleteAllComments($postID)
    {
        //Deletes all comments associated with this post.
        $query = 'delete from [0] where PostID=[1]';
        $arguments = array($this->TABLE, $postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query,$arguments);
    }

    public function ViewComments($blogID, $postID)
    {
        //Returns a ViewCommentCollectionView with data from the Comments table.
        $query = 'select * from [0] where BlogID=[1] and PostID=[2] order by Timestamp asc';
        $arguments = array($this->TABLE, $blogID, $postID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $comments = $this->SQLResultsToViewCommentViews($response);
        return new Presentation_View_ViewCommentCollectionView($comments);
    }

    public function ViewCommentsByID($commentID)
    {
        //Returns a ViewCommentCollectionView with data from the Comments table.
        $query = 'select * from [0] where CommentID=[1] order by Timestamp desc';
        $arguments = array($this->TABLE, $commentID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $comments = $this->SQLResultsToViewCommentViews($response);
        return new Presentation_View_ViewCommentCollectionView($comments);
    }

    public function GetCommentCounts($postIDs)
    {
        //Given an array of postIDs to look at, returns an array of comment counts in the same array indices.
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $arguments = array($this->TABLE);
        
        $insertme = '0';
        foreach($postIDs as $key=>$value)
        {
            $insertme = $insertme.' or PostID=['.($key+1).']';
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
