<?php

class DataAccess_DataAccessFactory extends DataAccess_DataAccess
{
    static protected $DB_SETTINGS = 'Settings/DBSettings.php';

    private function __contruct()
    {
	//Do Nothing
    }

    public static function GetInstance()
    {
   	if(!isset($_SESSION['DataAccess_DataAccessFactory']))
	{
        require self::$DB_SETTINGS;
			
	    switch($DB_ENVIRONMENT)
	    {
          case 'MySQL':
		      $_SESSION['DataAccess_DataAccessFactory'] = DataAccess_MySQLDataAccess::GetInstance();
		      break;
          default:
		      throw new Exception('Unknown Database Environment');
		      break;
	    }
   	}

   	    return $_SESSION['DataAccess_DataAccessFactory'];
    }
}
?>
