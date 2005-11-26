<?php

  //this class represents a post to be edited
class Presentation_View_EditPostView extends Presentation_View_View
{
    private $post;
    
    public function __construct($post)
    {
	if (is_array($post))
	{
	    throw new Exception("EditPostViews only support a single ViewPostView");
	}
	$this->post = $post;
    }

    public function Display()
    {
	return 'This is an edit post view! :D<br />'.$this->post->DisplayAsForm();
    }
}

?>
