<?php

    session_start();

    //CHANGE '\' to '/'

    function __autoload($className)
    {
    	$path = str_replace('_', '/', $className);
    	require_once("$path.php");
    }

    $aFrontController = new Presentation_FrontController();

    $aFrontController->HandleRequest();

?>
