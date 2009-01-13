<?php

$require_login = false;
require_once 'include/set_env.php';

$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/attendance.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='camp' order by date desc"));
$t->assign('loc', 'home');

$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='current.php'>Current Members</a> > Band Camp");

$t->display('main.tpl');

?>