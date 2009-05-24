<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/movein.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='movein' order by date desc"));
$t->assign('loc', 'movein');
 
$t->assign('sidebar', 'sidebar/current.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='current.php'>Prospective Members</a> > Move-In Information");
 
$t->display('main.tpl');
?>