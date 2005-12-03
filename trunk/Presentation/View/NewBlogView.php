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
            
            
            . '<p><label for="theme">Theme:</label>'
            . '<select name="theme">';
        $themeslist = BusinessLogic_Blog_BlogDataAccess::GetInstance()->GetThemesList();
        foreach ($themeslist as $key=>$value)
        {
            $form = $form.'<option value="'.$value['ThemeID'].'">'.$value['Title'].'</option>';
        }
        $form = $form.'</select></p>'
            
            . '<p><label for="headertog">Header Image:</label>'
            . '<input type="radio" name="headertog" value="no"> None<br />'
            . '<input type="radio" name="headertog" value="def"> Theme Default<br />'
            . '<input type="radio" name="headertog" value="cust"> Custom URL: <input type="text" name="headerimg"></p>'

            . '<p><label for="footertog">Footer Image:</label>'
            . '<input type="radio" name="footertog" value="no"> None<br />'
            . '<input type="radio" name="footertog" value="def"> Theme Default<br />'
            . '<input type="radio" name="footertog" value="cust"> Custom URL: <input type="text" name="footerimg"></p>'
            
            . '<p><label for="about">About Text (for sidebar):</label>'
            . '<textarea name="about" rows="3" cols="40"></textarea></p>'
            
            . '<p><input type="submit" class="submit-register" value="Create Blog"></p>'
            . '</fieldset>'
            . '</form>';
        
        return $form;
    }
}

?>
