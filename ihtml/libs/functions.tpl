<div id="navcontainer">
<ul id="navlist">
{php}

function is_active($this_loc)
{
	global $t;
	if( $t->get_template_vars('loc') == $this_loc )
	{
		return " id ='active'";
	}
	else
	{
		return "";
	}
}

global $t;

print "<li" . is_active('index') ."><a href='members.php?loc=index'>Home</a></li>";
print "<li" . is_active('my_profile') ."><a href='members.php?loc=my_profile'>My Profile</a></li>";
print "<li" . is_active('library') ."><a href='members.php?loc=library'>Library</a></li>";
print "<li" . is_active('view_attendance') ."><a href='members.php?loc=view_attendance'>View Attendance</a></li>";

if( $_SESSION['user']->has_priv("attendance") )
{
   print "<li" . is_active('attendance') ."><a href='members.php?loc=attendance'>Attendance</a></li>";
}

if( $_SESSION['user']->has_priv("roster") )
{
   print "<li" . is_active('roster') ."><a href='members.php?loc=roster'>Roster</a></li>";
}

if( $_SESSION['user']->has_priv("schedule") )
{
   print "<li" . is_active('schedule') ."><a href='members.php?loc=schedule'>Schedule</a></li>";
}

if( $t->get_template_vars('tournament_form_enabled') ) // Different retrieve
{
   print "<li" . is_active('tournament_form') ."><a href='members.php?loc=tournament_form'>Tournament Form</a></li>";
}

if( $_SESSION['user']->has_priv("tournament") )
{
   print "<li" . is_active('tournament') ."><a href='members.php?loc=tournament_setup'>Tournament</a></li>";
}

if( $_SESSION['user']->has_priv("content") )
{
   print "<li" . is_active('content') ."><a href='members.php?loc=content'>Content</a></li>";
}

if( $_SESSION['user']->has_priv("calendar") )
{
   print "<li" . is_active('calendar') ."><a href='members.php?loc=calendar'>Calendar</a></li>";
}

if( $_SESSION['user']->has_priv("roster") )
{
   print "<li" . is_active('recruitment') ."><a href='members.php?loc=recruitment'>Recruitment</a></li>";
}

//print "<li" . is_active('help') ."><a href='members.php?loc=help'>Help</a></li>";

if( $_SESSION['user']->has_priv("admin") )
{
   print "<li" . is_active('admin') ."><a href='members.php?loc=setup'>Setup</a></li>";
}

print "<li" . is_active('logout') ."><a href='members.php?logout=true'>Logout</a></li>";
{/php}
</ul>
</div> <!-- End navcontainer -->