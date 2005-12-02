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
            . '<legend>&nbsp;Edit Post</legend>'
            . '<input type="hidden" name="postID" value="'.$this->post->GetPostID().'">'

            . '<p><label for="title">Title:</label>'
            . '<input type="text" name="title" maxlength="30" value="'.$this->post->GetTitle().'"></p>'

            . '<p><label>Author:</label>'
            . $this->post->GetAuthorName().'</p>'

            . '<p><label for="public">Public (Anonymous can view):</label>'
            . '<input type="checkbox" name="public" '.$checkmark.'></p>'

            . '<p><label for="timestamp">Timestamp:</label>'
            . 'Change To Now: <input type="radio" name="timestamp" value="now"><br />'
            . 'Leave Original ('.$this->post->GetTimestamp().'): <input type="radio" name="timestamp" value="orig" checked></p>'

            . '<p><label for="content">Content:</label>'
            . '<textarea name="content" rows="5" cols="40">'.$this->post->GetContent().'</textarea></p>'

            . '<p><input type="submit" class="submit-register" value="Edit Post"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
