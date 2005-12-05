<?php

class Presentation_View_NewBlogView extends Presentation_View_View
{
    private $blogID;
    private $themeslist;
    
    public function __construct($blogID, $themeslist)
    {
        //blogid is passed solely for returning to system when user submits processnewblog form
        $this->blogID = $blogID;
        $this->themeslist = $themeslist;
    }

    public function Display()
    {
        $form = '<fieldset><legend>Create Your Blog</legend>'
            . '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewBlog">'
            . '<table id="formtable"><tr><td colspan="2"><label for="title">Title:</label>'
            . ' <input type="text" name="title"></td></tr>'
            
            . '<tr><td colspan="2"><label for="theme">Theme:</label> <select name="theme">';
        foreach ($this->themeslist as $key=>$value)
        {
            $form = $form.'<option value="'.$key.'">'.$value.'</option>';
        }
        $form = $form.'</select></td></tr>'
            
            . '<tr><td colspan="2"><label for="headertog">Header Image:</label></td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="no"></td><td>None</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="def" checked></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="cust"></td><td>Custom URL: <input type="text" name="headerimg" size="40"></td></tr>'

            . '<tr><td colspan="2"><label for="footertog">Footer Image:</label></td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="no"></td><td>None</td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="def" checked></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="cust"></td><td>Custom URL: <input type="text" name="footerimg" size="40"></td></tr>'
            
            . '<tr><td colspan="2"><label for="about">About Text (for sidebar):</label></td></tr>'
            . '<tr><td colspan="2" align="center"><textarea name="about" rows="7" cols="50"></textarea></td></tr>'
            
            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Create Blog"></td></tr></table>'
            . '</form></fieldset>';
        
        return $form;
    }
}

?>
