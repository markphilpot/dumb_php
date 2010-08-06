<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/generic.tpl');
$t->assign('sidebar', 'sidebar/resources.tpl');

$t->assign('front', $db->getAll("select * from dumb_content where loc='academic_help'"));

$t->assign('loc', 'resources');

$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='resources.php'>Member Resources</a> > Academic Help");

$t->display('main.tpl');
?>
