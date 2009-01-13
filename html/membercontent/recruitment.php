<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";

global $db;

if( isset($_REQUEST['clear']) )
{
	$result = $db->getAll('delete from dumb_freshmen_form');
	$result = $db->getAll('delete from dumb_recruitment');
}

if( isset($_REQUEST['delete']) )
{
   $result = $db->getAll('delete from dumb_freshmen_form where id = ?', array($_REQUEST['delete']));
   $result = $db->getAll('delete from dumb_recruitment where id = ?', array($_REQUEST['delete']));
}
?>

<div id="options">
<script type="text/javascript">
function delete_value(loc)
{
   var c = confirm("Are you sure you want to delete this entry?"+'\n'+"This action cannot be undone.")
   if( c == true )
   {
      location = loc
   }
}
</script>
<h3 class="center">&nbsp;</h3>
<div class="border">
<h4 class="center">Options</h4>
<?php

$res = $db->getAll('select email from dumb_freshmen_form');

$email = '';
while(list($temp, $lst) = each($res))
{
   $email .= $lst['email'].',';
}
// Remove last comma
$bcclist = substr($email, 0, strlen($email)-1);

?>
<p><a href="mailto:?bcc=<?php echo $bcclist; ?>">Email All</a></p>
<p>Generate Mail Merge</p>
<hr/>
<p><a href="javascript:delete_value('members.php?loc=recruitment&clear=true')">Remove All Entries</a></p>
</div>
</div> <!-- end options -->

<div id="main_app">

<h3 class="center">Recruitment Entries</h3>

<?php
global $db;
	
$result = '';
$order = '';
if(isset($_REQUEST['OrderBy'])) {
	switch($_REQUEST['OrderBy']) {
	case "name":
		$order = "name";
		break;
	case "city":
		$order = "city";
		break;
	case "state":
		$order = "state";
		break;
	case "instrument":
		$order = "instrument";
		break;
	case "graduation":
		$order = "graduation";
		break;
	case "submission":
		$order = "submission";
		break;
	default:
		$order = "id";
	}

	$quer = "SELECT * from dumb_freshmen_form ORDER BY ".$order." ASC";
	$result = $db->getAll($quer);
} else {
	$result = $db->getAll("select * from dumb_freshmen_form");
}
	
	startTable(1, 0, 1, "100%");
	startClassRow("table_header");
	startHeaderColumn();
	print "<a href=\"members.php?loc=recruitment&OrderBy=name\">Name</a>";
	nextHeaderColumn();
	print "<a href=\"members.php?loc=recruitment&OrderBy=city\">City</a>";
	nextHeaderColumn();
	print "<a href=\"members.php?loc=recruitment&OrderBy=state\">State</a>";
	nextHeaderColumn();
	print "<a href=\"members.php?loc=recruitment&OrderBy=instrument\">Instrument</a>";
    	nextHeaderColumn();
    	print "<a href=\"members.php?loc=recruitment&OrderBy=graduation\">Graduation Year</a>";
    	nextHeaderColumn();
    	print "<a href=\"members.php?loc=recruitment&OrderBy=submission\">Submission Date</a>";
    	nextHeaderColumn();
   	print "Dumb POC";
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
		
		// Process dates
		$date = explode("-", $row['submission']);
		// DD-MM-YYYY
    	$date_s = $date[1]."-".$date[2]."-".$date[0];
    	
    	// retrive dumb poc
    	$inner_result = $db->getAll('select poc from dumb_recruitment where id = ?', array($row['id']));
    	list($temp, $det) = each($inner_result); // one row
		
		startColumn();
		print $row['name'];
		nextColumn();
		print $row['city'];
		nextColumn();
		print $row['state'];
		nextColumn();
		print $row['instrument'];
		nextColumn();
		print $row['graduation'];
		nextColumn();
		print $date_s;
      nextColumn();
      print $det['poc'];
      nextColumn();  
      print "<a href='members.php?loc=recruitment_entry&entry_id=".$row['id']."'>View</a>";
      if($_SESSION['user']->has_priv("recruitment")){
	      print " -- <a href='members.php?loc=recruitment_edit&entry_id=".$row['id']."'>Edit</a> -- ";
      
      	      print "<a href=\"javascript:delete_value('members.php?loc=recruitment&delete=".$row['id']."')\">Remove</a> ";
      }
	endColumn();
	endRow();
	}
	
	endTable();
	
?>

</div> <!-- end main_app -->
