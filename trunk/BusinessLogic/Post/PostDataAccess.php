<?php

class BusinessLogic_Post_PostDataAccess
{
    //Helper class which interacts with the Data Access layer.
    //This class is also responsible for converting data into a View structure.

    private $TABLE;
    private $TIMESTAMP_FIELD;

    private function __construct()
    {
	$this->TABLE = 'Posts';
	$this->TIMESTAMP_FIELD = 'Timestamp';
    }

    static public function GetInstance()
    {
	if (!isset($_SESSION['BusinessLogic_Post_PostDataAccess']))
	{
	    $_SESSION['BusinessLogic_Post_PostDataAccess'] = new BusinessLogic_Post_PostDataAccess();
	}
	return $_SESSION['BusinessLogic_Post_PostDataAccess'];
    }

    public function NewPost($blogID)
    {
	//Creates a new empty post with the proper postID contained within.
	$query = 'select MAX(PostID) from [0] where BlogID=\'[1]\'';
	$arguments = array($this->TABLE, $blogID);

	$DataAccess = DataAccess_DataAccessFactory::GetInstance();
	//TODO: what kind of value does this return? int? string? Make sure it's an int:
	$response = $DataAccess->Select($query, $arguments);

	$newPostData = new Presentation_View_CompositePostView($blogID, ++$response,
							       '', '',
							       true, 0,
							       '');
	return new Presentation_View_NewPostView($newPostData);
    }

    public function ProcessNewPost($postView)
    {
	//Inserts data into the Posts table.
	$query = 'insert into [0] (PostID,BlogID,Author,Title,Timestamp,Content) VALUES ([1],[2],[3],[4],[5],[6])';
	$arguments = array($this->TABLE, $postview->GetPostID(),$postView->GetBlogID(),
			   $postView->GetAuthor(), $postView->GetTitle(), $postView->GetTimestamp(),
			   $postView->GetContent());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Insert($query, $arguments);
    }

    public function EditPost($blogID, $postID)
    {
	//Returns an EditPostView with data from the Posts table.
	$postarray = $this->ViewPostsByID($blogID,$postID);
	return new Presentation_View_EditPostView($postarray[0]);
    }

    public function ProcessEditPost($postView)
    {
	//Updates the Posts table with the new data.
	$query = 'update [0] set Author=[1], Title=[2], Timestamp=[3], Content=[4] where PostID=[5]';
	$arguments = array($this->TABLE, $postview->GetAuthor(),$postView->GetTitle(),
			   $postView->GetTimestamp(), $postView->GetContent(), $postView->GetPostID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
    }

    public function DeletePost($blogID, $postID)
    {
	//Returns a DeletePostView with data from the Posts table.
	$postarray = $this->ViewPostsByID($blogID,$postID);
	return new Presentation_View_DeletePostView($postarray[0]);
    }

    public function ProcessDeletePost($postView)
    {
	$blogID = $postView->GetBlogID();
	$postID = $postView->GetPostID();

	//Updates the Posts table with the new data.
	$query = 'delete from [0] where BlogID=[1] and PostID=[2]';
	$arguments = array($this->TABLE, $blogID, $postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    public function ViewPostsByID($blogID, $postID)
    {
	//Returns a ViewPostView with data from the Posts table.
	$query = 'select * from [0] where BlogID=[1] and PostID=[2] order by Timestamp desc';
        $arguments = array($this->TABLE, $blogID, $postID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositePosts = $this->SQLResultsToCompositePostViews($response);
	return new Presentation_View_ViewPostView($compositePosts);
    }

    public function ViewPostsByRecentCount($blogID, $postCount)
    {
	//Returns a ViewPostView with data from the Posts table.
	$query = 'select * from [0] where BlogID=[1] order by Timestamp desc limit [2]';
        $arguments = array($this->TABLE, $blogID, $postCount);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositePosts = $this->SQLResultsToCompositePostViews($response);
	return new Presentation_View_ViewPostView($compositePosts);
    }

    public function ViewPostsByDaysOld($blogID, $daysOld)
    {
	//Returns a ViewPostView with data from the Posts table.
	$query = 'select * from [0] where BlogID=[1] and Timestamp >= date_sub(curdate(),interval [2] day) order by Timestamp desc';
	
	$arguments = array($this->TABLE, $blogID, $daysOld);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositePosts = $this->SQLResultsToCompositePostViews($response);
	return new Presentation_View_ViewPostView($compositePosts);
    }

    public function ViewPostsByMonth($blogID, $year, $month)
    {
	//Returns a ViewPostView with data from the Posts table.
	if (strlen($year) != 4)
	{
	    throw new Exception('Year must be 4 digits.');
	}

	$followingmonth = ($month%12)+1;
	if ($followingmonth == 1)
	{
	    $followingyear = $year + 1;
	}
	else
	{
	    $followingyear = $year;
	}
	if (strlen($month) < 2)
	{
	    $month = '0'+$month;
	}

	$query = 'select Timestamp from [0] where BlogID=[1] and Timestamp >= [2][3]01 and Timestamp < [4][5]01';

	$arguments = array($this->TABLE, $blogID, $year, $month, $followingyear, $followingmonth);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

	$compositePosts = $this->SQLResultsToCompositePostViews($response);
	return new Presentation_View_ViewPostView($compositePosts);
    }

    public function ViewPostsByDay($blogID, $year, $month, $date)
    {
	//Returns a ViewPostView with data from the Posts table.
	if (strlen($year) != 4)
	{
	    throw new Exception('Year must be 4 digits.');
	}

	$begtime = '000000';
	$endtime = '235959';

	$query = 'select Timestamp from [0] where BlogID=[1] and Timestamp >= [2][3][4]'.$begtime.' and Timestamp <= [2][3][4]'.$endtime.'';

	$arguments = array($this->TABLE, $blogID, $year, $month, $date);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
	$response = $DataAccess->Select($query, $arguments);

	$compositePosts = $this->SQLResultsToCompositePostViews($response);
	return new Presentation_View_ViewPostView($compositePosts);
    }

    public function GetPostAuthorID($blogID, $postID)
    {
	//Returns the authorid of a given post within this blog.
	//Used by PostSecurity to determine if an Author can mess with a post.
	$query = 'select Author from [0] where BlogID=[1] and PostID=[2] order by Timestamp desc';
	$arguments = array($this->TABLE, $blogID, $postID);

	$DataAccess = DataAccess_DataAccessFactory::GetInstance();
	$response = $DataAccess->Select($query, $arguments);

	//TODO: what kind of value does this return? int? string? Make sure it's an int
	return $response;
    }

    private function SQLResultsToCompositePostViews($results)
    {
	if (count($results) < 1)
	{
	    throw new Exception('No posts were found.');
	}

	//go through each row and make a postview from it:
	foreach ($results as $key=>$value)
	{
	    //TODO: make sure that "public" is being sent as a boolean:
	    $returnme[$key] = new Presentation_View_CompositePostView($value['BlogID'], $value['PostID'],
								      $value['Author'], $value['Title'],
								      $value['Public'], $value['Timestamp'],
								      $value['Content']);
	}
	return $returnme;
    }
}

?>
