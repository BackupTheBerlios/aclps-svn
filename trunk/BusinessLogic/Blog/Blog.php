<?php

class BusinessLogic_Blog_Blog
{
    private function __construct()
    {
	//Singleton
    }
    
    static public function GetInstance()
    {
	if (!isset($_SESSION['BusinessLogic_Blog_Blog']))
	{
	    $_SESSION['BusinessLogic_Blog_Blog'] = new BusinessLogic_Blog_Blog();
	}
        return $_SESSION['BusinessLogic_Blog_Blog'];
    }

    public function HandleRequest()
    {
        if (!isset($_GET['blogID']))
       	{
            throw new Exception('Malformed Request-View Blog');
	}

	$aViewBlogView = $this->ViewBlog($_GET['blogID']);

	$request = $_GET['Action'];

	switch($request)
	{
	case 'ViewBlog':
	    $aViewBlogView->SetContent(BusinessLogic_Post_Post::GetInstance()->ViewPostsByRecentCount($_GET['blogID'],10));
	    $this->BlogViewCountUpdate($_GET['blogID']);
	    break;		
	case 'ViewArchive':
	    //TODO
	    break;
        case 'ViewDashboard':
            //GetUserID will throw an exception if the user is not logged in
            $userID = BusinessLogic_User_User::GetInstance()->GetUserID();
            $aViewBlogView->SetContent($this->ViewDashboard($userID));
            break;
	case 'EditAbout':
            $aViewBlogView->SetContent($this->EditAbout($_GET['blogID']));
	    break;
	case 'ProcessEditAbout':
	    //TODO
		//if it's not set throw exception
	    break;
	case 'EditBlogImages':
            //TODO
            break;
	case 'ProcessEditBlogImages':
            //TODO
            break;
	case 'EditBlogLayout':
	    //TODO
	    break;
	case 'ProcessEditBlogLayout':
	    //TODO
	    break;
	case 'EditLinks':
	    //TODO
	    break;
	case 'ProcessEditLinks':
	    //TODO
	    break;
	case 'EditMembers':
	    //TODO
	    break;
	case 'ProcessEditMembers':
	    //TODO
	    break;
	case 'NewBlog':
            //blogid is passed solely for returning to system when user submits processnewblog form
            $aViewBlogView->SetContent($this->NewBlog($_GET['blogID']));
	    break;
	case 'ProcessNewBlog':
            $title = $_POST['title'];
            $about = $_POST['about'];
            //TODO: before submitting new blog to data: clean the header/footer image urls
            $headerimg = $_POST['headerimg'];
            $footerimg = $_POST['footerimg'];
            //TODO: also ensure that chosen themeID is actually an available theme
            $theme = $_POST['theme'];
            $this->ProcessNewBlog($title,$about,$theme,$headerimg,$footerimg);

            //forward user to viewing their dashboard:
            $userID = BusinessLogic_User_User::GetInstance()->GetUserID();
            $aViewBlogView->SetContent($this->ViewDashboard($userID));
	    break;
     case 'ViewSearch':
        $aViewBlogView->SetContent($this->ViewSearch());
        $aViewBlogView->SetSideContent($this->ViewPopular());
        break;

	default:
	    $aViewBlogView->SetContent(BusinessLogic_User_User::GetInstance()->HandleRequest());
	}

	return $aViewBlogView;
    }

    public function ViewBlog($blogID)
    {
	$aBlogSecurity = BusinessLogic_Blog_BlogSecurity::GetInstance();

	switch($aBlogSecurity->ViewBlog($blogID))
	{
	case 'Owner':
	    $contentOptions = "<a href=index.php?Action=NewPost&blogID=$blogID>New Post</a>"
		. " : <a href=index.php?Action=EditMembers&blogID=$blogID>Edit Memberships</a>"
		. " : <a href=index.php?Action=EditLayout&blogID=$blogID>Edit Layout</a>";
	    break;
              
	case 'Editor':
	    $contentOptions = "<a href=index.php?Action=NewPost&blogID=$blogID>New Post</a>";
	    break;
              
	case 'Author':
	    $contentOptions = "<a href=index.php?Action=NewPost&blogID=$blogID>New Post</a>";
	    break;
              
	    //FALL THROUGH
	case 'Nobody':
	default:
	    $contentOptions = '';
	    break;
	}
          
	$aBlogDataAccess = BusinessLogic_Blog_BlogDataAccess::GetInstance();
	return $aBlogDataAccess->ViewBlog($blogID, $contentOptions);
    }

