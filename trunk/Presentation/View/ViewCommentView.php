<?php

  //this class represents a single comment's data, and can display it.
class Presentation_View_ViewCommentView extends Presentation_View_View
{
    private $blogID;
    private $postID;
    private $commentID;
    private $authorID;
    private $authorName;
    private $title;
    private $timestamp;
    private $controls;
    private $content;

    public function __construct($blogID, $postID, $commentID, $authorID, $title, $timestamp, $content)
    {
	$this->blogID = $blogID;
	$this->postID = $postID;
	$this->commentID = $commentID;
	$this->authorID = $authorID;
	$this->authorName = BusinessLogic_User_User::ConvertUIDToName($this->authorID);
	$this->title = $title;
	//TODO: perhaps some sort of checking on the timestamp?
	$this->timestamp = $timestamp;
	$this->content = $content;

	$this->controls = '';
    }

    public function SetControls($bool)
    {
	if ($bool)
	{
	    $url = '';//TODO
	    $this->controls = '<div id="commentcontrols"><a href="'.$url.'">Edit Comment</a> <a href="'.$url.'">Delete Comment</a></div>';
	}
	else
	{
	    $this->controls = '';
	}
    }

    public function Display()
    {
	$displaystr = $this->controls.
	    '<div id="commenttitle">'.$this->title.'</div>'.
	    '<div id="commentauthor">'.$this->authorName.'</div>'.
	    '<div id="commenttime">'.$this->timestamp.'</div>'.
	    '<div id="commentcontent">'.$this->content.'</div>';
	return $displaystr;
    }

    public function DisplayAsForm()
    {
	//TODO: make this a pretty form
	return "THIS SHOULD BE A PRETTY FORM!<br />Title ".$this->title."<br />Content: ".$this->content;
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

    public function SetAuthorID($aAuthor)
    {
	$this->authorID = $aAuthor;
	$this->authorName = BusinessLogic_User_User::ConvertUIDToName($aAuthor);
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

    //commentID cannot be changed, is always set in constructor
    public function GetCommentID()
    {
	if (!isset($this->commentID))
	{
	    return new Exception("CommentID must always be set.");
	}
	return $this->commentID;
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
