<?php

  //this class represents a single post's data, and can display it.
class Presentation_View_CompositePostView extends Presentation_View_View
{
    private $blogID;
    private $postID;
    private $author;
    private $title;
    private $timestamp;
    private $content;
    
    public function __construct($blogID, $postID, $author, $title, $timestamp, $content)
    {
	$this->blogID = $blogID;
	$this->postID = $postID;
	$this->author = $author;
	$this->title = $title;
	//TODO: perhaps some sort of checking on the timestamp?
	$this->timestamp = $timestamp;
	$this->content = $content;
    }

    public function Display()
    {
	//TODO: make this pretty
	return "Title: ".$this->blogID."<br />Content: ".$this->content;
    }

    public function SetContent($aContent)
    {
	$this->content = $aContent;
    }
    public function GetContent()
    {
	return $this->content;
    }

    public function SetAuthor($aAuthor)
    {
	$this->author = $aAuthor;
    }
    public function GetAuthor()
    {
	return $this->author;
    }

    public function SetTitle($aTitle)
    {
	$this->title = $aTitle;
    }
    public function GetTitle()
    {
	return $this->title;
    }

    public function SetTimestamp($aTimestamp)
    {
	//TODO: perhaps some sort of checking on the new timestamp?
	$this->timestamp = $aTimestamp;
    }
    public function GetTimestamp()
    {
	return $this->timestamp;
    }

    //postID cannot be changed
    public function GetPostID()
    {
	return $this->postID;
    }

    //blogID cannot be changed
    public function GetBlogID()
    {
	return $this->blogID;
    }
}

?>
