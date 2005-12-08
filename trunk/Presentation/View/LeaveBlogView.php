<?php

  //this class represents a post to be deleted
class Presentation_View_LeaveBlogView extends Presentation_View_View
{
    private $blogID;
    private $leavingBlogID;
    private $title;
    
    public function __construct($blogID, $leavingBlogID, $title)
    {
        $this->blogID = $blogID;
        $this->leavingBlogID = $leavingBlogID;
        $this->title = $title;
    }

    public function Display()
    {
        $form = '<fieldset><legend> Leave Blog</legend>'
            . '<form method="post" action="index.php?blogID='. $this->blogID
            . '&leavingBlogID=' . $this->leavingBlogID
            . '&Action=ProcessLeaveBlog">'
            . '<table id="formtable"><tr><td align="center">Do you really want to leave ' . $this->title . '?</td></tr>'
            . '<tr><td align="center"><input type="submit" id="submit" value="Yes"></td></tr></table>'
            . '</form></fieldset>';
        return $form;
    }
}

?>
