<?php

class Presentation_View_EditLinksView extends Presentation_View_View
{
	private $urls;	//array
	private $titles; //array
	private $blogID;

	public function __construct($blogID,$urls,$titles)
	{
		
		$this->urls = $urls;
		$this->blogID = $blogID;
	}

    public function Display()
    {
		$count = $count($urls);	//this is the number of textboxes needed...

		$form = '<form method="post" action="index.php?Action=EditLinks&blogID=' . $this->blogID . '">'
            	. '<fieldset>'
            	. '<legend>&nbsp;Edit Links</legend>'
            	. '<p>'
            	. '<center>Edit the links below:</center>'
           		. '</p>'

					
		for($i = 0; $i < $count; ++$i;)
		{
            
			$form = . '<input type="text" name="Url" value="' . $this->urls[i] . '">'
            		. '<br />'
					. '<input type="text" name="Title" value="' . $this->titles[i] . '">'
            		. '<br />'
 
        }
		   $form = . '<input type="submit" class="submit-login" value="Submit">'
            . '</fieldset>'
			. '</form>';

		return $form;
 
    }	
}

?>
