<?php

  //this class represents a collection of posts, and can display them
class Presentation_View_ViewPostView extends Presentation_View_View
{
    private $posts;
    
    public function __construct($posts)
    {
	$this->posts = $posts;
    }

    public function Display()
    {
	foreach($this->posts as $key=>$value)
	{
	    print $value->Display();
	    //TODO: If there's anything that should go between posts (newline or something), add it here
	    print '<br />';
	}
    }

    public function AddView($post)
    {
	$this->posts[] = $post;
    }

    public function DeleteView($post)
    {
	foreach($this->posts as $key=>$value)
	{
	    if ($value->GetPostID() == $post->GetPostID() and
		$value->GetBlogID() == $post->GetBlogID())
	    {
		unset($this->posts[$key]);
		break;
	    }
	}
    }
}

?>
