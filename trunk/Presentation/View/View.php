<?php

abstract class Presentation_View_View
{
    protected $Views;
  
    public function AddView($aView)
    {
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
            $ret = $ret . $value->Display();
        }
        return $ret;
    }
}

?>
