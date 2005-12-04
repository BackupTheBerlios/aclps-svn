<?php

  //this class represents a collection (array, to be specific) of posts, and can display them as RSS
class Presentation_View_ViewPostRSSView extends Presentation_View_View
{
    private $posts;
    
    public function __construct($posts)
    {
        if (!is_array($posts))
        {
            throw new Exception("ViewPostRSSView must be passed an array of ViewPostViews");
        }
        $this->posts = $posts;
    }

    public function Display()
    {
        if (is_array($this->posts))
        {
            $ret = '<div id="postcollection">';
            if (count($this->posts) < 1)
            {
                $ret = $ret."No posts found.\n";
            } else {
                foreach($this->posts as $value)
                {
                    //If there's anything that should go between posts (newline or something), add it here
                    $ret = $ret.$value->Display()."\n";
                }
            }
            $ret = $ret.'</div>';
            return $ret;
        }
        elseif (!isset($this->posts))
        {
            return '<div id="postcollection">No Posts</div>';
        }
        else
        {
            throw new Exception("Contents of ViewPostCollectionView must either be an array or unset.");
        }
    }
}

?>