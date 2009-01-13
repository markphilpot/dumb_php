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

if( $_SESSION['user']->is_admin )
{
	die("Admin/Director User has no attendance");
}

/*
 * Populate general use arrays
 */

$categories = $db->getAll("select * from dumb_sec_categories");
$attendance = $db->getAll("select * from dumb_sec_attendance, dumb_sec_codes, dumb_sec_events " .
		"where dumb_sec_attendance.username = ? and " .
		"dumb_sec_events.category_id = ? and " .
		"dumb_sec_events.event_id = dumb_sec_attendance.event_id and " .
		"dumb_sec_attendance.code_id = dumb_sec_codes.code_id " .
		"order by dumb_sec_events.date, dumb_sec_events.event_id", array($_SESSION['user']->username,$cat));
$all = $db->getAll("select * from dumb_sec_events where " .
		"dumb_sec_events.category_id = ? " .
		"order by dumb_sec_events.date, dumb_sec_events.event_id", array($cat));
		
$codes = $db->getAll("select * from dumb_sec_codes where category_id = ?", array($cat));

/*
 * Compute statistics
 */

// Exclude Happy Points category
$max_points = $db->getAll("select max(dumb_sec_codes.points) as points, dumb_sec_events.event_id " .
		"from dumb_sec_codes, dumb_sec_events " .
		"where dumb_sec_codes.category_id = dumb_sec_events.category_id " .
		"and dumb_sec_events.category_id != 5 " .
		"group by dumb_sec_events.event_id;");
$max_possible = 0;
while(list($tmp,$row) = each($max_points))
{
	$max_possible += $row['points'];
}

$current_total = $db->getAll("select sum(dumb_sec_codes.points) AS points, " .
				"dumb_members.username, dumb_members.lastname, dumb_members.firstname " .
				"from dumb_members, dumb_sec_codes, dumb_sec_attendance " .
				"where dumb_members.username = dumb_sec_attendance.username " .
				"AND dumb_sec_codes.code_id = dumb_sec_attendance.code_id " .
				"and dumb_members.username = ?" .
				"group by dumb_members.username ", array($_SESSION['user']->username));
$user_total = 0;
while(list($tmp, $row) = each($current_total))
{
	$user_total = $row['points'];
}

$band_total = $db->getAll("select sum(dumb_sec_codes.points) AS points, " .
				"dumb_members.username, dumb_members.lastname, dumb_members.firstname " .
				"from dumb_members, dumb_sec_codes, dumb_sec_attendance " .
				"where dumb_members.username = dumb_sec_attendance.username " .
				"AND dumb_sec_codes.code_id = dumb_sec_attendance.code_id " .
				"group by dumb_members.username " );
$band_average = 0;
$band_max = 0;
$sum = 0;
$i = 0;
while(list($tmp, $row) = each($band_total))
{
	$sum += $row['points'];
	$i++;
	if($row['points'] > $band_max)
	{
		$band_max = $row['points'];
	}
}

if($i != 0)
	$band_average = round($sum / $i);

$section_computation = $db->getAll("select sum(dumb_sec_codes.points) as points, dumb_members.username,  dumb_instruments.instrument " .
		"from dumb_sec_codes, dumb_sec_attendance, dumb_members, dumb_instruments " .
		"where dumb_members.username = dumb_sec_attendance.username " .
		"and dumb_sec_codes.code_id = dumb_sec_attendance.code_id " .
		"and dumb_members.instrument_id = dumb_instruments.instrument_id " .
		"and dumb_members.instrument_id = ? " .
		"group by dumb_members.username " .
		"order by dumb_instruments.instrument_id", array($_SESSION['user']->info_map['instrument_id']));

$max = 0;
$sum = 0;
$i = 0;
$average = 0;
while(list($tmp, $row) = each($section_computation))
{
	$sum += $row['points'];
	$i++;
	if($row['points'] > $max)
	{
		$max = $row['points'];
	}
}

if($i != 0)
	$average = round($sum / $i);

?>
<div id="options">
<h3 class="center">&nbsp;</h3>
<form name="viewAttendance" method="post" action="members.php?loc=view_attendance">
<div class="border">
<h4 class="center">Select Category</h4>
<select name="cat">
<?php
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
<h4 class="center">Codes</h4>
<?php
print "<p>";
while(list($temp, $code) = each($codes))
{
	print "<b>" . $code['code'] . "</b> - " . $code['code_name'] . "<br />";
}
print "</p>";
?>
</div>

<div class="border">
<h4 class="center">Statistics</h4>
<p>Your Total: <?php echo $user_total; ?><br />
Maximum Possible*: <?php echo $max_possible; ?></p>
<p>Section Average: <?php echo $average; ?><br />
Section Maximum: <?php echo $max; ?></p>
<p>Band Average: <?php echo $band_average; ?><br />
Band Maximum: <?php echo $band_max; ?></p>
<p>* - Does not include Happy Points</p>
</div>
</div> <!-- end options -->

<div id="main_app">
<h3 class="center">View Attendance</h3>

<?php

startTable(1,1,1,"90%");

startClassRow("table_header");
startHeaderColumn();
print "Date";
nextHeaderColumn();
print "Event Name";
nextHeaderColumn();
print "Value";
endHeaderColumn();

$i = 0;
list($t, $att) = each($attendance);
while( list($temp, $row) = each($all) )
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
	if($att['event_id'] == $row['event_id'])
	{
		print $att['code_name'];
		
		// Go to the next value
		list($t, $att) = each($attendance);
	}
	else
	{
		print "&nbsp;";
	}
	endColumn();
	endRow();

	$i++;	
}

endTable();

?>

</div> <!-- end main app -->