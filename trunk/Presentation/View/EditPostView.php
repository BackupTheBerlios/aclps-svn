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

            . '<table id="formtable"><tr><td colspan="2"><label for="title">Title:</label>'
            . '<input type="text" name="title" maxlength="30" value="'.$this->post->GetTitle().'"></td></tr>'

            . '<tr><td><input type="checkbox" name="public" '.$checkmark.'></td>'
            . '<td><label for="public">Public (Anonymous can view)</label></td></tr>'

            . '<tr><td colspan="2"><label for="timestamp">Timestamp:</label></td></tr>'
            . '<tr><td><input type="radio" name="timestamp" value="now"></td><td>Change To Now</td></tr>'
            . '<tr><td><input type="radio" name="timestamp" value="orig" checked></td><td>Leave Original ('.$this->post->GetTimestamp().')</td></tr></table>'
w
            . '<tr><td colspan="2"><label for="content">Content:</label></td></tr>'
            . '<tr><td colspan="2"><textarea name="content" rows="5" cols="40">'.$this->post->GetContent().'</textarea></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Edit Post"></td></tr></table>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
