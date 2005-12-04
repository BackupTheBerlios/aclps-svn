<?php

class Presentation_View_ViewDashboardInvitationCollectionView extends Presentation_View_View
{

    public function __construct()
    {
        //Do Nothing
    }

    public function Display()
    {
        $ret = '<div id=dashboardinvitationcollection><div id=dashboardinvitationcollection_title>Invitations</div>';
        
        if (count($this->Views) > 0)
        {
            foreach($this->Views as $view)
            {
                $ret = $ret . $view->Display();
            }
        }
        else
        {
            $ret = $ret . 'You have no outstanding invitations.';
        }

        $ret = $ret . '</div>';

        return $ret;
    }
}

?>

