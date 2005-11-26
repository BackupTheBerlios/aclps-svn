<?php

  //this class represents a collection (array, to be specific) of posts, and can display them
class Presentation_View_ViewPostCollectionView extends Presentation_View_View
{
    private $posts;
    
    public function __construct($posts)
    {
	if (!is_array($posts))
	{
	    throw new Exception("ViewPostCollectionView must be passed an array of ViewPostViews");
	}
	$this->posts = $posts;
    }

    public function Display()
    {
	if (is_array($this->posts))
	{
	    $ret = "";
	    foreach($this->posts as $key=>$value)
	    {
		//TODO: If there's anything that should go between posts (newline or something), add it here
		$ret = $ret.'<p id="post">'.$value->Display()."</p>\n";
	    }
	    return $ret;
	}
	elseif (!isset($this->posts))
	{
	    return 'No Posts';
	}
	else
	{
            throw new Exception("Contents of ViewPostCollectionView must either be an array or unset.");
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

    public function RemovePrivatePosts()
    {
	foreach($this->posts as $key=>$value)
	{
	    if (!$value->GetPublic())
	    {
		unset($this->posts[$key]);
		break;
	    }
	}
    }
}

?>
