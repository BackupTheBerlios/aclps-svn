<?php

class BusinessLogic_Post_PostDataAccess
{
    //Helper class which interacts with the Data Access layer.
    //This class is also responsible for converting data into a View structure.

    private $TABLE;

    private function __construct()
    {
	$this->TABLE = 'Posts';
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
	//Creates a new empty post and returns it.
	//$blogID, $postID, $authorID, $title, $public, $timestamp, $content
	$userID = BusinessLogic_User_User::GetInstance()->GetUserID();
	$newPostData = new Presentation_View_ViewPostView($blogID, 0, $userID, '', true, 0, '');
	return new Presentation_View_NewPostView($newPostData);
    }

    public function ProcessNewPost($postView)
    {
	//Inserts data into the Posts table.
	$query = 'insert into [0] (BlogID,UserID,Title,Timestamp,Content) VALUES ([1],[2],[3],[4],[5])';
	$arguments = array($this->TABLE, $postView->GetBlogID(), $postView->GetAuthorID(),
			   $postView->GetTitle(), $postView->GetTimestamp(), $postView->GetContent());

        $response = $DataAccess->Insert($query, $arguments);
    }

    public function EditPost($postID)
    {
	//Returns an EditPostView with data from the Posts table.
	$postarray = $this->ViewPostsByID($postID);
	return new Presentation_View_EditPostView($postarray[0]);
    }

    public function ProcessEditPost($postView)
    {
	//Updates the Posts table with the new data.
	$query = 'update [0] set UserID=[1], Title=[2], Timestamp=[3], Content=[4] where PostID=[5]';
	$arguments = array($this->TABLE, $postview->GetAuthorID(),$postView->GetTitle(), $postView->GetTimestamp(),
			   $postView->GetContent(), $postView->GetPostID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
    }

    public function DeletePost($postID)
    {
	//Returns a DeletePostView with data from the Posts table.
	$postarray = $this->ViewPostsByID($postID);
	return new Presentation_View_DeletePostView($postarray[0]);
    }

    //TODO: DOES MYSQL AUTOSHIFT POSTIDS?
    //(IF TWO PEOPLE DELETE THE SAME ID AT ONCE, DO THEY END UP DELETING TWO POSTS?)
    public function ProcessDeletePost($postView)
    {
	$postID = $postView->GetPostID();

	//Updates the Posts table with the new data.
	$query = 'delete from [0] where PostID=[1]';
	$arguments = array($this->TABLE, $postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    public function ViewPostsByID($blogID, $postID, $commentView, $hideprivate)
    {
	//Returns a ViewPostView with data from the Posts table.
	//(blogid MUST be here to prevent someone from sneaking into a post through another blog that they better access to)
	$extras = '';
	if ($hideprivate)
	{
	    $extras = 'and Public=true ';
	}

	$query = 'select * from [0] where PostID=[1] and BlogID=[2] '.$extras.'order by Timestamp desc';
        $arguments = array($this->TABLE, $postID, $blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositePosts = $this->SQLResultsToViewPostViews($response);

	//This is a single post, so add comments to the bottom:
	$compositePosts[0]->SetBottomContent($commentView);
	return $compositePosts[0];
    }

    public function ViewPostsByRecentCount($blogID, $postCount, $hideprivate)
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	$extras = '';
	if ($hideprivate)
	{
	    $extras = 'and Public=true ';
	}

	$query = 'select * from [0] where BlogID=[1] '.$extras.'order by Timestamp desc limit [2]';
        $arguments = array($this->TABLE, $blogID, $postCount);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositePosts = $this->SQLResultsToViewPostViews($response);
	return new Presentation_View_ViewPostCollectionView($compositePosts);
    }

    public function ViewPostsByDaysOld($blogID, $daysOld, $hideprivate)
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	$extras = '';
	if ($hideprivate)
	{
	    $extras = 'and Public=true ';
	}

	$query = 'select * from [0] where BlogID=[1] '.$extras.'and Timestamp >= date_sub(curdate(),interval [2] day) order by Timestamp desc';
	
	$arguments = array($this->TABLE, $blogID, $daysOld);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $compositePosts = $this->SQLResultsToViewPostViews($response);
	return new Presentation_View_ViewPostCollectionView($compositePosts);
    }

    public function ViewPostsByMonth($blogID, $year, $month, $hideprivate)
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	$this->CheckTimes($year,$month,1);

	$followingmonth = ($month%12)+1;
	if ($followingmonth == 1)
	{
	    $followingyear = $year + 1;
	}
	else
	{
	    $followingyear = $year;
	}

	$month = str_pad($month, 2, '0', STR_PAD_LEFT);
	$year = str_pad($year, 4, '0', STR_PAD_LEFT);
	$followingmonth = str_pad($followingmonth, 2, '0', STR_PAD_LEFT);
	$followingyear = str_pad($followingyear, 4, '0', STR_PAD_LEFT);

	$begdate = $year.$month.'01';
	$enddate = $followingyear.$followingmonth.'01';

	$extras = '';
	if ($hideprivate)
	{
	    $extras = 'and Public=true ';
	}

	$query = 'select * from [0] where BlogID=[1] '.$extras.'and Timestamp >= [2] and Timestamp < [3]';

	$arguments = array($this->TABLE, $blogID, $begdate, $enddate);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

	$compositePosts = $this->SQLResultsToViewPostViews($response);
	return new Presentation_View_ViewPostCollectionView($compositePosts);
    }

    public function ViewPostsByDay($blogID, $year, $month, $date, $hideprivate)
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	$this->CheckTimes($year,$month,$date);

	$begtime = $year.$month.$date.'000000';
	$endtime = $year.$month.$date.'235959';

	$extras = '';
	if ($hideprivate)
	{
	    $extras = 'and Public=true ';
	}

	$query = 'select Timestamp from [0] where BlogID=[1] '.$extras.'and Timestamp >= [2] and Timestamp <= [3]';

	$arguments = array($this->TABLE, $blogID, $begtime, $endtime);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
	$response = $DataAccess->Select($query, $arguments);

	$compositePosts = $this->SQLResultsToViewPostViews($response);
	return new Presentation_View_ViewPostCollectionView($compositePosts);
    }

