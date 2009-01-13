<?php
$require_login = false;
require_once 'include/set_env.php';
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/leadership.tpl');
$t->assign('sidebar', 'sidebar/leadership.tpl');

$t->assign('director', $db->getAll("select * from dumb_content where loc='director' order by date desc"));
$t->assign('officers', $db->getAll("select * from dumb_members, dumb_officers, dumb_officer_list where dumb_members.username = dumb_officer_list.username and dumb_officer_list.officer_id = dumb_officers.officer_id and dumb_officers.officer_id != 5 and dumb_officers.officer_id != 6 order by dumb_officers.officer_id"));
$t->assign('drum_majors', $db->getAll("select * from dumb_members, dumb_officers, dumb_officer_list where dumb_members.username = dumb_officer_list.username and dumb_officer_list.officer_id = dumb_officers.officer_id and dumb_officers.officer_id = 5"));
$t->assign('section_leaders', $db->getAll("select * from dumb_members, dumb_officers, dumb_officer_list where dumb_members.username = dumb_officer_list.username and dumb_officer_list.officer_id = dumb_officers.officer_id and dumb_officers.officer_id = 6"));

$t->assign('loc', 'leadership');

$t->assign('breadcrumb', "<a href='index.php'>Home</a> > Leadership");

$t->display('main.tpl');
?>