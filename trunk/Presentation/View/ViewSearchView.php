<?php

class Presentation_View_ViewSearchView extends Presentation_View_View
{
    private $result;
    private $blogid;

    public function __construct($result)
    {
        $this->result = $result;
        
        if($_GET['blogID'])
            $this->blogid = $_GET['blogID'];
        else
            $this->blogid = 1;
    }
    
    public function Display()
    {
        $form = '<form method="post" action="index.php?Action=ViewSearch&blogID='
            . $this->blogid
            . '">'
            . '<fieldset>'
            . '<legend>&nbsp;Search</legend>'
            . '<p>';

        if(is_string($this->result))
        {
            $form .= $this->result.'<br />';
        }
        elseif(is_object($this->result))
        {
            $ret = $this->result->Display();
        }
        else
        {
            throw new Exception("Search Failure.");
        }

        $form .= '<center>Please enter the blog title you want to search:</center>'
            . '</p>'
            . '<label for="username">Blog Title:</label>'
            . '<input type="text" name="blog_title">'
            . '<br />'
            . '<input type="submit" class="submit-search" value="Search">'
            . '</fieldset>'
            . '</form>'
            . $ret;

        return $form;
    }
}

?>
