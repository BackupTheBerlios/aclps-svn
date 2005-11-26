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

	//TODO: fix this constructor when view is done:
	$newCommentData = new Presentation_View_CompositeCommentView($blogID, $postID,
								     0, '', '',
								     true, 0,
								     '');
	return new Presentation_View_NewCommentView($newCommentData);
    }

    public function ProcessNewComment($commentView)
    {
	//Inserts data into the Comments table.
	$query = 'select MAX(CommentID) from [0] where BlogID=[1] and PostID=[2]';
	$arguments = array($this->TABLE, $blogID, $postID);

	$DataAccess = DataAccess_DataAccessFactory::GetInstance();
	//TODO: what kind of value does this return? int? string? Make sure it's an int:
	//TODO: WHAT HAPPENS IF THERE ARE NO COMMENTS YET?
	$currMaxCommentID = $DataAccess->Select($query, $arguments);

	$query = 'insert into [0] (CommentID,PostID,BlogID,UserID,Title,Timestamp,Content) VALUES ([1],[2],[3],[4],[5],[6],[7])';
	$arguments = array($this->TABLE, ($currMaxCommentID+1), $commentView->GetPostID(), 
			   $commentView->GetBlogID(), $commentView->GetAuthorID(), $commentView->GetTitle(),
			   $commentView->GetTimestamp(), $commentView->GetContent());

        $response = $DataAccess->Insert($query, $arguments);
    }

    public function EditComment($blogID, $postID, $commentID)
    {
	//Returns an EditCommentView with data from the Comments table.
	$commentarray = $this->ViewCommentsByID($blogID,$postID, $commentID);
	return new Presentation_View_EditCommentView($commentarray[0]);
    }

    public function ProcessEditComment($commentView)
    {
	//Updates the Comments table with the new data.
	$query = 'update [0] set UserID=[1], Title=[2], Timestamp=[3], Content=[4] where BlogID=[5] and PostID=[6] and CommentID=[7]';
	$arguments = array($this->TABLE, $postview->GetAuthorID(), $postView->GetTitle(),
			   $postView->GetTimestamp(), $postView->GetContent(), $postView->GetBlogID(),
			   $postView->GetPostID(), $postView->GetCommentID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
    }

    public function DeleteComment($blogID, $postID, $commentID)
    {
	//Returns a DeleteCommentView with data from the Comments table.
	$commentarray = $this->ViewCommentsByID($blogID,$postID,$commentID);
	return new Presentation_View_DeleteCommentView($commentarray[0]);
    }

    public function ProcessDeleteComment($postView)
    {
	$blogID = $commentView->GetBlogID();
	$postID = $commentView->GetPostID();
	$commentID = $commentView->GetCommentID();

	//Updates the Comments table with the new data.
	$query = 'delete from [0] where BlogID=[1] and PostID=[2] and CommentID=[3]';
	$arguments = array($this->TABLE, $blogID, $postID, $commentID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    public function ViewComments($blogID, $postID)
    {
	//Returns a ViewCommentView with data from the Comments table.
	$query = 'select * from [0] where BlogID=[1] and PostID=[2] order by Timestamp asc';
        $arguments = array($this->TABLE, $blogID, $postID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositeComments = $this->SQLResultsToCompositeCommentViews($response);
	return new Presentation_View_ViewPostView($compositeComments);
    }

    public function ViewCommentsByID($blogID, $postID, $commentID)
    {
	//Returns a ViewCommentView with data from the Comments table.
	$query = 'select * from [0] where BlogID=[1] and PostID=[2] and CommentID=[2] order by Timestamp desc';
        $arguments = array($this->TABLE, $blogID, $postID, $commentID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositeComments = $this->SQLResultsToCompositeCommentViews($response);
	return new Presentation_View_ViewCommentView($compositeComments);
    }

    private function SQLResultsToCompositeCommentViews($results)
    {
	if (count($results) < 1)
	{
	    return array();
	}

	//go through each row and make a postview from it:
	foreach ($results as $key=>$value)
	{
	    //TODO: make sure that "public" is being sent as a boolean:
	    //TODO: do this constructor when views are done
	    $returnme[$key] = new Presentation_View_CompositeCommentView($value['BlogID'], $value['PostID'],
									 $value['CommentID'], $value['Author'],
									 $value['Title'], $value['Public'], 
									 $value['Timestamp'], $value['Content']);
	}
	return $returnme;
    }
}

?>
