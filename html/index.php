<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/index.tpl');
$t->assign('sidebar', 'sidebar/index.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='front' order by date desc"));
$t->assign('news', $db->getAll("select * from dumb_content where loc='news' order by date desc"));

$t->assign('upcoming', $db->getAll("select * from dumb_calendar where date > NOW() order by date limit 4"));
$t->assign('events', $db->getAll("select * from dumb_schedule, dumb_categories where dumb_schedule.category_id = dumb_categories.category_id and dumb_schedule.date > NOW() order by dumb_schedule.date limit 4"));

$t->assign('breadcrumb', "Home");

$t->assign('loc', 'home');
$t->display('main.tpl');
?>
