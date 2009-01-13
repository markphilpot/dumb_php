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

$app = 'index';
if( isset($_GET['loc']) )
{
	$app = $_GET['loc'];
}

$form_enabled = false;
$result = $db->getAll("select * from dumb_setup where parameter = 'tournamentForm'");
list($temp, $row) = each($result); // One row
if($row['value'] == "true")
{
	$form_enabled = true;
}

$t->assign('title', 'DUMB Members');
$t->assign('app', 'membercontent/'.$app.'.php');
$t->assign('loc', $app);
$t->assign('tournament_form_enabled', $form_enabled);
$t->assign('alt_style', 'true');
$t->display('members.tpl');

?>
