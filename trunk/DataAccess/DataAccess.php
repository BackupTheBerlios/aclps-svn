<?php
abstract class DataAccess_DataAccess
{
    static protected $DB_SETTINGS = '../Settings/DBSettings.php';
    
    abstract public function Select($baseQuery, $arguments);
    abstract public function Insert($baseQuery, $arguments);
    abstract public function Update($baseQuery, $arguments);
    abstract public function Delete($baseQuery, $arguments);
}
?>
