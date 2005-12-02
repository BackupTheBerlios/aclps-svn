<?php

class Presentation_View_ViewEditLinksView extends Presentation_View_View
{
	private $urls;
	private $titles;

	public function __construct($urls,$titles)
	{
		
		$this->urls = $urls;
		$this->titles = $titles;
	}
		


    public function Display()
    {
		$result =array($this->urls,$this->titles);
		return $result;
 
    }
}

?>
