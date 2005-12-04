<?php

class Presentation_View_ViewAssociatedBlogCollectionView extends Presentation_View_View
{

    public function __construct()
    {
        //Do Nothing
    }
    
    public function Display()
    {
        $ret = '<div id=associatedblogcollection><div id=associatedblogcollection_title>Associated Blogs</div>';
        
        if (count($this->Views) > 0)
        {
            foreach($this->Views as $view)
            {
                $ret = $ret . $view->Display();
            }
        }
        else
        {
            $ret = $ret . 'You are not associated with any blogs.';
        }

        $ret = $ret . '</div>';

        return $ret;
    }
}

?>
