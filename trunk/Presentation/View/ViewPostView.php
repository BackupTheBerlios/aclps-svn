<?php

class Presentation_View_ViewPostView
{
    private $blogID;
    private $postID;
    private $author;
    private $title;
    private $timestamp;
    private $content;
    
    public function __construct($blogID, $postID, $author, $title, $timestamp,$content)
    {
	$this->blogID = $blogID;
	$this->postID = $postID;
	$this->author = $author;
	$this->title = $title;
	$this->timestamp = $timestamp;
	$this->content = $content;
    }
    
    public function Display()
    {
	die("ViewPostView does not display. Call CompositePostView.Display() instead");
    }
    
    public function setContent($aContent)
    {
	$this->content = $aContent;
    }
    public function getContent()
    {
	return $this->content;
    }

    public function setAuthor($aAuthor)
    {
	$this->author = $aAuthor;
    }
    public function getAuthor()
    {
	return $this->author;
    }

    public function setTitle($aTitle)
    {
	$this->title = $aTitle;
    }
    public function getTitle()
    {
	return $this->title;
    }

    public function setTimestamp($aTimestamp)
    {
	//TODO: perhaps some sort of checking on the new timestamp?
	$this->timestamp = $aTimestamp;
    }
    public function getTimestamp()
    {
	return $this->timestamp;
    }

    //postID cannot be changed
    public function getPostID()
    {
	return $this->postID;
    }

    //blogID cannot be changed
    public function getBlogID()
    {
	return $this->blogID;
    }
}

?>
