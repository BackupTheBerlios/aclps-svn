<?php

require_once 'DataAccess.php';
class DataAccess_MySQLDataAccess extends DataAccess_DataAccess
{

    static protected $DB_SETTINGS = 'Settings/DBSettings.php';

  	private $dbHost;
  	private $dbName;
  	private $dbUser;
  	private $dbPassword;

	private $connections;

  	function __construct($dbHost, $dbName, $dbUser, $dbPassword)
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
		//TODO
	}

  	public function Update($baseQuery, $arguments)
	{
		//TODO
	}

  	public function Delete($baseQuery, $arguments)
	{
		//TODO
	}

	private function GetConnection($action)
	{
		//TODO add variations on getting connections by action
		//TODO need error checking

		$connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
		
        if (mysqli_connect_errno())
        {
            throw new Exception(mysqli_connect_error());
        }

		return $connection;
	}

	private function Query($connection, $query)
	{
		$query = $this->InsertSlashes($query);
		$queryResult = $connection->query($query);

		//TODO check for query failure
		//TODO check for success but no result (i.e. update, etc.)

		$returnResult = array();

		while ($row = $queryResult->fetch_assoc())
		{
			$returnResult[] = $this->RemoveSlashes($row);
		}

		return $returnResult;
	}

	private function InsertArgumentsIntoQuery($baseQuery, $arguments)
	{
		$size = count($arguments) - 1;

		if (!strstr($baseQuery, '[' . $size . ']') or strstr($baseQuery, '[' . ($size + 1) . ']'))
		{
			throw new Exception('Malformed Query');
		}

		//TODO: need to catch exception

		$query = $baseQuery;

		foreach($arguments as $key=>$value)
		{
			//TODO: NEED TO SANITIZE $value
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
