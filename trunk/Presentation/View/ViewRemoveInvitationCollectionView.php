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
        $form = '<div id=removeinvitationcollection>'
            . '<form method="post" action="index.php?&Action=ProcessRemoveInvitation&blogID=' . $this->blogID . '">'
            . '<fieldset>'
            . '<legend>Remove Invitations</legend>';

        if (count($this->Views) > 0)
        {

            foreach($this->Views as $view)
            {
                $form = $form . $view->Display();
            }

            $form = $form
            . '<input type="submit" id="submit" value="Remove Invitations">';
        }
        else
        {
            $form = $form . 'You have no outstanding invitations.';
        }

        $form = $form
            . '</fieldset>'
            . '</form>'
            . '</div>';

        return $form;
    }
}

?>

