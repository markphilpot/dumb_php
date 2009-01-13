<?php
$require_login = false;
require_once 'include/set_env.php';
$category = "1";
if( isset($_REQUEST['cat']) )
{
	$category = $_REQUEST['cat'];
}
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/schedule.tpl');
$t->assign('schedule', $db->getAll('select * from dumb_schedule, dumb_categories where dumb_schedule.category_id = dumb_categories.category_id and dumb_schedule.category_id = ? order by dumb_schedule.date',
									array($category)));
$t->assign('category', $db->getAll('select * from dumb_categories where category_id = ?',
									array($category)));
$t->assign('loc', 'calendar');

$result = $db->getAll('select * from dumb_categories where category_id = ?', array($category));
list($tmp, $row) = each($result);

$t->assign('sidebar', 'sidebar/calendar.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='calendar.php'>Calendar</a> > ".$row['name']);

$t->display('main.tpl');
?>