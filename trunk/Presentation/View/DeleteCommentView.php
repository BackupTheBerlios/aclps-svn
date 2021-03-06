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
        $form = '<fieldset><legend><img src="UI/Themes/Images/Controls/deletecomment.png" id="controlbarimg" /> Comment Deletion</legend>'
            . '<form method="post" action="index.php?blogID='.$this->comment->GetBlogID().'&Action=ProcessDeleteComment">'
            . '<input type="hidden" name="postID" value="'.$this->comment->GetPostID().'">'
            . '<input type="hidden" name="commentID" value="'.$this->comment->GetCommentID().'">'
            . '<table id="formtable"><tr><td align="center">Do you really want to delete this comment?</td></tr>'
            . '<tr><td align="center"><input type="submit" id="submit" value="Yes"></td></tr></table>'
            . '</form></fieldset>';
        return $this->comment->Display().$form;
    }
}

?>
