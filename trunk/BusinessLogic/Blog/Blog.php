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
				$aboutContent = BusinessLogic_Blog_BlogDataAccess::GetInstance()->EditAbout($blogID);
				return new Presentation_View_EditAboutView($aboutContent,$blogID);
			}
			else
			{
				throw new Exception('Authentication failed.');
			}
    }

    public function ProcessEditAbout()
    {
	//TODO
    }

    public function EditBlogImages()
    {
	//TODO
    }

    public function ProcessEditBlogImages()
    {
	//TODO
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
	//TODO
    }

    public function ProcessNewBlog()
    {
	//TODO
    }
}

?>
