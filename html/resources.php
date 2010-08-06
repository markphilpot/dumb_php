<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/resources.tpl');
$t->assign('sidebar', 'sidebar/resources.tpl');

$t->assign('front', $db->getAll("select * from dumb_content where loc='member_resources' order by date desc"));
$t->assign('loc', 'member_resources');

$t->assign('breadcrumb', "<a href='index.php'>Home</a> > Member Resources");

$t->display('main.tpl');
?>
