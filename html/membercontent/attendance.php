<script language='javascript' type="text/javascript">

function Nothing()
{
	// Do Nothing
}

function SetUsers(n) 
{
    var temp = document.attendance.elements.length;

   	for (var i=0; i < temp; i++)
	{
		if(document.attendance.elements[i].type == "checkbox")
			if(document.attendance.elements[i].name == "USERS[]")
				document.attendance.elements[i].checked = n;
	}
}

function SetEvents(n) 
{
    var temp = document.attendance.elements.length;

   	for (var i=0; i < temp; i++)
	{
		if(document.attendance.elements[i].type == "checkbox")
			if(document.attendance.elements[i].name == "EVENTS[]")
				document.attendance.elements[i].checked = n;
	}
} 

function InverseUsers()
{
	var temp = document.attendance.elements.length;

	for (var i=0; i < temp; i++)
	{
		if(document.attendance.elements[i].type == "checkbox")
			if(document.attendance.elements[i].name == "USERS[]")
				if(document.attendance.elements[i].checked == 1)
					document.attendance.elements[i].checked = 0;
     	 	  	else
					document.attendance.elements[i].checked = 1;       
    }
}

function InverseEvents()
{
	var temp = document.attendance.elements.length;

	for (var i=0; i < temp; i++)
	{
		if(document.attendance.elements[i].type == "checkbox")
			if(document.attendance.elements[i].name == "EVENTS[]")
				if(document.attendance.elements[i].checked == 1)
					document.attendance.elements[i].checked = 0;
     	 	  	else
					document.attendance.elements[i].checked = 1;      
    }
}

</script>

<?php

require_once("include/html_util.php");
global $db;

if(!isset($_SESSION['s']))
{
	$s = "initialize";
	$sec_attendance_category = 1;
	$sec_attendance_instrument = "all";

	$_SESSION['s'] = "initialize";
	$_SESSION['sec_attendance_category'] = 1;
	$_SESSION['sec_attendance_instrument'] = "all";
}

if(isset($_REQUEST['processform']))
{
	if($_REQUEST['categories'] != "null")
	{
		$_SESSION['sec_attendance_category'] = $_REQUEST['categories'];
	}

	if($_REQUEST['instrument'] != "null")
	{
		$_SESSION['sec_attendance_instrument'] = $_REQUEST['instrument'];
	}
}

// Test if we are deleting values
if(!isset($_REQUEST['USERS']) || !isset($_REQUEST['EVENTS']))
{
	//echo "Users & Events are null";
}
elseif($_REQUEST['code'] == "null")
{
	//echo "Value is null";
}
elseif($_REQUEST['code'] == "remove")
{
	while( list($temp, $event) = each($_REQUEST['EVENTS']) )
	{
		reset($_REQUEST['USERS']);
		while( list($temp, $user) = each($_REQUEST['USERS']) )
		{
		    $statement = $db->prepare('delete from dumb_sec_attendance where username = ? and event_id = ?');
			$result =& $db->execute( $statement, array($user, $event) );
		}
	}
}
else
{
	while( list($temp,$event) = each($_REQUEST['EVENTS']) )
	{
		reset($_REQUEST['USERS']);
		while( list($temp, $user) = each($_REQUEST['USERS']) )
		{
			$statement = $db->prepare('select * from dumb_sec_attendance where username = ? and event_id = ?');
			$result =& $db->execute( $statement, array($user, $event) );
			
			if( $result->numRows() > 0 )
			{
				// Update
				$statement = $db->prepare('update dumb_sec_attendance set code_id = ? where username = ? and event_id = ?');
				$result =& $db->execute( $statement, array( $_REQUEST['code'], $user, $event ) );
			}
			else
			{
				// Add (user, event, value)
				$statement = $db->prepare('insert into dumb_sec_attendance values (?, ?, ?)');
				$result =& $db->execute( $statement, array( $user, $event, $_REQUEST['code'] ) );
			}
		}
	}
}

$codes = $db->getAll('select * from dumb_sec_codes where category_id = ?', array($_SESSION['sec_attendance_category']) );

?>
<form name="attendance" method="post" action="members.php?loc=attendance">

