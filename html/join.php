<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/join.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='join' order by date desc"));
$t->assign('loc', 'current');
 
$t->assign('sidebar', 'sidebar/current.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='current.php'>Prospective Members</a> > Join the Duke Band");
 
$t->display('main.tpl');
?>