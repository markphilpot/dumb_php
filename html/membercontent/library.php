<?php
require_once('include/html_util.php');
global $db;

function recursive_del_dir($dir_id)
{
	global $db;
	
	// Delete all files in current directory
	$db->getAll("delete from dumb_library where lib_dir_id = ?", array($_REQUEST['del_dir_id']));
	
	// Find all sub directories
	$sub_dirs = $db->getAll("select * from dumb_library_dir where parent_id = ?", array($dir_id));
	
	while(list($temp, $row) = each($sub_dirs))
	{
		// For each sub directory
		recursive_del_dir($row['lib_dir_id']);
	}
	
	// Delete current directory on the way up
	$db->getAll("delete from dumb_library_dir where lib_dir_id = ?", array($dir_id));
}

if(!isset($_REQUEST['dir_id']))
{
	$_REQUEST['dir_id'] = "1";
}

if(isset($_REQUEST['del_id']))
{
	// Delete file
	$db->getAll("delete from dumb_library where lib_id = ?", array($_REQUEST['del_id']));
}

if(isset($_REQUEST['del_dir_id']))
{
	// Delete directory, need to be recursive.
	recursive_del_dir($_REQUEST['del_dir_id']);
}

if(isset($_REQUEST['create']))
{
	$db->getAll("insert into dumb_library_dir values( NULL, ?, ?)", array($_REQUEST['dir_id'], $_REQUEST['new_dir']));
}

if(isset($_REQUEST['upload']) && $_FILES['user_file']['size'] > 0)
{
	$file_name = $_FILES['user_file']['name'];
	$tmp_name = $_FILES['user_file']['tmp_name'];
	$file_size = $_FILES['user_file']['size'];
	$file_type = $_FILES['user_file']['type'];
	
	$fp = fopen($tmp_name, 'r');
	$content = fread($fp, filesize($tmp_name));
	fclose($fp);
	
	$db->getAll("insert into dumb_library values(NULL, ?, ?, ?, ?, ?, ?)", array( $_REQUEST['dir_id'],
																											$file_name,
																											$file_type,
																											$file_size,
																											$content,
																											$_REQUEST['user_description']));
}

/*
 * Select current files in dir_id
 */

$files = $db->getAll("select lib_id, name, type, size, description from dumb_library where lib_dir_id = ? order by name", array($_REQUEST['dir_id']));
$dirs = $db->getAll("select * from dumb_library_dir where parent_id = ? order by dir_name", array($_REQUEST['dir_id']));

/*
 * Get current directory informaiton
 */


$continue = true;
$curr = $_REQUEST['dir_id'];
$path = "";
while( $continue )
{
	$current_dir = $db->getAll("select * from dumb_library_dir where lib_dir_id = ?", array($curr));
	list($temp, $row) = each($current_dir); // One row
	
	$dir_str = " / <a href='members.php?loc=library&dir_id=".$row['lib_dir_id']."'>".$row['dir_name']."</a>";
	$temp = $dir_str . $path;
	$path = $temp;
	
	$curr = $row['parent_id'];
	
	if( $curr == 0 )
	{
		// Root
		$continue = false;
	}
}

?>
<div id="options">
<?php
if($_SESSION['user']->has_priv('library'))
{
?>
<h3 class="center">Upload</h3>
<form method="post" enctype="multipart/form-data">
<h4 class="center">Browse to File</h4>
<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
<input type="file" name="user_file" size="10" />
<h5 class="center">Description</h4>
<input type="text" name="user_description" size="20" />
<input type="submit" name="upload" value="Upload" />
<p>Note: File will be uploaded to current location</p>
<h4 class="center">New Directory</h4>
<input type="text" name="new_dir" size="20" />
<input type="submit" name="create" value="Create" />
</form>
<?php
}
?>
</div> <!-- end options -->

<div id="main_app">
<h1 class="center">Library</h1>
<script type="text/javascript">
	function delete_dir(loc)
	{
		var c = confirm("Are you sure you want to delete this Directory?"+'\n'+"This action cannot be undone and will delete everything in it.")
		if( c == true )
		{
			location = loc
		}
	}
	function delete_file(loc)
	{
		var c = confirm("Are you sure you want to delete this File?"+'\n'+"This action cannot be undone.")
		if( c == true )
		{
			location = loc
		}
	}
</script>

<?php

startTable(1, 0, 1, "100%");
startClassRow("table_header");
startHeaderColumn();
print $path;
endHeaderColumn();
endRow();

startRow();
startColumn();

/*
 * Display
 */
startTable(1, 0, 1, "100%");
startClassRow("table_header");
startHeaderColumn();
print "&nbsp;";
nextHeaderColumn();
print "File Name";
nextHeaderColumn();
print "Description";
nextHeaderColumn();
print "Size";
nextHeaderColumn();
print "&nbsp;";
endHeaderColumn();
endRow();

while( list($temp, $row) = each($dirs))
{
	startRow();
	startColumn();
	print "&nbsp;"; // File/Dir Icon
	nextColumn();
	print "<a href='members.php?loc=library&dir_id=".$row['lib_dir_id']."'>".$row['dir_name']."</a>";
	nextColumn();
	print "Directory";
	nextColumn();
	print "&nbsp;";
	nextColumn();
	if($_SESSION['user']->has_priv('library'))
	{
		print "<a href=\"javascript:delete_dir('members.php?loc=library&dir_id=".$_REQUEST['dir_id']."&del_dir_id=".$row['lib_dir_id']."')\">Remove</a>";
	}
	else
	{
		print "&nbsp;";
	}
	endColumn();
	endRow();
}

while( list($temp, $row) = each($files))
{
	startRow();
	startColumn();
	print "&nbsp;"; // File/Dir Icon
	nextColumn();
	print "<a href='download.php?lib_id=".$row['lib_id']."' target='_blank'>".$row['name']."</a>";
	nextColumn();
	print $row['description'];
	nextColumn();
	print $row['size'];
	nextColumn();
	if($_SESSION['user']->has_priv('library'))
	{
		print "<a href=\"javascript:delete_file('members.php?loc=library&dir_id=".$_REQUEST['dir_id']."&del_id=".$row['lib_id']."')\">Remove</a>";
	}
	else
	{
		print "&nbsp;";
	}
	endColumn();
	endRow();
}

endTable();

endColumn();
endRow();
endTable();
?>

</div> <!-- end main app -->