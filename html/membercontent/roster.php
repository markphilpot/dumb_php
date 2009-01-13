<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";

global $db;

$instrument = "all";
$orderby = "lastname";
$desc = "";

if( isset($_REQUEST['instrument']) )
{
	if( $_REQUEST['instrument'] != "null" )
	{
		$instrument = $_REQUEST['instrument'];
	}
}

if( isset($_REQUEST['orderby']) )
{
	if( $_REQUEST['orderby'] != "null" )
	{
		$orderby = $_REQUEST['orderby'];
	}
}

if( isset($_REQUEST['desc']) )
{
	if( $_REQUEST['desc'] != "null" )
	{
		$desc = " desc";
	}
}

// Adding user
if( isset($_REQUEST['add']) && isset($_REQUEST['username']) )
{
	$result = $db->getAll("insert into dumb_members set username = ?, password=md5('dumb@123')", array($_REQUEST['username']));
}

// Deleting user
if( isset($_REQUEST['user']) )
{
	$result = $db->getAll("delete from dumb_members where username = ?", array($_REQUEST['user']));
	$result = $db->getAll("delete from dumb_priv where username = ?", array($_REQUEST['user']));
	$result = $db->getAll("delete from dumb_sec_attendance where username = ?", array($_REQUEST['user']));
	$result = $db->getAll("delete from dumb_officer_list where username = ?", array($_REQUEST['user']));
}
?>

<div id="options">
<h3 class="center">Roster Management</h3>
<div class="border">
<h4 class="center">View by Instrument</h4>
<form name="view" method="post" action="members.php?loc=roster&fcn=view">
		<?php displayInstrumentSelect() ?>
		<input type="submit" name="Go" value="Go">
</form>
</div>
<div class="border">
<h4 class="center">Add Member</h4>
<form name="add_user" method="post" action="members.php?loc=roster">
	<p>Username: 
		<input type="text" name="username" size="8" maxlength="8">
		<input type="submit" name="add" value="Add">
	</p>
</form>
</div>

</div> <!-- end options -->

<div id="main_app">

<h3 class="center">View Roster</h3>
<script type="text/javascript">
function delete_value(loc)
{
	var c = confirm("Are you sure you want to delete this user?"+'\n'+"This action cannot be undone.")
	if( c == true )
	{
		location = loc
	}
}
</script>
	
<?php
	global $db;
	$result;
	
	if( $instrument != "all" )
	{
		$result = $db->getAll("select * from dumb_members, dumb_instruments where dumb_members.instrument_id = dumb_instruments.instrument_id and dumb_members.instrument_id = ? order by ".$orderby.$desc, array($instrument) );
	}
	else
	{
		$result = $db->getAll("select * from dumb_members, dumb_instruments where dumb_members.instrument_id = dumb_instruments.instrument_id order by ".$orderby.$desc);
	}
	
	if($desc == " desc")
	{
		$desc = "";
	}
	else
	{
		$desc = "&desc=1";
	}
	
	startTable(2, 0, 1, "100%");
	startClassRow("table_header");
	startHeaderColumn();
	print "<a href='members.php?loc=roster&instrument=$instrument&orderby=username$desc'>Username</a>";
	nextHeaderColumn();
	print "<a href='members.php?loc=roster&instrument=$instrument&orderby=firstname$desc'>First Name</a>";
	nextHeaderColumn();
	print "<a href='members.php?loc=roster&instrument=$instrument&orderby=lastname$desc'>Last Name</a>";
	nextHeaderColumn();
	print "<a href='members.php?loc=roster&instrument=$instrument&orderby=dumb_members.instrument_id$desc'>Instrument</a>";
	nextHeaderColumn();
	print "<a href='members.php?loc=roster&instrument=$instrument&orderby=year$desc'>Year</a>";
	nextHeaderColumn();
	print "&nbsp;";
	endHeaderColumn();	
	endRow();
	
	$i=0;
	while( list($temp, $row) = each($result) )
	{
		if( $i % 2 == 0 )
		{
			startRow();
		}
		else
		{
			startClassRow("table_alt");
		}
		
		$i++;
		
		startColumn();
		print $row['username'];
		nextColumn();
		print $row['firstname'];
		nextColumn();
		print $row['lastname'];
		nextColumn();
		print $row['instrument'];
		nextColumn();
		print $row['year'];
		nextColumn();
		print "<a href='members.php?loc=edit_member&username=".$row['username']."'>Edit</a> - <a href=\"javascript:delete_value('members.php?loc=roster&user=".$row['username']."')\">Remove</a>";
		endColumn();
		endRow();
	}
	
	endTable();
	
?>

</div> <!-- end main_app -->
