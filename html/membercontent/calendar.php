<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";
require_once 'Date/Date.php';

require_once('include/FCKeditor/fckeditor.php');

global $db;

/*
 * Create form
 */

$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=calendar');

$form->registerElementType('fckeditor','include/fckelement.php','HTML_QuickForm_fckeditor');


/*
 * Process form
 */

$form_data = $form->getSubmitValues();

if( isset($form_data['add']) )
{
	// Process date
	$date = $form_data['date']['Y'] .'-'. $form_data['date']['m'] .'-'. $form_data['date']['d'];
	$db->getAll('insert into dumb_calendar values(NULL, ?, ?, ?)', array($form_data['title'], $form_data['details'], $date) );
}

if( isset($form_data['update']) )
{
	$date = $form_data['date']['Y'] .'-'. $form_data['date']['m'] .'-'. $form_data['date']['d'];
	$db->getAll('update dumb_calendar set details = ?, title = ?, date = ? where calendar_id = ?', array($form_data['details'], $form_data['title'], $date, $form_data['id']) );
}

if( isset($_REQUEST['del_id']) )
{
	$db->getAll('delete from dumb_calendar where calendar_id = ?', array($_REQUEST['del_id']));
}

?>

<div id="options">
<h3 class="center">&nbsp;</h3>

</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Calendar Management</h3>
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
	
<?php

	
 	$r =& new HTML_QuickForm_Renderer_QuickHtml();
	
	$data;
	
	if( isset($_REQUEST[edit_id]) )
	{
		// Set defaults
		$entry = $db->getAll("select * from dumb_calendar where calendar_id = ?", array($_REQUEST[edit_id]));
		
		list( $temp, $row ) = each($entry); // only one row returned
		
		$date = expandDate($row['date']);
		$date_defaults = array( 'd' => $date['Day'], 'm' => $date['Month'], 'Y' => $date['Year'] );
		$form_defaults = array( 'details' => $row['details'],
										'title' => $row['title'],
										'date' => $date_defaults,
										'id' => $row['calendar_id']);

		$form->addElement('date', 'date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2003, 'maxYear'=>2020));
		$form->addElement('text', 'title', 'Title:');
		$form->addElement("fckeditor","details",'',array( 'toolbarset'=>'Content' ,'width' => '100%', 'height' => 300 ));
		$form->addElement('hidden', 'id', 'id');
		$form->addElement('submit', 'update', 'Update');
		$form->setDefaults($form_defaults);
		
		$form->accept($r);
		
		$data = '<table width="100%" border="0" cellspacing="0" cellpadding="1">' .
			'<tr> <th class="table_header">Update Event</th>' .
			'<td rowspan="2">'.$r->elementToHtml('details'). $r->elementToHtml('id').'</td> </tr>' .
			'<tr> <td width="250" valign"top"> <p>Date: '.$r->elementToHtml('date').'<br />Title: ' .
			$r->elementToHtml('title') . '</p><p>'.$r->elementToHtml('update').'</p>' .
			'</td> </tr> </table>';
	}
	else
	{
		$date_defaults = array( 'd' => date('d'), 'm' => date('m'), 'Y' => date('Y'));
		$form->setDefaults(array('date' => $date_defaults));
		// Make Elements
		$form->addElement('header', '', 'Add Entry');
		$form->addElement('date', 'date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2003, 'maxYear'=>2020));
		$form->addElement('text', 'title', 'Title:');
		$form->addElement("fckeditor","details",'',array( 'toolbarset'=>'Content' ,'width' => '100%', 'height' => 300 ));
		$form->addElement('submit', 'add', 'Add');
		
		$form->accept($r);
		
		$data = '<table width="100%" border="0" cellspacing="0" cellpadding="1">' .
			'<tr> <th class="table_header">Add Event</th>' .
			'<td rowspan="2">'.$r->elementToHtml('details').'</td> </tr>' .
			'<tr> <td width="250" valign"top"> <p>Date: '.$r->elementToHtml('date').'<br />Title: ' .
			$r->elementToHtml('title') . '</p><p>'.$r->elementToHtml('add').'</p>' .
			'</td> </tr> </table>';
	}
	
	print "<div class='border'>";
	echo $r->toHtml($data);
	print "</div>";
	
	$result = $db->getAll("select * from dumb_calendar order by date asc");
		
	startTable(2, 0, 1, "100%");
	startClassRow("table_header");
	startHeaderColumn();
	print "Date";
	nextHeaderColumn();
	print "Title";
	nextHeaderColumn();
	print "Details";
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
		
		$entry_date = new Date($row['date'] . ' 00:00:00');
		
		startColumn();
		print $entry_date->format('%m-%d-%Y');
		nextColumn();
		print $row['title'];
		nextColumn();
		print $row['details'];
		nextColumn();
		print "<a href='members.php?loc=calendar&edit_id=".$row['calendar_id']."'>Edit</a> - <a href=\"javascript:delete_value('members.php?loc=calendar&del_id=".$row['calendar_id']."')\">Delete</a>";
		endColumn();
		endRow();
	}
	
	endTable();
	
?>
</div> <!-- end main_app -->
