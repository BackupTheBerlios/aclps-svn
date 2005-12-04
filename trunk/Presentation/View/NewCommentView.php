<?php

  //this class represents a new comment
class Presentation_View_NewCommentView extends Presentation_View_View
{
    private $postID;
    private $blogID;
    
    public function __construct($blogID,$postID)
    {
        $this->postID = $postID;
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewComment">'
            . '<fieldset>'
            . '<legend>Add A Comment</legend>'
            . '<input type="hidden" name="postID" value="'.$this->postID.'">'

            . '<table id="formtable"><tr><td><label for="title">Title:</label> '
            . '<input type="text" maxlength="30" size="30" name="title"></td></tr>'

            . '<tr><td><label for="content">Content:</label></td></tr>'
            . '<tr><td><textarea name="content" rows="5" cols="40"></textarea></td></tr>'

            . '<tr><td><input type="submit" id="submit" value="Add Comment"></td></tr></table>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
