<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/testimonials.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='testimonials' order by date desc"));
$t->assign('loc', 'testimonials');
 
$t->assign('sidebar', 'sidebar/about.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='dim.php'>Information</a> > Testimonials");
 
$t->display('main.tpl');
?>