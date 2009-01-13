<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";

global $db;

if( isset($_REQUEST['edit_cat']) )
{
	$edit_cat = $db->getAll("select * from dumb_sec_categories where category_id = ?", array($_REQUEST['edit_cat']) );
}

if( isset($_REQUEST['update']) )
{
	$db->getAll("update dumb_sec_categories set category_name = ? where category_id = ?", array($_REQUEST['edit_category_name'], $_REQUEST['edit_category_id']));
}

if( isset($_REQUEST['add']) && isset($_REQUEST['new_category']) )
{
	$db->getAll("insert into dumb_sec_categories values (NULL, ?)", array($_REQUEST['new_category']));
}

if( isset($_REQUEST['del_cat']) )
{
	// Find all attendance events within current category
	$entries = $db->getAll("select * from dumb_sec_attendance, dumb_sec_events where " .
			"dumb_sec_events.category_id = ? and " .
			"dumb_sec_events.event_id = dumb_sec_attendance.event_id", array($_REQUEST['del_cat']));
	
	// Delete all records associated with the corresponding event ids
	while( list($temp, $row) = each($entries) )
	{
		$db->getAll("delete from dumb_sec_attendance where event_id = ?", array($row['event_id']));
	}
	
	$db->getAll("delete from dumb_sec_codes where category_id = ?", array($_REQUEST['del_cat']));
	$db->getAll("delete from dumb_sec_events where category_id = ?", array($_REQUEST['del_cat']));
	
	// Finally delete the category
	$db->getAll("delete from dumb_sec_categories where category_id = ?", array($_REQUEST['del_cat']));
}

$categories = $db->getAll("select * from dumb_sec_categories");

?>
<div id="options">
<h3 class="center">&nbsp;</h3>

<div class="border">
<h4 class="center">Add Category</h4>
<form name="attendance" method="post" action="members.php?loc=sec_categories">

  <input type="text" name="new_category" />
  <input type="submit" name="add" value="Add" />

<?php
if( isset($_REQUEST['edit_cat']) )
{
	print "<h4 class='center'>Update Category</h4>";
	list($temp, $row) = each($edit_cat); // One row
	print "<input type='text' name='edit_category_name' value='".$row['category_name']."' />";
	print "<input type='hidden' name='edit_category_id' value='".$row['category_id']."' />";
	print "<input type='submit' name='update' value='Update' />";
} 
?>

</form>
</div>
<h5 class="center"><a href="members.php?loc=attendance">Back</a></h5>
</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Edit Attendance Categories</h3>

<script type="text/javascript">
	function delete_value(loc)
	{
		var c = confirm("Are you sure you want to delete this category?"+'\n'+"This will remove all associated attendance records and codes associated with this category."+'\n'+"This action cannot be undone.")
		if( c == true )
		{
			location = loc
		}
	}
</script>

<?php

startTable(1,1,1,"90%");

startClassRow("table_header");
startHeaderColumn();
print "Category Name";
nextHeaderColumn();
print "&nbsp;";
endHeaderColumn();

$i = 0;

while( list($temp, $row) = each($categories) )
{
	if( $i % 2 == 0 )
	{
		startRow();
	}
	else
	{
		startClassRow("table_alt");
	}
	
	startColumn();
	print $row['category_name'];
	nextColumn();
	print "<a href='members.php?loc=sec_categories&edit_cat=".$row['category_id']."'>Edit</a> - <a href=\"javascript:delete_value('members.php?loc=sec_categories&del_cat=".$row['category_id']."')\">Remove</a>";
	endColumn();
	endRow();
	
}

endTable();

?>

</div> <!-- end main app -->