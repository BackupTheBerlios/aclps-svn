<?php

class Presentation_View_ViewEditBlogImagesView extends Presentation_View_View
{
	private $headerImage;
	private $footerImage;

	public function __construct($headerImage,$footerImage)
	{
		
		$this->headerImage = $headerImage;
		$this->footerImage = $footerImage;
	}

    public function Display()
    {
		$images = array($this->headerImage,$this->footerImage);
		return $images;
 
    }
}

?>
