<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";

global $db;

/*
 * Create Form
 */

$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=edit_member');

/*
 * Process form if needed
 */

$form_data = $form->getSubmitValues();

if( isset($form_data['update']) )
{
	$db->getAll("update dumb_members set firstname = ?, lastname = ?, " .
			"instrument_id = ?, pepband = ?, " .
			"year = ?, phone = ?, email = ? where username = ?", array($form_data['firstname'],
																						  $form_data['lastname'],
																						  $form_data['instrument'],
																						  $form_data['pepband'],
																						  $form_data['year']['Y'],
																						  $form_data['phone'],
																						  $form_data['email'],
																						  $_REQUEST['username']));
																						  
	// Clear existing officer information
	$db->getAll("delete from dumb_officer_list where username = ?", array($_REQUEST['username']));
	$db->getAll("delete from dumb_priv where username = ?", array($_REQUEST['username']));

	// Loop over officer array
	if(isset($form_data['officer']) && is_array($form_data['officer']))
	{
		while( list($off, $item) = each($form_data['officer']) )
		{
			$db->getAll("insert into dumb_officer_list values (?, ?)", array($_REQUEST['username'], $off));
			
			$priv = $db->getAll("select * from dumb_officers, dumb_officer_priv where " .
					"dumb_officers.officer_id = dumb_officer_priv.officer_id and dumb_officers.officer_id = ?", array($off));
			
			while( list($temp, $row) = each($priv) )
			{
				$db->getAll("insert into dumb_priv values (?, ?)", array($_REQUEST['username'], $row['priv']));
			}
		}
	}
}
 
/*
 *  Retrieve Information
 */

if( !isset($_REQUEST['username']) )
{
	die("Incorrect Username");
}

/*
 * Reset Password
 */
$password_update = "";
if( isset($_REQUEST['reset_password']) )
{
	$db->getAll("update dumb_members set password = md5(?) where username = ?", array( "dumb@123", $_REQUEST['username']));
	$password_update = "Password Reset.";
}

$member = $db->getAll("select * from dumb_members where username = ?", array($_REQUEST['username']));
$officer = $db->getAll("select * from dumb_officer_list, dumb_officers where " .
		"dumb_officer_list.officer_id = dumb_officers.officer_id and " .
		"username = ?", array($_REQUEST['username']));

// Support
$officers = $db->getAll("select * from dumb_officers");
$instruments = $db->getAll("select * from dumb_instruments");

/*
 * Create Form
 */

$r =& new HTML_QuickForm_Renderer_QuickHtml();

$form->addElement('hidden', 'username', null);
$form->addElement('text', 'firstname', null, array('size' => 20, 'maxlength' =>20 ));
$form->addElement('text', 'lastname', null, array('size' => 30, 'maxlength' => 30 ));
$inst = array();
while( list($temp, $row) = each($instruments) )
{
	$inst[ $row['instrument_id'] ] = $row['instrument'];
}
$form->addElement('select', 'instrument', null, $inst);
$form->addElement('text', 'pepband', null, array('size' => 1, 'maxlength' => 1));
$form->addElement('date', 'year', null, array('format'=>'Y', 'minYear'=>2003, 'maxYear'=>2012));
$form->addElement('text', 'phone', null, array('size' => 14, 'maxlength' => 14 ));
$form->addElement('text', 'email', null, array('size' => 30, 'maxlength' => 30 ));


$checkbox = array();
while( list($temp, $row) = each($officers) )
{
	array_push($checkbox, HTML_QuickForm::createElement('checkbox', $row['officer_id'], null, $row['title']));
}
// Creates a checkboxes group
$form->addGroup($checkbox, 'officer', null, ' <br /> ');

$form->addElement('submit', 'update', 'Update');

list($temp, $row) = each($member); // Only one row

$officer_vals;

while( list($temp, $off) = each($officer) )
{
	$officer_vals[$off['officer_id']] = true;
}

// Set Defaults
$date_defaults = array( 'd' => 1, 'm' => 1, 'Y' => $row['year'] );
$form_defaults = array( 'username' => $row['username'],
								'firstname' => $row['firstname'],
								'lastname' => $row['lastname'],
								'instrument' => $row['instrument_id'],
								'pepband' => $row['pepband'],
								'year' => $date_defaults,
								'phone' => $row['phone'],
								'email' => $row['email'],
								'officer' => $officer_vals);

$form->setDefaults($form_defaults);

$form->accept($r);

$data = '<table width="50%" border="1" cellspacing="0" cellpadding="1">' .
		'<tr> <th class="table_header">Username</th> <td>'. $_REQUEST['username'] . '</td> </tr>' .
		'<tr> <th class="table_header">First Name</th> <td>'.$r->elementToHtml('firstname').'</td> </tr>' .
		'<tr> <th class="table_header">Last Name</th> <td>'.$r->elementToHtml('lastname').'</td> </tr>' .
		'<tr> <th class="table_header">Instrument</th> <td>'.$r->elementToHtml('instrument').'</td> </tr>' .
		'<tr> <th class="table_header">Pep Band</th> <td>'.$r->elementToHtml('pepband').'</td> </tr>' .
		'<tr> <th class="table_header">Year</th> <td>'.$r->elementToHtml('year').'</td> </tr>' .
		'<tr> <th class="table_header">Phone</th> <td>'.$r->elementToHtml('phone').'</td> </tr>' .
		'<tr> <th class="table_header">Email</th> <td>'.$r->elementToHtml('email').'</td> </tr>' .
		'<tr> <th class="table_header" colspan="2">Officer</th> </tr>' .
		'<tr> <td colspan="2">'.$r->elementToHtml('officer').'</td> </tr>' .
		'</table>';
?>

<div id="options">
<h3 class="center">&nbsp;</h3>
<div class="border">
<h4 class="center">Member Options</h4>
<form name="resetPwd" method="post" action="members.php?loc=edit_member&username=<?php print $_REQUEST['username']; ?>">
	<p>Reset Password (dumb@123): 
		<input type="submit" name="reset_password" value="Reset">
	</p>
</form>
<?php print "<p>$password_update</p>"; ?>
</div>
<h5 class="center"><a href="members.php?loc=roster">Back</a></h5>
</div> <!-- end options -->

<div id="main_app">

<h3 class="center">Edit Memeber</h3>
	
<?php
echo $r->toHtml($data);	
?>

</div> <!-- end main_app -->
