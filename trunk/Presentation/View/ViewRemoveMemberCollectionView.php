<?php

class Presentation_View_ViewRemoveMemberCollectionView extends Presentation_View_View
{
    private $blogID;

    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<div id=removemembercollection>'
            . '<form method="post" action="index.php?&Action=ProcessRemoveMember&blogID=' . $this->blogID . '">'
            . '<fieldset>'
            . '<legend>Remove Members</legend>';

        if (count($this->Views) > 0)
        {

            foreach($this->Views as $view)
            {
                $form = $form . $view->Display();
            }

            $form = $form
            . '<input type="submit" id="submit" value="Remove Members">';
        }
        else
        {
            $form = $form . 'You have no outstanding members.';
        }

        $form = $form
            . '</fieldset>'
            . '</form>'
            . '</div>';

        return $form;
    }
}

?>

