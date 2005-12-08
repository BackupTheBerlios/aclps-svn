<?php

class Presentation_View_NewPostView extends Presentation_View_View
{
    private $blogID;
    private $defaulttitle;
    private $defaultcontent;
    private $errmsg;
    
    public function __construct($blogID,$defaulttitle,$defaultcontent,$defaultpublic,$errmsg)
    {
        $this->blogID = $blogID;
        $this->defaulttitle = $defaulttitle;
        $this->defaultcontent = $defaultcontent;
        $this->errmsg = $errmsg;
        if ($defaultpublic)
        {
            $this->defaultpublic = 'checked';
        }
        else
        {
            $this->defaultpublic = '';
        }
    }

    public function Display()
    {
        $form = '<fieldset><legend><img src="UI/Themes/Images/Controls/newpost.png" id="controlbarimg" /> Add A Post</legend>'
            . '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewPost">';

        if (strlen($this->errmsg) > 0)
        {
            $form .= '<p>'.$this->errmsg.'</p>';
        }
        
        $form .= '<table id="formtable"><tr><td colspan="2"><label for="title">Title:</label> '
            . '<input type="text" maxlength="30" size="30" name="title" value="'.htmlspecialchars($this->defaulttitle).'"></td></tr>'
            
            . '<tr><td><input type="checkbox" name="public" '.$this->defaultpublic.'></td>'
            . '<td><label for="public">Public (Anonymous can view)</label></td></tr>'

            . '<tr><td colspan="2"><label for="content">Content:</label></td></tr>'
            . '<tr><td colspan="2"><textarea name="content" rows="15" cols="60">'.htmlspecialchars($this->defaultcontent).'</textarea></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Add Post"></td></tr></table>'
            . '</form></fieldset>';

        return $form;
    }
}

?>
