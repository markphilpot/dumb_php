<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/about.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='about' order by date desc"));
$t->assign('loc', 'about');
 
$t->assign('sidebar', 'sidebar/about.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > About Us");
 
$t->display('main.tpl');
?>