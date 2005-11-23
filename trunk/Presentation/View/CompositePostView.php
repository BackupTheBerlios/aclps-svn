<?php

class Presentation_View_CompositePostView extends Presentation_View_View
{
    private $Posts;
    
    public function __construct($posts)
    {
	$this->Posts[] = $posts;
    }
    
    public function Display()
    {
	foreach($this->Posts as $key=>$value)
	{
	    $value->$Display();
	}
    }

    public function AddPost($post)
    {
	$this->Posts[] = $post;
    }

    public function DeletePost($post)
    {
	foreach($this->Views as $key=>$value)
	{
	    if ($value == $post)
	    {
		unset($this->Posts[$key]);
		break;
	    }
	}
    }
}
?>