    public function ViewArchive()
    {
	//TODO
    }

    public function ViewDashboard($userID)
    {
        return BusinessLogic_Blog_BlogDataAccess::GetInstance()->ViewDashboard($userID);
    }

    public function ViewSearch()
    {
        if($_POST['blog_title'] != '')
        {
            $result = BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessSearch($_POST['blog_title']);
        }
        else
        {
            $result = "You have to enter something!!!";
        }
        
        return new Presentation_View_ViewSearchView($result);
    }
    
    public function ViewPopular()
    {
        return BusinessLogic_Blog_BlogDataAccess::GetInstance()->ViewPopular();
    }

    public function EditAbout($blogID)
    {
        if(BusinessLogic_Blog_BlogSecurity::GetInstance()->EditAbout($blogID))
        {
            return BusinessLogic_Blog_BlogDataAccess::GetInstance()->EditAbout($blogID);
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
    }

    public function ProcessEditAbout($blogID,$aboutContent)
    {      
    	if(BusinessLogic_Blog_BlogSecurity::GetInstance()->ProcessEditAbout($blogID))
        {
            BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessEditAbout($blogID, $aboutContent);
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewBlog&blogID=' . $blogID;
            header("Location: $path");
            exit;	
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
    }

    public function EditBlogImages($blogID)
    {
        if(BusinessLogic_Blog_BlogSecurity::GetInstance()->EditBlogImages($blogID))
        {
            BusinessLogic_Blog_BlogDataAccess::GetInstance()->EditBlogImages($blogID);
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
    }

    public function ProcessEditBlogImages($blogID, $headerImage, $footerImage)
    {
        if(BusinessLogic_Blog_BlogSecurity::GetInstance()->ProcessEditBlogImages($blogID))
        {
            BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessEditBlogImages($blogID, $headerImage, $footerImage);
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewBlog&blogID=' . $blogID;
            header("Location: $path");
            exit;
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
    }
    
    public function EditLinks()
    {
		if(BusinessLogic_Blog_BlogSecurity::GetInstance()->EditLinks($blogID))
        {
            BusinessLogic_Blog_BlogDataAccess::GetInstance()->EditLinks($blogID);
        }
        else
        {
            throw new Exception('Authentication failed.');
        }

    }

    public function ProcessEditLinks()
    {
	//TODO
    }

    public function EditMembers()
    {
	//TODO
    }

    public function ProcessEditMembers()
    {
	//TODO
    }

    public function NewBlog($blogID)
    {
        //Calls the BlogSecurity class to determine if the user can create a new blog. If so, a NewBlogView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Blog_BlogSecurity::GetInstance()->NewBlog())
        {
            throw new Exception('Authentication failed.');
        }
        //blogid is passed solely for returning to system when user submits processnewblog form
        return new Presentation_View_NewBlogView($blogID);
    }

    public function ProcessNewBlog($title,$about,$theme,$headerimg,$footerimg)
    {
        //Calls BlogSecurity to determine if the user can create a new blog. If so, it will process the form data in NewBlogView and call BlogDataAccess.ProcessNewBlog() to commit the new data to storage. Otherwise, an exception is thrown. Returns the blog ID of the new blog.
        if (!BusinessLogic_Blog_BlogSecurity::GetInstance()->ProcessNewBlog())
        {
            throw new Exception('Authentication failed.');
        }
        //TODO: add this user as owner of this blog
        return BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessNewBlog($title,$about,$theme,$headerimg,$footerimg);
    }
    
    public function BlogViewCountUpdate($blogID)
    {
    BusinessLogic_Blog_BlogDataAccess::GetInstance()->BlogViewCountUpdate($blogID);
    }

}

?>
