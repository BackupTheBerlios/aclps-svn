<?php

class Presentation_View_ViewDashboardInvitationCollectionView extends Presentation_View_View
{

    public function __construct()
    {
        //Do Nothing
    }

    public function Display()
    {
        $ret = '<div id="dashboardcollection">';

        if (count($this->Views) > 0)
        {
            $ret .= '<div id="dashboardcollectionheader">You\'ve Been Invited!</div>';
            foreach($this->Views as $view)
            {
                $ret .= $view->Display();
            }
        }
        else
        {
            $ret .= 'You have no pending invitations.';
        }

        $ret .= '</div>';

        return $ret;
    }
}

?>

