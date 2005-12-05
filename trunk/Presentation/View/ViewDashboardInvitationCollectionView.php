<?php

class Presentation_View_ViewDashboardInvitationCollectionView extends Presentation_View_View
{

    public function __construct()
    {
        //Do Nothing
    }

    public function Display()
    {
        $ret = '<fieldset id="subset"><legend>Invitations</legend>';
        
        if (count($this->Views) > 0)
        {
            foreach($this->Views as $view)
            {
                $ret .= $view->Display();
            }
        }
        else
        {
            $ret .= 'You have no pending invitations.';
        }

        $ret .= '</fieldset>';

        return $ret;
    }
}

?>

