<?php

class Presentation_View_ViewBlogView extends Presentation_View_View
{
    private $topBar;
    private $headerImage;
    private $footerImage;
    private $content;
    private $sideContent;
    private $sideBottom;
    private $theme;
    private $blogID;
    private $contentOptions;
    
    public function __construct($blogID, $contentOptions, $headerImage, $footerImage, $theme)
    {
	$this->blogID = $blogID;
	$this->contentOptions = $contentOptions;
	$this->headerImage = $headerImage;
	$this->footerImage = $footerImage;
	$this->theme = $theme;
      
	$this->content = '';
	$this->sideContent = '';
    }
    
    public function Display()
    {
        die("ViewBlogView does not display.");
    }
    
    public function SetTopBar($topBar)
    {
        $this->topBar = $topBar;
    }
    
    public function SetContent($aContent)
    {
        $this->content = $aContent;
    }
    
    public function SetSideContent($aSideContent)
    {
        $this->sideContent[] = $aSideContent;
    }
    
    public function SetSideBottom($aSideBottom)
    {
        $this->sideBottom[] = $aSideBottom;
    }
    
    public function DisplayTopBar()
    {
    	if (is_object($this->topBar))
    	{
    	    return $this->topBar->Display();
    	}
    	elseif (isset($this->topBar))
    	{
    	    return $this->topBar;
    	}
    	else
    	{
    	    return '&nbsp;';
    	}
    }

    public function DisplayHeaderImage()
    {
	if (is_object($this->headerImage))
	{
	    return $this->headerImage->Display();
	}
	elseif (isset($this->headerImage))
	{
	    return $this->headerImage;
	}
	else
	{
	    return '&nbsp;';
	}
    }
    
    public function DisplayFooterImage()
    {
	if (is_object($this->footerImage))
	{
	    return $this->footerImage->Display();
	}
	elseif (isset($this->footerImage))
	{
	    return $this->footerImage;
	}
	else
	{
	    return '&nbsp;';
	}
    }
    
    public function DisplayContent()
    {
	if (is_object($this->content))
	{
            $ret = $this->contentOptions . '<br />' . $this->content->Display();
            return $ret;
        }
	elseif (isset($this->content))
	{
	    return $this->content;
        }
        else
        {
            return '&nbsp;';
        }
    }
    
    public function DisplaySideContent()
    {

        if ($this->sideContent != '')
        {
            foreach($this->sideContent as $view)
            {
                return $view->Display();
            }
        }
        else
        {
            return '&nbsp;';
        }
    }
    
    public function DisplaySideBottom()
    {

        if ($this->sideBottom != '')
        {
            foreach($this->sideBottom as $view)
            {
                return $view->Display();
            }
        }
        else
        {
            return '&nbsp;';
        }
    }
    
    public function DisplayTheme()
    {
	if (is_object($this->theme))
	{
            return $this->theme->Display();
	}
	elseif (isset($this->theme))
	{
	    return $this->theme;
        }
	else
	{
	    return '&nbsp;';
	}
    }
}

?>
