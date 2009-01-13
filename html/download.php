<?php

require_once 'include/user.php';

$require_login = true;
require_once 'include/set_env.php';


if( !isset($_SESSION['user']) )
{
	if( !isset($_REQUEST['username']) )
	{
		session_destroy();
		die("Username not set, and user object not registered... should not happen. Destroying session");
	}
	else
	{
		$_SESSION['user'] = new User($_REQUEST['username']);
	}
}

global $db;

if(isset($_REQUEST['lib_id']))
{
	$file = $db->getAll("select * from dumb_library where lib_id = ?", array($_REQUEST['lib_id']));
	
	list($temp, $row) = each($file); // One row
	
	header("Content-length: " . $row['size']);
	header("Content-type: " . $row['type']);
	header("Content-Disposition: attachment; filename=" . $row['name']);
	echo $row['content'];
	
	exit;
}

?>