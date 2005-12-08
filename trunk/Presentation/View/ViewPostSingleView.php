<?php

  //this class represents a post to be deleted
class Presentation_View_ViewPostSingleView extends Presentation_View_View
{
    private $post;
    private $comments;
    private $commentform;
    
    public function __construct($post,$comments)
    {
        if (is_array($post))
        {
            throw new Exception("SinglePostViews only support a single ViewPostView");
        }
        $this->post = $post;
        $this->comments = $comments;
        try {
            $this->commentform = BusinessLogic_Comment_Comment::GetInstance()->NewComment($post->GetBlogID(),$post->GetPostID(),'','','');
        } catch (Exception $e) {
            //do nothing, this user just cant make new comments
        }
    }

    public function Display()
    {
        $ret = $this->post->Display();
        $ret .= '<div id="commentlabel">Comments:</div>';
        $ret .= $this->comments->Display();
        if (isset($this->commentform))
        {
            $ret .= $this->commentform->Display();
        }
        return $ret;
    }

    public function GetPost()
    {
        return $this->post;
    }
}

?>
