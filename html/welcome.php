<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/welcome.tpl');
$t->assign('front', $db->getAll("select * from dumb_content where loc='welcome' order by date desc"));
$t->display('main.tpl');
?>