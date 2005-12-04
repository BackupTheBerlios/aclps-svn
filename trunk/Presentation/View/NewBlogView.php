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
            . '<legend>Create Your Blog</legend>'
            
            . '<table id="formtable"><tr><td colspan="2"><label for="title">Title:</label>'
            . ' <input type="text" name="title"></td></tr>'
            
            . '<tr><td colspan="2"><label for="theme">Theme:</label> <select name="theme">';
        $themeslist = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemesList();
        foreach ($themeslist as $key=>$value)
        {
            $form = $form.'<option value="'.$value['ThemeID'].'">'.$value['Title'].'</option>';
        }
        $form = $form.'</select></td></tr>'
            
            . '<tr><td colspan="2"><label for="headertog">Header Image:</label></td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="no"></td><td>None</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="def" checked></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="headertog" value="cust"></td><td>Custom URL: <input type="text" name="headerimg"></td></tr>'

            . '<tr><td colspan="2"><label for="footertog">Footer Image:</label></td></tr>'
            . '<tr><td>None</td><td><input type="radio" name="footertog" value="no"></td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="def" checked></td><td>Theme Default</td></tr>'
            . '<tr><td><input type="radio" name="footertog" value="cust"></td><td>Custom URL: <input type="text" name="footerimg"></td></tr>'
            
            . '<tr><td colspan="2"><label for="about">About Text (for sidebar):</label></td></tr>'
            . '<tr><td colspan="2"><textarea name="about" rows="3" cols="40"></textarea></td></tr>'
            
            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Create Blog"></td></tr></table>'
            . '</fieldset>'
            . '</form>';
        
        return $form;
    }
}

?>
