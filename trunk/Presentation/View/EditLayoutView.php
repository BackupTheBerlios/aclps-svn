<?php

class Presentation_View_EditLayoutView extends Presentation_View_View
{
    private $blogID;
    private $themes;

    public function __construct($blogID, $themes)
    {
        $this->blogID = $blogID;
        $this->blogID = $themes;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?&Action=ProcessEditLayout&blogID=' . $this->blogID . '>'
            . '<fieldset>'
            . '<legend>&nbsp;Edit Your Blog</legend>'

            . '<p><label for="theme">Theme:</label>'
            . '<select name="theme">';
        foreach ($themes as $key=>$value)
        {
            $form = $form . '<option value="'.$value['ThemeID'].'">'.$value['Title'].'</option>';
        }
        
        $form = $form.'</select></p>'
            . '<p><label for="headerimg">Custom Header Image URL (blank=theme default):</label>'
            . '<input type="text" name="headerimg"></p>'

            . '<p><label for="footerimg">Custom Footer Image URL (blank=theme default):</label>'
            . '<input type="text" name="footerimg"></p>'

            . '<p><label for="about">About Text (for sidebar):</label>'
            . '<textarea name="about" rows="3" cols="40"></textarea></p>'

            . '<p><input type="submit" class="submit-register" value="Submit Changes"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
