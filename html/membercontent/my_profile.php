<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";

global $db;

/*
 * Create Form
 */

$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=my_profile');

$status = "";

/*
 * Process form if needed
 */

$form_data = $form->getSubmitValues();

if( isset($form_data['update']) )
{
	$db->getAll("update dumb_members set firstname = ?, lastname = ?, " .
			"instrument_id = ?, " .
			"year = ?, phone = ?, email = ? where username = ?", array($form_data['firstname'],
																						  $form_data['lastname'],
																						  $form_data['instrument'],
																						  $form_data['year']['Y'],
																						  $form_data['phone'],
																						  $form_data['email'],
																						  $_SESSION['user']->username));
	$status = "Member information updated<br />";
																						  
}

if( isset($form_data['set_password']) )
{
	if( $form_data['password'] == $form_data['confirm_pass'] )
	{
		$db->getAll("update dumb_members set password = md5(?) where username = ?", array($form_data['password'], $_SESSION['user']->username));
		$status .= "Password updated";	
	}
	else
	{
		$status .= "Unable to update password.";
	}
}
 
/*
 *  Retrieve Information
 */

if( $_SESSION['user']->is_admin )
{
	die("Profile not available for admin or director");
}

$member = $db->getAll("select * from dumb_members where username = ?", array($_SESSION['user']->username));
$officer = $db->getAll("select * from dumb_officer_list, dumb_officers where " .
		"dumb_officer_list.officer_id = dumb_officers.officer_id and " .
		"username = ?", array($_SESSION['user']->username));

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
$form->addElement('date', 'year', null, array('format'=>'Y', 'minYear'=>2013, 'maxYear'=>2030));
$form->addElement('text', 'phone', null, array('size' => 14, 'maxlength' => 14 ));
$form->addElement('text', 'email', null, array('size' => 30, 'maxlength' => 30 ));
$form->addElement('password', 'password', null, array('size' => 10, 'maxlength' => 10));
$form->addElement('password', 'confirm_pass', null, array('size' => 10, 'maxlength' => 10));

$form->addElement('submit', 'update', 'Update Profile');
$form->addElement('submit', 'set_password', 'Set Password');

list($temp, $row) = each($member); // Only one row

$officer_values = " - ";
while( list($temp, $off) = each($officer) )
{
	$officer_values .= $off['title'];
	$officer_values .= " - ";
}

// Set Defaults
$date_defaults = array( 'd' => 1, 'm' => 1, 'Y' => $row['year'] );
$form_defaults = array( 'username' => $row['username'],
								'firstname' => $row['firstname'],
								'lastname' => $row['lastname'],
								'instrument' => $row['instrument_id'],
								'year' => $date_defaults,
								'phone' => $row['phone'],
								'email' => $row['email']);

$form->setDefaults($form_defaults);

$form->accept($r);

$data = '<table width="50%" border="1" cellspacing="0" cellpadding="1">' .
		'<tr> <th class="table_header">Username</th> <td>'. $_SESSION['user']->username . '</td> </tr>' .
		'<tr> <th class="table_header">Set Password</th> <td>' . $r->elementToHtml('password') . ' Confirm: ' . $r->elementToHtml('confirm_pass') . $r->elementToHtml('set_password'). '</td> </tr>' .
		'<tr> <th class="table_header">First Name</th> <td>'.$r->elementToHtml('firstname').'</td> </tr>' .
		'<tr> <th class="table_header">Last Name</th> <td>'.$r->elementToHtml('lastname').'</td> </tr>' .
		'<tr> <th class="table_header">Instrument</th> <td>'.$r->elementToHtml('instrument').'</td> </tr>' .
		'<tr> <th class="table_header">Year</th> <td>'.$r->elementToHtml('year').'</td> </tr>' .
		'<tr> <th class="table_header">Phone</th> <td>'.$r->elementToHtml('phone').'</td> </tr>' .
		'<tr> <th class="table_header">Email</th> <td>'.$r->elementToHtml('email').'</td> </tr>' .
		'<tr> <th class="table_header" colspan="2">Officer</th> </tr>' .
		'<tr> <td colspan="2">'.$officer_values.'</td> </tr>' .
		'</table>';
?>

<div id="options">
<h3 class="center">&nbsp;</h3>
<p> <?php echo $status ?> </p>
<h5 class="center"><a href="members.php?loc=index">Back</a></h5>
</div> <!-- end options -->

<div id="main_app">

<h3 class="center">My Profile</h3>
	
<?php
echo $r->toHtml($data);	
?>

</div> <!-- end main_app -->
