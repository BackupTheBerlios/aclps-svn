<?php
session_start();
function __autoload($className)
{
    $path = str_replace('_', '/', $className);
    require_once("$path.php");
}
$aFrontController = new Presentation_FrontController();
$aFrontController->HandleRequest();
?>
