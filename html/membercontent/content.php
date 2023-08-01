<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";
require_once 'Date/Date.php';

require_once('include/FCKeditor/fckeditor.php');

global $db;

$area = "front";
$preview = "";

if( isset($_REQUEST['area']) )
{
	$area = $_REQUEST['area'];
}

/*
 * Create form
 */

$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=content');

$form->registerElementType('fckeditor','include/fckelement.php','HTML_QuickForm_fckeditor');


/*
 * Process form
 */

$form_data = $form->getSubmitValues();

if( isset($form_data['add']) )
{
	// Process date
	$date = $form_data['entry_date']['Y'] .'-'. $form_data['entry_date']['m'] .'-'. $form_data['entry_date']['d'];
	$db->getAll('insert into dumb_content values(NULL, ?, ?, ?, ?)', array($date, $form_data['entry_title'], $form_data['entry_content'], $form_data['area']) );
}

if( isset($form_data['update']) )
{
	$date = $form_data['up_entry_date']['Y'] .'-'. $form_data['up_entry_date']['m'] .'-'. $form_data['up_entry_date']['d'];
	$db->getAll('update dumb_content set content = ?, title = ?, date = ? where content_id = ?', array($form_data['up_entry_content'], $form_data['up_entry_title'], $date, $form_data['up_entry_id']) );
}

if( isset($form_data['update_area']) )
{
	$date = $form_data['up_entry_date']['Y'] .'-'. $form_data['up_entry_date']['m'] .'-'. $form_data['up_entry_date']['d'];
	$db->getAll('update dumb_content set content = ?, title = ?, date = ? where loc = ?', array($form_data['entry_content'], $form_data['entry_title'], $date, $form_data['area']));
}

if( isset($_REQUEST['del_cid']) )
{
	$db->getAll('delete from dumb_content where content_id = ?', array($_REQUEST['del_cid']));
}

$locList = $db->getAll('select distinct loc from dumb_content');

?>

<div id="options">
<h3 class="center">&nbsp;</h3>

<div class="border">
<h4 class="center">Content Areas</h4>
<p>
<?php

while( list($temp, $row) = each($locList) )
{
		
	$l = $row['loc'];
	print "&#149; <a href='members.php?loc=content&area=$l'>$l</a><br />";
}
?>
</p>
</div>

