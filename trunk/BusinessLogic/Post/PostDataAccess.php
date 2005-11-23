<?php

class BusinessLogic_Post_PostSecurity
{
    //Helper class which interacts with the Data Access layer. This class is also responsible for converting data into a View structure.

    private function __construct()
    {
	//Do Nothing
    }

    static public function GetInstance()
    {
	if (!isset($_SESSION['BusinessLogic_Post_PostDataAccess']))
	{
	    $_SESSION['BusinessLogic_Post_PostDataAccess'] = new BusinessLogic_Post_PostDataAccess();
	}
	return $_SESSION['BusinessLogic_Post_PostDataAccess'];
    }

    public function ProcessNewPost()
    {
	//Inserts data into the Posts table.
	//TODO
    }

    public function EditPost()
    {
	//Returns an EditPostView with data from the Posts table.
	//TODO
    }

    public function ProcessEditPost()
    {
	//Updates the Posts table with the new data.
	//TODO
    }

    public function DeletePost()
    {
	//Returns a DeletePostView with data from the Posts table.
	//TODO
    }

    public function ProcessDeletePost()
    {
	//Updates the Posts table with the new data.
	//TODO
    }

    public function ViewPost()
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	//TODO
    }

    public function ViewPostByRecent()
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	//TODO
    }

    public function ViewPostByDay()
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	//TODO
    }

    public function ViewPostByMonth()
    {
	//Returns a ViewPostCollectionView with data from the Posts table.
	//TODO
    }
}

?>