    private function CheckTimes($year,$month,$date)
    {
	//Throws if year/month/day are out of range.
	if (strlen($year) != 4)
	{
	    throw new Exception('Year must be 4 digits.');
	}
	elseif ($month > 12 or $month < 1)
	{
	    throw new Exception('Month must be greater than 0/less than 13');
	}
	elseif ($date > 31 or $date < 1)
	{
	    throw new Exception('Day must be greater than 0/less than 32');
	}
    }

    public function GetPostAuthorID($postID)
    {
	//Returns the authorid of a given post.
	//Used by PostSecurity to determine if an Author can mess with a post.
	$query = 'select UserID from [0] where PostID=[1] order by Timestamp desc';
	$arguments = array($this->TABLE, $postID);

	$DataAccess = DataAccess_DataAccessFactory::GetInstance();
	$response = $DataAccess->Select($query, $arguments);

	return $response[0]['UserID'];
    }

    private function SQLResultsToViewPostViews($results)
    {
	if (count($results) < 1)
	{
	    throw new Exception('No posts were found.');
	}

	//go through each row and make a postview from it:
	foreach ($results as $key=>$value)
	{
	    //TODO: make sure that "public" is being sent as a boolean (might be an int: 0 or 1):
	    $returnme[$key] = new Presentation_View_ViewPostView($value['BlogID'], $value['PostID'],
								      $value['UserID'], $value['Title'],
								      $value['Public'], $value['Timestamp'],
								      $value['Content']);
	}
	return $returnme;
    }
}

?>
