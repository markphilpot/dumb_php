<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/generic.tpl');
$t->assign('sidebar', 'sidebar/leadership.tpl');

$t->assign('front', $db->getAll("select * from dumb_content where loc='sectionleaders'"));

$t->assign('loc', 'leadership');

$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='leadership.php'>Leadership</a> > Section Leaders");

$t->display('main.tpl');
?>