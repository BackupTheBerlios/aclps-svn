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
            . '<legend>Add A Post</legend>'

            . '<p><input type="text" maxlength="30" name="title">'
            . '<label for="title">Title</label></p>'

            . '<p><input type="checkbox" name="public" checked>'
            . '<label for="public">Public (Anonymous can view)</label></p>'

            . '<p><textarea name="content" rows="5" cols="40"></textarea>'
            . '<label for="content">Content</label></p>'

            . '<p><input type="submit" id="submit" value="Add Post"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
