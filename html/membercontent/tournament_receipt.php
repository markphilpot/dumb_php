<?php
require_once "include/database.php";

$status = 'Error processing tournament form';

global $db;

if( isset($_REQUEST['Submit']) )
{
//	if( !isset($_REQUEST['disclaimer']) )
//	{
//		$status = 'Invalid tournament form.  Check to make sure you have read the disclaimer and ' .
//				'checked the box.';
//	}
//
//	else
//	{

		$_REQUEST["dummy_addition"] = 7; // Check with Brian to see if this is needed
		
		//Removed Disclaimer & Preference - Temp set them now, option_6_phone is OBE as well
		// TODO -- Remove from Database schema if not needed anymore
		$_REQUEST['disclaimer'] = 1;
		$_REQUEST['prefer'] = 1;
		
		for($i=1;$i<9;$i++){
			if(!isset($_REQUEST["option_".$i])){
//				echo "option_".$i." is not set";
				$_REQUEST["option_".$i] = 0;
			}
		}

		$statement = $db->prepare("select username, disclaimer from dumb_tournament_form where username = ?");
		$result = $db->execute( $statement , array($_REQUEST['username']) );
		
		// If previous entry
		if( $result->numRows() > 0 )
		{
			$db->getAll( "update dumb_tournament_form set disclaimer = ?, prefer = ?, " .
				"exp = ?, joined = ?, abroad = ?, option_1 = ?, option_2 = ?, option_3 = ?, " .
				"option_4 = ?, option_5 = ?, option_6 = ?, option_7 = ?, option_8 = ?, comments = ? where username = ?", 
				array($_REQUEST['disclaimer'], $_REQUEST['prefer'],
				      $_REQUEST['exp'], $_REQUEST['joined'],
				      $_REQUEST['abroad'], $_REQUEST['option_1'],
				      $_REQUEST['option_2'], $_REQUEST['option_3'],
				      $_REQUEST['option_4'], $_REQUEST['option_5'],
				      $_REQUEST['option_6'], $_REQUEST['option_7'], $_REQUEST['option_8'],
				      $_REQUEST['comments'], $_REQUEST['username'] ) );
		}
		else
		{
			$db->getAll( "insert into dumb_tournament_form set disclaimer = ?, prefer = ?, " .
				"exp = ?, joined = ?, abroad = ?, option_1 = ?, option_2 = ?, option_3 = ?, " .
				"option_4 = ?, option_5 = ?, option_6 = ?, option_7 = ?, option_8 = ?, comments = ?, username = ?", 
				array($_REQUEST['disclaimer'], $_REQUEST['prefer'],
				      $_REQUEST['exp'], $_REQUEST['joined'],
				      $_REQUEST['abroad'], $_REQUEST['option_1'],
				      $_REQUEST['option_2'], $_REQUEST['option_3'],
				      $_REQUEST['option_4'], $_REQUEST['option_5'],
				      $_REQUEST['option_6'], $_REQUEST['option_7'], $_REQUEST['option_8'],
				      $_REQUEST['comments'], $_REQUEST['username'] ) );
		}
//	}
}

?>
<div id="options">
<h3 class="center">&nbsp;</h3>
</div> <!-- end options -->

<div id="main_app">
<h1 class="center">Tournament Receipt</h1>

<?
	//remove this soon
	print_r($_REQUEST);
	//end remove
?>	

<p>Please save/print this receipt for your records.</p>
<h4>Form Values</h4>
<?php
$stamp = date("U");
echo "<p><b>Form Submitted</b> -- ".date("F j, Y, g:i a")." --".$stamp."</p>";
$check = crc32($dbPass.$stamp);
printf("<p><b>Checksum</b> -- %u<p>", $check);
echo "<p>";


/*
//this is the old output


//echo "<b>Disclaimer</b> ".$_REQUEST['disclaimer']."<br/>";
//echo "<b>Prefer</b> ".$_REQUEST['prefer']."<br/>";
echo "<b>Exp</b> ".$_REQUEST['exp']."<br />";
echo "<b>Joined</b> ".$_REQUEST['joined']."<br />";
echo "<b>Abroad</b> ".$_REQUEST['abroad']."<br/>";
echo "<b>Option 1</b> ".$_REQUEST['option_1']."<br/>";
echo "<b>Option 2</b> ".$_REQUEST['option_2']."<br/>";
echo "<b>Option 3</b> ".$_REQUEST['option_3']."<br/>";
echo "<b>Option 4</b> ".$_REQUEST['option_4']."<br/>";
echo "<b>Option 5</b> ".$_REQUEST['option_5']."<br/>";
echo "<b>Option 6</b> ".$_REQUEST['option_6']."<br/>";
echo "<b>Option 7</b> ".$_REQUEST['option_7']."<br/>";
echo "<b>Option 8</b> ".$_REQUEST['option_8']."<br/>";
echo "<b>Comments</b> ".$_REQUEST['comments']."<br/>";
//echo "<b>Phone</b> ".$_REQUEST['option_6_phone']."<br/>";
echo "</p>";

//added translation table to make reciept readable
$result = $db->getAll("select option_id,description from dumb_tournament_options where 1 order by option_id asc");
print_r($result);
foreach($result as $row) {
	echo "Option ";
        echo $row['option_id'];
	echo ": ";
	echo $row['description'];
	echo "<br/>";

}

*/
$result = $db->getAll("select option_id,description from dumb_tournament_options where 1 order by option_id asc");
//the new output has the OPTION DESCRIPTON, not just the number
echo "<b>Exp</b> ".$_REQUEST['exp']."<br />";
echo "<b>Joined</b> ".$_REQUEST['joined']."<br />";
echo "<b>Abroad</b> ".$_REQUEST['abroad']."<br/>";

echo "<br/>PLEASE MAKE SURE THIS IS CORRECT!  Next to each trip is the number 
you ranked it.  If a trip has a rank of 0, it means you said you could not travel.  
If anything is incorrect, please fill out the form again and contact the webmaster.<br/><br/>";

echo "<b>".$result[0]['description'].": ".$_REQUEST['option_1']."<br/>";
echo "<b>".$result[1]['description'].": ".$_REQUEST['option_2']."<br/>";
echo "<b>".$result[2]['description'].": ".$_REQUEST['option_3']."<br/>";
echo "<b>".$result[3]['description'].": ".$_REQUEST['option_4']."<br/>";
echo "<b>".$result[4]['description'].": ".$_REQUEST['option_5']."<br/>";
echo "<b>".$result[5]['description'].": ".$_REQUEST['option_6']."<br/>";
echo "<b>".$result[6]['description'].": ".$_REQUEST['option_7']."<br/>";
echo "<b>".$result[7]['description'].": ".$_REQUEST['option_8']."<br/>";
echo "<b>Comments</b> ".$_REQUEST['comments']."<br/>";
echo "</p>";




?>

</div> <!-- end main app -->
