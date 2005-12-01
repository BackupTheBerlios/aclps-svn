<?php

class Presentation_View_ViewSearchView extends Presentation_View_View
{
    private $errorMessage;

    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
    
    public function Display()
    {
        $form = '<form method="post" action="index.php?Action=ProcessSearch&blogID=1">'
            . '<fieldset>'
            . '<legend>&nbsp;Search</legend>'
            . '<p>';

      if ($this->errorMessage != '')
      {
        $form = $form
                . $this->errorMessage
                . '<br />';
      }

        $form = $form
            . '<center>Please enter the blog title you want to search:</center>'
            . '</p>'
            . '<label for="username">Blog Title:</label>'
            . '<input type="text" name="blog_title">'
            . '<br />'
            . '<input type="submit" class="submit-search" value="Search">'
            . '</fieldset>'
            . '</form>';
            
        return $form;
    }
}

?>
