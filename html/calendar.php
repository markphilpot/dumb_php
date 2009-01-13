<?php
$require_login = false;
require_once 'include/set_env.php';
require_once 'Date/Date.php';
$now = new Date();
$month = $now->month;
$year = $now->year;
if( isset($_REQUEST['month']) && isset($_REQUEST['year']) )
{
	if( $_REQUEST['month'] > 0 && $_REQUEST['month'] < 13 )
	{
		$month = $_REQUEST['month'];
	}
	if( $_REQUEST['year'] > 1970 && $_REQUEST['year'] < 3000 )
	{
		// Just to make sure we are in the ballpark
		$year = $_REQUEST['year'];
	}
}
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/calendar.tpl');
$t->assign('month', $month);
$t->assign('year', $year);
$events = array();
$calendar_events = $db->getAll('select * from dumb_calendar order by date');
$schedule_events = $db->getAll('select schedule_id, name, opponent, location, date from dumb_schedule, dumb_categories where dumb_schedule.category_id = dumb_categories.category_id');
while( list($temp, $cal) = each($calendar_events))
{
	if( array_key_exists($cal['date'], $events) )
	{
		// Append
		array_push( $events[ $cal['date'] ], array( 'calendar_id'=>$cal['calendar_id'],
																  'title'=>$cal['title']) );
	}
	else
	{
		// Add new
		$events[ $cal['date'] ] = array();
		array_push( $events[ $cal['date'] ], array( 'calendar_id'=>$cal['calendar_id'],
																  'title'=>$cal['title']) );
	}
}
while( list($temp, $sch) = each($schedule_events))
{
	if( array_key_exists($sch['date'], $events) )
	{
		// Append
		array_push( $events[ $sch['date'] ], array( 'schedule_id'=>$sch['schedule_id'],
																  'category'=>$sch['name'],
																  'opponent'=>$sch['opponent']) );
	}
	else
	{
		// Add new
		$events[ $sch['date'] ] = array();
		array_push( $events[ $sch['date'] ], array( 'schedule_id'=>$sch['schedule_id'],
																  'category'=>$sch['name'],
																  'opponent'=>$sch['opponent']) );
	}
}
$t->assign('events', $events);
$t->assign('loc', 'calendar');
$t->assign('sidebar', 'sidebar/calendar.tpl');

$t->assign('breadcrumb', "<a href='index.php'>Home</a> > Calendar");

$t->display('main.tpl');
?>