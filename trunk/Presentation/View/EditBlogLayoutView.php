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
            . '<table id="formtable"><tr><td><label for="blogTitle">Blog Title:</label></td>'
            . '<td><input type="text" name="blogTitle" value=' . $this->title . '></td></tr>'
            . '<tr><td><label for="theme">Theme:</label></td>'
            . '<td><select name="theme">';
        foreach ($themes as $key=>$value)
        {
            $form = $form . '<option value="'.$value['ThemeID'].'">'.$value['Title'].'</option>';
        }
        
        $form = $form.'</select></td></tr>'
            . '<tr><td><label for="headerImage">Custom Header Image URL (blank=theme default):</label></td>'
            . '<td><input type="text" name="headerImage" value="' . $this->headerImage . '"></td></tr>'

            . '<tr><td><label for="footerImage">Custom Footer Image URL (blank=theme default):</label></td>'
            . '<td><input type="text" name="footerImage" value="' . $this->footerImage . '"></td></tr>'

            . '<tr><td colspan="2"><label for="about">About Text (for sidebar):</label></td></tr>'
            . '<tr><td colspan="2"><textarea name="about" rows="3" cols="40">' . $this->about . '</textarea></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Submit Changes"></td></tr></table>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
