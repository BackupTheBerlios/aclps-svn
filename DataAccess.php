<?php
require_once("Configuration.php");

class DA extends Conf{
    private $link;
    private $result;
    private $lock;
////////////////////////////////////////////////////////////////////////////
    
    public function query($queries=""){
        $method = strtok($queries, " \n\t");
        if(($method == 'update')||($method == 'insert'))
            $this->lock = 1;
        else if($method == 'select')
            $this->lock = 0;
        else
            $this->lock = -1;
        $this->db_connect();
        $this->db_select();
        $this->db_execute($queries);
        mysql_close($this->link);
    }
    
    public function get(){
        if($this->lock == 1)
            return $this->result;
        else if($this->lock == 0){
            return mysql_fetch_array($this->result, MYSQL_BOTH);
        }
        else
            return -1;
    }

    private function db_connect(){
        $this->link = mysql_connect($this->db_host, $this->db_user,
        $this->db_pass, true) or die('Could not connect: ' . mysql_error());
    }
    
    private function db_select(){
        (mysql_select_db("$this->db_name", $this->link)
        or die ("Can\'t use $this->db_name : " . mysql_error()));
    }
    
    private function db_execute($Q){
        $this->result = mysql_query($Q) or die('Query failed: ' . mysql_error());
    }
}
?>
