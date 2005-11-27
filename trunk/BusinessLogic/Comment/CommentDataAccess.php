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

    public function NewComment($blogID, $postID, $userID)
    {
	//Creates a new empty comment and returns it.
	//$blogID, $postID, $commentID, $authorID, $title, $timestamp, $content
	$newCommentData = new Presentation_View_ViewCommentView($blogID, $postID, 0, $userID, 
								'', 0, '');
	return new Presentation_View_NewCommentView($newCommentData);
    }

    public function ProcessNewComment($commentView)
    {
	//Inserts data into the Comments table.
	$query = 'insert into [0] (PostID,BlogID,UserID,Title,Timestamp,Content) VALUES ([1],[2],[3],[4],[5],[6])';
	$arguments = array($this->TABLE, $commentView->GetPostID(), $commentView->GetBlogID(),
			   $commentView->GetAuthorID(), $commentView->GetTitle(),
			   $commentView->GetTimestamp(), $commentView->GetContent());
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
	$query = 'update [0] set UserID=[1], Title=[2], Timestamp=[3], Content=[4] where CommentID=[5]';
	$arguments = array($this->TABLE, $postview->GetAuthorID(), $postView->GetTitle(),
			   $postView->GetTimestamp(), $postView->GetContent(), $postView->GetCommentID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
    }

    public function DeleteComment($commentID)
    {
	//Returns a DeleteCommentView with data from the Comments table.
	$commentarray = $this->ViewCommentsByID($commentID);
	return new Presentation_View_DeleteCommentView($commentarray[0]);
    }

    public function ProcessDeleteComment($commentView)
    {
	//Updates the Comments table with the new data.
	$commentID = $commentView->GetCommentID();

	$query = 'delete from [0] where CommentID=[1]';
	$arguments = array($this->TABLE, $commentID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    public function ProcessDeleteAllComments($postView)
    {
	//Deletes all comments associated with this post.
	$postID = $postView->GetPostID();

	$query = 'delete from [0] where PostID=[1]';
	$arguments = array($this->TABLE, $postID);

	$DataAccess = DataAccess_DataAccessFactory::GetInstance();
	$response = $DataAccess->Delete($query,$arguments);

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
	//TODO: how bad is having a lot of select statements like this?
	$DataAccess = DataAccess_DataAccessFactory::GetInstance();
	$returnme = array();
	$arguments = array($this->TABLE,20);//a little optimization: just replace the 2nd index each loop
	foreach($postIDs as $key=>$value)
	{
	    $query = 'select COUNT(commentID) from [0] where PostID=[1]';
	    $arguments[1] = $postIDs[$key];
	    $response = $DataAccess->Select($query, $arguments);
	    
	    //TODO: is this an int?:
	    $returnme[$key] = $response[0];
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
								    $value['CommentID'], $value['Author'],
								    $value['Title'], $value['Timestamp'],
								    $value['Content']);
	}
	return $returnme;
    }
}

?>
