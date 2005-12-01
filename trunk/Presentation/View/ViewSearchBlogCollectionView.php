<?php

  //this class represents a collection (array, to be specific) of blogs, and can display them
class Presentation_View_ViewSearchBlogCollectionView extends Presentation_View_View
{
    private $blogs;
    private $looking;
    
    public function __construct($blogs, $search)
    {
        $this->blogs = $blogs;
        $this->looking = '<div id="searchblogs">Searching for:'
            .$search.'</div>';
    }

    public function Display()
    {
        if(is_array($this->blogs))
        {
            $ret = $this->looking.'<div id="blogcollection">';
            foreach($this->blogs as $value)
            {
                //If there's anything that should go between posts (newline or something), add it here
                $ret = $ret.'<p id="blog">'.$value->Display()."</p>\n";
            }
            $ret = $ret.'</div>';
            return $ret;
        }
        elseif($this->blogs == 0)
        {

            return ($this->looking.'<div id="blogcollection">No Results Found.</div>');
        }
        else
        {
            throw new Exception("Content of blogs in ViewSearchBlogCollectionView".
                " must be an array or zero integer.");
        }
    }

}
?>
