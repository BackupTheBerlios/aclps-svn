<?php

class Presentation_View_ViewRemoveInvitationCollectionView extends Presentation_View_View
{
    private $blogID;

    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<fieldset><legend>Remove Invitations</legend>'
            . '<form method="post" action="index.php?&Action=ProcessRemoveInvitation&blogID=' . $this->blogID . '">';

        if (count($this->Views) > 0)
        {

            foreach($this->Views as $view)
            {
                $form .= $view->Display();
            }

            $form .= '<input type="submit" id="submit" value="Remove Invitations">';
        }
        else
        {
            $form .= 'You have no pending invitations.';
        }

        $form .= '</form></fieldset>';

        return $form;
    }
}

?>

