<?php

  //this class represents a single comment's data, and can display it.
class Presentation_View_CompositeCommentView extends Presentation_View_View
{
    private $blogID;
    private $postID;
    private $authorID;
    private $authorName;
    private $title;
    private $public;
    private $timestamp;
    private $content;

    public function __construct($blogID, $postID, $commentID, $author, $title, $public, $timestamp, $content)
    {
	$this->blogID = $blogID;
	$this->postID = $postID;
	$this->authorID = $author;
	$this->authorName = BusinessLogic_User_User::ConvertUIDToName($this->authorID);
	$this->title = $title;
	$this->public = $public;
	//TODO: perhaps some sort of checking on the timestamp?
	$this->timestamp = $timestamp;
	$this->content = $content;
    }

    public function Display()
    {
	//TODO: make this pretty
	$displaystr = '<div id="commenttitle">'.$this->title.'</div>'.
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
	    return new Exception("PlogID must always be set.");
	}
	return $this->blogID;
    }
}

?>
