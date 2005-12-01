<?php

  //this class represents a new comment
class Presentation_View_NewCommentView extends Presentation_View_View
{
    private $postID;
    private $blogID
    
    public function __construct($postID,$blogID)
    {
        $this->postID = $postID;
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewComment">'
            . '<fieldset>'
            . '<legend>&nbsp;Create New Comment</legend>'
            . '<input type="hidden" name="postID" value="'.$this->postID.'">'

            . '<p><label for="title">Title:</label>'
            . '<input type="text" name="title"></p>'

            . '<p><label for="content">Content:</label>'
            . '<textarea name="content" rows="5" cols="40"></textarea></p>'

            . '<p><input type="submit" class="submit-register" value="Add Post"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
