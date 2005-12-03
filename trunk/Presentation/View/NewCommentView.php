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

            . '<p><input type="text" maxlength="30" name="title">'
            . '<label for="title">Title</label></p>'

            . '<p><textarea name="content" rows="5" cols="40"></textarea>'
            . '<label for="content">Content</label></p>'

            . '<p><input type="submit" id="submit" value="Add Comment"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
