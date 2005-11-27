<?php

class Presentation_View_ViewErrorView extends Presentation_View_View
{
    private $exc;

    public function __construct($exc)
    {
	$this->exc = $exc;
    }

    public function Display()
    {
      return '<p><b>Woah nelly!</b></p>'
	  .'<p>There was an error in your request!</p>'
	  .'<p>The error message is: <b>'.$this->exc->getMessage().'</b></p>'
	  .'<p>It also has this stacktrace:</p>'
	  .'<p>'.str_replace("\n","<br />\n",$this->exc->getTraceAsString()).'</p>';
    }
}

?>
