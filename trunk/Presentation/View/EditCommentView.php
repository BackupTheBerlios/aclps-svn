<?php

  //this class represents a comment to be edited
class Presentation_View_EditCommentView extends Presentation_View_View
{
    private $comment;
    
    public function __construct($comment)
    {
	if (is_array($comment))
	{
	    throw new Exception("EditCommentViews only support a single CompositeCommentView");
	}
	$this->comment = $comment;
    }

    public function Display()
    {
	return 'This is an edit comment view! :D<br />'.$this->comment->DisplayAsForm();
    }
}

?>
