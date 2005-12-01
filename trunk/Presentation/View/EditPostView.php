<?php

class Presentation_View_EditPostView extends Presentation_View_View
{
    private $post;
    
    public function __construct($post)
    {
        if (is_array($post))
        {
            throw new Exception("EditPostViews only support a single ViewPostView");
        }
        $this->post = $post;
    }

    public function Display()
    {
        //TODO: make display
        //note: can only set timestamp as "<original>" or "Now"
        return 'This is an edit post view! :D<br />';
    }
}

?>
