<?php

class Presentation_View_ViewInvitationCollectionView extends Presentation_View_View
{

    public function __construct()
    {
        //Do Nothing
    }

    public function Display()
    {
        $ret = '<div id=invitationcollection><div id=invitationcollection_title>Invitations</div>';

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

