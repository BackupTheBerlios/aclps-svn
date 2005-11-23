<?php

class Presentation_FrontController
{
    public function HandleRequest()
    {
        if (!isset($_GET['Action']))
        {
            //TODO: CHANGE TO USE RELATIVE PATHS
            header("Location: http://cs411.beoba.net/ACLPS/index.php?Action=ViewBlog&blogID=1");
            exit;
        }

	$aBlog = BusinessLogic_Blog_Blog::GetInstance();
	$result = $aBlog->HandleRequest();

	$aViewer = new Presentation_Viewer();
	$aViewer->Display($result);
    }
}

?>
