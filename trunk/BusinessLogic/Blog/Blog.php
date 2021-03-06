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
	    $_SESSION['BusinessLogic_Blog_Blog'] = serialize(new BusinessLogic_Blog_Blog());
	}
        return unserialize($_SESSION['BusinessLogic_Blog_Blog']);
    }

    public function HandleRequest()
    {
        if (!isset($_GET['blogID']))
       	{
            throw new Exception('Malformed Request: Missing BlogID');
        }

    	$aViewBlogView = $this->ViewBlog($_GET['blogID']);

        //add additional default side content here (before/above or after/below the calendar):
        $today = getdate();
        if (isset($_GET['year']))
            $year = $_GET['year'];
        else
            $year = $today['year'];
        if (isset($_GET['month']))
            $month = $_GET['month'];
        else
            $month = $today['mon'];
        $aViewBlogView->SetSideContent($this->ViewCalendar($_GET['blogID'],$year,$month));

    	$request = $_GET['Action'];

    	switch($request)
    	{
        case 'ViewBlog':
            print $this->defaultpostcount;
            $aViewBlogView->SetContent(BusinessLogic_Post_Post::GetInstance()->ViewPostsByRecentCount($_GET['blogID'],10));
            $this->ProcessCount($_GET['blogID']);
            break;
            
        case 'ViewDashboard':
            //GetUserID will throw an exception if the user is not logged in
            $userID = BusinessLogic_User_User::GetInstance()->GetUserID();
            $aViewBlogView->SetContent($this->ViewDashboard($userID));
            break;
            
        case 'EditBlogLayout':
            $aViewBlogView->SetContent($this->EditBlogLayout($_GET['blogID'],''));
            break;
            
        case 'ProcessEditBlogLayout':
            $title = $_POST['blogTitle'];
            if (strlen($title) < 1)
            {
                $aViewBlogView->SetContent($this->EditBlogLayout($_GET['blogID'],'Blog title cannot be empty.'));
                break;
            }
            $about = $_POST['about'];
            
            //ensure that chosen themeID is actually an available theme
            $themeslist = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemesList();
            foreach ($themeslist as $key=>$value)
            {
                if ($key.'' == $_POST['theme'])
                {
                    $themeid = $_POST['theme'];
                    break;
                }
            }
            if (!isset($themeid))
                throw new Exception("Invalid Theme ID");
            
            $headertog = $_POST['headertog'];
            if ($headertog == "no")
                $headerimg = '';
            elseif ($headertog == "cust")
            {
                $headerimg = $_POST['headerImage'];
                //insert http if not there:
                if (!strstr($headerimg,"://"))
                {
                    $headerimg = 'http://'.$headerimg;
                }
            }
            else
                $headerimg = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemeDefaultHeader($themeid);

            $footertog = $_POST['footertog'];
            if ($footertog == "no")
                $footerimg = '';
            elseif ($footertog == "cust")
            {
                $footerimg = $_POST['footerImage'];
                //insert http if not there:
                if (!strstr($footerimg,"://"))
                {
                    $footerimg = 'http://'.$footerimg;
                }
            }
            else
                $footerimg = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemeDefaultFooter($themeid);
            
            $this->ProcessEditBlogLayout($_GET['blogID'],$title,$about,$themeid,$headerimg,$footerimg);
            
            //forward user to viewing their modified blog:
            $path = $_SERVER['DIRECTORY_ROOT'].'index.php?Action=ViewBlog&blogID='.$_GET['blogID'];
            header("Location: $path");
            exit;
	    
        case 'EditMembership':
            $aViewBlogView->SetContent($this->EditMembership($_GET['blogID']));
            break;
	    
        case 'NewBlog':
            $aViewBlogView->SetContent($this->NewBlog($_GET['blogID'],''));
            break;
            //blogid is passed solely for returning to system when user submits processnewblog form
            //$path = $_SERVER['DIRECTORY_ROOT'].'index.php?Action=ViewBlog&blogID='.$this->NewBlog($_GET['blogID']);
            //header("Location: $path");
            //exit;
	    
        case 'ProcessNewBlog':
            $title = $_POST['title'];
            if (strlen($title) < 1)
            {
                $aViewBlogView->SetContent($this->NewBlog($_GET['blogID'],'Blog title cannot be empty.'));
                break;
            }
            $about = $_POST['about'];
            
            //ensure that chosen themeID is actually an available theme
            $themeslist = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemesList();
            foreach ($themeslist as $key=>$value)
            {
                if ($key.'' == $_POST['theme'])
                {
                    $themeid = $_POST['theme'];
                    break;
                }
            }
            if (!isset($themeid))
                throw new Exception("Invalid Theme ID");
            
            $headertog = $_POST['headertog'];
            if ($headertog == "no")
                $headerimg = '';
            elseif ($headertog == "cust")
            {
                $headerimg = $_POST['headerimg'];
                //insert http if not there:
                if (!strstr($headerimg,"://"))
                {
                    $headerimg = 'http://'.$headerimg;
                }
            }
            else
                $headerimg = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemeDefaultHeader($themeid);

            $footertog = $_POST['footertog'];
            if ($footertog == "no")
                $footerimg = '';
            elseif ($footertog == "cust")
            {
                $footerimg = $_POST['footerimg'];
                //insert http if not there:
                if (!strstr($footerimg,"://"))
                {
                    $footerimg = 'http://'.$footerimg;
                }
            }        
            else
                $footerimg = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemeDefaultFooter($themeid);
            
            $newBlogID = $this->ProcessNewBlog($title,$about,$themeid,$headerimg,$footerimg);
            
            //forward user to viewing their new blog:
            $path = $_SERVER['DIRECTORY_ROOT'].'index.php?Action=ViewBlog&blogID='.$newBlogID;
            header("Location: $path");
            exit;
            
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
	   $aBlogDataAccess = BusinessLogic_Blog_BlogDataAccess::GetInstance();
           $rssurl = $_SERVER['SCRIPT_URI'].'?Action=ViewRSS&blogID='.$blogID;
	   return $aBlogDataAccess->ViewBlog($blogID, $aBlogSecurity->ViewBlog($blogID), $rssurl);
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
        
        return new Presentation_View_ViewSearchView($result, $more);
    }
    
    public function ViewPopular()
    {
        return BusinessLogic_Blog_BlogDataAccess::GetInstance()->ViewPopular();
    }

    public function ViewCalendar($blogID, $year, $month)
    {
        return BusinessLogic_Blog_BlogDataAccess::GetInstance()->ViewCalendar($blogID, $year, $month);
    }

    public function ViewRSS($blogID)
    {
        $rssView = BusinessLogic_Post_Post::GetInstance()->ViewRSS($blogID,10);
        $blogData = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetBlogInfo($blogID);
        $blogurl = $_SERVER['SCRIPT_URI'].'?Action=ViewBlog&blogID='.$blogID;
        $posturlprefix = $_SERVER['SCRIPT_URI'].'?Action=ViewPost&blogID='.$blogID.'&postID=';
        $rssView->AddBlogInfo($blogData[0], $blogData[1], $blogurl, $posturlprefix);
        return $rssView;
    }

    public function EditBlogLayout($blogID,$headertext)
    {
        if(BusinessLogic_Blog_BlogSecurity::GetInstance()->EditBlogLayout($blogID))
        {
            return BusinessLogic_Blog_BlogDataAccess::GetInstance()->EditBlogLayout($blogID,$headertext);
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
    }

    public function ProcessEditBlogLayout($blogID,$title,$about,$themeid,$headerimg,$footerimg)
    {
        if (BusinessLogic_Blog_BlogSecurity::GetInstance()->ProcessEditBlogLayout($blogID))
        {
            BusinessLogic_Blog_BlogDataAccess::GetInstance()->ProcessEditBlogLayout($blogID,$title,$about,$themeid,$headerimg,$footerimg);
        }
        else
        {
            throw new Exception('Authentication failed.');
        }
    }

    public function EditMembership($blogID)
    {
        $aBlogSecurity = BusinessLogic_Blog_BlogSecurity::GetInstance();
        
        $permission = $aBlogSecurity->EditMembership($blogID);
        
        if ($permission == 'Owner' or $permission == 'Editor')
        {
            $aViewManageInvitationsView = new Presentation_View_ViewManageInvitationsView($blogID);
            
            $permission = BusinessLogic_User_User::GetInstance()->GetPermissionForBlog($blogID);
            $aViewManageMembersView = new Presentation_View_ViewManageMembersView($blogID, $permission);

            $aViewEditMembershipView = new Presentation_View_ViewEditMembershipView();

            $aViewEditMembershipView->AddView($aViewManageInvitationsView);
            $aViewEditMembershipView->AddView($aViewManageMembersView);

            return $aViewEditMembershipView;
        }
        else
        {
            throw new Exception('You are not authorized to access this..');
        }
    }

    public function NewBlog($blogID,$headertext)
    {
        //Calls the BlogSecurity class to determine if the user can create a new blog. If so, a NewBlogView is returned. Otherwise, an exception is thrown.
        if (!BusinessLogic_Blog_BlogSecurity::GetInstance()->NewBlog())
        {
            throw new Exception('You are already the owner of another blog, you may not own two blogs at once.');
        }
        //blogid is passed solely for returning to system when user submits processnewblog form
        $themeslist = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemesList();
        return new Presentation_View_NewBlogView($blogID,$themeslist,$headertext);
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
