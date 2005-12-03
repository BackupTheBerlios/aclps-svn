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
            . '<legend>Edit header and footer images</legend>'
            . '<p>Edit the field below:</p>'
            . '<p><input type="text" name="headerImage" value="' . $this->headerImage . '"></p>'
            . '<p><input type="text" name="footerImage" value="' . $this->footerImage . '"></p>'
            . '<p><input type="submit" id="submit" value="Submit"></p>'
            . '</fieldset>'
            . '</form>';

			return $form;
 
    }

	
}

?>
