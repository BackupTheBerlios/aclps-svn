<?php

print_r($_SESSION);

session_start();

//CHANGE '\' to '/'

//If you're getting weird file-not-found errors for a file that was recently moved in the code,
//you need to clear the cs411.beoba.net cookie in your browser. - Nick
function __autoload($className)
{
    $path = str_replace('_', '/', $className);
    require_once("$path.php");
}

$aFrontController = new Presentation_FrontController();

$aFrontController->HandleRequest();

?>
