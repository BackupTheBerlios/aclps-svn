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

    private $linkprefix;

    public function __construct($blogID, $postID, $commentID, $authorID, $title, $timestamp, $content)
    {
        $this->blogID = $blogID;
        $this->postID = $postID;
        $this->commentID = $commentID;
        $this->authorID = $authorID;
        $this->authorName = BusinessLogic_User_User::ConvertUserIDToName($authorID);
        $this->title = $title;
        $this->timestamp = $timestamp;
        $this->content = $content;

        $this->controls = '';
        $this->linkprefix = explode('?',$_SERVER['REQUEST_URI'], 2);
        $this->linkprefix = $this->linkprefix[0];
    }

    public function SetControls($bool)
    {
        if ($bool)
        {
            $editurl = $linkprefix.'?Action=EditComment&blogID='.$this->blogID.'&commentID='.$this->commentID;
            $deleteurl = $linkprefix.'?Action=DeleteComment&blogID='.$this->blogID.'&commentID='.$this->commentID;
            $this->controls = '<div id="commentcontrols"><a href="'.$editurl.'">Edit Comment</a> <a href="'.$deleteurl.'">Delete Comment</a></div>';
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

    public function GetContent()
    {
        return $this->content;
    }

    public function GetAuthorID()
    {
        return $this->authorID;
    }

    public function GetAuthorName()
    {
        return $this->authorName;
    }

    public function GetTitle()
    {
        return $this->title;
    }

    public function GetTimestamp()
    {
        return $this->timestamp;
    }

    public function GetCommentID()
    {
        return $this->commentID;
    }

    public function GetPostID()
    {
        return $this->postID;
    }

    public function GetBlogID()
    {
        return $this->blogID;
    }
}

?>
