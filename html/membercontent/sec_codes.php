<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";
require_once 'Date/Date.php';

global $db;

$cat = 1;

if( isset($_REQUEST['cat']) )
{
	$cat = $_REQUEST['cat'];
}

/*
 * Process DB Requests
 */

if( isset($_REQUEST['update']) )
{
	$db->getAll("update dumb_sec_codes set code_name = ?, code = ?, points = ?, category_id = ? where code_id = ?", array($_REQUEST['up_code_name'], $_REQUEST['up_code'], $_REQUEST['up_points'], $_REQUEST['up_code_category'], $_REQUEST['up_code_id']));
}

if( isset($_REQUEST['add']) && isset($_REQUEST['code_name']) )
{
	$db->getAll("insert into dumb_sec_codes values (NULL, ?, ?, ?, ?)", array($_REQUEST['code_category'], $_REQUEST['code_name'], $_REQUEST['code'], $_REQUEST['points']));
}

if( isset($_REQUEST['del_code']) )
{
	$db->getAll("delete from dumb_sec_attendance where code_id = ?", array($_REQUEST['del_code']));
	$db->getAll("delete from dumb_sec_codes where code_id = ?", array($_REQUEST['del_code']));
}

/*
 * Populate general use arrays
 */

$categories = $db->getAll("select * from dumb_sec_categories");
$codes = $db->getAll("select * from dumb_sec_codes where category_id = ? order by points desc", array($cat));

/*
 * Create Add Form
 */
 
$form =& new HTML_QuickForm('entryForm', 'POST', 'members.php?loc=sec_codes');

$r =& new HTML_QuickForm_Renderer_QuickHtml();

// Make Elements
$form->addElement('header', '', 'Add Code');
$form->addElement('text', 'code_name', 'Name:', array('maxlength' => 30));
$form->addElement('text', 'code', 'Code:', array('size' => 4, 'maxlength' => 4));
$form->addElement('text', 'points', 'Points:', array('size' => 3, 'maxlength' => 3));
$entry_cats = array();
while( list($temp, $row) = each($categories) )
{
	$entry_cats[ $row['category_id'] ] = $row['category_name'];
}
$form->addElement('select', 'code_category', 'Category:', $entry_cats);
$form->addElement('submit', 'add', 'Add');

$form->accept($r);

$data = '<h4 class="center">Add Code</h4>' .
		'<b>Category:</b><br /> '.$r->elementToHtml('code_category').'<br />' .
				'<b>Name:</b><br /> '.$r->elementToHtml('code_name').'<br />' .
						'<b>Code:</b><br /> '.$r->elementToHtml('code').'<br />' .
								'<b>Points:</b><br />' .$r->elementToHtml('points');

/*
 * Create Update Form (if requested)
 */
$up_form =& new HTML_QuickForm('updateForm', 'POST', 'members.php?loc=sec_codes');

$up_r =& new HTML_QuickForm_Renderer_QuickHtml();

$up_data;

if( isset($_REQUEST['edit_code']) )
{
	$edit_event = $db->getAll("select * from dumb_sec_codes where code_id = ?", array($_REQUEST['edit_code']));
	while( list($temp, $row) = each($edit_event) ) // One row
	{
		$form_defaults = array( 'up_code_name' => $row['code_name'],
										'up_code_category' => $row['category_id'],
										'up_code_id' => $row['code_id'],
										'up_code' => $row['code'],
										'up_points' => $row['points']);
		
		// Make Elements
		$up_form->setDefaults($form_defaults);
		$up_form->addElement('header', '', 'Update Code');
		$up_form->addElement('hidden', 'up_code_id', 'code_id');
		$up_form->addElement('text', 'up_code_name', 'Name:', array('maxlength' => 30));
		$up_form->addElement('text', 'up_code', 'Code:', array('size' => 4, 'maxlength' => 4));
		$up_form->addElement('text', 'up_points', 'Points:', array('size' => 3, 'maxlength' => 3));
		$entry_cats = array();
		reset($categories);
		while( list($temp, $row) = each($categories) )
		{
			$entry_cats[ $row['category_id'] ] = $row['category_name'];
		}
		$up_form->addElement('select', 'up_code_category', 'Category:', $entry_cats);
		$up_form->addElement('submit', 'update', 'Update');
		
		$up_form->accept($up_r);
		
		$up_data = '<h4 class="center">Update Code</h4>' . $up_r->elementToHtml('up_code_id') .
				'<b>Category:</b><br /> '.$up_r->elementToHtml('up_code_category').'<br />' .
						'<b>Name:</b><br /> '.$up_r->elementToHtml('up_code_name').'<br />' .
								'<b>Code:</b><br /> '.$up_r->elementToHtml('up_code').'<br />' .
										'<b>Points:</b><br />' .$up_r->elementToHtml('up_points');
	}
}

?>
<div id="options">
<h3 class="center">&nbsp;</h3>
<form name="sec_codes" method="post" action="members.php?loc=sec_codes">
<div class="border">
<h4 class="center">Select Category</h4>
<select name="cat">
<?php
reset($categories);
while( list($temp, $row) = each($categories) )
{
	if( $row['category_id'] == $cat )
	{
		print "<option value='".$row['category_id']."' selected>".$row['category_name']."</option>\n";
	}
	else
	{
		print "<option value='".$row['category_id']."'>".$row['category_name']."</option>\n";
	}
}
?>
</select>
  <input type="submit" name="view" value="Go" />
</div>
</form>
<div class="border">
<?php
echo $r->toHtml($data);
?>
</div>
<?php
if( isset($_REQUEST['edit_code']) )
{
	print "<div class='border'>";
	print $up_r->toHtml($up_data);
	print "</div>";
}
?>
<h5 class="center"><a href="members.php?loc=attendance">Back</a></h5>
</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Edit Attendance Codes</h3>

<script type="text/javascript">
	function delete_value(loc)
	{
		var c = confirm("Are you sure you want to delete this code?"+'\n'+"This action cannot be undone.")
		if( c == true )
		{
			location = loc
		}
	}
</script>

<?php

startTable(1,1,1,"90%");

startClassRow("table_header");
startHeaderColumn();
print "Name";
nextHeaderColumn();
print "Code";
nextHeaderColumn();
print "Points";
nextHeaderColumn();
print "&nbsp;";
endHeaderColumn();

$i = 0;

while( list($temp, $row) = each($codes) )
{
	if( $i % 2 == 0 )
	{
		startRow();
	}
	else
	{
		startClassRow("table_alt");
	}
	
	startColumn();
	print $row['code_name'];
	nextColumn();
	print $row['code'];
	nextColumn();
	print $row['points'];
	nextColumn();
	print "<a href='members.php?loc=sec_codes&edit_code=".$row['code_id']."'>Edit</a> - <a href=\"javascript:delete_value('members.php?loc=sec_codes&del_code=".$row['code_id']."')\">Remove</a>";
	endColumn();
	endRow();
	
}

endTable();

?>

</div> <!-- end main app -->