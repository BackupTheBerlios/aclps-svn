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

        $arguments = array($this->$TABLE, $blogID);

        $DataAccess = DataAccess_DataAccessFactory::GetInstance();

        $result = $DataAccess->Select($query, $arguments);
        
        if (count($result) < 1)
        {
	    throw new Exception('Request for unknown blog.');
        }
        
    	$row = $result[0];

        $aViewBlogView = new Presentation_View_ViewBlogView($blogID, $contentOptions, $row['HeaderImage'],
                                                            $row['FooterImage'], $row['Theme']);
                                                            
        $aViewBlogView->SetTopBar(BusinessLogic_User_User::GetInstance()->GetTopBar());
		$aViewBlogView->SetSideContent(new Presentation_View_ViewAboutView($row['About']));
                                                            
        //TODO: ADD SIDE CONTENT
        
        return $aViewBlogView;
        
    }

    public function ViewArchive()
    {
	//TODO
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

		$query = 'update [0] set HeaderImage = [1], FooterImage = [2] where BlogID = [3]';
		$arguments = array('Blogs', $headerImage,$footerImage, $blogID);

		$DataAccess = DataAccess_DataAccessFactory::GetInstance(); 
		return $DataAccess->Update($query, $arguments);


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
