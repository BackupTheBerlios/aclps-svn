<?php

class Presentation_View_NewPostView extends Presentation_View_View
{
    private $blogID;
    
    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        //TODO: make display
        return 'This is a new post view! :D<br />';
    }
}

?>
