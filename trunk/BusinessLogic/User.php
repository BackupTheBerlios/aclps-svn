<?php

class BusinessLogic_User
{
    private $status;
    private $auth_table;
	
    public function HandleRequest()
    {
	//Checks $_GET['action'] to see if the action belongs to the User class. If so, the appropriate function is called. Otherwise, an exception is thrown.
	//TODO
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

    public function UserPermission()
	//Returns the permission level for this user ("owner", "editor", "author" or "nobody")
}

?>
