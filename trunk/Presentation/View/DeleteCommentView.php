<?php

  //this class represents a comment to be deleted
class Presentation_View_DeleteCommentView extends Presentation_View_View
{
    private $comment;
    
    public function __construct($comment)
    {
        if (is_array($comment))
        {
            throw new Exception("DeleteCommentViews only support a single ViewCommentView");
        }
        $this->comment = $comment;
    }

    public function Display()
    {
        //note: postid is needed to forward user back to post after delete:
        $form = '<form method="post" action="index.php?blogID='.$this->comment->GetBlogID().'&Action=ProcessDeleteComment">'
            . '<fieldset>'
            . '<legend>&nbsp;Comment Deletion</legend>'
            . '<input type="hidden" name="postID" value="'.$this->comment->GetPostID().'">'
            . '<input type="hidden" name="commentID" value="'.$this->comment->GetCommentID().'">'
            . '<p><center>Do you really want to delete this comment?</center></p>'
            . '<p><input type="submit" class="submit-register" value="Yes"></p>'
            . '</fieldset>'
            . '</form>';
        return $this->comment->Display().$form;
    }
}

?>
