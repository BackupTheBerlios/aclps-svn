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

            . '<table id="formtable"><tr><td colspan="2"><label for="title">Title:</label> '
            . '<input type="text" maxlength="30" name="title"></td></tr>'

            . '<tr><td><input type="checkbox" name="public" checked></td>'
            . '<td><label for="public">Public (Anonymous can view)</label></td></tr>'

            . '<tr><td colspan="2"><label for="content">Content:</label></td></tr>'
            . '<tr><td colspan="2"><textarea name="content" rows="5" cols="40">'.$this->post->GetContent().'</textarea></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Add Post"></td></tr></table>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
