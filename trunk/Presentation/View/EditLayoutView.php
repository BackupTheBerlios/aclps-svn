<?php

class Presentation_View_EditBlogLayoutView extends Presentation_View_View
{
    private $blogID;
    private $themes;
    private $title;
    private $about;
    private $themeID;
    private $headerImage;
    private $footerImage;

    public function __construct($blogID, $themes, $title, $about, $themeID, $headerImage, $footerImage)
    {
        $this->blogID = $blogID;
        $this->blogID = $themes;
        $this->title = $title;
        $this->about = $about;
        $this->themeID = $themeID;
        $this->headerImage = $headerImage;
        $this->footerImage = $headerImage;
    }

    public function Display()
    {
        $form = '<form method="post" action="index.php?&Action=ProcessEditLayout&blogID=' . $this->blogID . '>'
            . '<fieldset>'
            . '<legend>&nbsp;Edit Your Blog</legend>'
            . '<p><label for="blogTitle">Blog Title:</label>'
            . '<input type="text" name="blogTitle"></p>'
            . '<p><label for="theme">Theme:</label>'
            . '<select name="theme">';
        foreach ($themes as $key=>$value)
        {
            $form = $form . '<option value="'.$value['ThemeID'].'">'.$value['Title'].'</option>';
        }
        
        $form = $form.'</select></p>'
            . '<p><label for="headerImage">Custom Header Image URL (blank=theme default):</label>'
            . '<input type="text" name="headerImage"></p>'

            . '<p><label for="footerImage">Custom Footer Image URL (blank=theme default):</label>'
            . '<input type="text" name="footerImage"></p>'

            . '<p><label for="about">About Text (for sidebar):</label>'
            . '<textarea name="about" rows="3" cols="40"></textarea></p>'

            . '<p><input type="submit" class="submit-register" value="Submit Changes"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
