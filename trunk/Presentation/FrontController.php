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
	$result = $aBlog->HandleRequest();

	$aViewer = new Presentation_Viewer();
	$aViewer->Display($result);
    }
}

?>
