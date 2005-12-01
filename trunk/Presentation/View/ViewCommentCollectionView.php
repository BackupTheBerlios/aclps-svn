<?php

  //this class represents a collection (array, to be specific) of comments, and can display them
class Presentation_View_ViewCommentCollectionView extends Presentation_View_View
{
    private $comments;
    
    public function __construct($comments)
    {
        if (!is_array($comments))
        {
            throw new Exception("ViewCommentCollectionView must be passed an array of ViewCommentViews");
        }
        $this->comments = $comments;
    }

    public function Display()
    {
        if (is_array($this->comments))
        {
            $ret = '<div id="commentcollection">';
            foreach($this->comments as $value)
            {
                $ret = $ret.'<p id="comment">'.$value->Display()."</p>\n";
            }
            $ret = $ret.'</div>';
            try {
                //try to show a new comment form at end of list of comments:
                $ret = $ret.BusinessLogic_Comment_Comment::GetInstance()->NewComment($blogID, $postID);
            } catch (Exception $e) {
                //don't show new comment form if user lacks permission
            }
            return $ret;
        }
        elseif (!isset($this->comments))
        {
            $ret = 'No Comments Posted Yet.';
            try {
                //try to show a new comment form at end of list of comments:
                $ret = $ret.BusinessLogic_Comment_Comment::GetInstance()->NewComment($blogID, $postID);
            } catch (Exception $e) {
                //don't show new comment form if user lacks permission
            }
            return $ret;
        }
        else
        {
            throw new Exception("Contents of ViewCommentCollectionView must either be an array or unset.");
        }
    }

    public function GetComments()
    {
        return $this->comments;
    }
}

?>
