<?php

  //this class represents a comment to be edited
class Presentation_View_EditCommentView extends Presentation_View_View
{
    private $comment;
    
    public function __construct($comment)
    {
        if (is_array($comment))
        {
            throw new Exception("EditCommentViews only support a single ViewCommentView");
        }
        $this->comment = $comment;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?blogID='.$this->comment->GetBlogID().'&Action=ProcessEditComment">'
            . '<fieldset>'
            . '<legend>Edit Comment</legend>'
            . '<input type="hidden" name="postID" value="'.$this->comment->GetPostID().'">'
            . '<input type="hidden" name="commentID" value="'.$this->comment->GetCommentID().'">'

            . '<table id="formtable"><tr><td><label for="title">Title:</label> '
            . '<input type="text" name="title" maxlength="30" size="30" value="'.$this->comment->GetTitle().'"></td></tr>'

            . '<tr><td><label for="content">Content:</label></td></tr>'
            . '<tr><td><textarea name="content" rows="5" cols="40">'.$this->comment->GetContent().'</textarea></td></tr>'

            . '<tr><td align="center"><input type="submit" id="submit" value="Edit Comment"></td></tr></table>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
