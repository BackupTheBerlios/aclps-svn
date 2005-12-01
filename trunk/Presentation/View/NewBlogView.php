<?php

class Presentation_View_NewBlogView extends Presentation_View_View
{
    private $blogID;
    
    public function __construct($blogID)
    {
        //blogid is passed solely for returning to system when user submits processnewblog form
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewBlog">'
            . '<fieldset>'
            . '<legend>&nbsp;Create Your Blog</legend>'

            . '<p><label for="title">Title:</label>'
            . '<input type="text" name="title"></p>'

            //TODO: pulldown menu for themes
            . '<p><label for="theme">Theme:</label>'
            . '<input type="text" name="theme"></p>'

            . '<p><label for="headerimg">Custom Header Image URL (blank=theme default):</label>'
            . '<input type="text" name="headerimg"></p>'

            . '<p><label for="footerimg">Custom Footer Image URL (blank=theme default):</label>'
            . '<input type="text" name="footerimg"></p>'

            . '<p><label for="about">Description (for sidebar):</label>'
            . '<textarea name="about" rows="3" cols="40"></textarea></p>'

            . '<p><input type="submit" class="submit-register" value="Create Blog"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
