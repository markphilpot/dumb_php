<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
global $db;

$form_enabled = false;

/*
 * Process incomming
 */

if(isset($_REQUEST['form']))
{
	if($_REQUEST['form'] == "true")
	{
		$db->getAll("update dumb_setup set value = 'true' where parameter = 'tournamentForm'");
	}
	else
	{
		$db->getAll("update dumb_setup set value = 'false' where parameter = 'tournamentForm'");
	}
}

if(isset($_REQUEST['del']))
{
	$db->getAll("delete from dumb_tournament_form");
}

/*
 * Determine if form is enabled
 */

$result = $db->getAll("select * from dumb_setup where parameter = 'tournamentForm'");
list($temp, $row) = each($result); // One row
if($row['value'] == "true")
{
	$form_enabled = true;
}

/*
 * Populate statistics
 */

$total_membership;
$received_entries;

$result = $db->getAll("select count(*) as total from dumb_members");
list($temp, $row) = each($result);
$total_membership = $row['total'];

$result = $db->getAll("select count(*) as total from dumb_tournament_form");
list($temp, $row) = each($result);
$received_entries = $row['total'];

$forms = $db->getAll("select dumb_members.username as Username, dumb_tournament_form.username as formUsername, " .
		"dumb_members.firstname, dumb_members.lastname from dumb_members left join dumb_tournament_form on " .
		"dumb_members.username = dumb_tournament_form.username order by dumb_members.lastname");

?>
<script type="text/javascript">
	function delete_value(loc)
	{
		var c = confirm("Are you sure you want to delete all Tournament Form Entries?"+'\n'+"This action cannot be undone.")
		if( c == true )
		{
			location = loc
		}
	}
</script>
<div id="options">
<h3 class="center">&nbsp;</h3>
<div class="border">
<h4 class="center">Tournament Options</h4>
<p>&#149; <a href="members.php?loc=tournament_form_setup">Tournament Form Setup</a><br />
<?php if($form_enabled) { ?>
   &#149; <a href="members.php?loc=tournament_setup&form=false">Disable Tournament Form</a><br />
<?php } else { ?>
	&#149; <a href="members.php?loc=tournament_setup&form=true">Enable Tournament Form</a><br />
<?php } ?>
   <hr width="75%" size="1" />
   &#149; <a href="members.php?loc=tournament_selection">Generate Selection Sheets</a><br />
   <hr width="75%" size="1" />
   &#149; <a href="javascript:delete_value('members.php?loc=tournament_setup&del=true')">Clear All Form Entries</a><br />
</p>
</div>
<h5>Note: After enabling or disabling the tournament form, you will have to refresh to see
the option in the menu.</h5>
</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Current Tournament Entries and Statistics</h1>
<?php

startTable(0,1,1,"100%");
startRow();
startColumnColSpan(2);
print "<b>Received $received_entries of $total_membership possible entries</b>";
endColumn();
endRow();
startClassRow("table_header");
startHeaderColumn();
print "Awaiting Entries";
nextHeaderColumn();
print "Submitted Entries";
endHeaderColumn();
endRow();

startRow();
startColumnVA("top");
// Awaiting entries
startTable(0,0,0,"100%");
$i = 0;
while(list($temp, $row) = each($forms))
{
	if( $row['formUsername'] == null )
	{
		if($i % 2 == 0)
		{
			startClassRow("table_alt");
		}
		else
		{
			startRow();
		}
		
		startColumn();
		print $row['lastname'] . ", " . $row['firstname'];
		endColumn();
		endRow();
		$i++;
	}
}
endTable();

endColumn();
startColumnVA("top");
// Submitted entries
startTable(0,0,0,"100%");
$i = 0;
reset($forms);
while(list($temp, $row) = each($forms))
{
	if( $row['formUsername'] != null )
	{
		if($i % 2 == 0)
		{
			startClassRow("table_alt");
		}
		else
		{
			startRow();
		}
		
		startColumn();
		print $row['lastname'] . ", " . $row['firstname'];
		endColumn();
		endRow();
		$i++;
	}
}
endTable();

endColumn();
endRow();

endTable();

?>

</div> <!-- end main app -->