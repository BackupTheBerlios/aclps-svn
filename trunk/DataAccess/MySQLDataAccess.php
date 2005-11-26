<?php

require_once 'DataAccess.php';
class DataAccess_MySQLDataAccess implements DataAccess_DataAccess
{
    static protected $DB_SETTINGS = 'Settings/DBSettings.php';

    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPassword;

    private $connections;

    private function __construct($dbHost, $dbName, $dbUser, $dbPassword)
    {
	$this->dbHost = $dbHost;
	$this->dbName = $dbName;
	$this->dbUser = $dbUser;
	$this->dbPassword = $dbPassword;
    }

    public static function GetInstance()
    {
   	if(!isset($_SESSION['DataAccess_MySQLDataAccess']))
	{
	    require self::$DB_SETTINGS;
	    $_SESSION['DataAccess_MySQLDataAccess'] = new DataAccess_MySQLDataAccess($dbHost, $dbName, $dbUser, $dbPassword);
   	}
    
   	return $_SESSION['DataAccess_MySQLDataAccess'];
    }

    public function Select($baseQuery, $arguments)
    {
	$query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
	$connection = $this->GetConnection('Select');
	return $this->Query($connection, $query);
    }

    public function Insert($baseQuery, $arguments)
    {
	$query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
	$connection = $this->GetConnection('Insert');
	return $this->Query($connection, $query);
    }

    public function Update($baseQuery, $arguments)
    {
	$query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
	$connection = $this->GetConnection('Update');
	return $this->Query($connection, $query);
    }

    public function Delete($baseQuery, $arguments)
    {
	$query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
	$connection = $this->GetConnection('Delete');
	return $this->Query($connection, $query);
    }

    private function GetConnection($action)
    {
	//TODO add variations on getting connections by action
	//TODO need error checking

	$connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
		
        if(mysqli_connect_errno())
        {
            throw new Exception(mysqli_connect_error());
        }

	return $connection;
    }

//returns true on success for update, insert, etc.
//returns the results on success for select, etc.
//throws an exception on failure for all
    private function Query($connection, $query)
    {
	$query = $this->InsertSlashes($query);
	print 'QUERY: '.$query.'<br />';
	$queryResult = $connection->query($query);
	
	if(!$queryResult)
	{
	    throw new Exception($connection->error);
	}
	else if(is_bool($queryResult))
	{
	    return true;
	}
	
	$returnResult = array();
	
	while ($row = $queryResult->fetch_assoc())
	{
	    $returnResult[] = $this->RemoveSlashes($row);
	}
	
	return $returnResult;
    }

    private function InsertArgumentsIntoQuery($baseQuery, $arguments)
    {
	$lastindex = count($arguments)-1;

	//check for too few or too many replacement flags in basequery:
	if (!strstr($baseQuery, '[' . $lastindex . ']') or
	    strstr($baseQuery, '[' . ($lastindex+1) . ']'))
	{
	    throw new Exception('Malformed Query');
	}

	//TODO: need to catch exception

	$query = $baseQuery;

	foreach($arguments as $key=>$value)
	{
	    //SANITIZE $value
	    //note: it bitches about being unable to find a mysql to connect to (to call the library function)
	    //in our case, the webserver isnt running one locally, so we use the server that we're getting our data from
	    $connection = mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword);
	    $value = mysql_real_escape_string($value, $connection);
	    $query = str_replace('[' . $key . ']', $value, $query);
	}
	
	return $query;
    }
			
    private function InsertSlashes($query)
    {
	if (!get_magic_quotes_gpc())
	{
	    return addslashes($query);
	}	
	else
	{
	    return $query;
	}
    }

    private function RemoveSlashes($result)
    {
	if (!get_magic_quotes_gpc())
	{
	    foreach($result as $key=>$value)
	    {
		$result[$key] = stripslashes($value);
	    }
		
	    return $result;
	}	
	else
	{
	    return $query;
	}
    }
}
?>
