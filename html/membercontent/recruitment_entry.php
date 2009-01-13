<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";

global $db;

/*
 * Create Form
 */
if(!isset($_REQUEST['entry_id']))
{
   die("invalid recruitment entry_id");
}

$entry_id = $_REQUEST['entry_id'];

$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=recruitment_entry&entry_id='.$entry_id);

/*
 * Process form if needed
 */

$form_data = $form->getSubmitValues();

if( isset($form_data['update']) )
{
   
   $db->getAll("update dumb_recruitment set poc = ?, log = ? where id = ?", array($form_data['poc'],
   																										 $form_data['log'],
   																										 $entry_id));
}
 

$entry = $db->getAll("select * from dumb_freshmen_form where id = ?", array($entry_id));
$details = $db->getAll("select * from dumb_recruitment where id = ?", array($entry_id));

list($temp, $rec) = each($entry); // Only one entry
list($temp, $det) = each($details); // Only one entry

/*
 * Create Form
 */

$r =& new HTML_QuickForm_Renderer_QuickHtml();

$form->addElement('text', 'poc', 'Dumb POC', array('size' => 40, 'maxlength' =>40 ));
$form->addElement('textarea', 'log', 'Contact Log' );

$form->addElement('submit', 'update', 'Update');

// Set Defaults
$form_defaults = array( 'poc' => $det['poc'],
								'log' => $det['log']);

$form->setDefaults($form_defaults);

$form->accept($r);

$data = '<table width="95%" border="1" cellspacing="0" cellpadding="1">' .
		'<tr> <th class="table_header">Dumb POC</th> <td>'. $r->elementToHtml('poc') . '</td> </tr>' .
		'<tr> <th class="table_header">Last Updated</th> <td>'.$det['last_update'].'</td> </tr>' .
		'<tr> <th class="table_header">Contact Log</th> <td>'.$r->elementToHtml('log').'</td> </tr>' .
		'<tr> <th>&nbsp;</th> <td>'.$r->elementToHtml('update').'</td> </tr>' .
		'</table>';

print '<div id="options">';
print '<h3 class="center">&nbsp;</h3>';
print '<div class="border">';
print '<h4 class="center">Recruit Functions</h4>';
print '</div>';
print '<h5 class="center"><a href="members.php?loc=recruitment">Back</a></h5>';
	if($_SESSION['user']->has_priv("recruitment")){
		print '<h5 class="center"><a href="members.php?loc=recruitment_edit&entry_id='.$entry_id.'">Edit</a></h5>';
      }
?>
</div> <!-- end options -->

<div id="main_app">

<h3 class="center">Recruit</h3>

<div id="entry">
<h4 class="center">Contact Information</h4>
<img style="float: right;" src="<?php echo $det['picture_file']; ?>" alt="image" />
<p>
<?php // Display recruit
	echo $rec['name']."<br/>";
	echo $rec['address']."<br/>";
	echo $rec['city'].", ".$rec['state']." ".$rec['zip'];
?>
</p>
<p><a href="mailto:<?php echo $rec['email']; ?>"><?php echo $rec['email'];?></a></p>
<p><?php echo $rec['phone']; ?></p>
<h4 class="center">Details</h4>
<p><b>Instrument</b> -- <?php echo $rec['instrument']; ?></p>
<p><b>Highschool/Band Director</b> -- <?php echo $rec['highschool'].' / '.$rec['director']; ?></p>
<p><b>Graduation Year</b> -- <?php echo $rec['graduation']; ?></p>
<p><b>Intended Major</b> -- <?php echo $rec['major']; ?></p>
<p><b>Shirt Size</b> -- <?php echo $rec['size']; ?></p>
<p><b>Questions</b></p>
<p><blockquote><?php echo $rec['questions']; ?></blockquote></p>

<h4 class="center">Recruitment Communication</h4>
<?php
echo $r->toHtml($data);	
?>
</div>

</div> <!-- end main_app -->
