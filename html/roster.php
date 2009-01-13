<?php
$require_login = false;
require_once 'include/set_env.php';
$order_by = "dumb_members.lastname,dumb_members.firstname";
if( isset($_REQUEST['orderby']) )
{
	if($_REQUEST['orderby'] == 'firstname')
	{
	   $order_by = "dumb_members.firstname,dumb_members.lastname";
	}
	else if($_REQUEST['orderby'] == 'lastname')
	{
	   $order_by = "dumb_members.lastname,dumb_members.firstname";
	}
	else if($_REQUEST['orderby'] == 'instrument')
	{
	   $order_by = "dumb_members.instrument_id";
	}
	else if($_REQUEST['orderby'] == 'pepband')
	{
	   $order_by = "dumb_members.pepband";
	}
	else if($_REQUEST['orderby'] == 'year')
	{
	   $order_by = "dumb_members.year";
	}
}
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/roster.tpl');
$t->assign('roster', $db->getAll("select * from dumb_members, dumb_instruments where dumb_members.instrument_id = dumb_instruments.instrument_id order by " . $order_by));
$t->assign('loc', 'information');

$t->assign('sidebar', 'sidebar/information.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='dim.php'>Information</a> > Roster");

$t->display('main.tpl');
?>