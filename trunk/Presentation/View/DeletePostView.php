<?php

  //this class represents a post to be deleted
class Presentation_View_DeletePostView extends Presentation_View_View
{
    private $post;
    
    public function __construct($post)
    {
        if (is_array($post))
        {
            throw new Exception("DeletePostViews only support a single ViewPostView");
        }
        $this->post = $post;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?blogID='.$this->post->GetBlogID().'&Action=ProcessDeletePost">'
            . '<fieldset>'
            . '<legend>Post Deletion</legend>'
            . '<input type="hidden" name="postID" value="'.$this->post->GetPostID().'">'
            . '<p>Do you really want to delete this post?</p>'
            . '<p><input type="submit" class="submit-register" value="Yes"></p>'
            . '</fieldset>'
            . '</form>';
        return $this->post->Display().$form;
    }
}

?>
