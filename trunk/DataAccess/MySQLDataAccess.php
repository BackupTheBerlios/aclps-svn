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
        $connection = $this->GetConnection('Select');
        $query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
        $result = $this->Query($connection, $query);
        
        $returnResult = array();
        while ($row = $result->fetch_assoc())
        {
            $returnResult[] = $this->RemoveSlashes($row);
        }
        
        return $returnResult;
    }

    public function Insert($baseQuery, $arguments)
    {
        $connection = $this->GetConnection('Insert');
        $query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
        return $this->Query($connection, $query);
    }

    public function Update($baseQuery, $arguments)
    {
        $connection = $this->GetConnection('Update');
        $query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
        return $this->Query($connection, $query);
    }

    public function Delete($baseQuery, $arguments)
    {
        $connection = $this->GetConnection('Delete');
        $query = $this->InsertArgumentsIntoQuery($baseQuery, $arguments);
        return $this->Query($connection, $query);
    }

    private function GetConnection($action)
    {
	$connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
		
        if(mysqli_connect_errno())
        {
            throw new Exception(mysqli_connect_error());
        }

	return $connection;
    }

    private function Query($connection, $query)
    {
        print 'DBQUERY: '.$query.'<br />';//TODO: remove debug info when done
        $queryResult = $connection->query($query);
	
        if(!$queryResult)
        {
            throw new Exception($connection->error);
        }

        return $queryResult;
    }

    private function InsertArgumentsIntoQuery($baseQuery, $arguments)
    {
        //if no arguments, assume there's nothing to insert
        if (count($arguments) < 1)
        {
            return $baseQuery;
        }

        $lastindex = count($arguments)-1;
        
        //check for too few or too many replacement flags in basequery:
        if (!strstr($baseQuery, '[' . $lastindex . ']') or
            strstr($baseQuery, '[' . ($lastindex+1) . ']'))
        {
            throw new Exception('Malformed Query');
        }
        
    	$query = $baseQuery;
        
        foreach($arguments as $key=>$value)
        {
            $value = DataAccess_InputSanitizer::SanitizeInput($value);
            $value = $this->InsertSlashes($value);
    	    $query = str_replace('[' . $key . ']', $value, $query);
    	}
	
    	return $query;
    }
			
    private function InsertSlashes($value)
    {
        if (!get_magic_quotes_gpc())
        {
            return addslashes($value);
    	}
    	else
    	{
    	    return $value;
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
