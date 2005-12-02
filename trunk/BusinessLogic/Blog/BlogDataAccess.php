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

        $aViewBlogView = new Presentation_View_ViewBlogView($blogID, $contentOptions, $blogRow['HeaderImage'],
                                                            $blogRow['FooterImage'], $themeRow['URL']);
                                                            
        $aViewBlogView->SetTopBar(BusinessLogic_User_User::GetInstance()->GetTopBar());
        $aViewBlogView->SetSideContent(new Presentation_View_ViewAboutView($blogRow['About']));
        $aViewBlogView->SetSideContent(new Presentation_View_ViewCalendarView());
        
        return $aViewBlogView;
    }

    public function GetThemesList()
    {
        //Returns a list of themes in a two dimensional array:
        //[0] => Array ( [ThemeID] => 1 [Title] => Default [URL] => UI/Themes/Default.css )
        //[1] => Array ( [ThemeID] => 2 [Title] => SomethingElse [URL] => Other/Theme.css )
    	$query = 'select * from Themes where 1';

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        return $DataAccess->Select($query, array());
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
        if ($user->IsUserBlogOwner)
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
        $query = 'select BlogID from [0] where UserID=[1]';
        $arguments = array('User_Auth', $userID);
        $associatedBlogResult = $DataAccess->Select($query, $arguments);
        
        $associatedBlogs = array();
        
        if (count($associatedBlogResult) > 0)
        {
            foreach ($associatedBlogResult as $key=>$value)
            {
                $query = 'select Title from [0] where BlogID=[1]';
                $arguments = array('Blogs', $value['BlogID']);
                $result = $DataAccess->Select($query, $arguments);
                $associatedBlogs[$value['BlogID']] = $result[0]['Title'];
            }
        }
        
        $ViewAssociatedBlogsView = new Presentation_View_ViewAssociatedBlogsView($associatedBlogs);
        
        $ViewDashboardView = new Presentation_View_ViewDashboardView;
        $ViewDashboardView->AddView($ViewMyBlogView);
        $ViewDashboardView->AddView($ViewAssociatedBlogsView);

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

    public function EditAbout($blogID)
    {
        $query = 'select About from [0] where blogID=[1]';
        $arguments = array('Blogs', $blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        
        return new Presentation_View_EditAboutView($result['About'],$blogID);
    }

    public function ProcessEditAbout($blogID,$aboutContent)
    {
        $query = 'update [0] set About = [1] where BlogID = [2]';
        $arguments = array('Blogs', $aboutContent, $blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance(); 
        return $DataAccess->Update($query, $arguments);
    }
    
    public function EditBlogImages($blogID)
    {
        $query = 'select HeaderImage,FooterImage from [0] where blogID=[1]';
        $arguments = array('Blogs', $blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $result = $DataAccess->Select($query, $arguments);
        
        return Presentation_View_EditBlogImagesView($result['HeaderImage'],$result['FooterImage'],$blogID);
    }

    public function ProcessEditBlogImages($blogID, $headerImage, $footerImage)
    {
        $query = 'update [0] set HeaderImage = "[1]", FooterImage = "[2]" where BlogID = [3]';
        $arguments = array('Blogs', $headerImage,$footerImage, $blogID);
        
        $DataAccess = DataAccess_DataAccessFactory::GetInstance(); 
        return $DataAccess->Update($query, $arguments);
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
        //Inserts data into the Blogs table.
        $query = 'insert into Blogs (Title,About,Theme,HeaderImage,FooterImage) VALUES ("[0]","[1]","[2]","[3]","[4]")';
        $arguments = array($title, $about,$theme,$headerimg,$footerimg);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();
        $response = $DataAccess->Insert($query, $arguments);
        return $response;
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
