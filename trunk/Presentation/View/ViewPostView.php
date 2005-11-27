<?php

  //this class represents a single post's data, and can display it.
class Presentation_View_ViewPostView extends Presentation_View_View
{
    private $blogID;
    private $postID;
    private $authorID;
    private $public;

    private $title;
    private $authorName;
    private $timestamp;
    private $controls;
    private $commentlink;
    private $content;

    private $linkprefix;

    public function __construct($blogID, $postID, $authorID, $title, $public, $timestamp, $content)
    {

	$this->blogID = $blogID;
	$this->postID = $postID;
	$this->authorID = $authorID;
	$this->authorName = "arr";//BusinessLogic_User_User::ConvertUIDToName($authorID); //TODO: make this function
	$this->title = $title;
	$this->public = $public;
	//TODO: perhaps some sort of checking on the timestamp?
	$this->timestamp = $timestamp;
	$this->content = $content;

	$this->controls = '';
	$this->commentlink = '';
	$this->linkprefix = explode('?',$_SERVER['REQUEST_URI'], 2);
	$this->linkprefix = $this->linkprefix[0];
    }

    public function SetControls($bool)
    {
	//Set by PostSecurity shortly after post creation, before it's returned to viewer
	if ($bool)
	{
	    $editurl = $linkprefix.'?Action=EditPost&blogID='.$this->blogID.'&postID='.$this->postID;
	    $newurl = $linkprefix.'?Action=DeletePost&blogID='.$this->blogID.'&postID='.$this->postID;
	    $this->controls = '<div id="postcontrols"><a href="'.$editurl.'">Edit Post</a> <a href="'.$newurl.'">Delete Post</a></div>';
	}
	else
	{
	    $this->controls = '';
	}
    }

    public function SetCommentCount($commentcount)
    {
	//Set by the PostCollectionView in its contructor on all posts.
	$url = $linkprefix.'?Action=ViewPost&blogID='.$this->blogID.'&postID='.$this->postID;
	$this->commentlink = '<a href="'.$url.'">Comments ('.$commentcount.')</a>';
    }

    public function Display()
    {
	$displaystr = $this->controls.
	    '<div id="posttitle">'.$this->title.'</div>'.
	    '<div id="postauthor">'.$this->authorName.'</div>'.
	    '<div id="posttime">'.$this->timestamp.'</div>'.
	    '<div id="postcontent">'.$this->content.'</div>'.
	    '<div id="postcommentlink">'.$this->commentlink.'</div>';
	return $displaystr;
    }

    public function DisplayAsForm()
    {
	//TODO: make this a pretty form
	return "THIS SHOULD BE A PRETTY FORM!<br />Title ".$this->title."<br />Content: ".$this->content;
    }

    public function DisplayWithComments()
    {
	//TODO: same as normal display, but also showing comments rather than the commentlink
    }

    public function SetContent($aContent)
    {
	$this->content = $aContent;
    }
    public function GetContent()
    {
	if (isset($this->content))
	{
	    return $this->content;
	}
	return "&nbsp;";
    }

    public function GetAuthorID()
    {
	if (isset($this->authorID))
	{
	    return $this->authorID;
	}
	return "&nbsp;";
    }
    public function GetAuthorName()
    {
	if (isset($this->authorName))
	{
	    return $this->authorName;
	}
	return "&nbsp;";
    }

    public function SetTitle($aTitle)
    {
	$this->title = $aTitle;
    }
    public function GetTitle()
    {
	if (isset($this->title))
	{
	    return $this->title;
	}
	return "&nbsp;";
    }

    public function SetPublic($public)
    {
	if (!is_bool($public))
	{
	    throw new Exception("Public/private status must be a boolean value.");
	}
	$this->public = $public;
    }
    public function GetPublic()
    {
	if (!isset($this->public))
	{
	    throw new Exception("Public/private status must always be set.");
	}
	return $this->public;
    }

    public function SetTimestamp($aTimestamp)
    {
	//TODO: perhaps some sort of checking on the new timestamp?
	$this->timestamp = $aTimestamp;
    }
    public function GetTimestamp()
    {
	if (isset($this->timestamp))
	{
	    return $this->timestamp;
	}
	return "&nbsp;";
    }

    //postID cannot be changed, is always set in constructor
    public function GetPostID()
    {
	if (!isset($this->postID))
	{
	    return new Exception("PostID must always be set.");
	}
	return $this->postID;
    }

    //blogID cannot be changed, is always set in constructor
    public function GetBlogID()
    {
	if (!isset($this->blogID))
	{
	    return new Exception("BlogID must always be set.");
	}
	return $this->blogID;
    }
}

?>
