<?php

  //this class represents a post to be deleted
class Presentation_View_DeleteBlogView extends Presentation_View_View
{
    private $blogID;
    private $deleteBlogID;
    private $title;
    
    public function __construct($blogID, $deleteBlogID, $title)
    {
        $this->blogID = $blogID;
        $this->deleteBlogID = $deleteBlogID;
        $this->title = $title;
    }

    public function Display()
    {
        $form = '<fieldset><legend> Delete Blog</legend>'
            . '<form method="post" action="index.php?blogID='. $this->blogID
            . '&deleteBlogID=' . $this->deleteBlogID
            . '&Action=ProcessDeleteBlog">'
            . '<table id="formtable"><tr><td align="center">Do you really want to delete ' . $this->title . '?</td></tr>'
            . '<tr><td align="center"><input type="submit" id="submit" value="Yes"></td></tr></table>'
            . '</form></fieldset>';
        return $form;
    }
}

?>
