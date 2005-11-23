<?php

class Presentation_View_ViewBlogView extends Presentation_View_View
{
    private $headerImage;
    private $footerImage;
    private $content;
    private $sideContent;
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
    
    public function SetContent($aContent)
    {
        $this->content = $aContent;
    }
    
    public function SetSideContent($aSideContent)
    {
	$this->content = $aSideContent;
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
            $ret = $contentOptions . '</br>' . $this->content->Display();
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
        if (is_object($this->sideContent))
	{
            return $this->sideContent->Display();
	}
	elseif (isset($this->sideContent))
	{
            return $this->sideContent;
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
