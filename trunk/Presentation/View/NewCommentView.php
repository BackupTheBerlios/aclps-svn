<?php

  //this class represents a new comment
class Presentation_View_NewCommentView extends Presentation_View_View
{
    private $postID;
    private $blogID;
    private $defaulttitle;
    private $defaultcontent;
    private $errmsg;
    
    public function __construct($blogID,$postID,$defaulttitle,$defaultcontent,$errmsg)
    {
        $this->postID = $postID;
        $this->blogID = $blogID;
        $this->defaulttitle = $defaulttitle;
        $this->defaultcontent = $defaultcontent;
        $this->errmsg = $errmsg;
    }

    public function Display()
    {
        $form = '<fieldset><legend><img src="UI/Themes/Images/Controls/newcomment.png" id="controlbarimg" /> Add A Comment</legend>'
            . '<form method="post" action="index.php?blogID='.$this->blogID.'&Action=ProcessNewComment">';

        if (strlen($this->errmsg) > 0)
        {
            $form .= '<p>'.$this->errmsg.'</p>';
        }

        $form .= '<input type="hidden" name="postID" value="'.$this->postID.'">'

            . '<table id="formtable"><tr><td><label for="title">Title:</label> '
            . '<input type="text" maxlength="30" size="30" name="title" value="'.htmlspecialchars($this->defaulttitle).'"></td></tr>'

            . '<tr><td><label for="content">Content:</label></td></tr>'
            . '<tr><td><textarea name="content" rows="7" cols="40">'.htmlspecialchars($this->defaultcontent).'</textarea></td></tr>'

            . '<tr><td align="center"><input type="submit" id="submit" value="Add Comment"></td></tr></table>'
            . '</form></fieldset>';

        return $form;
    }
}

?>
