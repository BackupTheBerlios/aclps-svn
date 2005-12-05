<?php

  //this class represents a single post's data, and can display it.
class Presentation_View_ViewPostView extends Presentation_View_View
{
    private $blogID;
    private $postID;
    private $authorID;
    private $authorName;

    private $public;
    private $showcontrols;

    private $title;
    private $content;
    private $timestamp;
    private $commentlink;

    public function __construct($blogID, $postID, $authorID, $title, $public, $timestamp, $content)
    {
        $this->blogID = $blogID;
        $this->postID = $postID;
        $this->authorID = $authorID;
        $this->authorName = BusinessLogic_User_User::ConvertUserIDToName($authorID);
        $this->title = $title;
        $this->public = $public;
        $this->timestamp = $timestamp;
        $this->content = $content;
        $this->showcontrols = false;
    }

    public function ActivateControls($bool)
    {
        $this->showcontrols = $bool;
    }

    public function SetCommentCount($commentcount)
    {
        //Set by the PostCollectionView in its contructor on all posts.
        $url = 'index.php?Action=ViewPost&blogID='.$this->blogID.'&postID='.$this->postID;
        $this->commentlink = '<a href="'.$url.'">Comments ('.$commentcount.')</a>';
    }

    public function Display()
    {
        if ($this->showcontrols)
        {
            $editurl = 'index.php?Action=EditPost&blogID='.$this->blogID.'&postID='.$this->postID;
            $newurl = 'index.php?Action=DeletePost&blogID='.$this->blogID.'&postID='.$this->postID;
            $controls = '<div id="postcontrols">'.
                '<img src="UI/Themes/Images/Controls/editpost.png" id="controlbarimg" /> '.
                '<a href="'.$editurl.'">Edit Post</a> | '.
                '<img src="UI/Themes/Images/Controls/deletepost.png" id="controlbarimg" /> '.
                '<a href="'.$newurl.'">Delete Post</a></div>';
        }
        else
        {
            $controls = '';
        }

        $displaystr = '<div id="post">'.
            $controls.
            '<div id="posttitle">'.$this->title.'</div>'.
            '<div id="postauthor">'.$this->authorName.'</div>'.
            '<div id="posttime">'.$this->timestamp.'</div>'.
            '<div id="postcontent">'.$this->content.'</div>'.
            '<div id="postcommentlink">'.$this->commentlink.'</div>'.
            '</div>';
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

    public function GetPublic()
    {
        return $this->public;
    }

    public function GetTimestamp()
    {
        return $this->timestamp;
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
