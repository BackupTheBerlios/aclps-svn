<?php

class BusinessLogic_Post_PostDataAccess
{
    //Helper class which interacts with the Data Access layer.
    //This class is also responsible for converting data into a View structure.

    private function __construct()
    {
        //do nothing
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
        $query = 'insert into Posts (BlogID,UserID,Title,Timestamp,Content) VALUES ("[0]","[1]","[2]",NOW(),"[3]")';
        $filteredContent = BusinessLogic_ACLPSCodeConverter::ACLPSCodeToHTML($postView->GetContent());
        $arguments = array($postView->GetBlogID(), $postView->GetAuthorID(),
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
            $query = 'update Posts set Title="[0]", Timestamp=NOW(), Public=[1], Content="[2]" where PostID=[3]';
            $arguments = array($postView->GetTitle(), $postView->GetPublic(),
                               $filteredContent, $postView->GetPostID());
        }
        else
        {
            $query = 'update Posts set Title="[0]", Public=[1], Content="[2]" where PostID=[3]';
            $arguments = array($postView->GetTitle(), $postView->GetPublic(),
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
        $query = 'delete from Posts where PostID=[0]';
        $arguments = array($postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Delete($query, $arguments);
    }

    private function GetSinglePost($postID)
    {
        //Returns a single post's viewpostview.
        $query = 'select * from Posts where PostID=[0]';
        $arguments = array($postID);

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

        $query = 'select * from Posts where PostID=[0] and BlogID=[1] '.$extras.'order by Timestamp desc';
        $arguments = array($postID, $blogID);
        
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

        $query = 'select * from Posts where BlogID=[0] '.$extras.'order by Timestamp desc limit [1]';
        $arguments = array($blogID, $postCount);

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

        $query = 'select * from Posts where BlogID=[0] '.$extras.'and Timestamp >= date_sub(curdate(),interval [1] day) order by Timestamp desc';

        $arguments = array($blogID, $daysOld);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $posts = $this->SQLResultsToViewPostViews($response);
        return new Presentation_View_ViewPostCollectionView($posts);
    }

    public function GetDatesWithPostsForMonth($blogID, $year, $month, $hideprivate)
    {
        //DONT CALL THIS DIRECTLY, call the function in Post (it will determine $hideprivate for you).
        //Returns an array mapping of dates->true (true=posts on that date, nothing=no posts)
        //If hideprivate is true, doesn't return true for days on which there are only private posts.

        $this->CheckTimes($year,$month,1);

        $extras = '';
        if ($hideprivate)
        {
            $extras = 'and Public=true ';
        }

        $query = 'select DAYOFMONTH(Timestamp) from Posts where BlogID=[0] '.$extras.'and YEAR(Timestamp) = [1] and MONTH(Timestamp) = [2]';
        $arguments = array($blogID, $year, $month);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $returnme = array();
        foreach ($response as $key=>$value)
        {
            $returnme[$value['Timestamp']] = true;
        }
        return $returnme;
    }

    public function ViewPostsByMonth($blogID, $year, $month, $hideprivate)
    {
        //Returns a ViewPostCollectionView with data from the Posts table.
        $this->CheckTimes($year,$month,1);

        $extras = '';
        if ($hideprivate)
        {
            $extras = 'and Public=true ';
        }

        $query = 'select * from Posts where BlogID=[0] '.$extras.'and YEAR(Timestamp) = [1] and MONTH(Timestamp) = [2]';
        $arguments = array($blogID, $year, $month);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        $posts = $this->SQLResultsToViewPostViews($response);
        return new Presentation_View_ViewPostCollectionView($posts);
    }

    public function ViewPostsByDay($blogID, $year, $month, $date, $hideprivate)
    {
        //Returns a ViewPostCollectionView with data from the Posts table.
        $this->CheckTimes($year,$month,$date);

        $extras = '';
        if ($hideprivate)
        {
            $extras = 'and Public=true ';
        }

        $query = 'select * from Posts where BlogID=[0] '.$extras.'and YEAR(Timestamp) = [1] and MONTH(Timestamp) = [2] and DAYOFMONTH(Timestamp) = [3]';

        $arguments = array($blogID, $year, $month, $date);

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
        $query = 'select UserID from Posts where PostID=[0] order by Timestamp desc';
        $arguments = array($postID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Select($query, $arguments);

        return $response[0]['UserID'];
    }

    private function SQLResultsToViewPostViews($results)
    {
        if (count($results) < 1)
        {
            return array();
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