</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Content Management</h3>
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
if( $area == "front" || $area == "news" )
{
	$page_name = ($area == "front") ? "Front Page" : "News Page";
	
 	$r =& new HTML_QuickForm_Renderer_QuickHtml();
	
	$data;
	
	if( isset($_REQUEST[edit_id]) )
	{
		// Set defaults
		$entry = $db->getAll("select * from dumb_content where content_id = ?", array($_REQUEST[edit_id]));
		
		list( $temp, $row ) = each($entry); // only one row returned
		
		$date = expandDate($row['date']);
		$date_defaults = array( 'd' => $date['Day'], 'm' => $date['Month'], 'Y' => $date['Year'] );
		$form_defaults = array( 'up_entry_content' => $row['content'],
										'up_entry_title' => $row['title'],
										'up_entry_date' => $date_defaults,
										'up_entry_id' => $row['content_id']);

		$form->addElement('date', 'up_entry_date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2013, 'maxYear'=>2030));
		$form->addElement('text', 'up_entry_title', 'Title:');
		//$form->addElement('textarea', 'up_entry_content', '', array('rows' => 7, 'cols' => 60));
		$form->addElement("fckeditor","up_entry_content",'',array( 'toolbarset'=>'Content' ,'width' => '100%', 'height' => 300 ));
		$form->addElement('hidden', 'area', $area);
		$form->addElement('hidden', 'up_entry_id', 'id');
		$form->addElement('submit', 'update', 'Update');
		$form->setDefaults($form_defaults);
		
		$form->accept($r);
		
		$data = '<table width="100%" border="0" cellspacing="0" cellpadding="1">' .
			'<tr> <th class="table_header">Update Entry</th>' .
			'<td rowspan="2">'.$r->elementToHtml('up_entry_content'). $r->elementToHtml('up_entry_id').'</td> </tr>' .
			'<tr> <td width="250" valign"top"> <p>Date: '.$r->elementToHtml('up_entry_date').'<br />Title: ' .
			$r->elementToHtml('up_entry_title') . '</p><p>'.$r->elementToHtml('update').'</p>' .
			'</td> </tr> </table>' . $r->elementToHtml('area');
	}
	else
	{
		$date_defaults = array( 'd' => date('d'), 'm' => date('m'), 'Y' => date('Y'));
		$form->setDefaults(array('entry_date' => $date_defaults));
		// Make Elements
		$form->addElement('header', '', 'Add Entry');
		$form->addElement('date', 'entry_date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2013, 'maxYear'=>2030));
		$form->addElement('text', 'entry_title', 'Title:');
		//$form->addElement('textarea', 'entry_content', '', array('rows' => 7, 'cols' => 60));
		$form->addElement("fckeditor","entry_content",'',array( 'toolbarset'=>'Content' ,'width' => '100%', 'height' => 300 ));
		$form->addElement('hidden', 'area', $area);
		$form->addElement('submit', 'add', 'Add');
		
		$form->accept($r);
		
		$data = '<table width="100%" border="0" cellspacing="0" cellpadding="1">' .
			'<tr> <th class="table_header">Add Entry</th>' .
			'<td rowspan="2">'.$r->elementToHtml('entry_content').'</td> </tr>' .
			'<tr> <td width="250" valign"top"> <p>Date: '.$r->elementToHtml('entry_date').'<br />Title: ' .
			$r->elementToHtml('entry_title') . '</p><p>'.$r->elementToHtml('add').'</p>' .
			'</td> </tr> </table>' . $r->elementToHtml('area');
	}
	
	print "<div class='border'>";
	echo $r->toHtml($data);
	print "</div>";
	
	print "<h4>".$page_name."</h4>";
	
	$result = $db->getAll("select * from dumb_content where loc = ? order by date desc", array($area) );
		
	startTable(2, 0, 1, "100%");
	startClassRow("table_header");
	startHeaderColumn();
	print "Date";
	nextHeaderColumn();
	print "Title";
	nextHeaderColumn();
	print "Entry";
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
		print $row['content'];
		nextColumn();
		print "<a href='members.php?loc=content&area=".$area."&edit_id=".$row['content_id']."'>Edit</a> - <a href=\"javascript:delete_value('members.php?loc=content&area=".$area."&del_cid=".$row['content_id']."')\">Delete</a>";
		endColumn();
		endRow();
	}
	
	endTable();
	
	?>
		
		
<?php
}
else
{
	// Big content, DIM, Attendance policy, etc
	$result = $db->getAll("select * from dumb_content where loc = ?", array($area) );
	
	// Will only be one entry
	while( list($temp, $row) = each($result) )
	{
	 	$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=content');
		
		$r =& new HTML_QuickForm_Renderer_QuickHtml();
		
		$date = expandDate($row['date']);
		$date_defaults = array( 'd' => $date['Day'], 'm' => $date['Month'], 'Y' => $date['Year'] );
		$form_defaults = array( 'entry_content' => $row['content'],
										'entry_title' => $row['title'],
										'entry_date' => $date_defaults );
		
		
		$form->setDefaults( $form_defaults );
		
		// Make Elements
		$form->addElement('header', '', 'Edit Entry');
		$form->addElement('date', 'entry_date', 'Date:', array('format'=>'m-d-Y', 'minYear'=>2013, 'maxYear'=>2030));
		$form->addElement('text', 'entry_title', 'Title:');
		//$form->addElement('textarea', 'entry_content', '', array('rows' => 25, 'cols' => 80));
		$form->addElement("fckeditor","entry_content",'',array( 'toolbarset'=>'Content' ,'width' => '100%', 'height' => 500 ));
		$form->addElement('hidden', 'area', $area);
		$form->addElement('submit', 'update_area', 'Update');
		
		$form->accept($r);
		
		$data = '<table width="100%" border="0" cellspacing="1" cellpadding="1">' .
			'<tr> <th class="table_header">'.$r->elementToHtml('entry_title').'<br/>'.$r->elementToHtml('entry_date').'</th> </tr>' .
			'<tr> <td>'.$r->elementToHtml('entry_content').'</td> </tr>' .
			'<tr> <td>'.$r->elementToHtml('update_area') . $r->elementToHtml('area') . '</td> </tr> </table>';

		print "<div class='border'>";
		echo $r->toHtml($data);
		print "</div>";
		
		print "<h4>Preview</h4>";
		
		print "<div class='border'>";
		print $row['content'];
		print "</div>";
	}
}
?>

</div> <!-- end main_app -->
