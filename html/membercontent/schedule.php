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

if( isset($_REQUEST['event_category']) )
{
	$cat = $_REQUEST['event_category'];
}

if( isset($_REQUEST['up_event_category']))
{
	$cat = $_REQUEST['up_event_category'];
}

/*
 * Process DB Requests
 */

if( isset($_REQUEST['update']) )
{
	$date = $_REQUEST['up_event_date']['Y'] .'-'. $_REQUEST['up_event_date']['m'] .'-'. $_REQUEST['up_event_date']['d'];
	$db->getAll("update dumb_schedule set category_id = ?, opponent = ?, location = ?," .
			" time = ?, date= ?, pepband = ?, tv = ?, details = ? where schedule_id = ?", array($_REQUEST['up_event_category'],
																											  $_REQUEST['up_event_opponent'],
																											  $_REQUEST['up_event_location'],
																											  $_REQUEST['up_event_time'],
																											  $date,
																											  $_REQUEST['up_event_pepband'],
																											  $_REQUEST['up_event_tv'],
																											  $_REQUEST['up_event_details'],
																											  $_REQUEST['up_event_id'] ) );
}

if( isset($_REQUEST['add']) && isset($_REQUEST['event_opponent']) )
{
	$date = $_REQUEST['event_date']['Y'] .'-'. $_REQUEST['event_date']['m'] .'-'. $_REQUEST['event_date']['d'];
	$db->getAll("insert into dumb_schedule values (NULL, ?, ?, ?, ?, ?, ?, ?, ?)", array($_REQUEST['event_category'],
																											  $_REQUEST['event_opponent'],
																											  $_REQUEST['event_location'],
																											  $_REQUEST['event_time'],
																											  $date,
																											  $_REQUEST['event_pepband'],
																											  $_REQUEST['event_tv'],
																											  $_REQUEST['event_details']) );
}

if( isset($_REQUEST['del_event']) )
{
	$db->getAll("delete from dumb_schedule where schedule_id = ?", array($_REQUEST['del_event']));
}

/*
 * Populate general use arrays
 */

$categories = $db->getAll("select * from dumb_categories");
$events = $db->getAll("select * from dumb_schedule where category_id = ? order by date", array($cat));

/*
 * Create Add Form
 */
 
$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=schedule');

$r =& new HTML_QuickForm_Renderer_QuickHtml();

