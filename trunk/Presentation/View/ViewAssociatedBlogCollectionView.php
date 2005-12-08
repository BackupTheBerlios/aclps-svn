<?php

class Presentation_View_ViewAssociatedBlogCollectionView extends Presentation_View_View
{

    public function __construct()
    {
        //Do Nothing
    }
    
    public function Display()
    {
        $ret = '<div id=dashboardcollection>';
        $ret = $ret . '<b>Associated Blogs:</b><br />';
        
        if (count($this->Views) > 0)
        {
            foreach($this->Views as $view)
            {
                $ret = $ret . $view->Display();
            }
        }
        else
        {
            $ret .= '<div id=dashboardelementnoborder>You are not associated with any blogs.</div>';
        }

        $ret .= '</div>';

        return $ret;
    }
}

?>
