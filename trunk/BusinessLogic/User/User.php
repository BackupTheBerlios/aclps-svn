<?php

class BusinessLogic_User_User
{
    private function __construct()
    {
	//Do Nothing
    }

    static public function GetInstance()
    {
	if (isset($_SESSION['BusinessLogic_User_User']))
	{
	    return $_SESSION['BusinessLogic_User_User'];
	}
	else
	{
	    $_SESSION['BusinessLogic_User_User'] = new BusinessLogic_User_User();
	    return $_SESSION['BusinessLogic_User_User'];
	}
    }

    public function HandleRequest()
    {
	//Checks $_GET['action'] to see if the action belongs to the Post class. If so, the appropriate function is called. Otherwise, dies, as there is no further link in the CHAIN OF RESPONSIBILITY
	$request = $_GET['Action'];
	switch($request)
	{
	    //TODO: add actions here, if any
	default:
	    die('Unknown Request.');
	}
    }

    public function EditUserData()
    {
	//Returns an EditUserDataView.
	//TODO
    }

    public function ProcessEditUserData()
    {
	//Processes the form data in EditUserDataView and modifies the Users table.
	//TODO
    }

    public function ViewSignIn()
    {
	//Returns a ViewSignInView if the user is not signed in.
	//TODO
    }

    public function ProcessSignIn()
    {
	//Processes the form data in ViewSignInView and authenticates the user. Signs In the user if the credentials are valid.
	//TODO
    }

    private function GetPasshash($string)
    {
	//Encrypts a password into a hash, returns the hash.
	//The database stores these hashes, so compare what this returns against that when verifying a user.
	return crypt($string,$string); //Use string itself as the salt
    }

    public function ProcessSignOut()
    {
	//Sets $_SESSION = array(). Confirms sign out by returning ViewSignOutView
	//TODO
    }

    public function ViewRegister()
    {
	//Returns a ViewRegisterView
	//TODO
    }

    public function ProcessRegister()
    {
	//Processes the form data in EditUserDataView and modifies the Users table.
	//TODO
    }

    public static function ConvertUIDToName($userID)
    {
	//Given a userID, returns the user's name
	//TODO
	return 'username';
    }

    public function UserPermission($blogID)
    {
	//Returns the permission level for this user ("owner"=80, "editor"=40, "author"=20 or "nobody"=0)
	//TODO
	return 'nobody';
    }
}

?>
