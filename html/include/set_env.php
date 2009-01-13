<?php
/**
 * set_env.php 
 * @author Tom Anderson <toma@etree.org>
 * @version 2.5
 *
 * This script sets up smarty, pear, and all other
 * global settings for a php application.
 */

// Custom Error Handling
require_once 'include/error.php';

// Client specific variables
set_error_handler("ErrorHandler");
error_reporting(E_ALL ^ E_NOTICE);

$EMAIL = "foo@bar.net";

require_once 'include/database.php';

// Setup include path
$web_root = 'http://localhost/dumb/dumbPHP/html/';
$app_root = '..';
# note:  sometimes pear is installed at /usr/local/lib/php/ but I 
# recommend getting a copy that is local to your application so 
# you can more easily controll when pear code is updated.
$pear = $app_root . '/pear';
$smarty = $app_root . '/smarty';
//---------------------------------------
//--- end user edit

// Path to .ihtml (template) files
define('TEMPLATE_DIR', "$app_root/ihtml/");
$delim = (PHP_OS == "WIN32" || PHP_OS == "WINNT") ? ';': ':';
//ini_set('include_path', ".{$delim}$pear{$delim}$app_root/include{$delim}$app_root{$delim}$smarty");

set_include_path( '.' . PATH_SEPARATOR . 
                               $pear . PATH_SEPARATOR . 
                               $pear . '/DB' . PATH_SEPARATOR .
                               $pear . '/Date' . PATH_SEPARATOR .
                               $app_root . '/include' . PATH_SEPARATOR . 
                               $app_root . '/membercontent' . PATH_SEPARATOR .
                               $smarty );

// Set magic quotes based on database type.  If using either of these and using odbc, 
// you will need to set this by hand.  
// You should be able to safely comment this out if your system is setup right. 
//ini_set('magic_quotes_sybase', ($dbType == 'mssql' || $dbType == 'sybase') ? '1': '0');

// Include pear database handler, auth object (remove if not used), and smarty
require_once 'DB/DB.php';
require_once 'Auth/Auth.php';
require_once 'Smarty.class.php';

// Change error handling as necessary
// PEAR_ERROR_RETURN, PEAR_ERROR_PRINT, PEAR_ERROR_TRIGGER, PEAR_ERROR_DIE or PEAR_ERROR_CALLBACK
// PEAR::setErrorHandling(PEAR_ERROR_PRINT);
PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'errhndl');

function errhndl ($err) {
    echo '<pre>' . $err->message;
    print_r($err);
    die();
} 

// Connect to the database and set fetch mode as necessary.  Include db extension as needed
// The next two lines can be safely commented if your system is setup right.
$extension = "php_$dbType" . (strpos(PHP_OS, "WIN") >= 0 ? ".dll" : ".so");
if (!function_exists($dbType . '_connect')) dl($extension);

// Connect to the database
$db = DB::connect(array(
    'username' => $dbUser,
    'password' => $dbPass,
    'hostspec' => $dbServer,
    'database' => $dbDatabase,
    'phptype' => $dbType
));

$db->setFetchMode(DB_FETCHMODE_ASSOC);  // Other modes possible; I find assoc best.
$db->setOption('optimize', 'portability');  // This is really useful for me personally but may not be for everyone.

// Setup template object - NOTE:  in this example, 'smarty' is a symlink to 
// the smarty directory.  This allows you to upgrade Smarty without changing code.
$t = new smarty;
$t->template_dir = TEMPLATE_DIR;
// For other compile and cache directory options, see the comment by Pablo Veliz at the bottom of this article.
$t->compile_dir = $app_root . '/compile';
$t->cache_dir = $app_root . '/cache';
// Because you should never touch smarty files, store your custom smarty functions, modifiers, etc. in /include
$t->plugins_dir = array($app_root . '/include', $app_root . '/smarty/plugins');

// Change comment on these when you're done developing to improve performance
$t->force_compile = true;
//$t->caching = true;

## GLOBALS:  $db, $t
session_start();

// Add user authentication here if you want
// This example uses pear::Auth
//$dns = "mysql://".$dbUser.":".$dbPass."@".$dbServer."/".$dbDatabase;
$dns = "mysql://".$dbUser."@".$dbServer."/".$dbDatabase;

$auth = new Auth('DB', array(
		'dsn' => $dns,
		'table' => 'dumb_members',
		'usernamecol' => 'username',
		'passwordcol' => 'password' ),
	'login_function',
	$require_login
);

$admin_login = false;

function login_function()
{
	global $auth, $db, $admin_login;
	
	$status = '';
	
	if( $auth->getStatus() == AUTH_WRONG_LOGIN )
	{
		$status = "Invalid username/password";
	}
	
	if( isset($_REQUEST['username']) && isset($_REQUEST['password']) )
	{
		// Check dumb_admin table for admin/director user
		$statement = $db->prepare("select * from dumb_admin where username = ? and password = md5(?)");
		$result = $db->execute($statement, array($_REQUEST['username'], $_REQUEST['password']));
		
		if( $result->numRows() == 1 )
		{
			// Admin or director login
			$_SESSION['user'] = new User($_REQUEST['username']);
			$admin_login = true;
		}
	}
	else if( isset($_SESSION['user']) )
	{
		$admin_login = true;
	}
	
	if( !$admin_login )
	{
		include('include/login.php');
	}
	
}

// Don't make auth reconnect (this is a shortcoming in pear::Auth)
$auth->storage->db &= $db;

// Check for logout
if ($_REQUEST['logout'])
{
	$auth->start();
	$auth->logout();
	session_destroy();
	header("Location: members.php");
	exit();
}

// Init the session for this user and authorize.
$auth->start();

if ( !$auth->getAuth() && $require_login && !$admin_login )
{
	// Have printed login form above
	die();
}

// Assign any global smarty values here.
$t->assign('web_root', $web_root);

// Strip slashes from the REQUEST array

if( !empty($_REQUEST) )
{
	foreach($_REQUEST as $x => $y)
	{
		if( !is_array($_REQUEST[$x]) )
		{
			$_REQUEST[$x] = stripslashes($y);
		}
	}
}
?>