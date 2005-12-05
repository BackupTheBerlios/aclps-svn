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
        
        $form = '<fieldset><legend>Edit Links</legend>'
            . '<form method="post" action="index.php?Action=EditLinks&blogID=' . $this->blogID . '">'
            . '<table id="formtable"><tr><td>Link URL</td><td>Link Text</td></tr>';
        for($i = 0; $i < $count; ++$i;)
        {
            $form .= '<tr><td><input type="text" name="Url" value="' . $this->urls[$i] . '"></td>'
                . '<td><input type="text" name="Title" value="' . $this->titles[$i] . '"></td></tr>';
        }
        $form .= '<tr><td colspan="2"><input type="submit" id="submit" value="Submit"></td></tr></table>'
            . '</form></fieldset>';
        
        return $form;
    }	
}

?>
