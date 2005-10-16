DataAccess Usage
--------------------------------
The basic structure:


class DA extends Conf{
    private $link;
    private $result;
    private $lock;
    
    public function query($queries="");   //input a query, only allow "select", "update", "insert into"
    public function get();                //return the query results;
                                          //return true on success or false on failure when the query is "insert into" or "update"
                                          //return false on failure or a row of data each time when the function is called, when the query is "select"

    private function db_connect();
    private function db_select();
    private function db_execute($Q);
}

Example for retriving the data:

<?php
require_once("DataAccess.php");

$Q = "select * from Users";
$Data = new DA();
$Data->query($Q);
while($S = $Data->get())
  echo $S['Username'].$S['Password'].$S['Email'];
?>

