<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/freshmen.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='welcome' order by date desc"));
$t->assign('loc', 'freshmen');

$t->assign('sidebar', 'sidebar/freshmen.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > Admitted Freshmen");

$t->display('main.tpl');
?>