<?php

class Presentation_View_NewBlogView extends Presentation_View_View
{
    private $blogID;
    private $themeslist;
    
    public function __construct($blogID, $themeslist,$headertext)
    {
        //blogid is the current blogid being viewed when working on the form
        $this->blogID = $blogID;
        $this->themeslist = $themeslist;
        $this->headertext = $headertext;
    }

    public function Display()
    {
        $form = '<fieldset><legend>Create Your Blog</legend>'
            . '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewBlog">';

        if (strlen($this->headertext) > 0)
        {
            $form .= '<p>'.$this->headertext.'</p>';
        }

        $form .= '<table id="formtable"><tr><td colspan="2"><label for="title">Title:</label>'
            . ' <input type="text" name="title"></td></tr>'
            . '<tr><td colspan="2"><label for="theme">Theme:</label> <select name="theme">';

        foreach ($this->themeslist as $key=>$value)
        {
            $form .= '<option value="'.$key.'">'.$value.'</option>';
        }
        $form .= '</select></td></tr>'
            . '<tr><td colspan="2"><label for="headertog">Header Image:</label></td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="no" onFocus="javascript:headerImage.value=\'\'"></td><td>None</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="def" checked onFocus="javascript:headerImage.value=\'\'"></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="cust"></td><td>Custom URL: <input type="text" name="headerimg" size="40" onFocus="javascript:headertog[2].checked=\'1\'"></td></tr>'

            . '<tr><td colspan="2"><label for="footertog">Footer Image:</label></td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="no" onFocus="javascript:footerImage.value=\'\'"></td><td>None</td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="def" checked onFocus="javascript:footerImage.value=\'\'"></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="cust"></td><td>Custom URL: <input type="text" name="footerimg" size="40" onFocus="javascript:footertog[2].checked=\'1\'"></td></tr>'
            
            . '<tr><td colspan="2"><label for="about">About Text (for sidebar):</label></td></tr>'
            . '<tr><td colspan="2" align="center"><textarea name="about" rows="7" cols="50"></textarea></td></tr>'
            
            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Create Blog"></td></tr></table>'
            . '</form></fieldset>';
        
        return $form;
    }
}

?>
