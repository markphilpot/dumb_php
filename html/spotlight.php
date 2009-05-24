<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/spotlight.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='spotlight' order by date desc"));
$t->assign('loc', 'spotlight');
 
$t->assign('sidebar', 'sidebar/about.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='about.php'>About Us</a> > Member Spotlight");
 
$t->display('main.tpl');
?>