<div id="options">
<h3 class="center">Attendance Options</h3>
<?php
if( $_SESSION['user']->has_priv('changeValues') )
{
print "<div class='border'>";
print "<h4 class='center'>Change Values</h4>";
print "<select name='code'>";
	print "<option value='null'>Select Value</option>";

	while(list($temp,$code) = each($codes))
	{
		print "<option value='" . $code['code_id'] . "'>" . $code['code'] . "</option>";
	}

	print "<option value='remove'>Remove</option>";
print "</select>";
print "<input type='submit' name='Submit3' value='Change'>";
print "</div> <!-- end Change Values -->";
}

// Display Categories
$categories = $db->getAll('select * from dumb_sec_categories');

print "<div class='border'>";
print "<h4 class='center'>Select Category</h4>";
print "<select name='categories'>";
while(list($temp, $cat) = each($categories))
{
	if($cat['category_id'] == $_SESSION['sec_attendance_category'])
	{
		print "<option value='" . $cat['category_id'] . "' selected>" . $cat['category_name'] . "</option>";
	}
	else
	{
		print "<option value='" . $cat['category_id'] . "'>" . $cat['category_name'] . "</option>";
	}
}
print "</select>";
print "<input type='submit' name='Submit2' value='Go'>";
print "<input type='hidden' name='processform' value='true'>";
print "</div> <!-- end Select Category -->";

// Display Javascript helpers
if( $_SESSION['user']->has_priv('changeValues') )
{
?>
<div class="border">
<h4 class="center">Select...</h4>
<table width="100%" border="1">
 <tr> 
  <td width="50%">
  &#149; <a href="javascript:SetUsers(1);">All Users</a><br />
  &#149; <a href="javascript:SetUsers(0);">No Users</a><br />
  &#149; <a href="javascript:InverseUsers();">Invert</a><br />
  </td>
  <td>
  &#149; <a href="javascript:SetEvents(1);">All Events</a><br />
  &#149; <a href="javascript:SetEvents(0);">No Events</a><br />
  &#149; <a href="javascript:InverseEvents();">Invert</a>
  </td>
 </tr>
</table>
</div>
<?php
}

// Display Codes
$codes = $db->getAll('select * from dumb_sec_codes where category_id = ?', array($_SESSION['sec_attendance_category']) );
print "<div class='border'>";
print "<h4 class='center'>Code Key</h4>";
print "<p>";
while(list($temp, $code) = each($codes))
{
	print "<b>" . $code['code'] . "</b> - " . $code['code_name'] . "<br />";
}
print "</p>";
print "</div> <!-- end Display Codes -->";

// Editing values
if( $_SESSION['user']->has_priv('changeValues') )
{
?>
<div class="border">
<h4 class="center">Edit Attendance Parameters</h4>
<p>&#149; <a href="members.php?loc=sec_categories">Edit Catagories</a><br />
&#149; <a href="members.php?loc=sec_events">Edit Events</a><br />
&#149; <a href="members.php?loc=sec_codes">Edit Codes</a></p>
</div>
<?php
}

?>
</div> <!-- end options -->

<div id="main_app">
<h4>View by Instrument:
<?php
$instruments = $db->getAll('select * from dumb_instruments');
$current_inst = $db->getAll('select * from dumb_instruments where instrument_id = ?', array($_SESSION['sec_attendance_instrument']) );
$current_cat = $db->getAll('select * from dumb_sec_categories where category_id = ?', array($_SESSION['sec_attendance_category']) );
print "<select name='instrument'>";
echo "<option value='null'>Select Instrument...</option>";
echo "<option value='all'>All Instruments</option>";

while( list($temp,$inst) = each($instruments) )
{
	print "<option value='" . $inst['instrument_id'] . "'>" . $inst['instrument'] . "</option>";
}	
print "</select>";
print "<input type='submit' name='Submit' value='Go'>";
if( $_SESSION['sec_attendance_instrument'] == "all" )
{
	print "Currently Viewing <i>All</i> ";
}
else
{
	print "Currently Viewing <i>" . $current_inst[0]['instrument'] . "</i> ";
}
print "In <i> " . $current_cat[0]['category_name'] . "</i></h4>";

// Retrieve all attendance
$attendance_result;
$events_result;
$codes_result;
$users_result;

