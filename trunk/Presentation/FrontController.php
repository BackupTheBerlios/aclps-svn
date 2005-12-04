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
        elseif ($_GET['Action'] == 'ViewRSS' && isset($_GET['blogID']))
        {
            //this is all the way up here because it is being viewed outside of the normal blog html
            //might think of it this way:
            //we're looking at the blog in an entirely different mode than the "default" of loading the page in a browser,
            //so it'd make sense for the front controller to call a different display
            $rssView = BusinessLogic_Blog_Blog::GetInstance()->ViewRSS($_GET['blogID']);
            header('Content-Type: application/xml');
            print $rssView->Display();
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
