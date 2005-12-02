<?php

  //this class represents a post to be deleted
class Presentation_View_ViewPostSingleView extends Presentation_View_View
{
    private $post;
    private $comments;
    
    public function __construct($post,$comments)
    {
        if (is_array($post))
        {
            throw new Exception("SinglePostViews only support a single ViewPostView");
        }
        $this->post = $post;
        $this->comments = $comments;
        $this->commentform = BusinessLogic_Comment_Comment::GetInstance()->NewComment($post->GetBlogID(),$post->GetPostID());
    }

    public function Display()
    {
        $commentlabel = '<div id="commentlabel">Comments:</div>';
        return $this->post->Display().$commentlabel.$this->comments->Display().$this->commentform->Display();
    }

    public function GetPost()
    {
        return $this->post;
    }
}

?>
