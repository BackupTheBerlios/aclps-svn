<?php

class BusinessLogic_Blog_BlogSecurity
{
    private function __construct()
    {
      //Do Nothing
    }

    static public function GetInstance()
    {
      if (isset($_SESSION['BusinessLogic_Blog_BlogSecurity']))
      {
        return $_SESSION['BusinessLogic_Blog_BlogSecurity'];
      }
      else
      {
        $_SESSION['BusinessLogic_Blog_BlogSecurity'] = new BusinessLogic_Blog_BlogSecurity();
        return $_SESSION['BusinessLogic_Blog_BlogSecurity'];
      }

    }
    
	public function ViewBlog($blogID)
	{
		//SHOULD BE
		/*
        return BusinessLogic_User::GetInstance()->UserPermission($blogID);
		*/
		return 'nobody';
	}

	public function ViewArchive()
	{
		//TODO
	}

	public function EditAbout()
	{
		//TODO
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
