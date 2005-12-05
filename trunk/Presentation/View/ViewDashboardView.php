<?php

class Presentation_View_ViewDashboardView extends Presentation_View_View
{
    //more or less a typedef (i.e. VDV has the same functionality as View)
    public function Display()
    {
        foreach($this->Views as $key=>$value)
        {
            $ret .= $value->Display();
        }
        return $ret;
    }
}

?>
