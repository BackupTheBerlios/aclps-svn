<?php

  //this class represents a collection (array, to be specific) of comments, and can display them
class Presentation_View_ViewCommentView extends Presentation_View_View
{
    private $comments;
    
    public function __construct($comments)
    {
	if (!is_array($comments))
	{
	    throw new Exception("ViewCommentView must be passed an array of CompositeCommentViews");
	}
	$this->comments = $comments;
    }

    public function Display()
    {
	if (is_array($this->comments))
	{
	    $ret = "";
	    foreach($this->comments as $key=>$value)
	    {
		//TODO: If there's anything that should go between comments (newline or something), add it here
		$ret = $ret.'<p id="comment">'.$value->Display()."</p>\n";
	    }
	    return $ret;
	}
	elseif (!isset($this->comments))
	{
	    return 'No Comments';
	}
	else
	{
            throw new Exception("Contents of ViewCommentView must either be an array or unset.");
        }
    }

    public function AddView($comment)
    {
	$this->comments[] = $comment;
    }

    public function DeleteView($comment)
    {
	foreach($this->comments as $key=>$value)
	{
	    if ($value->GetCommentID() == $comment->GetCommentID() and
		$value->GetPostID() == $comment->GetPostID() and
		$value->GetBlogID() == $comment->GetBlogID())
	    {
		unset($this->comments[$key]);
		break;
	    }
	}
    }
}

?>
