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
        $form = '<fieldset><legend><img src="UI/Themes/Images/Controls/deletepost.png" id="controlbarimg" /> Post Deletion</legend>'
            . '<form method="post" action="index.php?blogID='.$this->post->GetBlogID().'&Action=ProcessDeletePost">'
            . '<input type="hidden" name="postID" value="'.$this->post->GetPostID().'">'
            . '<table id="formtable"><tr><td align="center">Do you really want to delete this post?</td></tr>'
            . '<tr><td align="center"><input type="submit" id="submit" value="Yes"></td></tr></table>'
            . '</form></fieldset>';
        return $this->post->Display().$form;
    }
}

?>
