<?php

  //this class represents a collection (array, to be specific) of comments, and can display them
class Presentation_View_ViewCommentCollectionView extends Presentation_View_View
{
    private $comments;
    
    public function __construct($comments)
    {
	if (!is_array($comments))
	{
	    throw new Exception("ViewCommentCollectionView must be passed an array of ViewCommentViews");
	}
	$this->comments = $comments;
    }

    public function Display()
    {
	if (is_array($this->comments))
	{
	    $ret = '<div id="commentcollection">';
	    foreach($this->comments as $value)
	    {
		//If there's anything that should go between posts (newline or something), add it here
		$ret = $ret.'<p id="comment">'.$value->Display()."</p>\n";
	    }
	    $ret = $ret.'<p>TODO: needs newcomment form iff user can make comments</p>';
	    $ret = $ret.'</div>';
	    return $ret;
	}
	elseif (!isset($this->comments))
	{
	    return 'No Comments';
	}
	else
	{
            throw new Exception("Contents of ViewCommentCollectionView must either be an array or unset.");
        }
    }

    public function GetComments()
    {
	return $this->comments;
    }
}

?>
