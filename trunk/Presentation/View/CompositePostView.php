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
	//TODO: This should be HTMLized
	foreach($this->Posts as $key=>$value)
	{
	    $value->$Display();
	}
    }

    public function AddView($view)
    {
	$this->Posts[] = $post;
    }

    public function DeleteView($view)
    {
	foreach($this->Views as $key=>$value)
	{
	    if ($value->getPostID() == $view->getPostID())
	    {
		unset($this->Posts[$key]);
		break;
	    }
	}
    }
}
?>
