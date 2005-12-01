<?php

abstract class Presentation_View_View
{
    private $Views;
  
    public function AddView($aView)
    {
	//print_r( $this->Views);
	$this->Views[] = $aView;
    }

    public function DeleteView($aViewName)
    {
	foreach($this->Views as $key=>$value)
	{
	    if ($value == $aViewName)
	    {
		unset($this->Views[$key]);
		break;
	    }
	}
    }
  
    public function Display()
    {
        $ret = '';
        foreach($this->Views as $key=>$value)
        {
            $ret = $ret . '<br />' . $value->Display();
        }
        return $ret;
    }
}

?>
