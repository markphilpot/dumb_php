<?php
require_once "include/database.php";

$status = 'Error processing tournament form';

global $db;

if( isset($_REQUEST['Submit']) )
{
	if( !isset($_REQUEST['disclaimer']) )
	{
		$status = 'Invalid tournament form.  Check to make sure you have read the disclaimer and ' .
				'checked the box.';
	}
	else
	{
		$statement = $db->prepare("select username, disclaimer from dumb_tournament_form where username = ?");
		$result = $db->execute( $statement , array($_REQUEST['username']) );
		
		// If previous entry
		if( $result->numRows() > 0 )
		{
			$db->getAll( "update dumb_tournament_form set disclaimer = ?, prefer = ?, " .
				"exp = ?, joined = ?, abroad = ?, option_1 = ?, option_2 = ?, option_3 = ?, " .
				"option_4 = ?, option_5 = ?, option_6 = ?, option_6_phone = ?, comments = ? where username = ?", 
				array($_REQUEST['disclaimer'], $_REQUEST['prefer'],
				      $_REQUEST['exp'], $_REQUEST['joined'],
				      $_REQUEST['abroad'], $_REQUEST['option_1'],
				      $_REQUEST['option_2'], $_REQUEST['option_3'],
				      $_REQUEST['option_4'], $_REQUEST['option_5'],
				      $_REQUEST['option_6'], $_REQUEST['option_6_phone'],
				      $_REQUEST['comments'], $_REQUEST['username'] ) );
		}
		else
		{
			$db->getAll( "insert into dumb_tournament_form set disclaimer = ?, prefer = ?, " .
				"exp = ?, joined = ?, abroad = ?, option_1 = ?, option_2 = ?, option_3 = ?, " .
				"option_4 = ?, option_5 = ?, option_6 = ?, option_6_phone = ?, comments = ?, username = ?", 
				array($_REQUEST['disclaimer'], $_REQUEST['prefer'],
				      $_REQUEST['exp'], $_REQUEST['joined'],
				      $_REQUEST['abroad'], $_REQUEST['option_1'],
				      $_REQUEST['option_2'], $_REQUEST['option_3'],
				      $_REQUEST['option_4'], $_REQUEST['option_5'],
				      $_REQUEST['option_6'], $_REQUEST['option_6_phone'],
				      $_REQUEST['comments'], $_REQUEST['username'] ) );
		}
	}
}

?>
<div id="options">
<h3 class="center">&nbsp;</h3>
</div> <!-- end options -->

<div id="main_app">
<h1 class="center">Tournament Receipt</h1>
<p>Please save/print this receipt for your records.</p>
<h4>Form Values</h4>
<?php
$stamp = date("U");
echo "<p><b>Form Submitted</b> -- ".date("F j, Y, g:i a")." --".$stamp."</p>";
$check = crc32($dbPass.$stamp);
printf("<p><b>Checksum</b> -- %u<p>", $check);
echo "<p>";
echo "<b>Disclaimer</b> ".$_REQUEST['disclaimer']."<br/>";
echo "<b>Prefer</b> ".$_REQUEST['prefer']."<br/>";
echo "<b>Exp</b> ".$_REQUEST['exp']."<br />";
echo "<b>Joined</b> ".$_REQUEST['joined']."<br />";
echo "<b>Abroad</b> ".$_REQUEST['abroad']."<br/>";
echo "<b>Option 1</b> ".$_REQUEST['option_1']."<br/>";
echo "<b>Option 2</b> ".$_REQUEST['option_2']."<br/>";
echo "<b>Option 3</b> ".$_REQUEST['option_3']."<br/>";
echo "<b>Option 4</b> ".$_REQUEST['option_4']."<br/>";
echo "<b>Option 5</b> ".$_REQUEST['option_5']."<br/>";
echo "<b>Option 6</b> ".$_REQUEST['option_6']."<br/>";
echo "<b>Phone</b> ".$_REQUEST['option_6_phone']."<br/>";
echo "</p>";

?>

</div> <!-- end main app -->