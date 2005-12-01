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

    public function ProcessNewPost($postView)
    {
        //Inserts data into the Posts table.
        $query = 'insert into [0] (BlogID,UserID,Title,Timestamp,Content) VALUES ("[1]","[2]","[3]",NOW(),"[4]")';
        $filteredContent = BusinessLogic_ACLPSCodeConverter::ACLPSCodeToHTML($postView->GetContent());
        $arguments = array($this->TABLE, $postView->GetBlogID(), $postView->GetAuthorID(),
                           $postView->GetTitle(), $filteredContent);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Insert($query, $arguments);
        return $response;
    }

    public function EditPost($postID)
    {
        //Returns an EditPostView with data from the Posts table.
        return new Presentation_View_EditPostView($this->GetSinglePost($postID));
    }

    public function ProcessEditPost($postView,$useNowForTimestamp)
    {
        //Updates the Posts table with the new data.
        $filteredContent = BusinessLogic_ACLPSCodeConverter::ACLPSCodeToHTML($postView->GetContent());
        if ($useNowForTimestamp)
        {
            $query = 'update [0] set Title="[1]", Timestamp=NOW(), Public=[2], Content="[3]" where PostID=[4]';
            $arguments = array($this->TABLE, $postView->GetTitle(), $postView->GetPublic(),
                               $filteredContent, $postView->GetPostID());
        }
        else
        {
            $query = 'update [0] set Title="[1]", Public=[2], Content="[3]" where PostID=[4]';
            $arguments = array($this->TABLE, $postView->GetTitle(), $postView->GetPublic(),
                               $filteredContent, $postView->GetPostID());
        }

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Update($query, $arguments);
    }

    public function DeletePost($postID)
    {
        //Returns a DeletePostView with data from the Posts table.
        return new Presentation_View_DeletePostView($this->GetSinglePost($postID));
    }

    public function ProcessDeletePost($postID)
    {
        //Updates the Posts table with the new data.
        $query = 'delete from [0] where PostID=[1]';
        $arguments = array($this->TABLE, $postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    private function GetSinglePost($postID)
    {
        //Returns a single post's viewpostview.
        $query = 'select * from [0] where PostID=[1]';
        $arguments = array($this->TABLE, $postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $posts = $this->SQLResultsToViewPostViews($response);
        return $posts[0];
    }

    public function ViewPostsByID($blogID, $postID, $commentView, $hideprivate)
    {
        //Returns a ViewPostView with data from the Posts table.
        //(blogid MUST be here to prevent someone from sneaking into a post through another blog that they have better access to)
        $extras = '';
        if ($hideprivate)
        {
            $extras = 'and Public=1 ';
        }

        $query = 'select * from [0] where PostID=[1] and BlogID=[2] '.$extras.'order by Timestamp desc';
        $arguments = array($this->TABLE, $postID, $blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $posts = $this->SQLResultsToViewPostViews($response);

        //This is a single post, so add comments to the bottom if they're provided):
        if (is_object($commentView)) {
            $posts[0]->SetBottomContent($commentView);
        }
        return $posts[0];
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

        $posts = $this->SQLResultsToViewPostViews($response);
        return new Presentation_View_ViewPostCollectionView($posts);
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

        $posts = $this->SQLResultsToViewPostViews($response);
        return new Presentation_View_ViewPostCollectionView($posts);
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

        $posts = $this->SQLResultsToViewPostViews($response);
        return new Presentation_View_ViewPostCollectionView($posts);
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

        $posts = $this->SQLResultsToViewPostViews($response);
        return new Presentation_View_ViewPostCollectionView($posts);
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
            $returnme[$key] = new Presentation_View_ViewPostView($value['BlogID'], $value['PostID'],
                                                                 $value['UserID'], $value['Title'],
                                                                 $value['Public'], $value['Timestamp'],
                                                                 $value['Content']);
        }
        return $returnme;
    }
}

?>
