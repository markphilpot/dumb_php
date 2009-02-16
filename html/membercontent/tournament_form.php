<?php

require_once "include/dumb_util.php";
require_once "include/html_util.php";

?>

<div id="options">
<h3 class="center">&nbsp;</h3>
</div> <!-- end options -->

<div id="main_app">
<h1 class="center">Tournament Form</h1>

<form name="Tform" id="Tform" method="post" action="members.php?loc=tournament_receipt">

<p>Check your spring schedule carefully before filling out this form!  Please
read the packet on the tournment process before proceeding.</p>
<input type="hidden" name="username" value="<?php print $_SESSION['user']->username; ?>">

<!--
<h4>Travel Preferences</h4>
<p><input type="checkbox" name="disclaimer" value="1">
I have read the information regarding tournment selection and
understand the process.  I have also verified my attendance records
and they are correct.</p>
-->
<!--
<p><input type="radio" name="prefer" value="W">
I prefer to travel with the <b>women's team</b> rather than the men's
(although I will accept any invitation)<br />
<input type="radio" name="prefer" value="M">
I prefer to travel with the <b>men's team</b> rather than the women's
(although I will accept any invitation)<br />
<input type="radio" name="prefer" value="N" checked>
I have <b>no preference</b> and will accept any invitation
</p>
-->

<h4>DUMB Experience</h4>
<p>Please note this has nothing to do with your class standing.  For example,
a senior could still be a first-year member.</p>
<p>
<input type="radio" name="exp" value="1" checked> First Year<br />
<input type="radio" name="exp" value="2"> Second Year<br />
<input type="radio" name="exp" value="3"> Third Year<br />
<input type="radio" name="exp" value="4"> Fourth Year</p>

<p>If you joined the band <i>this</i> year, please indicate when you joined:</p>
<p><input type="radio" name="joined" value="na" checked> I did not join the band this year<br />
<input type="radio" name="joined" value="beginning"> I joined at the beginning of football season<br />
<input type="radio" name="joined" value="midway"> I joined midway through football season<br />
<input type="radio" name="joined" value="end"> I joined after football season</p>

<p>If you have spent a semester away from band (i.e. abroad), please indicate that below:</p>
<input type="radio" name="abroad" value="none" checked> I have not taken any semesters off from band<br />
<input type="radio" name="abroad" value="fall"> I took a fall off from band<br />
<input type="radio" name="abroad" value="spring"> I took a spring off from band<br />

<p>Put any notes about semesters abroad in the comments section</p>

<h4>Personal Information</h4>
<p>Please verify that the information below is correct.  If it is not, please
click <a href="members.php?loc=my_profile">here</a> and correct it before proceeding.</p>

<p><i>
<?php
$first = $_SESSION['user']->info_map['firstname'];
$last = $_SESSION['user']->info_map['lastname'];
$inst = getInstrument($_SESSION['user']->info_map['instrument_id']);
$email = $_SESSION['user']->info_map['email'];
$phone = $_SESSION['user']->info_map['phone'];

print "$first $last<br />";
print "$inst<br />";
print "$email<br />";
print "$phone";
?>
</i></p>

<h4>Availability</h4>
<p>All games/times/dates are approximate.  We may leave anywhere from the
Tuesday before to the Friday of that same week.  For underclassmen, please take a moment
to consider that you must travel with the women's team <b>before</b> you can
travel with the men's team.  The Women's ACC Tournament counts toward this.</p>

<p><b>Put this list in order of your preference.  What you want most goes at the top.  (Just drag the items in the list.)</b></p>

<?php
// Display Selection Options

function isTournamentOptionEnabled($i)
{
	global $db;
	
	$result = $db->getAll("select * from dumb_setup where parameter = 'tournament_option$i'");
	
	list($t, $option) = each($result);
	
	if( $option['value'] == "true" )
	{
		return true;
	}
	else
	{
		return false;
	}
}
global $db;

$options = $db->getAll("select * from dumb_tournament_options");

//print_r($options);

/*
startTable(1, 1, 1, "100%");
 */

echo "<ul id=\"options_list\">";
$i=0;
foreach($options as $row)
{
	echo "<li id=\"option_". $row['option_id'] ."\">
	<table width=600><tr><td width=400><b>".  $row['description'] ."</b> </td><td>
	".$row['date']."</td></tr></table></li>";
}

