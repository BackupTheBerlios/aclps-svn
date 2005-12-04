<?php

class Presentation_View_ViewChangeMemberRankCollectionView extends Presentation_View_View
{
    private $blogID;

    public function __construct($blogID)
    {
        $this->blogID = $blogID;
    }

    public function Display()
    {
        $form = '<div id=changememberrankcollection>'
            . '<form method="post" action="index.php?&Action=ProcessChangeMemberRank&blogID=' . $this->blogID . '">'
            . '<fieldset>'
            . '<legend>Change Members\' Rank</legend>';

        if (count($this->Views) > 0)
        {

            foreach($this->Views as $view)
            {
                $form = $form . $view->Display();
            }

            $form = $form
            . '<input type="submit" id="submit" value="Change Members\' Rank">';
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