if($_SESSION['sec_attendance_instrument'] == "all")
{
	$statement = "select dumb_sec_attendance.username, dumb_sec_codes.code, dumb_sec_attendance.event_id from dumb_members, dumb_sec_attendance, dumb_sec_events, dumb_sec_codes where ".
						"dumb_sec_events.category_id = ? ".
						"and dumb_members.username = dumb_sec_attendance.username ".
						"and dumb_sec_attendance.event_id = dumb_sec_events.event_id ".
						"and dumb_sec_attendance.code_id = dumb_sec_codes.code_id";
	$attendance_result = $db->getAll($statement, array($_SESSION['sec_attendance_category']));
	
	$users_result = $db->getAll('select username, firstname, lastname from dumb_members order by lastname');
}
else
{
	$statement = "select dumb_sec_attendance.username, dumb_sec_codes.code, dumb_sec_attendance.event_id from dumb_members, dumb_sec_attendance, dumb_sec_events, dumb_sec_codes where ".
						"dumb_members.instrument_id = ? ".
						"and dumb_sec_events.category_id = ? ".
						"and dumb_members.username = dumb_sec_attendance.username ".
						"and dumb_sec_attendance.event_id = dumb_sec_events.event_id ".
						"and dumb_sec_attendance.code_id = dumb_sec_codes.code_id";
	$attendance_result = $db->getAll($statement, array($_SESSION['sec_attendance_instrument'],$_SESSION['sec_attendance_category']) );

	$users_result = $db->getAll('select username, firstname, lastname from dumb_members where instrument_id = ? order by lastname', array($_SESSION['sec_attendance_instrument']) );
}
	
$events_result = $db->getAll('select * from dumb_sec_events where category_id = ? order by date desc', array($_SESSION['sec_attendance_category']) );

$codes_result = $db->getAll('select * from dumb_sec_codes where category_id = ?', array($_SESSION['sec_attendance_category']) );

// Make results into accessible map
$ac;

while( list($temp,$row) = each($attendance_result) )
{
	$ac[$row['event_id']][$row['username']] = $row['code'];
}

$event_ids = array();
$event_dates = array();

while(list($temp,$event) = each($events_result))
{
	array_push($event_ids, $event['event_id']);

	// Process dates
	$date = explode("-", $event['date']);
	// DD-MM-YYYY
    $date_s = $date[1]."-".$date[2]."-".$date[0];

	array_push($event_dates, $date_s);
}

$usernames = array();
$lastfirst = array();

while(list($temp,$user) = each($users_result))
{
	array_push($usernames, $user['username']);
	array_push($lastfirst, $user['lastname'] . ", " . $user['firstname']);
}

startTable(0,0,1,"100%");
startClassRow("table_header");

startColumn();
	echo "&nbsp;";
endColumn();

for($i = 0; $i < count($event_dates); $i++) // Equal length vectors
{
	startHeaderColumn();

	// Put in Check boxes
	echo $event_dates[$i];
	echo "<br />";
	echo "<input type='checkbox' name='EVENTS[]' value='".$event_ids[$i]."'>";

	endHeaderColumn();
}

startColumn();

echo "Total U's";

endColumn();

endRow();


$count = 1;
$total = 0;

for($j = 0; $j < count($usernames); $j++) // Equal length vectors
{
	if($count%2 == 0)
	{
		startClassRow("table_alt");
	}
	else
	{
		startRow();
	}

	$count++;

	startColumn();

	echo "<input type='checkbox' name='USERS[]' value='".$usernames[$j]."'>";
    echo $lastfirst[$j];
	endColumn();

	for($k=0; $k < count($event_ids); $k++)
	{
		$code_value = "null";
		$code_value = $ac[$event_ids[$k]][$usernames[$j]];

		if($code_value == "")
		{
			$code_value = "null";
		}		

		if($code_value != "null")
		{
			if(ord($code_value) == ord("U"))
			{
				startClassColumn("attendance_u");
				$total++;
			}
			else
			{
				startColumn();
			}

			echo $code_value;
		}
		else
		{
			startColumn();
			echo "&nbsp;";
		}

		endColumn();
	}

	startColumn();

	echo $total;

	endColumn();				

	endRow();

	$total = 0;
}

endTable();

?>
</div> <!-- end main_app -->

</form>

