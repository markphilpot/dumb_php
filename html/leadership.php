<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/director.tpl');
$t->assign('sidebar', 'sidebar/leadership.tpl');

$t->assign('front', $db->getAll("select * from dumb_content where loc='director' order by date desc"));
$t->assign('loc', 'director');

$t->assign('breadcrumb', "<a href='index.php'>Home</a> > Leadership");

$t->display('main.tpl');
?>