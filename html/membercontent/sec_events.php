<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";
require_once 'Date/Date.php';

global $db;

$cat = 1;

if( isset($_REQUEST['cat']) )
{
	$cat = $_REQUEST['cat'];
}

/*
 * Process DB Requests
 */

if( isset($_REQUEST['update']) )
{
	$date = $_REQUEST['up_event_date']['Y'] .'-'. $_REQUEST['up_event_date']['m'] .'-'. $_REQUEST['up_event_date']['d'];
	$db->getAll("update dumb_sec_events set event_name = ?, date = ?, category_id = ? where event_id = ?", array($_REQUEST['up_event_name'], $date, $_REQUEST['up_event_category'], $_REQUEST['up_event_id']));
}

if( isset($_REQUEST['add']) && isset($_REQUEST['event_name']) )
{
	$date = $_REQUEST['event_date']['Y'] .'-'. $_REQUEST['event_date']['m'] .'-'. $_REQUEST['event_date']['d'];
	$db->getAll("insert into dumb_sec_events values (NULL, ?, ?, ?)", array($_REQUEST['event_category'], $_REQUEST['event_name'], $date));
}

if( isset($_REQUEST['del_event']) )
{
	$db->getAll("delete from dumb_sec_attendance where event_id = ?", array($_REQUEST['del_event']));
	$db->getAll("delete from dumb_sec_events where event_id = ?", array($_REQUEST['del_event']));
}

if( isset($_REQUEST['del_all']))
{
	$events = $db->getAll("select * from dumb_sec_events where category_id = ?", array($_REQUEST['del_all']));
	while( list($temp, $row) = each($events) )
	{
		$db->getAll("delete from dumb_sec_attendance where event_id = ?", array($row['event_id']));
	}
	$db->getAll("delete from dumb_sec_events where category_id = ?", array($_REQUEST['del_all']));
}

/*
 * Populate general use arrays
 */

$categories = $db->getAll("select * from dumb_sec_categories");
$events = $db->getAll("select * from dumb_sec_events where category_id = ? order by date", array($cat));

/*
 * Create Add Form
 */
 
$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=sec_events');

$r =& new HTML_QuickForm_Renderer_QuickHtml();

$date_defaults = array( 'd' => date('d'), 'm' => date('m'), 'Y' => date('Y'));
$form->setDefaults(array('event_date' => $date_defaults));
// Make Elements
$form->addElement('header', '', 'Add Event');
$form->addElement('date', 'event_date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2003, 'maxYear'=>2010));
$form->addElement('text', 'event_name', 'Name:');
$entry_cats = array();
while( list($temp, $row) = each($categories) )
{
	$entry_cats[ $row['category_id'] ] = $row['category_name'];
}
$form->addElement('select', 'event_category', 'Category:', $entry_cats);
$form->addElement('submit', 'add', 'Add');

$form->accept($r);

$data = '<h4 class="center">Add Event</h4>' .
		'<b>Category:</b><br /> '.$r->elementToHtml('event_category').'<br />' .
				'<b>Name:</b><br /> '.$r->elementToHtml('event_name').'<br />' .
						'<b>Date:</b><br /> '.$r->elementToHtml('event_date');

/*
 * Create Update Form (if requested)
 */
$up_form =& new HTML_QuickForm('updateForm', 'POST', 'members.php?loc=sec_events');

$up_r =& new HTML_QuickForm_Renderer_QuickHtml();

$up_data;

if( isset($_REQUEST['edit_event']) )
{
	$edit_event = $db->getAll("select * from dumb_sec_events where event_id = ?", array($_REQUEST['edit_event']));
	while( list($temp, $row) = each($edit_event) ) // One row
	{
		$date = expandDate($row['date']);
		$date_defaults = array( 'd' => $date['Day'], 'm' => $date['Month'], 'Y' => $date['Year'] );
		$form_defaults = array( 'up_event_name' => $row['event_name'],
										'up_event_category' => $row['category_id'],
										'up_event_id' => $row['event_id'],
										'up_event_date' => $date_defaults);
		
		// Make Elements
		$up_form->addElement('header', '', 'Add Event');
		$up_form->addElement('hidden', 'up_event_id', 'event_id');
		$up_form->addElement('date', 'up_event_date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2003, 'maxYear'=>2010));
		$up_form->addElement('text', 'up_event_name', 'Name:');
		$entry_cats = array();
		reset($categories);
		while( list($temp, $row) = each($categories) )
		{
			$entry_cats[ $row['category_id'] ] = $row['category_name'];
		}
		$up_form->addElement('select', 'up_event_category', 'Category:', $entry_cats);
		$up_form->addElement('submit', 'update', 'Update');
		$up_form->setDefaults($form_defaults);
		$up_form->accept($up_r);
		
		$up_data = '<h4 class="center">Update Event</h4>' . $up_r->elementToHtml('up_event_id') .
				'<b>Category:</b><br /> '.$up_r->elementToHtml('up_event_category').'<br />' .
						'<b>Name:</b><br /> '.$up_r->elementToHtml('up_event_name').'<br />' .
								'<b>Date:</b><br /> '.$up_r->elementToHtml('up_event_date');
	}
}

?>
<div id="options">
<h3 class="center">&nbsp;</h3>
<form name="sec_events" method="post" action="members.php?loc=sec_events">
<div class="border">
<h4 class="center">Select Category</h4>
<select name="cat">
<?php
reset($categories);
while( list($temp, $row) = each($categories) )
{
	if( $row['category_id'] == $cat )
	{
		print "<option value='".$row['category_id']."' selected>".$row['category_name']."</option>\n";
	}
	else
	{
		print "<option value='".$row['category_id']."'>".$row['category_name']."</option>\n";
	}
}
?>
</select>
  <input type="submit" name="view" value="Go" />
</div>
</form>
<div class="border">
<?php
echo $r->toHtml($data);
?>
</div>
<?php
if( isset($_REQUEST['edit_event']) )
{
	print "<div class='border'>";
	print $up_r->toHtml($up_data);
	print "</div>";
}
?>
<script type="text/javascript">
	function delete_all(loc)
	{
		var c = confirm("Are you sure you want to remove all events?"+'\n'+"This action cannot be undone.")
		if( c == true )
		{
			location = loc
		}
	}
</script>
<div class="border">
<h4 class="center">Bulk Options</h4>
<p><a href="javascript:delete_all('members.php?loc=sec_events&del_all=<?php echo $cat; ?>')">Remove All Events</a></p>
</div>

<h5 class="center"><a href="members.php?loc=attendance">Back</a></h5>
</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Edit Attendance Events</h3>

<script type="text/javascript">
	function delete_value(loc)
	{
		var c = confirm("Are you sure you want to delete this event?"+'\n'+"This action cannot be undone.")
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
print "Date";
nextHeaderColumn();
print "Event Name";
nextHeaderColumn();
print "&nbsp;";
endHeaderColumn();

$i = 0;

while( list($temp, $row) = each($events) )
{
	if( $i % 2 == 0 )
	{
		startRow();
	}
	else
	{
		startClassRow("table_alt");
	}
	$entry_date = new Date($row['date'] . ' 00:00:00');
	
	startColumn();
	print $entry_date->format('%A, %B %e, %Y');
	nextColumn();
	print $row['event_name'];
	nextColumn();
	print "<a href='members.php?loc=sec_events&edit_event=".$row['event_id']."'>Edit</a> - <a href=\"javascript:delete_value('members.php?loc=sec_events&del_event=".$row['event_id']."')\">Remove</a>";
	endColumn();
	endRow();
	
}

endTable();

?>

</div> <!-- end main app -->