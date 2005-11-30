<?php

  //this class represents a new post
class Presentation_View_NewPostView extends Presentation_View_View
{
    private $post;
    
    public function __construct($post)
    {
        if (is_array($post))
        {
            throw new Exception("NewPostViews only support a single ViewPostView");
        }
        $this->post = $post;
    }

    public function Display()
    {
        //TODO: make display
        return 'This is a new post view! :D<br />'.$this->post->DisplayAsForm();
    }
}

?>
