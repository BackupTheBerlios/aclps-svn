<?php

class Presentation_View_NewPostView extends Presentation_View_View
{
    private $blogID;
    
    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewPost">'
            . '<fieldset>'
            . '<legend>&nbsp;Create New Post</legend>'

            . '<p><label for="title">Title:</label>'
            . '<input type="text" name="title"></p>'

            . '<p><label for="public">Public (Anonymous can view):</label>'
            . '<input type="checkbox" name="public" checked></p>'

            . '<p><label for="content">Content:</label>'
            . '<textarea name="content" rows="5" cols="40"></textarea></p>'

            . '<p><input type="submit" class="submit-register" value="Add Post"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
