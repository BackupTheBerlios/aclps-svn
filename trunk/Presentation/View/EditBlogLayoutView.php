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
            . '<legend>Edit Your Blog</legend>'
            . '<p><input type="text" name="blogTitle" value=' . $this->title . '></p>'
            . '<label for="blogTitle">Blog Title</label></p>'
            . '<p><select name="theme">';
        foreach ($themes as $key=>$value)
        {
            $form = $form . '<option value="'.$value['ThemeID'].'">'.$value['Title'].'</option>';
        }
        
        $form = $form.'</select><label for="theme">Theme:</label></p>'
            . '<p><input type="text" name="headerImage" value="' . $this->headerImage . '">'
            . '<label for="headerImage">Custom Header Image URL (blank=theme default)</label></p>'

            . '<p><input type="text" name="footerImage" value="' . $this->footerImage . '">'
            . '<label for="footerImage">Custom Footer Image URL (blank=theme default)</label></p>'

            . '<p><textarea name="about" rows="3" cols="40">' . $this->about . '</textarea>'
            . '<label for="about">About Text (for sidebar)</label></p>'

            . '<p><input type="submit" id="submit" value="Submit Changes"></p>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
