<?php

class Presentation_View_EditPostView extends Presentation_View_View
{
    private $post;
    
    public function __construct($post)
    {
        if (is_array($post))
        {
            throw new Exception("EditPostViews only support a single ViewPostView");
        }
        $this->post = $post;
    }

    public function Display()
    {
        if ($this->post->GetPublic())
        {
            $checkmark = 'checked';
        }
        else
        {
            $checkmark = '';
        }
        $form = '<form method="post" action="index.php?blogID='.$this->post->GetBlogID().'&Action=ProcessEditPost">'
            . '<fieldset>'
            . '<legend>Edit Post</legend>'
            . '<input type="hidden" name="postID" value="'.$this->post->GetPostID().'">'

            . '<p><input type="text" name="title" maxlength="30" value="'.$this->post->GetTitle().'">'
            . '<label for="title">Title</label></p>'

            . '<p><input type="checkbox" name="public" '.$checkmark.'>'
            . '<label for="public">Public (Anonymous can view)</label></p>'

            . '<p><input type="radio" name="timestamp" value="now"> Change To Now</p>'
            . '<p><input type="radio" name="timestamp" value="orig" checked> Leave Original ('.$this->post->GetTimestamp().')</p>'
            . '<p><label for="timestamp">Timestamp</label></p>'

            . '<p><textarea name="content" rows="5" cols="40">'.$this->post->GetContent().'</textarea>'
            . '<label for="content">Content</label></p>'

            . '<p><input type="submit" id="submit" value="Edit Post"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
