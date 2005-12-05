<?php

class Presentation_View_ViewBlogView extends Presentation_View_View
{

    private $theme;
    private $topBar;
    private $headerImage;
    private $footerImage;

    private $title;
    private $rssurl;

    private $blogID;
    private $contentOptions;
    private $content;
    private $sideContent;
    
    public function __construct($blogID, $contentOptionsFlag, $headerImage, $footerImage, $theme, $title, $rssurl)
    {
	$this->blogID = $blogID;
	$this->SetContentOptions($contentOptionsFlag);
	$this->headerImage = $headerImage;
	$this->footerImage = $footerImage;
	$this->theme = $theme;
        $this->title = $title;
        $this->rssurl = $rssurl;
	$this->content = '';
	$this->sideContent = '';
    }

    private function SetContentOptions($flag)
    {
        switch($flag)
        {
        case 3:
            $this->contentOptions = '<div id="blogcontrols">'
                . '<img src="UI/Themes/Images/Controls/home.png" id="controlbarimg" /> '
                . ' <a href="index.php?Action=ViewBlog&blogID=' . $blogID . '">Home</a> | '
                . '<img src="UI/Themes/Images/Controls/newpost.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=NewPost&blogID='.$blogID.'">New Post</a> | '
                . '<img src="UI/Themes/Images/Controls/admin2.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=EditBlogLayout&blogID='.$blogID.'">Blog Appearance</a></a> | 'x
                . '<img src="UI/Themes/Images/Controls/editusers.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=EditMembership&blogID='.$blogID.'">Blog Membership</a>'
                . '</div>';
            break;
            
        case 2:
            $this->contentOptions = '<div id="blogcontrols">'
                . '<img src="UI/Themes/Images/Controls/home.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=ViewBlog&blogID=' . $blogID . '">Home</a> | '
                . '<img src="UI/Themes/Images/Controls/newpost.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=NewPost&blogID=' . $blogID . '">New Post</a> | '
                . '<img src="UI/Themes/Images/Controls/editusers.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=EditMembership&blogID='.$blogID.'">Blog Membership</a>'
                . '</div>';
            break;
            
        case 1:
            $this->contentOptions = '<div id="blogcontrols">'
                . '<img src="UI/Themes/Images/Controls/home.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=ViewBlog&blogID=' . $blogID . '">Home</a> | '
                . '<img src="UI/Themes/Images/Controls/newpost.png" id="controlbarimg" /> '
                . '<a href="index.php?Action=NewPost&blogID=' . $blogID . '">New Post</a>'
                . '</div>';
            break;
            
        case 0:
        default:
            $this->contentOptions = '';
            break;
        }
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
	elseif (isset($this->headerImage) && strlen($this->headerImage) > 0)
	{
	    return '<a href="index.php?Action=ViewBlog&blogID='.$this->blogID.'"><img id="blogheaderimg" src="'.$this->headerImage.'"/></a>';
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
	elseif (isset($this->footerImage) && strlen($this->footerImage) > 0)
	{
	    return '<a href="index.php?Action=ViewBlog&blogID='.$this->blogID.'"><img id="blogfooterimg" src="'.$this->footerImage.'"/></a>';
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
            $ret = $this->contentOptions . $this->content->Display();
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
                $final_view .= $view->Display();
            }
            
            return $final_view;
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

    public function DisplayTitle()
    {
        if (is_object($this->title))
        {
            return $this->title->Display();
        }
        elseif (isset($this->title))
        {
            return $this->title;
        }
        else
        {
            return '';
        }
    }

    public function DisplayRSSURL()
    {
        if (is_object($this->rssurl))
        {
            return $this->rssurl->Display();
        }
        elseif (isset($this->rssurl))
        {
            return $this->rssurl;
        }
        else
        {
            return '';
        }
    }
}

?>
