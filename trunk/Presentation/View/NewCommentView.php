<?php

  //this class represents a new comment
class Presentation_View_NewCommentView extends Presentation_View_View
{
    private $comment;
    
    public function __construct($comment)
    {
	if (is_array($comment))
	{
	    throw new Exception("NewCommentViews only support a single ViewCommentView");
	}
	$this->comment = $comment;
    }

    public function Display()
    {
	return 'This is a new comment view! :D<br />'.$this->comment->DisplayAsForm();
    }
}

?>
