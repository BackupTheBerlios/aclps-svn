<?php

  //this class represents a post to be deleted
class Presentation_View_DeletePostView extends Presentation_View_View
{
    private $post;
    
    public function __construct($post)
    {
        if (is_array($post))
        {
            throw new Exception("DeletePostViews only support a single ViewPostView");
        }
        $this->post = $post;
    }

    public function Display()
    {
        //TODO: make display
        return 'This is a delete post view! :D<br />'.$this->post->Display();
    }
}

?>
