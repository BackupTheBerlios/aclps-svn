<?php

  //this class represents a collection (array, to be specific) of posts, and can display them
class Presentation_View_ViewPostCollectionView extends Presentation_View_View
{
    private $posts;
    
    public function __construct($posts)
    {
        if (!is_array($posts))
        {
            throw new Exception("ViewPostCollectionView must be passed an array of ViewPostViews");
        }
        $this->posts = $posts;
        $this->SetCommentCounts();
    }

    private function SetCommentCounts() {
        //Sets the comment counts for each post in this collection.
        foreach($this->posts as $key => $value)
        {
            $postIDs[$key] = $value->GetPostID();
        }

        $commentCounts = BusinessLogic_Comment_CommentDataAccess::GetInstance()->GetCommentCounts($postIDs);

        foreach($this->posts as $key => $value)
        {
            $value->SetCommentCount($commentCounts[$key]);
        }
	    
    }

    public function Display()
    {
        if (is_array($this->posts))
        {
            $ret = '<div id="postcollection">';
            if (count($this->posts) < 1)
            {
                $ret .= "No posts found.\n";
            }
            else
            {
                foreach($this->posts as $value)
                {
                    $ret .= $value->Display()."\n";
                }
            }
            $ret .= '</div>';
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

    public function GetPosts()
    {
        return $this->posts;
    }
}

?>
