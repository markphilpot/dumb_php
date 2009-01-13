<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/history.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='history' order by date desc"));
$t->assign('loc', 'information');

$t->assign('sidebar', 'sidebar/information.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='dim.php'>Information</a> > History");

$t->display('main.tpl');
?>