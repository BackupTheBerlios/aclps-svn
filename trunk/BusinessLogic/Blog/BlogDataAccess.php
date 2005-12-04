<?php

class BusinessLogic_Blog_BlogDataAccess
{
    private $TABLE;

    private function __construct()
    {
	$this->$TABLE = 'Blogs';
    }

    static public function GetInstance()
    {
	if (!isset($_SESSION['BusinessLogic_Blog_BlogDataAccess']))
	{
	    $_SESSION['BusinessLogic_Blog_BlogDataAccess'] = new BusinessLogic_Blog_BlogDataAccess();
	}
        return $_SESSION['BusinessLogic_Blog_BlogDataAccess'];

    }
    
    public function ViewBlog($blogID, $contentOptions)
    {
    	$query = 'select * from [0] where BlogID=[1]';
        $arguments = array('Blogs', $blogID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $blogResult = $DataAccess->Select($query, $arguments);
        
        if (count($blogResult) < 1)
        {
	    throw new Exception('Request for unknown blog.');
        }

    	$blogRow = $blogResult[0];

    	$query = 'select URL from [0] where ThemeID=[1]';
        $arguments = array('Themes', $blogRow['ThemeID']);
        $themeResult = $DataAccess->Select($query, $arguments);

        $themeRow = $themeResult[0];

        $aViewBlogView = new Presentation_View_ViewBlogView($blogID, $contentOptions, $blogRow['HeaderImage'], $blogRow['FooterImage'], $themeRow['URL']);

        
        $aViewBlogView->SetTopBar(BusinessLogic_User_User::GetInstance()->GetTopBar());
        $aViewBlogView->SetSideContent(new Presentation_View_ViewAboutView($blogRow['About']));
        
        return $aViewBlogView;
    }

    public function GetThemesList()
    {
        //Returns a list of themes in a two dimensional array:
        //[0] => Array ( [ThemeID] => 1 [Title] => Default [URL] => UI/Themes/Default.css )
        //[1] => Array ( [ThemeID] => 2 [Title] => SomethingElse [URL] => Other/Theme.css )
    	$query = 'select ThemeID, Title from [0]';
        $arguments = array('Themes');
        
        $result = DataAccess_DataAccessFactory::GetInstance()->Select($query, $arguments);

        $themes = array();
        foreach($result as $key=>$row)
        {
            $themes[$row['ThemeID']] = $row['Title'];
        }
        
        print_r($themes);
        print '<br />';
        
        return $themes;


    }
    public function GetThemeDefaultHeader($themeID)
    {
        //Given a themeID, returns that theme's default header image url.
    	$query = 'select DefaultHeader from Themes where ThemeID=[0]';
        $arguments = array($themeID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $data = $DataAccess->Select($query, $arguments);
        return $data[0]['DefaultHeader'];
    }
    public function GetThemeDefaultFooter($themeID)
    {
        //Given a themeID, returns that theme's default footer image url.
    	$query = 'select DefaultFooter from Themes where ThemeID=[0]';
        $arguments = array($themeID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $data = $DataAccess->Select($query, $arguments);
        return $data[0]['DefaultFooter'];
    }

    public function ViewArchive()
    {
	//TODO
    }

    public function ViewDashboard($userID)
    {
        $user = BusinessLogic_User_User::GetInstance();
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        
        // Get My Blog Information
        if ($user->IsUserBlogOwner())
        {
            $blogID = $user->GetUserBlogID();
            
            $query = 'select Title from [0] where blogID=[1]';
            $arguments = array('Blogs', $blogID);
            $result = $DataAccess->Select($query, $arguments);
            
            $blogTitle = $result[0]['Title'];
        }
        else
        {
            $blogID = 0;
            $blogTitle = '';
        }
        
        $ViewMyBlogView = new Presentation_View_ViewMyBlogView($blogID, $blogTitle);
        
        //Get Associated Blog Information
        $query = 'select BlogID, Auth from [0] where UserID=[1]';
        $arguments = array('User_Auth', $user->GetUserID());
        $associatedBlogResult = $DataAccess->Select($query, $arguments);
        
        $associatedBlogs = array();
        
        if (count($associatedBlogResult) > 0)
        {
            foreach ($associatedBlogResult as $key=>$value)
            {
                $query = 'select Title from [0] where BlogID=[1]';
                $arguments = array('Blogs', $value['BlogID']);
                $result = $DataAccess->Select($query, $arguments);
                
                $blogID = $value['BlogID'];
                $rank = $value['Auth'];
                $title = $result[0]['Title'];
                
                $associatedBlogs[$blogID] = array('rank' => $rank, 'title' => $title);
                
            }
        }
        
        $aViewAssociatedBlogCollectionView = new Presentation_View_ViewAssociatedBlogCollectionView();
        
        foreach($associatedBlogs as $blogID=>$arr)
        {
            $aViewAssociatedBlogCollectionView->AddView(new Presentation_View_ViewAssociatedBlogView($blogID, $arr['title'], $arr['rank']));
        }
        
        //Get Invitations
        $query = 'select * from [0] where UserID=[1]';
        $arguments = array('Invitations', $user->GetUserID());
        $invitationsResult = $DataAccess->Select($query, $arguments);

        $invitations = array();

        if (count($invitationsResult) > 0)
        {
            foreach ($invitationsResult as $key=>$value)
            {
                $query = 'select Title from [0] where BlogID=[1]';
                $arguments = array('Blogs', $value['BlogID']);
                $result = $DataAccess->Select($query, $arguments);

                $blogID = $value['BlogID'];
                $rank = $value['Rank'];
                $title = $result[0]['Title'];

                $invitations[$blogID] = array('rank' => $rank, 'title' => $title);

            }
        }

        $aViewDashboardInvitationCollectionView = new Presentation_View_ViewDashboardInvitationCollectionView();

        foreach($invitations as $blogID=>$arr)
        {
            $aViewDashboardInvitationCollectionView->AddView(new Presentation_View_ViewDashboardInvitationView($_GET['blogID'], $blogID, $arr['title'], $arr['rank']));
        }

        $ViewDashboardView = new Presentation_View_ViewDashboardView;
        $ViewDashboardView->AddView($ViewMyBlogView);
        $ViewDashboardView->AddView($aViewAssociatedBlogCollectionView);
        $ViewDashboardView->AddView($aViewDashboardInvitationCollectionView);

        return $ViewDashboardView;
    }
    
    //get the most 3 popular blogs
    public function ViewPopular()
    {
        $query = "select [0], [1] from Blogs order by Count desc limit 10";
        $arguments = array('BlogID', 'Title');

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);

        if(count($result) < 1)
        {
          throw new Exception("Can't find the most popular blog.");
        }
        else
        {
          return new Presentation_View_ViewPopularView($result);
        }
    }
    
    public function ViewCalendar($blogID, $year, $month)
    {
        $postsarray = BusinessLogic_Post_Post::GetInstance()->GetDatesWithPostsForMonth($blogID, $year, $month);
        return new Presentation_View_ViewCalendarView($blogID, $postsarray, $year, $month);
    }

    public function EditBlogLayout($blogID)
    {
        $query = "select * from [0] where BlogID=[1]";
        $arguments = array('Blogs', $blogID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        $row = $result[0];
        
        return new Presentation_View_EditBlogLayoutView($blogID, $this->GetThemesList(), $row['Title'], $row['About'], $row['ThemeID'], $row['HeaderImage'], $row['FooterImage']);
    }

    public function EditLinks($blogID)
    {
        $query = 'select URL, Title from [0] where blogID=[1]';
        $arguments = array('Links', $blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        
        return Presentation_View_EditLinks($result['URL'],$result['Title'],$blogID);
    }

    public function ProcessEditLinks($blogID,$urls,$titles)
    {
		$query = 'update [0] set URL = "[1]", Title = "[2]" where BlogID = [3]';
		$arguments = array('Links',$urls,$titles);
		
		$DataAccess = DataAccess_DataAccessFactory::GetInstance(); 
        return $DataAccess->Update($query, $arguments);
	}

    public function EditMembers()
    {
	//TODO
    }

    public function ProcessEditMembers()
    {
	//TODO
    }

    public function ProcessNewBlog($title,$about,$theme,$headerimg,$footerimg)
    {
        //TODO: fyi: could have a "random" column in the blog table, pass random num to it, then ask for that random num back
        //Inserts data into the Blogs table, and returns the new blog ID
        $query = 'insert into Blogs (Title,About,ThemeID,HeaderImage,FooterImage) VALUES ("[0]","[1]","[2]","[3]","[4]")';
        $arguments = array($title,$about,$theme,$headerimg,$footerimg);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Insert($query, $arguments);

        $query = 'select MAX(BlogID) from Blogs where Title="[0]" and About="[1]"';
        $arguments = array($title,$about);
        $response = $DataAccess->Select($query, $arguments);
        return $response[0]['MAX(BlogID)'];//return new blog id
    }
    
    //counter for a blog
    public function ProcessCount($blogID)
    {
        $query = "update Blogs set Count=Count+1 where BlogID=[0]";
        $arguments = array($blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Update($query, $arguments);
        
        if($result < 1)
        {
          throw new Exception('Counter update failed on blogID '.$blogID);
        }
    }

    public function ProcessSearch($blog_title)
    {
        $query = "select BlogID, Title, About from Blogs where Title like '%[0]%'";
        $arguments = array($blog_title);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);

        //there is no matching record in the DB
        if(count($result) < 1)
        {
        return new Presentation_View_ViewSearchBlogCollectionView(0, $blog_title);
        }
        else
        {
        foreach($result as $key => $value)
        {
            $blogsID[$key] = new Presentation_View_ViewSearchBlogView($value['BlogID'],
                $value['Title'], $value['About']);
        }

        return new Presentation_View_ViewSearchBlogCollectionView($blogsID, $blog_title);
        }
    }


}
?>
