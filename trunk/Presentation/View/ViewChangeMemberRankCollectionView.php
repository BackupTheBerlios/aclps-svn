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
        $form = '<fieldset><legend>Change Member Ranks</legend>'
            . '<form method="post" action="index.php?&Action=ProcessChangeMemberRank&blogID=' . $this->blogID . '">';

        if (count($this->Views) > 0)
        {

            foreach($this->Views as $view)
            {
                $form .= $view->Display();
            }

            $form .= '<input type="submit" id="submit" value="Change Member Ranks">';
        }
        else
        {
            $form .= 'There are no members that you can modify.';
        }

        $form .= '</form></fieldset>';

        return $form;
    }
}

?>

