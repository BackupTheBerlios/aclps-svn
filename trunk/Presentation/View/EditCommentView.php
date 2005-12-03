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

            . '<p><input type="text" name="title" maxlength="30" value="'.$this->comment->GetTitle().'">'
            . '<label for="title">Title</label></p>'

            . '<p><textarea name="content" rows="5" cols="40">'.$this->comment->GetContent().'</textarea>'
            . '<label for="content">Content</label></p>'

            . '<p><input type="submit" id="submit" value="Edit Comment"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
