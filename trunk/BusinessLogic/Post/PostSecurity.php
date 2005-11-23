<?php

class BusinessLogic_Post_PostSecurity
{
    //Helper class which determines whether a user can use a certain function.

    public function NewPost()
    {
	//Returns true if the user has privilege {Author, Editor, Owner}. Otherwise, false.
	//TODO
    }

    public function ProcessNewPost()
    {
	//Returns true if the user has privilege {Author, Editor, Owner}. Otherwise, false.
	//TODO
    }

    public function EditPost()
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	//TODO
    }

    public function ProcessEditPost()
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	//TODO
    }

    public function DeletePost()
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	//TODO
    }

    public function ProcessDeletePost()
    {
	//Returns true if the user has privilege {Editor, Owner}. Returns true if the user has privilege Author and is the creator of the post. Otherwise, false.
	//TODO
    }

    public function ViewPost()
    {
	//Returns the privilege level of the user.
	//TODO
    }

    public function ViewPostByRecent()
    {
	//Returns the privilege level of the user.
	//TODO
    }

    public function ViewPostByDay()
    {
	//Returns the privilege level of the user.
	//TODO
    }

    public function ViewPostByMonth()
    {
	//Returns the privilege level of the user.
	//TODO
    }
}

?>