//create a "CANNOT TRAVEL box and everything that gets put in there gets the CAN NOT TRAVEL pref number
?>

</ul>
<p><b>If you can not attend a specific trip, drag it into the box below</b></p> 
<table id="reject_list" width=100%> <tr><td>  <br></td></tr><table>
<br \>
</ul>

<br \>
<?
echo <<<EOF

<script type="text/javascript" language="javascript">
function submit_func() {

	var myselections = Sortable.serialize(document.getElementById('options_list'));
	//alert(myselections);

EOF;
	echo "for(i=1; i<=". count($options) ."; i++){";

echo <<<EOF

		myselections = myselections.replace("options_list[]=","");
		myselections = myselections.replace("&",",");
	}
	//alert(myselections);
	//comma separated list of choices, still in order
	var myorderedlist = myselections.split(",");
	var myform = document.getElementById('Tform')
	var i;
	var choicenum = 1;
	for(i=0; i<myorderedlist.length;i++){
//		alert("option " + myorderedlist[i] + " gets ranking " + choicenum );
		//add fields to form
		myfield = document.createElement("input");
		myfield.type = "hidden";
		myfield.name = "option_"+myorderedlist[i];
		myfield.id = "option_"+myorderedlist[i];
		myfield.value = choicenum;
		myform.appendChild(myfield);
		choicenum++;

	}

}
		
</script>
EOF;

?>

<script type="text/javascript" language="javascript">
  Sortable.create('options_list',{dropOnEmpty:true,containment:["options_list","reject_list"],ghosting:true,constraint:false});
//  alert(Sortable.serialize('options_list'));
  Sortable.create('reject_list', {ghosting:true,dropOnEmpty:true,containment:["options_list","secondlist"],constraint:false});
</script>

<?
/*	
	startRow();
	$i++;
	if( isTournamentOptionEnabled($i) )
	{
		startColumn();
		print "<b>Option " . $row['option_id'] . "</b>";
		//print "<input type='hidden' name='option_id' value='" . $row['option_id'] . "'>";
		nextColumn();
		print "Rank: ";
		print "<select name='option_".$row['option_id']."'>";
		print "<option value='0'>0 - Not available to travel</option>";
		print "<option value='1'>1 - Highest</option>";
		print "<option value='2'>2</option>";
		print "<option value='3'>3</option>";
		print "<option value='4'>4</option>";
		print "<option value='5'>5</option>";
		print "<option value='6'>6 - Lowest</option>";
		print "</select>";
		nextColumn();
		if( $i == 6 )
		{
			print "I am willing to be a substitute during <b>Spring Break</b>.<br />";
			print "As of now, I plan on being at the following phone number should I be needed as a last-minute replacement: <br />";
			print "<br/><input type='text' name='option_6_phone' size='14' maxlength='14'>";
		}
		else
		{
			print "I am available and willing to travel to the ";
			print "<b>". $row['description'] ."</b> ";
			print "(".$row['date'].")";
		}
		endColumn();
	}
	else
	{
		startColumn();
		//print "<input type='hidden' name='option_id' value='
		print "<input type='hidden' name='option_".$row['option_id']."' value='0'>";
		if( $i==6 )
		{
			print "<input type='hidden' name='option_6_phone' value='n/a'>";
		}
	}
	endRow();

 endTable();
 */


?>

<h4>Additional Comments</h4>
<p>The following area is for you to provide any other details of your request. This is
NOT a space for you to argue about your travel bid.</p>
  <p> 
 <textarea name="comments" cols="60" rows="10"></textarea>
  </p>
 
 <h4>Please take the time to verify your entries in this form!</h4>
  <p>Once the form is submitted, you will be presented with a receipt (i.e. 
 a lot of values that won't make much sense to you, but will to us). 
 It is very important that you save (or print) this receipt. This is 
 your verification that you indeed filled out the tournament form on 
 time, etc. You will be able to change your form during the entire time 
 the form is available. Each time you want to make changes, you must 
 <b>resubmit the entire form</b> as all previous entries will be overwritten</p>
  
<?php if( !isset($_REQUEST['preview']) ) 
{
?>
<p>
 <input type="submit" name="Submit" onClick="submit_func();">
 <input type="reset" name="Clear" value="Clear Form">
</p>
<?php } ?>

</form>

</div> <!-- end main app -->
