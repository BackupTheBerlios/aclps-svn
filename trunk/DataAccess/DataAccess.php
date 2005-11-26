<?php
interface DataAccess_DataAccess
{   
    public function Select($baseQuery, $arguments);
    public function Insert($baseQuery, $arguments);
    public function Update($baseQuery, $arguments);
    public function Delete($baseQuery, $arguments);
}
?>
