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
	    //TODO: real values for postcount/userid:
	    $aViewBlogView->SetContent(BusinessLogic_Post_Post::GetInstance()->ViewPostsByRecentCount($_GET['blogID'],10,1));
	    break;		
	case 'ViewArchive':
	    //TODO
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
	    //TODO
	    break;
	case 'ProcessNewBlog':
	    //TODO
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
	    $contentOptions = "<a href=index.php?Action=NewPost&BlogID=$blogID>New Post</a>"
		. " : <a href=index.php?Action=EditMembers&BlogID=$blogID>Edit Memberships</a>"
		. " : <a href=index.php?Action=EditLayout&BlogID=$blogID>Edit Layout</a>";
	    break;
              
	case 'Editor':
	    $contentOptions = "<a href=index.php?Action=NewPost&BlogID=$blogID>New Post</a>";
	    break;
              
	case 'Author':
	    $contentOptions = "<a href=index.php?Action=NewPost&BlogID=$blogID>New Post</a>";
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
	//TODO
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

    public function NewBlog()
    {
        //Calls the BlogSecurity class to determine if the user can create a new blog. If so, a NewBlogView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Blog_BlogSecurity::GetInstance()->NewBlog())
        {
            throw new Exception('Authentication failed.');
        }
        return new Presentation_View_NewBlogView();
    }

    public function ProcessNewBlog($blogView)
    {
        //Calls BlogSecurity to determine if the user can create a new blog. If so, it will process the form data in NewBlogView and call BlogDataAccess.ProcessNewBlog() to commit the new data to storage. Otherwise, an exception is thrown. Returns the blog ID of the new blog.
        if (!BusinessLogic_Blog_BlogSecurity::GetInstance()->ProcessNewBlog())
        {
            throw new Exception('Authentication failed.');
        }
        //TODO: before submitting new blog to data: clean the header/footer image (then make them html tags)
        //TODO: also ensure that chosen theme is actually an available theme
        return BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessNewBlog($blogView);
    }
}

?>
