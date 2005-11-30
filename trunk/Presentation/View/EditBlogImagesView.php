<?php

class Presentation_View_EditBlogImagesView extends Presentation_View_View
{
	private $headerImage;
	private $footerImage;
	private $blogID;

	public function __construct($headerImage,$footerImage,$blogID)
	{
		
		$this->headerImage = $headerImage;
		$this->footerImage = $footerImage;
		$this->blogID = $blogID;
	}

    public function Display()
    {
		$form = '<form method="post" action="index.php?Action=EditBlogImages&blogID=' . $this->blogID . '">'
            . '<fieldset>'
            . '<legend>&nbsp;Edit header and footer images</legend>'
            . '<p>'
            . '<center>Edit the field below:</center>'
            . '</p>'
            . '<input type="text" name="headerImage" value="' . $this->headerImage . '">'
            . '<br />'
			. '<input type="text" name="footerImage" value="' . $this->footerImage . '">'
            . '<br />'
            . '<input type="submit" class="submit-login" value="Submit">'
            . '</fieldset>'
            . '</form>';

			return $form;
 
    }

	
}

?>
