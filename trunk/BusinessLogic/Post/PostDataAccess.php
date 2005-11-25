<?php

class BusinessLogic_Post_PostSecurity
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

    public function ProcessNewPost($postView)
    {
	//Inserts data into the Posts table.
	$query = 'insert into [0] (PostID,BlogID,Author,Title,Timestamp,Content) VALUES (\'[1]\',\'[2]\',\'[3]\',\'[4]\',\'[5]\',\'[6]\')'
	$arguments = array($this->TABLE, $postview->GetPostID(),$postView->GetBlogID(),
			   $postView->GetAuthor(), $postView->GetTitle(), $postView->GetTimestamp(),
			   $postView->GetContent());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Insert($query, $arguments);
	//TODO: check valid timestamps?
    }

    public function EditPost($blogID, $postID)
    {
	//Returns an EditPostView with data from the Posts table.
	//TODO: create editpostview then get back to this
    }

    public function ProcessEditPost($postView)
    {
	//Updates the Posts table with the new data.
	$query = 'update [0] set Author=\'[1]\', Title=\'[2]\', Timestamp=\'[3]\', Content=\'[4]\' where PostID=\'[5]\'';
	$arguments = array($this->TABLE, $postview->GetAuthor(),$postView->GetTitle(),
			   $postView->GetTimestamp(), $postView->GetContent(), $postView->GetPostID());

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
	//TODO: check valid timestamps?
    }

    public function DeletePost($blogID, $postID)
    {
	//Returns a DeletePostView with data from the Posts table.
	//TODO: create deletepostview then get back to this
    }

    public function ProcessDeletePost($postID)
    {
	//Updates the Posts table with the new data.
	$query = 'delete from \'[0]\' where PostID=\'[1]\'';
	$arguments = array($this->TABLE, $postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    public function ViewPostsByID($blogID, $postID)
    {
	//Returns an array of ViewPostViews with data from the Posts table.
	$query = 'select * from \'[0]\' where BlogID=\'[1]\' and PostID=\'[2]\' order by Timestamp desc';
        $arguments = array($this->TABLE, $blogID, $postID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        return $this->SQLResultsToPostViews($response);
    }

    public function ViewPostsByRecentCount($blogID, $postCount)
    {
	//Returns an array of ViewPostViews with data from the Posts table.
	$query = 'select * from \'[0]\' where BlogID=\'[1]\' order by Timestamp desc limit \'[2]\'';
        $arguments = array($this->TABLE, $blogID, $postCount);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        return $this->SQLResultsToPostViews($response);
    }

    public function ViewPostsByDaysOld($blogID, $daysOld)
    {
	//Returns an array of ViewPostViews with data from the Posts table.
	$query = 'select * from \'[0]\' where BlogID=\'[1]\' and Timestamp >= date_sub(curdate(),interval [2] day) order by Timestamp desc';
	
	$arguments = array($this->TABLE, $blogID, $daysOld);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        return $this->SQLResultsToPostViews($response);
    }

    public function ViewPostsByMonth($blogID, $year, $month)
    {
	//Returns an array of ViewPostViews with data from the Posts table.
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

	$query = 'select Timestamp from \'[0]\' where Timestamp >= \'[1][2]01\' and Timestamp < \'[3][4]01\'';

	$arguments = array($this->TABLE, $year, $month, $followingyear, $followingmonth);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

	return $this->SQLResultsToPostViews($response);
    }

    public function ViewPostsByDay($blogID, $year, $month, $date)
    {
	//Returns an array of ViewPostViews with data from the Posts table.
	if (strlen($year) != 4)
	{
	    throw new Exception('Year must be 4 digits.');
	}

	$begtime = '000000';
	$endtime = '235959';

	$query = 'select Timestamp from \'[0]\' where Timestamp >= \'[1][2][3]'.$begtime.'\' and Timestamp <= \'[1][2][3]'.$endtime.'\'';

	$arguments = array($this->TABLE, $year, $month, $date);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
	$response = $DataAccess->Select($query, $arguments);

	return $this->SQLResultsToPostViews($response);
    }

    private function SQLResultsToPostViews($results)
    {
	if (count($results) < 1)
	{
	    throw new Exception('No posts were found.');
	}

	foreach ($results as $key=>$value)
	{
	    $returnme[$key] = new Presentation_View_ViewPostView($value['BlogID'], $value['PostID'],
								$value['Author'], $value['Title'],
								$value['Timestamp']);
	}
	return $returnme
    }
}

?>
