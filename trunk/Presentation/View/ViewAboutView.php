<?php

class Presentation_View_ViewAboutView extends Presentation_View_View
{
    private $aboutContent;
    
    public function __construct($aboutContent)
    {
        $this->aboutContent = $aboutContent;
    }
    
    public function Display()
    {
        return '<div id="abouttext">'.BusinessLogic_ACLPSCodeConverter::NewLineToBreak($this->aboutContent).'</div>';
    }
}
?>
