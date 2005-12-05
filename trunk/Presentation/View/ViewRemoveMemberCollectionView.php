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
        $form = '<fieldset><legend>Remove Members</legend>'
            . '<form method="post" action="index.php?&Action=ProcessRemoveMember&blogID=' . $this->blogID . '">';

        if (count($this->Views) > 0)
        {

            foreach($this->Views as $view)
            {
                $form .= $view->Display();
            }

            $form .= '<input type="submit" id="submit" value="Remove Members">';
        }
        else
        {
            $form .= 'There are no members that you can remove.';
        }

        $form .= '</form></fieldset>';

        return $form;
    }
}

?>

