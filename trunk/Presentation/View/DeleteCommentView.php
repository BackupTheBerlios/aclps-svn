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
        //TODO: make display
        return 'This is a delete comment view! :D<br />'.$this->comment->Display();
    }
}

?>
