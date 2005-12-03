<?php

class Presentation_View_ViewSearchView extends Presentation_View_View
{
    private $result;
    private $blogid;
    private $popular;

    public function __construct($result, $more)
    {
        $this->result = $result;
        $this->popular = $more;
        
        if($_GET['blogID'])
            $this->blogid = $_GET['blogID'];
        else
            $this->blogid = 1;
    }
    
    public function Display()
    {
        $form = '<fieldset><legend>&nbsp;Search</legend>'
            . '<form method="post" action="index.php?Action=ViewSearch&blogID='.$this->blogid.'">';

        if(is_string($this->result))
        {
            $form .= '<p>'.$this->result.'</p>';
        }
        elseif(is_object($this->result))
        {
            $form .= '<p>'.$this->result->Display().'</p>';
        }
        else
        {
            throw new Exception("Search Failure.");
        }

        $form .= '<p>Please enter the blog title you want to search:</p>'
            . '<p><input type="text" name="blog_title">'
            . '<label for="blog_title">Blog Title</label></p>'
            . '<p><input type="submit" id="submit" value="Search"></p>'
            . '</form></fieldset>'
            . $ret.$this->popular->Display();

        return $form;
    }
}

?>
