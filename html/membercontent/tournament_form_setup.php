<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";

global $db;

/*
 * Process form
 */
if(isset($_REQUEST['update']))
{
	for( $i = 1; $i <= $_REQUEST['num_options']; $i++ )
	{
		$db->getAll('update dumb_tournament_options set date = ?, description = ? where option_id = ?', array($_REQUEST[$i.'_date'], $_REQUEST[$i.'_text'], $i));
		$value = $_REQUEST[$i.'_enabled'];
		if( $value == null )
		{
			$value = "false";
		}
		else
		{
			$value = "true";
		}
		$db->getAll('update dumb_setup set value = ? where parameter = ?', array($value, 'tournament_option'.$i));
	}
}

/*
 * Retrieve all options
 */

$options = $db->getAll('select * from dumb_tournament_options');

/*
 * Create form
 */

$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=tournament_form_setup');

$r =& new HTML_QuickForm_Renderer_QuickHtml();

$i = 0;
$form_defaults = array();
while(list($t, $row) = each($options))
{
	$form_defaults[$row['option_id'].'_date'] = $row['date'];
	$form_defaults[$row['option_id'].'_text'] = $row['description'];
	$enabled = $db->getAll('select * from dumb_setup where parameter = ?', array('tournament_option'.$row['option_id']));
	list($t, $o) = each($enabled);
	if($o['value'] == "true")
	{
		$form_defaults[$row['option_id'].'_enabled'] = true;
	}
	$form->addElement('text', $row['option_id'].'_date', null, array('size' => 30, 'maxlength' => 30));
	$form->addElement('text', $row['option_id'].'_text', null, array('size' => 40, 'maxlength' => 40));
	$form->addElement('checkbox', $row['option_id'].'_enabled', null, null);
	$i++;
}
$form->addElement('hidden', 'num_options', null );
$form->addElement('submit', 'update', 'Update');

$form_defaults['num_options'] = $i;
$form->setDefaults($form_defaults);

$form->accept($r);

$data = '<table width="100%" border="1" cellspacing="0" cellpadding="2">' . $r->elementToHtml('num_options') .
		'<tr class="table_header"> <th>Option</th> <th>Date</th> <th>Description</th> <th>Enabled</th> </tr>';

reset($options);
while(list($t, $row) = each($options))
{
	$data .= '<tr> <td>'.$row['option_id'].'</td> <td>'.$r->elementToHtml($row['option_id'].'_date').'</td> <td>'.$r->elementToHtml($row['option_id'].'_text').'</td> <td>'.$r->elementToHtml($row['option_id'].'_enabled').'</td> </tr>';
}
$data .= '</table>';
						
?>
<div id="options">
<h3 class="center">&nbsp;</h3>
<div class="border">
<h4 class="center">Form Options</h4>
<p>&#149; <a href="members.php?loc=tournament_form&preview=true">Form Preview</a>
</p>
</div>
</div> <!-- end options -->

<div id="main_app">
<h1 class="center">Tournament Form Setup</h1>
<p>Below are the six options of the tournament form.  You can edit the title of the Option,
but it is recommended that you stick with the current setup for continuity between years.
Edit the date field to select the weekend of the Option (e.g. March 7-10, 2003).</p>
<?php
echo $r->toHtml($data);

?>

</div> <!-- end main app -->