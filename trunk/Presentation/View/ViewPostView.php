<?php

class Presentation_View_ViewPostView extends Presentation_View_View
{
    private $blogID;
    private $postID;
    private $author;
    private $title;
    private $timestamp;
    private $content;
    
    public function __construct($blogID, $postID, $author, $title, $timestamp)
    {
	$this->blogID = $blogID;
	$this->postID = $postID;
	$this->author = $author;
	$this->title = $title;
	$this->timestamp = $timestamp;

	$this->content = '';
    }
    
    public function Display()
    {
	die("ViewPostView does not display.");
    }
    
    public function SetContent($aContent)
    {
	$this->content = $aContent;
    }
    
    public function DisplayContent()
    {
	if (is_object($this->content))
	{
	    return $this->content->Display();
	}
	elseif (isset($this->content))
	{
	    return $this->content;
	}
	else
	{
	    return '&nbsp;';
	}
    }

    public function DisplayAuthor()
    {
	if (isset($this->author))
	{
	    return $this->author;
	}
	else
	{
	    return '&nbsp;';
	}
    }

    public function DisplayTitle()
    {
	if (isset($this->title))
	{
	    return $this->title;
	}
	else
	{
	    return '&nbsp;';
	}
    }

    public function DisplayTimestamp()
    {
	if (isset($this->timestamp))
	{
	    return $this->timestamp;
	}
	else
	{
	    return '&nbsp;';
	}
    }

}

?>
