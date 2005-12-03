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
        	    $this->ProcessCount($_GET['blogID']);
        	    break;

        	case 'ViewArchive':
        	    //TODO
        	    break;

            case 'ViewDashboard':
                //GetUserID will throw an exception if the user is not logged in
                $userID = BusinessLogic_User_User::GetInstance()->GetUserID();
                $aViewBlogView->SetContent($this->ViewDashboard($userID));
                break;

        	case 'EditBlogLayout':
        	    $aViewBlogView->SetContent($this->EditBlogLayout($_GET['blogID']));
        	    break;

        	case 'ProcessEditBlogLayout':
        	//TODO: wait for changes
//                if (isset($_POST['blogTitle']) and isset($_POST['theme']) and isset($_POST['headerImage']) $_POST['footerImage'], $_POST[''], $_POST['about'])
  //      	    $aViewBlogView->SetContent($this->ProcessEditBlogLayout($_GET['blogID'], $_POST['blogTitle'], $_POST['theme'], $_POST['headerImage'], $_POST['footerImage'], $_POST[''], $_POST['about']));
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

                //ensure that chosen themeID is actually an available theme
                $themeslist = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemesList();
                foreach ($themeslist as $key=>$value)
                {
                    if ($value['ThemeID'].'' == $_POST['theme'])
                    {
                        $themeid = $_POST['theme'];
                        break;
                    }
                }
                if (!isset($themeid))
                {
                    throw new Exception("Invalid Theme ID");
                }

                $headertog = $_POST['headertog'];
                if ($headertog == "no")
                {
                    $headerimg = '';
                }
                elseif ($headertog == "cust")
                {
                    $headerimg = $_POST['headerimg'];
                }
                else
                {
                    $headerimg = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemeDefaultHeader($themeid);
                }
                $footertog = $_POST['footertog'];
                if ($footertog == "no")
                {
                    $footerimg = '';
                }
                elseif ($footertog == "cust")
                {
                    $footerimg = $_POST['headerimg'];
                }
                else
                {
                    $footerimg = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemeDefaultFooter($themeid);
                }

                $newBlogID = $this->ProcessNewBlog($title,$about,$themeid,$headerimg,$footerimg);

                //forward user to viewing their new blog:
                $aViewBlogView->SetContent(BusinessLogic_Post_Post::GetInstance()->ViewPostsByRecentCount($newBlogID,10));
                break;

            case 'ViewSearch':
                $aViewBlogView->SetContent($this->ViewSearch($this->ViewPopular()));
                break;

        	default:
        	    $aViewBlogView->SetContent(BusinessLogic_User_User::GetInstance()->HandleRequest());
    	}

	   return $aViewBlogView;
    }
    
    //************************************
    //          ACTION FUNCTIONS
    //************************************
    
    public function ViewBlog($blogID)
    {
	   $aBlogSecurity = BusinessLogic_Blog_BlogSecurity::GetInstance();

	   switch($aBlogSecurity->ViewBlog($blogID))
	   {
	       case 'Owner':
	           $contentOptions = '<div id="blogcontrols">'
                    . ' <a href="index.php?Action=ViewBlog&blogID=' . $blogID . '">Home</a>'
                    . ' | <a href="index.php?Action=NewPost&blogID='.$blogID.'">New Post</a>'
                    . ' | <a href="index.php?Action=EditMembers&blogID='.$blogID.'">Edit Memberships</a>'
                    . ' | <a href="index.php?Action=EditBlogLayout&blogID='.$blogID.'">Edit Layout</a></a>'
                    . '</div>';
	           break;
              
	       case 'Editor':
	           $contentOptions = '<div id="blogcontrols">'
                    . ' <a href="index.php?Action=ViewBlog&blogID=' . $blogID . '">Home</a>'
                    . ' | <a href="index.php?Action=NewPost&blogID=' . $blogID . '">New Post</a>'
                    . '</div>';
	           break;
              
	       case 'Author':
	           $contentOptions = '<div id="blogcontrols">'
                    . ' <a href="index.php?Action=ViewBlog&blogID=' . $blogID . '">Home</a>'
                    . ' | <a href="index.php?Action=NewPost&blogID=' . $blogID . '">New Post</a>'
                    . '</div>';
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

    public function ViewSearch($more)
    {
        if($_POST['blog_title'] != '')
        {
            $result = BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessSearch($_POST['blog_title']);
        }
        else
        {
            $result = "Please Specify A Blog Name:";
        }
        
        return new Presentation_View_ViewSearchView($result, $more);
    }
    
    public function ViewPopular()
    {
        return BusinessLogic_Blog_BlogDataAccess::GetInstance()->ViewPopular();
    }

    public function EditBlogLayout($blogID)
    {
		if(BusinessLogic_Blog_BlogSecurity::GetInstance()->EditBlogLayout($blogID))
        {
            return BusinessLogic_Blog_BlogDataAccess::GetInstance()->EditBlogLayout($blogID);
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
    }

    public function EditLinks($blogID)
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

    public function ProcessEditLinks($blogID,$urls,$titles)
    {
		 if(BusinessLogic_Blog_BlogSecurity::GetInstance()->ProcessEditLinks($blogID))
        {
            BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessEditLinks($blogID, $urls,$titles);
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewBlog&blogID=' . $blogID;
            header("Location: $path");
            exit;
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
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
            throw new Exception('You are already the owner of another blog, you may not own two blogs at once.');
        }
        //blogid is passed solely for returning to system when user submits processnewblog form
        return new Presentation_View_NewBlogView($blogID);
    }

    public function ProcessNewBlog($title,$about,$theme,$headerimg,$footerimg)
    {
        //Calls BlogSecurity to determine if the user can create a new blog. If so, it will process the form data in NewBlogView and call BlogDataAccess.ProcessNewBlog() to commit the new data to storage, and call User.NewBlog to add the user as an owner for this blog. Otherwise, an exception is thrown. Returns the blog ID of the new blog.
        if (!BusinessLogic_Blog_BlogSecurity::GetInstance()->ProcessNewBlog())
        {
            throw new Exception('You are already the owner of another blog, you may not own two blogs at once.');
        }
        $newblogID = BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessNewBlog($title,$about,$theme,$headerimg,$footerimg);
        BusinessLogic_User_User::GetInstance()->NewBlog($newblogID);
        return $newblogID;
    }
    
    public function ProcessCount($blogID)
    {
        BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessCount($blogID);
    }

}

?>
