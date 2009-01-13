<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
if(isset($_REQUEST['cal_id']))
{
   $t->assign('which', 'calendar');
   $t->assign('event', $db->getAll("select * from dumb_calendar where calendar_id = ?", array($_REQUEST['cal_id'])));
}
elseif(isset($_REQUEST['sch_id']))
{
   $t->assign('which', 'schedule');
   $t->assign('event', $db->getAll("select * from dumb_schedule, dumb_categories where dumb_schedule.category_id = dumb_categories.category_id and schedule_id = ?", array($_REQUEST['sch_id'])));
}
$t->assign('alt_style', 'true');
$t->display('event.tpl');
?>