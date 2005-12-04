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

    public function __construct($blogID, $themes, $title, $about, $themeID, $headerImage, $footerImage, $defaultHeaderImage, $defaultFooterImage)
    {
        $this->blogID = $blogID;
        $this->themes = $themes;
        $this->title = $title;
        $this->about = $about;
        $this->themeID = $themeID;
        $this->headerImage = $headerImage;
        $this->footerImage = $footerImage;
        $this->defaultHeaderImage = $defaultHeaderImage;
        $this->defaultFooterImage = $defaultFooterImage;
    }

    public function Display()
    {
        //before generating the form, figure out whether the current header/footer are none/default/custom (for the radios):
        $headerNone = '';
        $headerDefault = '';
        $headerCustom = '';
        $headerCustomURL = '';
        if ($this->headerImage == '')
            $headerNone = ' checked';
        elseif ($this->headerImage == $this->defaultHeaderImage)
            $headerDefault = ' checked';
        else {
            $headerCustom = ' checked';
            $headerCustomURL = $this->headerImage;
        }

        $footerNone = '';
        $footerDefault = '';
        $footerCustom = '';
        $footerCustomURL = '';
        if ($this->footerImage == '')
            $footerNone = ' checked';
        elseif ($this->footerImage == $this->defaultFooterImage)
            $footerDefault = ' checked';
        else {
            $footerCustom = ' checked';
            $footerCustomURL = $this->footerImage;
        }
        
        $form = '<form method="post" action="index.php?&Action=ProcessEditBlogLayout&blogID=' . $this->blogID . '">'
            . '<fieldset>'
            . '<legend>Edit Your Blog</legend>'
            . '<table id="formtable"><tr><td colspan="2"><label for="blogTitle">Title:</label> '
            . '<input type="text" name="blogTitle" value=' . $this->title . '></td></tr>'
            . '<tr><td colspan="2"><label for="theme">Theme:</label> '
            . '<select name="theme">';
        foreach ($this->themes as $key=>$value)
        {
            //select the current theme in the pulldown menu:
            $currenttheme = '';
            if ($key == $this->themeID)
            {
                $currenttheme = ' selected';
            }
            $form = $form . '<option value="'.$key.'"'.$currenttheme.'>'.$value.'</option>';
        }
        
        $form = $form.'</select></td></tr>'
            . '<tr><td colspan="2"><label for="headertog">Header Image:</label></td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="no" '.$headerNone.'></td><td>None</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="def" '.$headerDefault.'></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="cust" '.$headerCustom.'></td><td>Custom URL: <input type="text" name="headerImage" value="'.$headerCustomURL.'" size="40"></td></tr>'

            . '<tr><td colspan="2"><label for="footertog">Footer Image:</label></td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="no" '.$footerNone.'></td><td>None</td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="def" '.$footerDefault.'></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="cust" '.$footerCustom.'></td><td>Custom URL: <input type="text" name="footerImage" value="'.$footerCustomURL.'" size="40"></td></tr>'

            . '<tr><td colspan="2"><label for="about">About Text (for sidebar):</label></td></tr>'
            . '<tr><td colspan="2" align="center"><textarea name="about" rows="3" cols="50">' . $this->about . '</textarea></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Update Blog"></td></tr></table>'
            . '</fieldset>'
            . '</form>';

        return $form;
    }
}

?>
