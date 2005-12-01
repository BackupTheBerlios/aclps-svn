<?php

class Presentation_FrontController
{
    public function HandleRequest()
    {
        if (!isset($_GET['Action']))
        {
            $path = $_SERVER['DIRECTORY_ROOT'] . 'index.php?Action=ViewBlog&blogID=1';
            header("Location: $path");
            exit;
        }

	$aBlog = BusinessLogic_Blog_Blog::GetInstance();
	
	try
    {
	   $view = $aBlog->HandleRequest();
    }
    catch (Exception $e)
    {
        echo 'An error occurred processing your request.<br /><b>Error Message:</b> ' . $e->getMessage();
        if ($_GET['debug'])
        {
            echo $e->getTraceAsString();
        }
        exit;
    }
    
	
	$aViewer = new Presentation_Viewer();
	$aViewer->Display($view);
    }
}

?>
