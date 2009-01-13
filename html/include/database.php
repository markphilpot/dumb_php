<?php
// Change these to match database
$dbUser = 'root';
$dbPass = '';
$dbServer = 'localhost';
$dbDatabase = 'griphiam';
$dbType = 'mysql';  ## Valid values: mysql, pgsql, oci8, odbc, sybase, fbsql, ibase, ifx, msql, mssql (These are pear supported dbs)

function get_db_connection()
{
	global $dbUser, $dbPass, $dbServer, $dbDatabase, $dbType;
	
	$database =  DB::connect(array(
    'username' => $dbUser,
    'password' => $dbPass,
    'hostspec' => $dbServer,
    'database' => $dbDatabase,
    'phptype' => $dbType
	));
	
	$database->setFetchMode(DB_FETCHMODE_ASSOC);  // Other modes possible; I find assoc best.
	$database->setOption('optimize', 'portability');
	
	return $database;
}

?>