$date_defaults = array( 'd' => date('d'), 'm' => date('m'), 'Y' => date('Y'));
$form->setDefaults(array('event_date' => $date_defaults));
// Make Elements
$form->addElement('header', '', 'Add Event');
$form->addElement('date', 'event_date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2003, 'maxYear'=>2020));
$form->addElement('text', 'event_time', 'Time:');
$form->addElement('text', 'event_opponent', 'Opponent:');
$form->addElement('text', 'event_location', 'Location:');
$form->addElement('text', 'event_pepband', 'Pepband:');
$form->addElement('text', 'event_tv', 'TV:');
$form->addElement('text', 'event_details', 'Details:');
$entry_cats = array();
while( list($temp, $row) = each($categories) )
{
	$entry_cats[ $row['category_id'] ] = $row['name'];
}
$form->addElement('select', 'event_category', 'Category:', $entry_cats);
$form->addElement('submit', 'add', 'Add');

$form->accept($r);

$data = '<h4 class="center">Add Event</h4>' .
		'<b>Category:</b><br /> '.$r->elementToHtml('event_category').'<br />' .
				'<b>Opponent:</b><br /> '.$r->elementToHtml('event_opponent').'<br />' .
						'<b>Date:</b><br /> '.$r->elementToHtml('event_date').'<br />' .
						'<b>Time:</b><br /> '.$r->elementToHtml('event_time').'<br />' .
								'<b>Location:</b><br />'.$r->elementToHtml('event_location').'<br />' .
										'<b>Pepband:</b><br />'.$r->elementToHtml('event_pepband').'<br />' .
												'<b>TV:</b><br />'.$r->elementToHtml('event_tv').'<br />' .
														'<b>Details:</b><br />'.$r->elementToHtml('event_details').'<br />';

/*
 * Create Update Form (if requested)
 */
$up_form =& new HTML_QuickForm('updateForm', 'POST', 'members.php?loc=schedule');

$up_r =& new HTML_QuickForm_Renderer_QuickHtml();

$up_data;

if( isset($_REQUEST['edit_event']) )
{
	$edit_event = $db->getAll("select * from dumb_schedule where schedule_id = ?", array($_REQUEST['edit_event']));
	while( list($temp, $row) = each($edit_event) ) // One row
	{
		$date = expandDate($row['date']);
		$date_defaults = array( 'd' => $date['Day'], 'm' => $date['Month'], 'Y' => $date['Year'] );
		$form_defaults = array( 'up_event_category' => $row['category_id'],
										'up_event_id' => $row['schedule_id'],
										'up_event_time' => $row['time'],
										'up_event_opponent' => $row['opponent'],
										'up_event_location' => $row['location'],
										'up_event_pepband' => $row['pepband'],
										'up_event_tv' => $row['tv'],
										'up_event_details' => $row['details'],
										'up_event_date' => $date_defaults);
		
		// Make Elements
		$up_form->addElement('header', '', 'Add Event');
		$up_form->addElement('hidden', 'up_event_id', 'event_id');
		$up_form->addElement('date', 'up_event_date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2003, 'maxYear'=>2020));
		$up_form->addElement('text', 'up_event_time', 'Time:');
		$up_form->addElement('text', 'up_event_opponent', 'Opponent:');
		$up_form->addElement('text', 'up_event_location', 'Location:');
		$up_form->addElement('text', 'up_event_pepband', 'Pepband:');
		$up_form->addElement('text', 'up_event_tv', 'TV:');
		$up_form->addElement('text', 'up_event_details', 'Details:');
		$entry_cats = array();
		reset($categories);
		while( list($temp, $row) = each($categories) )
		{
			$entry_cats[ $row['category_id'] ] = $row['name'];
		}
		$up_form->addElement('select', 'up_event_category', 'Category:', $entry_cats);
		$up_form->addElement('submit', 'update', 'Update');
		$up_form->setDefaults($form_defaults);
		$up_form->accept($up_r);
		
		$up_data = '<h4 class="center">Update Event</h4>' . $up_r->elementToHtml('up_event_id') .
				'<b>Category:</b><br /> '.$up_r->elementToHtml('up_event_category').'<br />' .
				'<b>Opponent:</b><br /> '.$up_r->elementToHtml('up_event_opponent').'<br />' .
						'<b>Date:</b><br /> '.$up_r->elementToHtml('up_event_date').'<br />' .
						'<b>Time:</b><br /> '.$up_r->elementToHtml('up_event_time').'<br />' .
								'<b>Location:</b><br />'.$up_r->elementToHtml('up_event_location').'<br />' .
										'<b>Pepband:</b><br />'.$up_r->elementToHtml('up_event_pepband').'<br />' .
												'<b>TV:</b><br />'.$up_r->elementToHtml('up_event_tv').'<br />' .
														'<b>Details:</b><br />'.$up_r->elementToHtml('up_event_details').'<br />';
	}
}

?>
<div id="options">
<h3 class="center">&nbsp;</h3>
<form name="sec_events" method="post" action="members.php?loc=schedule">
<div class="border">
<h4 class="center">Select Category</h4>
<select name="cat">
<?php
reset($categories);
while( list($temp, $row) = each($categories) )
{
	if( $row['category_id'] == $cat )
	{
		print "<option value='".$row['category_id']."' selected>".$row['name']."</option>\n";
	}
	else
	{
		print "<option value='".$row['category_id']."'>".$row['name']."</option>\n";
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
</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Edit Schedule</h3>

<script type="text/javascript">
	function delete_value(loc)
	{
		var c = confirm("Are you sure you want to delete this item?"+'\n'+"This action cannot be undone.")
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
print "Opponent";
nextHeaderColumn();
print "Location";
nextHeaderColumn();
print "Time";
nextHeaderColumn();
print "Pepband";
nextHeaderColumn();
print "TV";
nextHeaderColumn();
print "Details";
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
	print $row['opponent'];
	nextColumn();
	print $row['location'];
	nextColumn();
	print $row['time'];
	nextColumn();
	print $row['pepband'];
	nextColumn();
	print $row['tv'];
	nextColumn();
	print $row['details'];
	nextColumn();
	print "<a href='members.php?loc=schedule&cat=".$row['category_id']."&edit_event=".$row['schedule_id']."'>Edit</a> - <a href=\"javascript:delete_value('members.php?loc=schedule&cat=".$row['category_id']."&del_event=".$row['schedule_id']."')\">Remove</a>";
	endColumn();
	endRow();
	
}

endTable();

?>

</div> <!-- end main app -->
